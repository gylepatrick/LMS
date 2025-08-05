<?php 

class Profile extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model("Profile_Model");
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->library('session');
    }


    public function index() {
        $data['profiles'] = $this->Profile_model->get_profile();
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('templates/header');
        $this->load->view('profile_view', $data);
        $this->load->view('templates/footer');
    }


    public function update() {
        $data = array(
            'fullname' => $this->input->post('fullname'),
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
        );

        // Handle logo upload
        if (!empty($_FILES['user_profile']['name'])) {
            $config['upload_path'] = './profiles/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name'] = 'user_profile' . time();

            $this->upload->initialize($config);

            if ($this->upload->do_upload('user_profile')) {
                $uploadData = $this->upload->data();
                $data['user_profile'] = 'profiles/' . $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('profile');
            }
        }

        $this->Settings_model->update_settings($data);
        $this->session->set_flashdata('success', 'Profile updated successfully!');
        redirect('profile');
    }

}