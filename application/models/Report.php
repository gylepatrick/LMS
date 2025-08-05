<?php 

require_once 'BaseController.php';
class Report extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }



    public function get_all_office() {
        $this->db->order_by('item_code', 'ASC');
        $query = $this->db->get('inv_office');
        return $query->result();
    }
    
}