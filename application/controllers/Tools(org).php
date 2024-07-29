<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("tools");
         //$this->access_only_allowed_members();
    }

    function index() {
        $this->check_module_availability("module_assets_data");
        //$this->template->rander("tools/index");
        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("tools/index");
        }
        else if ($this->login_user->user_type == "staff")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("tools/index");
        }else {


        $this->template->rander("tools/index");
    } 
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Tools_model->get_one($this->input->post('id'));
        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $this->load->view('tools/modal_form', $view_data);
    }

     private function _get_unit_type_dropdown_select2_data() {
        //$unit_types = $this->Unit_type_model->get_all()->result();
         $unit_types = $this->Unit_type_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $unit_type_dropdown = array();

        

        foreach ($unit_types as $code) {
            $unit_type_dropdown[] = array("id" => $code->title, "text" => $code->title);
        }
        return $unit_type_dropdown;
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "quantity" => "required"
        )); 

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "quantity" => unformat_currency($this->input->post('quantity')),
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
"unit_type" => $this->input->post('unit_type'),
"tool_location" => $this->input->post('tool_location'),
"rate" => unformat_currency($this->input->post('item_rate'))
        );
        $save_id = $this->Tools_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Tools_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Tools_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Tools_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Tools_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
                $type = $data->unit_type ? $data->unit_type : "";

        return array($data->title,$data->description,
             $data->tool_location,
            to_decimal_format($data->quantity),
             $data->category,
            $data->make,
            $type,
            $data->rate,("₹
".$data->quantity*$data->rate),
            modal_anchor(get_uri("tools/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tool'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tool'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("tools/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */