<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OPAC extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url'); // Load URL helper
        $this->load->database(); // Load the database
        $this->load->model('Opac_model'); // Load OPAC model
        $this->load->model('Settings_model'); // Load Settings model
    }

   public function index() {
        $this->load->model('Opac_model');
        $data['settings'] = $this->Settings_model->get_settings(); // Fetch settings
        $data['books'] = $this->Opac_model->get_books(); // Fetch all books
        $data['subjects'] = $this->Opac_model->get_categories(); // Fetch distinct categories
        $this->load->view('library/opac', $data);
    }

    // public function search() {
    //     $keyword = $this->input->get('q');
    //     $this->load->model('Opac_model');
    //     $data['books'] = $this->Opac_model->search_books($keyword);
    //     $data['categories'] = $this->Opac_model->get_categories();
    //     $this->load->view('library/opac', $data);
    // }

    public function filterByCategory() {
        $category = $this->input->get('category');
        $this->load->model('Opac_model');
        $books = $this->Opac_model->get_books_by_category($category);
        echo json_encode($books);
    }

    public function search()
    {
        $keyword = $this->input->get('q');
        $this->load->model('Opac_model');
        $books = $this->Opac_model->search_books($keyword);
        echo json_encode($books);
    }
    
}
