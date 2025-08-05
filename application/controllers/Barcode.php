<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barcode extends CI_Controller {

    public function generate($code = '') {
        if (empty($code)) {
            show_error("No barcode provided!", 400);
        }

        // Load the Barcode39 library
        $this->load->library('Barcode39');

        // Set barcode data
        $this->barcode39->setCode($code);

        // Generate barcode image
        $this->barcode39->draw();
    }
}
