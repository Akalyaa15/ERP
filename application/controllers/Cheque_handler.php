<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cheque_handler extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("cheque_handler");
         //$this->access_only_allowed_members();
    }
        function index() {
        $this->check_module_availability("module_cheque_handler");
        $view_data['status_dropdown'] = $this->_get_cheque_status_dropdown();
        $view_data['cheque_statuses'] = $this->Cheque_status_model->get_details()->result();
        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['rm_members_dropdown'] = $this->_get_rm_members_dropdown();
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());
        $view_data['others_dropdown'] = $this->_get_others_dropdown();
        $view_data['vendors_dropdown'] = json_encode($this->_get_vendors_dropdown());
        //$this->template->rander("tools/index");
        if ($this->login_user->is_admin == "1")
        { 

            $this->template->rander("cheque_handler/index", $view_data);
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("cheque_handler/index", $view_data);
        }else {


        $this->template->rander("cheque_handler/index", $view_data);
    } 
    }

    private function _get_cheque_status_dropdown() {
          $statuses = $this->Cheque_status_model->get_details()->result();

             $status_dropdown = array(
                array("id" => "", "text" => "- " . lang("status") . " -")
            );

            foreach ($statuses as $status) {
                $status_dropdown[] = array("id" => $status->id, "text" => ( $status->key_name ? lang($status->key_name) : $status->title));
            }

        return json_encode($status_dropdown);
    }
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        // Prepare dropdown lists
        $view_data['bank_list_dropdown'] = array("" => "-") + $this->Bank_name_model->get_dropdown_list(array("title"));
        $view_data['cheque_category_dropdown'] = array("" => "-") + $this->Cheque_categories_model->get_dropdown_list(array("title"), "id", array("status" => "active"));
        $view_data['status_dropdown'] = array("" => "-") + $this->Cheque_status_model->get_dropdown_list(array("title"));
        $view_data['tm_dropdown'] = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), 'id', array("user_type" => "staff"));
        $view_data['rm_dropdown'] = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), 'id', array("user_type" => "resource"));
        $view_data['vendors_dropdown'] = $this->Vendors_model->get_dropdown_list(array("company_name"), 'id');
        $view_data['clients_dropdown'] = $this->Clients_model->get_dropdown_list(array("company_name"), 'id');
    
        // Fetch model info
        $view_data['model_info'] = $this->Cheque_handler_model->get_one($this->input->post('id'));
        
        // Fetch custom fields (ensure this method exists and works correctly)
        $view_data['custom_fields'] = $this->Custom_fields_model->get_custom_fields_for_context('cheque'); // Adjust based on your actual logic
        
        // Load the view with the view_data
        $this->load->view('cheque_handler/modal_form', $view_data);
    }
    function save() {
    $member_type=$this->input->post('member_type');
    if($member_type=="tm"){
    $member=$this->input->post('tm_member');
    }else if($member_type=="om"){
        $member=$this->input->post('rm_member');

    }else if($member_type=="clients"){
        $member=$this->input->post('client_member');

    }else if($member_type=="vendors"){
        $member=$this->input->post('vendor_member');

    }else if($member_type=="others"){
        $member=0;
$data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name')

    );
}
        $id = $this->input->post('id');
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "note");
        $new_files = unserialize($files_data);
        $data = array(
            "member_type" => $this->input->post('member_type'),
            "member_id" => $member,
            "cheque_number" =>  $this->input->post('cheque_no'),
            "bank_name" => $this->input->post('bank_name'),
            "payment_mode" => $this->input->post('payment_mode'),
            "account_number" => $this->input->post('account_number'),
            "cheque_category_id" => $this->input->post('cheque_category'),
"amount" =>unformat_currency($this->input->post('amount')),
"issue_date" =>$this->input->post('issue_date'),
"drawn_on" => $this->input->post('drawn_on'),
"valid_upto"=> $this->input->post('valid_upto'),    
"description"=> $this->input->post('description'),    
"status_id"=> $this->input->post('status_id')    

        ); 
        if($member_type=="others"){
        $member=0;
$data["first_name"] = $this->input->post('first_name');
$data["last_name"] = $this->input->post('last_name');
}
        if ($id) {
            $note_info = $this->Cheque_handler_model->get_one($id);
          $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $note_info->files, $new_files);
        }
        $data["files"] = serialize($new_files);

        $data["last_activity_user"]=$this->login_user->id;
         $data["last_activity"] = get_current_utc_time();
        $save_id = $this->Cheque_handler_model->save($data, $id);
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
         $save_id = $this->Cheque_handler_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Cheque_handler_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Cheque_handler_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $status = $this->input->post('status_id');
        $user_id = $this->input->post('user_id');
        $user_ids = $this->input->post('user_ids');
        $client_id = $this->input->post('client_id');
        $vendor_id = $this->input->post('vendor_id');
        $other_id = $this->input->post('other_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
if ($user_ids) {
        $options = array(
            
            "status_id" => $status,
            "user_id" => $user_ids,
            "client_id" => $client_id,
            "vendor_id" => $vendor_id,
            "other_id" => $other_id,
            "start_date" => $start_date,
            "end_date" => $end_date

           
        ); 
    }else{
        $options = array(
            
            "status_id" => $status,
            "user_id" => $user_id,
            "client_id" => $client_id,
            "vendor_id" => $vendor_id,
            "other_id" => $other_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
        ); 
    }

        /*$options = array(
            
            "status_id" => $status,
           
        ); */
        $list_data = $this->Cheque_handler_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Cheque_handler_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $deadline_text = "-";
        if ($data->valid_upto) {
            $deadline_text = format_to_date($data->valid_upto, false);
            if (get_my_local_time("Y-m-d") > $data->valid_upto) {
                $deadline_text = "<span class='text-danger'>" . $deadline_text . "</span> ";
            } else if (get_my_local_time("Y-m-d") == $data->valid_upto) {
                $deadline_text = "<span class='text-warning'>" . $deadline_text . "</span> ";
            }
        }
    
        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("notes/file_preview/" . $file_name)));
                }
            }
        }
    
        // Define $checkbox_class if you want to use it
        $checkbox_class = 'default-checkbox-class'; // Replace 'default-checkbox-class' with the actual class you need
    
        $check_status = js_anchor("<span class='$checkbox_class'></span>", array('title' => "", "class" => "", "data-id" => $data->id, "data-value" => $data->status_key_name === "done" ? "1" : "3", "data-act" => "update-cheque-status-checkbox")) . $data->id;
        $status = js_anchor($data->status_key_name ? lang($data->status_key_name) : $data->status_title, array('title' => "", "class" => "", "data-id" => $data->id, "data-value" => $data->status_id, "data-act" => "update-cheque-status"));
    


