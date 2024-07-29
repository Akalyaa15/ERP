<?php

if (!defined('BASEPATH')) 
    exit('No direct script access allowed');

class Terms_conditions_templates extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

 //    private function _templates() {
 //       $template_names= $this->Terms_conditions_templates_model->get_details()->result();
 //       $names=array();
 // foreach ($template_names as $template_name) {
 //     $names["name"]=$template_name->template_name;
 // }
 // print_r($names);
 //        return $names;
 //    }

    function index() {
        $this->template->rander("terms_conditions_templates/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Terms_conditions_templates_model->get_one($this->input->post('id'));
        $this->load->view('terms_conditions_templates/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $data = array(

            "template_name" => $this->input->post('template_name'),
            "custom_message" => decode_ajax_post_data($this->input->post('custom_message'))
        );
        $save_id = $this->Terms_conditions_templates_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
function save_title() {
        

        $id = $this->input->post('id');

        $data = array(
              "template_name" =>  $this->input->post('template_name'),
        );
        $save_id = $this->Terms_conditions_templates_model->save($data);
        if ($save_id) {
            echo json_encode(array("success" => true, 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
     function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Terms_conditions_templates_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Terms_conditions_templates_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }
    function restore_to_default() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $template_id = $this->input->post('id');

        $data = array(
            "custom_message" => ""
        );
        $save_id = $this->Terms_conditions_templates_model->save($data, $template_id);
        if ($save_id) {
            $default_message = $this->Terms_conditions_templates_model->get_one($save_id)->default_message;
            echo json_encode(array("success" => true, "data" => $default_message, 'message' => lang('template_restored')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
function to_default() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $set_zero = $this->Terms_conditions_templates_model->set_zero();

        $template_id = $this->input->post('id');

        $data = array(
            "is_default" =>1
        );
        $save_id = $this->Terms_conditions_templates_model->save($data, $template_id);
        if ($save_id) {
            $default_message = $this->Terms_conditions_templates_model->get_one($save_id)->default_message;
            echo json_encode(array("success" => true, "data" => $default_message, 'message' => lang('template_restored')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    function list_data() {
        $list = array();
            $template_names= $this->Terms_conditions_templates_model->get_details()->result();

        foreach ($template_names as $template_name ) {
            if($template_name->is_default){
               $default= "<span class='label label-success large'>Default</span>";
            }else{
                $default="";
            }
            $list[] = array("<span class='template-row' data-name='$template_name->id'>" . $template_name->template_name. "</span> ".$default);
        }
        echo json_encode(array("data" => $list));
    }

    /* load template edit form */

    function form($template_name = "") {
        $view_data['model_info'] = $this->Terms_conditions_templates_model->get_one_where(array("id" => $template_name));
        $this->load->view('terms_conditions_templates/form', $view_data);
    }

}

/* End of file email_templates.php */
/* Location: ./application/controllers/email_templates.php */