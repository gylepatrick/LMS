<?php

class Settings extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->library('session');
    }

    public function index() {
        $data['settings'] = $this->Settings_model->get_settings();
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('templates/header');
        $this->load->view('settings_view', $data);
        $this->load->view('templates/footer');
    }

    public function update() {
        $data = array(
            'school_name' => $this->input->post('school_name'),
            'address' => $this->input->post('address'),
            'checked_by' => $this->input->post('checked_by'),
            'approved_by' => $this->input->post('approved_by')
        );

        // Handle logo upload
        if (!empty($_FILES['school_logo']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name'] = 'school_logo_' . time();

            $this->upload->initialize($config);

            if ($this->upload->do_upload('school_logo')) {
                $uploadData = $this->upload->data();
                $data['school_logo'] = 'uploads/' . $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('settings');
            }
        }

        $this->Settings_model->update_settings($data);
        $this->session->set_flashdata('success', 'Settings updated successfully!');
        redirect('settings');
    }
}
