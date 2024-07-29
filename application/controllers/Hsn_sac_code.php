<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hsn_sac_code extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        $this->template->rander("hsn_sac_code/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Hsn_sac_code_model->get_one($this->input->post('id'));
        $this->load->view('hsn_sac_code/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "hsn_code" => "required",
            "gst" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "hsn_code" => $this->input->post('hsn_code'),
            "hsn_description" => $this->input->post('hsn_description'),
            "gst" => unformat_currency($this->input->post('gst'))
        );


        if (!$id) {
    // check the vendor invoice no     
        $data["hsn_code"] =$this->input->post('hsn_code');
        if ($this->Hsn_sac_code_model->is_hsn_code_exists($data["hsn_code"])) {
                echo json_encode(array("success" => false, 'message' => lang('hsn_code_already')));
                exit();
            }

        }
        if ($id) {
    // check the vendor invoice no     
        $data["hsn_code"] =$this->input->post('hsn_code');
        $data["id"] =$this->input->post('id');
       if ($this->Hsn_sac_code_model->is_hsn_code_exists($data["hsn_code"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('hsn_code_already')));
                exit();
            }

        }
        $save_id = $this->Hsn_sac_code_model->save($data, $id);
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
            if ($this->Hsn_sac_code_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Hsn_sac_code_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Hsn_sac_code_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Hsn_sac_code_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        return array($data->hsn_code,
            nl2br($data->hsn_description),
            to_decimal_format($data->gst)."%",
            modal_anchor(get_uri("hsn_sac_code/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_hsn_sac_code'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_hsn_sac_code'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hsn_sac_code/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */