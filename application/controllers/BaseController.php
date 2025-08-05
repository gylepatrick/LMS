
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Check if the user is logged in before allowing access to certain routes
        if (!$this->session->userdata('user_id')) {
            // If not logged in, redirect to the login page
            redirect('login');
        }
    }
}
