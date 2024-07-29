<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unit_type extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        $this->template->rander("unit_type/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Unit_type_model->get_one($this->input->post('id'));
        $this->load->view('unit_type/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
           
        ));

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            //"hsn_description" => $this->input->post('hsn_description'),
            "status" => $this->input->post('status'),
            "description" =>$this->input->post('description')
        );
        $save_id = $this->Unit_type_model->save($data, $id);
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
            if ($this->Unit_type_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Unit_type_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Unit_type_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Unit_type_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        return array($data->title,
            //nl2br($data->hsn_description),
            $data->description ? $data->description : "-",
            lang($data->status),
            modal_anchor(get_uri("unit_type/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_unit_type'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_unit_type'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("unit_type/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */