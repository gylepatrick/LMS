<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Library_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_inventory()
    {
        $this->db->order_by("barcode", "ASC");
        // $this->db->limit(10);
        $query = $this->db->get("lib"); 
        return $query->result();
    }

   
    public function get_classification()
    {
        $this->db->select('classification');
        $this->db->from("lib");
        $this->db->group_by('classification');
        $this->db->order_by('classification', 'ASC');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_categories()
    {
        $this->db->select('category_subject');
        $this->db->from('lib');
        $this->db->group_by('category_subject');
        $this->db->order_by('category_subject', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_books()
    {
        $this->db->order_by("barcode", "ASC");
        $query = $this->db->get("lib");
        return $query->result();
    }

    public function insert_book($data)
    {
        return $this->db->insert("lib", $data);
    }
    public function update_book($id, $data)
    {
        $this->db->where("id", $id);
        return $this->db->update("lib", $data);
    }
    public function fetch_book_by_barcode($barcode)
    {
        $this->db->where("barcode", $barcode);
        $query = $this->db->get("lib"); 
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return null;
    }

    public function fetch_borrowed_book_by_barcode($barcode, $return_btch)
    {
        $this->db->select("borrowed_books.*, lib.status");
        $this->db->from("borrowed_books");
        $this->db->join("lib", "lib.barcode = borrowed_books.barcode", "inner"); 
        $this->db->where("borrowed_books.barcode", $barcode); 
        $this->db->where("borrowed_books.borrow_btch", $return_btch);
        $this->db->where("lib.status", "checked_out"); 

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row(); 
        }

        return null;
    }

    public function insert_borrow($data)
    {
        $this->db->select("quantity");
        $this->db->where("id", $data["book_id"]);
        $query = $this->db->get("lib");

        if ($query->num_rows() == 0) {
            log_message("error", "Book not found: " . $data["book_id"]);
            return false; 
        }

        $row = $query->row();
        $current_quantity = (int) $row->quantity;
        $borrow_number = isset($data["quantity"]) ? (int) $data["quantity"] : 1;
        if ($borrow_number > $current_quantity) {
            log_message(
                "error",
                "Not enough copies available for book ID: " . $data["book_id"]
            );
            return false;
        }

        $new_quantity = $current_quantity - $borrow_number;

        $this->db->insert("borrowed_books", $data);

        if ($this->db->affected_rows() > 0) {
            $this->db->set("quantity", $new_quantity);
            if ($new_quantity == 0) {
                $this->db->set("status", "Borrowed");
            } else {
                $this->db->set("status", "Available");
            }

            $this->db->where("id", $data["book_id"]);
            $this->db->update("lib");

            return true;
        }

        return false;
    }

    public function get_borrowed_book_by_barcodes(
        $barcode = null,
        $return_btch = null
    ) {
        if (empty($return_btch)) {
            return null;
        }

        log_message(
            "debug",
            "Fetching borrowed book - Barcode: $barcode, Return Batch: $return_btch"
        );

        $this->db->select("borrowed_books.*");
        $this->db->from("borrowed_books");
        $this->db->where("borrowed_books.borrow_btch", $return_btch);
        if (!empty($barcode)) {
            $this->db->where("borrowed_books.barcode", $barcode);
        }
        $this->db->join("lib", "borrowed_books.barcode = lib.barcode", "inner");

        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->row_array() : null;
    }

    public function update_return_status(
        $barcode,
        $actual_return_date,
        $return_btch
    ) {
        $this->db->trans_start();

        log_message(
            "debug",
            "Starting book return process for barcode: " . $barcode
        );
        $this->db->select("id, quantity");
        $this->db->where("barcode", $barcode);
        $book_query = $this->db->get("lib");

        if ($book_query->num_rows() == 0) {
            log_message("error", "Book not found for barcode: " . $barcode);
            $this->db->trans_rollback();
            return false;
        }

        $book = $book_query->row();
        $book_id = $book->id;
        $current_quantity = (int) $book->quantity;

        log_message(
            "debug",
            "Book ID: " . $book_id . " | Current Quantity: " . $current_quantity
        );
        $this->db->select("quantity");
        $this->db->where("barcode", $barcode);
        $borrow_query = $this->db->get("borrowed_books");

        if ($borrow_query->num_rows() == 0) {
            log_message(
                "error",
                "No borrow record found for barcode: " . $barcode
            );
            $this->db->trans_rollback();
            return false;
        }

        $borrowed_record = $borrow_query->row();
        $borrow_number = isset($borrowed_record->borrowed_number)
            ? (int) $borrowed_record->borrowed_number
            : 1;

        log_message("debug", "Borrowed Number: " . $borrow_number);

        $this->db->where("borrow_btch", $return_btch);
        $this->db->update("borrowed_books", [
            "actual_returned_date" => $actual_return_date,
        ]);

        if ($this->db->affected_rows() == 0) {
            log_message(
                "error",
                "Failed to update return date for barcode: " . $barcode
            );
            $this->db->trans_rollback();
            return false;
        }

        log_message("debug", "Return date updated successfully.");

        $new_quantity = $current_quantity + $borrow_number;

        $this->db->where("id", $book_id);
        $this->db->update("lib", [
            "quantity" => $new_quantity,
            "status" => "Available",
        ]);

        if ($this->db->affected_rows() == 0) {
            log_message(
                "error",
                "Failed to update book quantity for ID: " . $book_id
            );
            $this->db->trans_rollback();
            return false;
        }

        log_message(
            "debug",
            "Book returned successfully. New Quantity: " . $new_quantity
        );

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_students()
    {
        $this->db->where("user_type", "student");
        return $this->db->get("users")->result();
    }

    public function get_brrw_no()
    {
        $this->db->where("actual_returned_date", null);

        $this->db->order_by("student_name", "DESC");
        return $this->db->get("borrowed_books")->result();
    }

    public function get_borrowed_books()
    {
        $this->db->order_by("borrow_date", "DESC");
        $query = $this->db->get("borrowed_books");
        return $query->result();
    }

    public function get_student_transactions($fullname)
    {
        $this->db->where("student_name", $fullname);
        return $this->db->get("borrowed_books")->result();
    }
    public function get_book_by_id($book_id)
    {
        return $this->db
            ->where("id", $book_id)
            ->get("lib")
            ->row();
    }

    public function get_book_by_barcode($barcode)
    {
        return $this->db
            ->where("barcode", $barcode)
            ->get("lib")
            ->row();
    }
    public function get_most_borrowed_books()
    {
        $limits = [100, 70, 50]; 
        $results = [];

        foreach ($limits as $limit) {
            $this->db->select(
                "lib.id, lib.book_title, lib.author, COUNT(borrowed_books.book_id) as borrow_count"
            );
            $this->db->from("borrowed_books");
            $this->db->join("lib", "lib.id = borrowed_books.book_id", "left");
            $this->db->group_by("borrowed_books.book_id");
            $this->db->order_by("borrow_count", "DESC");
            $this->db->limit($limit);

            $results[$limit] = $this->db->get()->result();
        }

        return $results;
    }

    public function search_books($query)
    {
        $this->db->like("book_title", $query);
        $this->db->or_like("author", $query);
        $this->db->or_like("year_published", $query);
        $this->db->or_like("isbn", $query);
        $query = $this->db->get("lib");

        return $query->result();
    }

    public function getMostBorrowedBooks()
    {
        $this->db->select("book_title, COUNT(book_title) as total_borrowed");
        $this->db->from("borrowed_books");
        $this->db->group_by("book_title");
        $this->db->order_by("total_borrowed", "DESC");
        $this->db->limit(5);

        $query = $this->db->get();
        return $query->result();
    }

    public function getBorrowedBooksByMonth()
    {
        $this->db->select(
            "DATE_FORMAT(borrow_date, '%M') as month, COUNT(*) as total_borrowed"
        );
        $this->db->from("borrowed_books");
        $this->db->group_by("YEAR(borrow_date), MONTH(borrow_date)");
        $this->db->order_by("MIN(borrow_date)", "ASC");

        $query = $this->db->get();
        return $query->result();
    }
}