//cheque handler member show
        $cheque_handler_member = "";
        $memebr_id = $data->member_id;
if($data->member_type=='tm'){
        if ($data->member_id) {
            
            $options = array("id" =>$data->member_id);
            $list_data = $this->Users_model->get_details($options)->row();
            $cheque_handler_member .= lang("team_member") . ": " . $list_data->first_name." ".$list_data->last_name;
        }
    }else if($data->member_type=='om'){
        if ($data->member_id) {
           
            $options = array("id" => $data->member_id);
            $list_data = $this->Users_model->get_details($options)->row();
            $cheque_handler_member .= lang("outsource_member") . ": " . $list_data->first_name." ".$list_data->last_name;
        }
    }else if ($data->member_type=='clients'){
if ($data->member_id) {
            
             $options = array("id" => $data->member_id);
            $list_data = $this->Clients_model->get_details($options)->row();
            $cheque_handler_member .= lang("client_company") . ": " . $list_data->company_name."<br>"; 
           
        }

    }else if ($data->member_type=='vendors'){
if ($data->member_id) {
            
            $options = array("id" => $data->member_id);
            $list_data = $this->Vendors_model->get_details($options)->row();
            $cheque_handler_member .= lang("vendor_company") . ": " . $list_data->company_name."<br>"; 
            /*$description .= lang("vendor_contact_member") . ": " . $data->linked_user_name;*/
        }

    }elseif ($data->member_type=='others') {
if ($data->first_name) {
            
             
            $cheque_handler_member .= lang("other_contact") . ": " . $data->first_name." ". $data->last_name;
        }

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



        return array($data->id,
            $data->description,
            $cheque_handler_member,
            $data->bank_name,
            $data->account_number,
             $data->cheque_number, $data->cheque_category,
            (get_setting("currency_symbol").$data->amount),

             format_to_date($data->issue_date, false),$data->issue_date,
            $data->drawn_on,$deadline_text,$status,$files_link,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("Cheque_handler/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_cheque'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_cheque'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Cheque_handler/delete"), "data-action" => "delete-confirmation"))
        );
    }
function save_task_status($id = 0) {
        $this->access_only_team_members();
        $data = array(
            "status_id" => $this->input->post('value')
        );

        $save_id = $this->Cheque_handler_model->save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, "message" => lang('record_saved')));

            $task_info = $this->Cheque_handler_model->get_one($save_id);

           // log_notification("project_task_updated", array("id" => $task_info->id, "task_id" => $save_id, "activity_log_id" => get_array_value($data, "activity_log_id")));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }


//get clients dropdown
    private function _get_clients_dropdown() {
        $clients_dropdown = array(array("id" => "", "text" => "- " . lang("client") . " -"));
        $clients = $this->Clients_model->get_dropdown_list(array("company_name"));
        foreach ($clients as $key => $value) {
            $clients_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $clients_dropdown;
    }

//get others dropdown
    private function _get_others_dropdown() {
        $others_members = $this->Cheque_handler_model->get_all_where(array("deleted" => 0, "member_type" => "others"), 0, 0, "first_name")->result();

        $others_members_dropdown = array(array("id" => "", "text" => "- " . lang("others") . " -"));
        foreach ($others_members as $others_member) {
            $others_members_dropdown[] = array("id" => 
                $others_member->id, "text" => $others_member->first_name . " " . $others_member->last_name);
        }
       return json_encode($others_members_dropdown);
    }



     //get clients dropdown
    private function _get_vendors_dropdown() {
        $vendors_dropdown = array(array("id" => "", "text" => "- " . lang("vendor") . " -"));
        $vendors = $this->Vendors_model->get_dropdown_list(array("company_name"));
        foreach ($vendors as $key => $value) {
            $vendors_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $vendors_dropdown;
    }


    //get team members dropdown
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }
private function _get_rm_members_dropdown() {
        $rm_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"), 0, 0, "first_name")->result();
 
        $rm_members_dropdown = array(array("id" => "", "text" => "- " . lang("outsource_member") . " -"));
        foreach ($rm_members as $rm_member) {
            $rm_members_dropdown[] = array("id" => $rm_member->id, "text" => $rm_member->first_name . " " . $rm_member->last_name);
        }

        return json_encode($rm_members_dropdown);
    }
















}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */