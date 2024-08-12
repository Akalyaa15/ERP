<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bank_name extends MY_Controller {

  function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("master_data");
        $this->access_only_allowed_members();
    }
function index() {
        $this->check_module_availability("module_master_data");
        //$this->template->rander("bank_name/index");
                if ($this->login_user->is_admin == "1")
        {
           $this->template->rander("bank_name/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("bank_name/index");
        }else {


        $this->template->rander("bank_name/index");
    } 
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Bank_name_model->get_one($this->input->post('id'));
        $account_numbers = explode(",", $this->Bank_name_model->get_account_number_suggestions($this->input->post('id')?$this->input->post('id'):0));
        $account_number_suggestions = array();
        foreach ($account_numbers as $account_number) {
            if ($account_number && !in_array($account_number, $account_number_suggestions)) {
                $account_number_suggestions[] = $account_number;
            }
        }
        if (!count($account_number_suggestions)) {
            $account_number_suggestions = array("0" => "");
        }
        $view_data['account_number_suggestions'] = $account_number_suggestions;
        $this->load->view('bank_name/modal_form', $view_data);
    }
function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"

            
        ));

        $id = $this->input->post('id');
        if($id){
            $ree=$this->Bank_name_model->get_one($this->input->post('id'));
            //$re2=$this->Branches_model->is_branch_exists($ree->branch_code);
            if(strtoupper($ree->title)!=strtoupper($this->input->post('title'))){
            if ($this->Bank_name_model->is_bank_name_exists($this->input->post('title'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_bank_name')));
            exit();
        } 
    }}
        if(!$id){
        if ($this->Bank_name_model->is_bank_name_exists($this->input->post('title'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_bank_name')));
            exit();
        }
    }
        $data = array(
            "title" =>  $this->input->post('title'),
             "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
            "account_number" => $this->input->post('account_number'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
        $save_id = $this->Bank_name_model->save($data, $id);
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
         $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Bank_name_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Bank_name_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Bank_name_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Bank_name_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Bank_name_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
$note_labels = "";
        if ($data->account_number) {
            $labels = explode(",", $data->account_number);
            foreach ($labels as $label) {
                $note_labels .= "<span class='label label-info clickable'>" . $label . "</span> ";
            }
            /*$note_labels .= "<br />" . $note_labels;*/
        }

        //last activity user name and date start 
         $last_activity_by_user_name= "-";
        if($data->last_activity_user){
        $last_activity_user_data = $this->Users_model->get_one($data->last_activity_user);
        $last_activity_image_url = get_avatar($last_activity_user_data->image);
        $last_activity_user = "<span class='avatar avatar-xs mr10'><img src='$last_activity_image_url' alt='...'></span> $last_activity_user_data->first_name $last_activity_user_data->last_name";
        
        if($last_activity_user_data->user_type=="resource"){
          $last_activity_by_user_name= get_rm_member_profile_link($data->last_activity_user, $last_activity_user );   
        }else if($last_activity_user_data->user_type=="client") {
          $last_activity_by_user_name= get_client_contact_profile_link($data->last_activity_user, $last_activity_user);
        }else if($last_activity_user_data->user_type=="staff"){
             $last_activity_by_user_name= get_team_member_profile_link($data->last_activity_user, $last_activity_user); 
       }else if($last_activity_user_data->user_type=="vendor"){
             $last_activity_by_user_name= get_vendor_contact_profile_link($data->last_activity_user, $last_activity_user); 
        }
       }
      
       $last_activity_date = "-";
       if($data->last_activity){
       $last_activity_date = format_to_relative_time($data->last_activity);
       }
       // end last activity   


        return array($data->title,
            $note_labels,
            $data->description ? $data->description : "-",
             lang($data->status),
             $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("bank_name/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_bank'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("bank_name/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file Earnings.php */
/* Location: ./application/controllers/Earnings.php */
