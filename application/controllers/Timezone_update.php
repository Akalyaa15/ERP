<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timezone_update extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        //initialize managerial permission
       // $this->init_permission_checker("attendance");
        //$this->access_only_allowed_members();
    }

    function index() {
        //$this->check_module_availability("module_attendance");


        $this->template->rander("timezone_update/timezone");
        //$this->load->view("timezone_update/time_zone");
     
    }


//delete/undo an event
    function update_user_timezone() {
        validate_submitted_data(array(
            "loginuser_id" => "required",
            "timezone_result"=>"required"
        ));

        $login_user_id = $this->input->post("loginuser_id");
        $login_user_timezone = $this->input->post("timezone_result");
        
         
        
  $data = array(
            "user_timezone" => $login_user_timezone
             
        );
        $save_id = $this->Users_model->save($data, $login_user_id);

        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => "record cannot be updated"));
        }
    }
   

   

    




}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */