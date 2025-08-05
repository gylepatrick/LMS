<?php
class Opac_model extends CI_Model {
    public function get_books() {
        return $this->db->get('lib')->result();
    }

    public function search_books($keyword) {
        $this->db->like('book_title', $keyword);
        $this->db->or_like('author', $keyword);
        return $this->db->get('lib')->result();
    }

    public function get_categories() {
        $this->db->distinct();
        $this->db->select('subject');
        return $this->db->get('lib')->result();
    }

    public function get_books_by_category($category) {
        $this->db->where('subject', $category);
        return $this->db->get('lib')->result();
    }
}
