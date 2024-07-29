<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Earnings extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        $this->template->rander("earnings/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Earnings_model->get_one($this->input->post('id'));
        $this->load->view('earnings/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",

            "percentage" => "required"
        ));

        $id = $this->input->post('id');
        $percentage = $this->input->post('percentage');
         $status = $this->input->post('status');
        $data = array(
            "title" =>  $this->input->post('title'),
            "percentage" => unformat_currency($this->input->post('percentage')),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
        );
        if ($status == 'active') {
            # code...
    
        if(!$id){     
         /*$options = array("product_id" => $product_id);*/
            //$item_info = $this->Earnings_model->get_details()->result();
            $basic_percentage = $this->Earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary"))->row();
            $other_percentage = $this->Earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>""))->result();
            //$basic_percentage_value = $basic_percentage->percentage;
            $salary_default = 10000;
            $salary = $salary_default/100;
            $basic_salary_value = $salary*$basic_percentage->percentage;
            $c = $basic_salary_value/100; 
$total=0;
            foreach($other_percentage as $other_per){
 $a=$c * $other_per->percentage;
 $total+=$a;

     }
$current_percentage =  $c*$percentage;    
$g = $basic_salary_value+$total+$current_percentage;            

if($g>$salary_default){
             echo json_encode(array("success" => false, 'message' => lang('earnings_percentage')));
            exit();
                        }
            }else if($id!=1){

            $basic_percentage = $this->Earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary"))->row();
            $options = array("id" => $id);
            $other_percentage = $this->Earnings_model->get_detailss($options)->result();
            //$basic_percentage_value = $basic_percentage->percentage;
            $salary_default = 10000;
            $salary = $salary_default/100;
            $basic_salary_value = $salary*$basic_percentage->percentage;
            $c = $basic_salary_value/100; 
$total=0;
            foreach($other_percentage as $other_per){
 $a=$c * $other_per->percentage;
 $total+=$a;

     }
$current_percentage =  $c*$percentage;    
$g = $basic_salary_value+$total+$current_percentage; 
 if($g>$salary_default){
             echo json_encode(array("success" => false, 'message' => lang('earnings_percentage')));
            exit();
                        }             

}else if($id==1){
            $basic_percentage = $percentage;
            $options = array("id" => $id);
            $other_percentage = $this->Earnings_model->get_detailss($options)->result();
            //$basic_percentage_value = $basic_percentage->percentage;
            $salary_default = 10000;
            $salary = $salary_default/100;
            $basic_salary_value = $salary*$basic_percentage;
            $c = $basic_salary_value/100; 
$total=0;
            foreach($other_percentage as $other_per){
 $a=$c * $other_per->percentage;
 $total+=$a;

     }
//$current_percentage =  $c*$percentage;    
$g = $basic_salary_value+$total; 
 if($g>$salary_default){
             echo json_encode(array("success" => false, 'message' => lang('earnings_percentage')));
            exit();
                        } 

}
        
} 
        $save_id = $this->Earnings_model->save($data, $id);
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
            if ($this->Earnings_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Earnings_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Earnings_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Earnings_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    /*private function _make_row($data) {
        return array($data->title,
            $data->description ? $data->description : "-",
            to_decimal_format($data->percentage),
            lang($data->status),
            modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tax'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("earnings/delete"), "data-action" => "delete-confirmation"))
        );
    }*/
    private function _make_row($data) {
        $delete = "";
        $edit = "";
        if ($data->key_name) {
            $edit = modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            
        }
        if (!$data->key_name) {
            $edit = modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("earnings/delete"), "data-action" => "delete-confirmation"));
        }
        return array($data->title,
            $data->description ? $data->description : "-",
            to_decimal_format($data->percentage)."%",
            lang($data->status),
            /*modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tax'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("earnings/delete"), "data-action" => "delete-confirmation")) */
            $edit.$delete,
        );
    }

}

/* End of file Earnings.php */
/* Location: ./application/controllers/Earnings.php */