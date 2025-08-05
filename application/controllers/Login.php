<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('Login_model');
    }

    public function index()
    {
        $this->load->view('library_login'); 
        $this->load->view('templates/sweetalert');  
    }

    public function submit()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
    
        $user = $this->Login_model->check_login($username, $password);
    
        if ($user) {
            $this->session->set_userdata([
                'user_id'         => $user->id,
                'user_name'       => $user->username,
                'full_name'       => $user->fullname,
                'school'          => $user->schl_name,
                'school_address'  => $user->schl_address,
                'user_type'       => $user->user_type
            ]);
    
            
    
            // Redirect based on user type
            if ($user->user_type === 'admin') {
                $this->session->set_flashdata('success', 'Login successful!');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Please use admin account to access Inventory!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('login');
        }
    }
    




    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('/');
    }

}
