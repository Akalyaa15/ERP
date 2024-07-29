<?php

class Forbidden_attendance extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $view_data["heading"] = "Permission Restricted !";
        $view_data["message"] = "You don't have  permission to access this module because you are an indoor user and trying to access from a diffrent IP address.";
        if ($this->input->is_ajax_request()) {
            $view_data["no_css"] = true;
        }
        $this->load->view("errors/html/error_general", $view_data);
    }

}
