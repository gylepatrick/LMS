<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Settings_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_settings() {
        return $this->db->get('settings')->row();
    }

    public function update_settings($data) {
        $this->db->where('id', 1); // assuming single settings row
        return $this->db->update('settings', $data);
    }

}