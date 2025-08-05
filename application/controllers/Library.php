<?php
require_once "BaseController.php";
class Library extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model("Library_model");
        $this->load->model("Login_model");
        $this->load->model("Settings_model");
        $this->load->library("form_validation");
        $this->load->library("session");
        $this->load->helper("url");
        $this->load->library("session");
    }

    // index page or the main page of the library
    public function index()
    {
        $data['settings'] = $this->Settings_model->get_settings(); // Fetch settings
        $data["books"] = $this->Library_model->get_inventory(); // get all books in database
        $data["students"] = $this->Library_model->get_students(); //get all users with type students
        $data["classifications"] = $this->Library_model->get_classification(); // get all classification
       

        if (!isset($data["books"]) || empty($data["books"])) {
            //checks if there's book or it is empty
            $data["books"] = [];
        }

        // load all the files needed for view (header, footer and index page)
        $this->load->view("templates/header");
        $this->load->view("templates/reports_modal");
        $this->load->view("library/index", $data); //include the data to display the result of thw query in getting books
        $this->load->view("templates/library_modal");
        $this->load->view("templates/sweetalert");
        $this->load->view("templates/footer");
    }


    public function store()
    {
        $this->form_validation->set_rules("book_title", "Book Title", "required");
        $this->form_validation->set_rules("author", "Author", "required");
        $this->form_validation->set_rules("barcode", "Barcode", "required");
        $this->form_validation->set_rules("isbn", "ISBN", "required|is_unique[lib.isbn]");
        $this->form_validation->set_rules("classification", "Classification", "required");
        $this->form_validation->set_rules("classification_number", "Classification Number", "required");
        $this->form_validation->set_rules("subject", "Subject", "required");
        $this->form_validation->set_rules("category_subject", "Category Subject", "required");
        $this->form_validation->set_rules("year_published", "Year Published", "required|numeric");
        $this->form_validation->set_rules("language", "Language", "required");
        $this->form_validation->set_rules("transaction_type", "Transaction Type", "required");
        $this->form_validation->set_rules("date_purchased", "Date Purchased", "required");
        $this->form_validation->set_rules("date_entered", "Date Entered", "required");
        $this->form_validation->set_rules("status", "Status", "required");
        $this->form_validation->set_rules("quantity", "Quantity", "required");
        $this->form_validation->set_rules("unit_cost", "Unit Cost", "required");
        $this->form_validation->set_rules("acq_cost", "Acquisition Cost", "required");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata("error", validation_errors());
            redirect("library/index");
        }

        $data = [
            "book_title" => $this->input->post("book_title"),
            "author" => $this->input->post("author"),
            "barcode" => $this->input->post("barcode"),
            "isbn" => $this->input->post("isbn"),
            "classification" => $this->input->post("classification"),
            "classification_number" => $this->input->post("classification_number"),
            "subject" => $this->input->post("subject"),
            "category_subject" => $this->input->post("category_subject"),
            "year_published" => $this->input->post("year_published"),
            "language" => $this->input->post("language"),
            "transaction_type" => $this->input->post("transaction_type"),
            "date_purchased" => $this->input->post("date_purchased"),
            "date_entered" => $this->input->post("date_entered"),
            "quantity" => $this->input->post("quantity"),
            "unit_cost" => $this->input->post("unit_cost"),
            "acq_cost" => $this->input->post("acq_cost"),
            "status" => $this->input->post("status"),
        ];

        if ($this->Library_model->insert_book($data)) {
            $this->session->set_flashdata("success", "Book added successfully.");
        } else {
            $this->session->set_flashdata("error", "Failed to add book.");
        }
        redirect("library/index");
    }

    // update book by id
    public function update()
    {
        $id = $this->input->post("id");

        $data = [
            "date_purchased" => $this->input->post("date_purchased"),
            "date_entered" => $this->input->post("date_entered"),
            "transaction_type" => $this->input->post("transaction_type"),
            "book_title" => $this->input->post("book_title"),
            "classification" => $this->input->post("classification"),
            "classification_number" => $this->input->post(
                "classification_number"
            ),
            "author" => $this->input->post("author"),
            "subject" => $this->input->post("subject"),
            "category_subject" => $this->input->post("category_subject"),
            "isbn" => $this->input->post("isbn"),
            "barcode" => $this->input->post("barcode"),
            "year_published" => $this->input->post("year_published"),
            "language" => $this->input->post("language"),
            "status" => $this->input->post("status"),
        ];

        $this->Library_model->update_book($id, $data);
        $this->session->set_flashdata("message", "Book updated successfully!");
        redirect(base_url("library/index"));
    }

    public function opac_results()
    {
        $this->load->model('Settings_model');
        $this->load->model('Opac_model');
        $data['settings'] = $this->Settings_model->get_settings(); // Fetch settings
        $data["books"] = $this->Library_model->get_inventory();
        $data["students"] = $this->Library_model->get_students();
        $data['subjects'] = $this->Opac_model->get_categories();  //get all categories

        if (!isset($data["books"]) || empty($data["books"])) {
            $data["books"] = []; // Ensure $books is an empty array if no books are found
        }

        $this->load->view("library/opac_results", $data);
    }

    // view borrowed books
    public function borrowed_books()
    {
        $data["borrowed_books"] = $this->Library_model->get_borrowed_books(); //displays all the borrowed books
        $data["students"] = $this->Library_model->get_students(); //get students from users table for students informatin

        $data["brrw_nos"] = $this->Library_model->get_brrw_no();
        $data[
            "most_borrowed_books"
        ] = $this->Library_model->get_most_borrowed_books(); //top borrowed books

        // views and templates
        $this->load->view("templates/header");
        $this->load->view("templates/reports_modal");
        $this->load->view("library/borrowed_books", $data);
        $this->load->view("templates/library_modal");
        $this->load->view("templates/footer");
    }

    // insert new borrow book
    public function borrow_book()
    {
        $this->load->model("Library_model");

        // Validate required fields
        // $this->form_validation->set_rules('student_id', 'Student ID', 'required|trim');
        $this->form_validation->set_rules(
            "student_name",
            "Student Name",
            "required|trim"
        );
        $this->form_validation->set_rules(
            "book_id",
            "Book ID",
            "required|trim"
        );
        $this->form_validation->set_rules(
            "book_title",
            "Book Title",
            "required|trim"
        );
        $this->form_validation->set_rules(
            "book_author",
            "Book Author",
            "required|trim"
        );
        $this->form_validation->set_rules(
            "borrow_date",
            "Borrow Date",
            "required|trim"
        );
        $this->form_validation->set_rules(
            "return_date",
            "Return Date",
            "required|trim"
        );
        $this->form_validation->set_rules("isbn", "ISBN", "required|trim");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata("error", validation_errors());
            redirect("library/borrowed_books");
        }

        $book_id = $this->input->post("book_id", true);
        $borrowed_number = (int) $this->input->post("borrowed_number", true); // Convert to integer

        // Check if the book exists and is available
        $book = $this->Library_model->get_book_by_id($book_id);
        if (!$book) {
            $this->session->set_flashdata("error", "Book not found.");
            redirect("library/borrowed_books");
        }

        if ($book->quantity < $borrowed_number) {
            $this->session->set_flashdata(
                "error",
                "Not enough copies available."
            );
            redirect("library/borrowed_books");
        }

        // Generate unique borrow batch code
        $borrow_code =
            "BRW-" .
            date("Ymd") .
            "-" .
            strtoupper(substr(md5(uniqid()), 0, 6));

        $data = [
            "student_id" => $this->input->post("student_id", true),
            "student_name" => $this->input->post("student_name", true),
            "book_id" => $book_id,
            "book_title" => $this->input->post("book_title", true),
            "book_author" => $this->input->post("book_author", true),
            "borrow_date" => $this->input->post("borrow_date", true),
            "return_date" => $this->input->post("return_date", true),
            "isbn" => $this->input->post("isbn", true),
            "barcode" => $this->input->post("barcode", true),
            "quantity" => $borrowed_number, // Corrected field name
            "borrow_btch" => $borrow_code,
        ];

        // Insert borrow record and update book quantity
        $insert = $this->Library_model->insert_borrow($data);

        if ($insert) {
            $this->session->set_flashdata(
                "success",
                "Book borrowed successfully!"
            );
        } else {
            $this->session->set_flashdata("error", "Failed to borrow book.");
        }

        redirect("library/borrowed_books");
    }

    // opac view
    public function opac()
    {
        $data["books"] = $this->Library_model->get_inventory();
        $data["students"] = $this->Library_model->get_students();

        if (!isset($data["books"]) || empty($data["books"])) {
            $data["books"] = []; // Ensure $books is an empty array if no books are found
        }

        $this->load->view("templates/header");
        $this->load->view("templates/reports_modal");
        $this->load->view("library/opac", $data);
        $this->load->view("templates/library_modal");
        $this->load->view("templates/sweetalert");
        $this->load->view("templates/footer");
    }
    // getting book by barcode for borrowing
    public function get_book_by_barcode()
    {
        $barcode = $this->input->post("barcode");

        if (!$barcode) {
            echo json_encode([
                "status" => "error",
                "message" => "Barcode required",
            ]);
            return;
        }

        $this->load->model("Library_model");
        $book = $this->Library_model->fetch_book_by_barcode($barcode);

        if ($book) {
            echo json_encode(["status" => "success", "data" => $book]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Book with barcode " . $barcode . " not found!",
            ]);
        }
    }

    // return book
    public function return_book()
    {
        $this->load->model("Library_model");

        // Validate required fields
        $this->form_validation->set_rules(
            "actual_return_date",
            "Return Date",
            "required|trim"
        );

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata("error", validation_errors());
            redirect("library/borrowed_books");
        }

        $actual_return_date = $this->input->post("actual_return_date", true);

        $barcode = $this->input->post("return_barcode", true);
        $return_btch = $this->input->post("return_btch", true);

        if (empty($barcode) || empty($return_btch)) {
            echo json_encode(["error" => "Missing barcode or return batch"]);
            $this->session->set_flashdata(
                "error",
                "Missing barcode or return batch"
            );
            return;
        }

        // Check if the book is borrowed
        $book = $this->Library_model->get_borrowed_book_by_barcodes(
            $barcode,
            $return_btch
        );

        if (!$book) {
            $this->session->set_flashdata(
                "error",
                "Book not found or not borrowed."
            );
            redirect("library/borrowed_books");
        }

        // if ($book->status === 'Available') {
        //     $this->session->set_flashdata('error', 'This book is already returned/not yet borrowed.');
        //     redirect('library/borrowed_books');
        // }

        // Update book status and return date
        $success = $this->Library_model->update_return_status(
            $barcode,
            $actual_return_date,
            $return_btch
        );

        if ($success) {
            $this->session->set_flashdata(
                "success",
                "Book returned successfully!"
            );
        } else {
            $this->session->set_flashdata("error", "Failed to return book.");
        }

        redirect("library/borrowed_books");
    }
    // getting borrowed book for returning based on barcode and borrowed code
    public function get_borrowed_book_by_barcode()
    {
        $barcode = $this->input->post("return_barcode");
        $return_btch = $this->input->post("return_btch");

        log_message("debug", "Received barcode: " . $barcode);
        log_message("debug", "Received return_btch: " . $return_btch);

        if (!$return_btch) {
            echo json_encode([
                "status" => "error",
                "message" => "Return batch is required",
            ]);
            return;
        }

        $this->load->model("Library_model");
        $book = $this->Library_model->get_borrowed_book_by_barcodes(
            $barcode,
            $return_btch
        );

        if ($book) {
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $book["id"],
                    "student_name" => $book["student_name"],
                    "book_title" => $book["book_title"],
                    "author" => $book["book_author"],
                    "borrowed_date" => $book["borrow_date"],
                    "due_date" => $book["return_date"],
                    "quantity" => $book["quantity"],
                ],
            ]);
        } else {
            log_message(
                "error",
                "Book not found for barcode: " .
                    $barcode .
                    " and return_btch: " .
                    $return_btch
            );
            echo json_encode([
                "status" => "error",
                "message" =>
                    "Book with barcode " .
                    $barcode .
                    " and return batch " .
                    $return_btch .
                    " not found!",
            ]);
        }
    }
    //library dashboard
    // Library dashboard
    public function library_dashboard()
    {
        $this->load->model('Library_model');

        // Existing data
        $data['most_borrowed_books'] = $this->Library_model->getMostBorrowedBooks();
        $data['borrowed_books_by_month'] = $this->Library_model->getBorrowedBooksByMonth();

        $data['book_subjects'] = $this->Library_model->getBookSubjectsWithCount();
        $data['book_categories'] = $this->Library_model->getBookCategoriesWithCount();
        $data['total_books'] = $this->Library_model->getTotalBooks();

        $this->load->view('templates/header');
        $this->load->view('library_dashboard', $data); // <-- Pass $data to view
        $this->load->view('templates/footer');
    }

    // student dashboard
    public function student_dashboard()
    {
        $fullname = $this->session->userdata("full_name");
        $data["transactions"] = $this->Library_model->get_student_transactions(
            $fullname
        );

        $this->load->view("templates/header");
        $this->load->view("library/student_dashboard", $data);
        $this->load->view("templates/sweetalert");
        $this->load->view("templates/footer");
    }

    // search books
    public function search_books()
    {
        $this->load->model("Library_model");
        $query = $this->input->post("query", true);
        $result = $this->Library_model->search_books($query);
        echo json_encode($result);
    }

    // to be transfered to AUTH
    // login for library
    public function login()
    {
        $this->load->view("login");
    }

    // FUNCTION SUBMIT FOR LOGI LIBRARY
    public function submit()
    {
        $username = $this->input->post("username");
        $password = $this->input->post("password");

        $user = $this->Login_model->check_login($username, $password);

        if ($user) {
            $this->session->set_userdata([
                "user_id" => $user->id,
                "user_name" => $user->username,
                "full_name" => $user->fullname,
                "school" => $user->schl_name,
                "school_address" => $user->schl_address,
                "user_type" => $user->user_type,
            ]);

            // Redirect based on user type
            if ($user->user_type === "student") {
                $this->session->set_flashdata("success", "Login successful!");
                redirect("library/student_dashboard");
            } elseif ($user->user_type === "librarian") {
                $this->session->set_flashdata("success", "Login successful!");
                redirect("library/library_dashboard");
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Please use an account for Librarian or Student!"
                );
                redirect("library/login");
            }
        } else {
            $this->session->set_flashdata(
                "error",
                "Invalid username or password"
            );
            redirect("login");
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata(
            "success",
            "You have been logged out successfully."
        );
        redirect("login");
    }
}
