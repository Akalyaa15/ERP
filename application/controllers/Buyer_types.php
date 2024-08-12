<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buyer_types extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        $this->template->rander("buyer_types/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Buyer_types_model->get_one($this->input->post('id'));
        $this->load->view('buyer_types/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "buyer_type" => "required",

            "profit_margin" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "buyer_type" =>  $this->input->post('buyer_type'),
            "profit_margin" => unformat_currency($this->input->post('profit_margin')),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
        );
        $save_id = $this->Buyer_types_model->save($data, $id);
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
            if ($this->Buyer_types_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Buyer_types_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Buyer_types_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Buyer_types_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        return array($data->buyer_type,
            $data->description ? $data->description : "-",
            to_decimal_format($data->profit_margin)."%",
            lang($data->status),
            //lang($data->status),
            modal_anchor(get_uri("buyer_types/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_buyer_type'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_buyer_type'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("buyer_types/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file Earnings.php */
/* Location: ./application/controllers/Earnings.php */