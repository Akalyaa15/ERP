<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Loan extends MY_Controller {

    function __construct() {
        parent::__construct();

        //$this->init_permission_checker("expense");

        //$this->access_only_allowed_members();
        $this->init_permission_checker("loan");
    }

    //load the expenses list view
    //load the expenses list view
    function index() {
        $this->check_module_availability("module_loan");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("loan", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['categories_dropdown'] = $this->_get_categories_dropdown();
        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());
        $view_data['vendors_dropdown'] = json_encode($this->_get_vendors_dropdown());
        $view_data['rm_members_dropdown'] = $this->_get_rm_members_dropdown();
        $view_data['projects_dropdown'] = $this->_get_projects_dropdown();

        //$this->template->rander("loan/index", $view_data);
        if ($this->login_user->is_admin == "1")
        { 

            $this->template->rander("loan/index", $view_data);
        }
        else if ($this->login_user->user_type == "staff")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("loan/index", $view_data);
        }else {


       $this->template->rander("loan/index", $view_data);
    } 
    }
    //get categories dropdown
    private function _get_categories_dropdown() {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0 , "status" => "active"), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
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
    //get projects dropdown
    private function _get_projects_dropdown() {
        $projects = $this->Projects_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $projects_dropdown = array(array("id" => "", "text" => "- " . lang("project") . " -"));
        foreach ($projects as $project) {
            $projects_dropdown[] = array("id" => $project->id, "text" => $project->title);
        }

        return json_encode($projects_dropdown);
    }

    //load the expenses list yearly view
    function yearly() {
        $this->load->view("loan/yearly_income");
    }

    //load custom expenses list
    function custom() {
        $this->load->view("loan/custom_income");
    }

    //load the add/edit expense form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Loan_model->get_one($this->input->post('id'));
         $model_infos = $this->Users_model->get_one($this->input->post('user_id'));
        $view_data['categories_dropdown'] = $this->Expense_categories_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
       $view_data['voucher_dropdown'] = array("0" => "-") + $this->Voucher_model->get_dropdown_list(array("id"), "id", array("voucher_type_id" => '1'));
       $view_data['payment_status_dropdown'] = $this->Payment_status_model->get_dropdown_list(array("title"),"id",array("status" =>"active"));
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;
         $others = $this->Voucher_expenses_model->get_all_where(array("deleted" => 0, "member_type" => "others"))->result();
        $others_dropdown = array();

        foreach ($others as $other) {
            $others_dropdown[$other->phone] = $other->f_name . " " . $other->l_name;
        }

        $view_data['others_dropdown'] = array("0" => "-") + $others_dropdown;
        $rm_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"))->result();
        $rm_members_dropdown = array();

        foreach ($rm_members as $rm_member) {
            $rm_members_dropdown[$rm_member->id] = $rm_member->first_name . " " . $rm_member->last_name;
        }

        $view_data['rm_members_dropdown'] = array("0" => "-") + $rm_members_dropdown ;
        $view_data['projects_dropdown'] = array("0" => "-") + $this->Projects_model->get_dropdown_list(array("title"));
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
//add the clients and vendors 
$view_data['vendors_dropdown'] = array("" => "-")+ $this->Vendors_model->get_dropdown_list(array("company_name"),'id');
   $view_data['clients_dropdown'] =  array("" => "-")+$this->Clients_model->get_dropdown_list(array("company_name"),'id');

$view_data['client_members_dropdown'] = $this->_get_users_dropdown_select2_data();
$view_data['vendor_members_dropdown'] = $this->_get_users_dropdown_select2_data();
//$view_data['vendor_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"),'id',array("user_type" => "vendor"));

        $model_info->project_id = $model_info->project_id ? $model_info->project_id : $this->input->post('project_id');
        $model_info->user_id = $model_info->user_id ? $model_info->user_id : $this->input->post('user_id');
$view_data['model_infos'] = $model_infos;
        $view_data['model_info'] = $model_info;

//voucher id dropdown
       $po_info = $this->Voucher_model->get_one($model_info->voucher_no); 
        $voucher_id_dropdown = array(array("id" => "", "text" => "-"));
        $voucher_id_dropdown[] = array("id" => $model_info->voucher_no, "text" => $po_info->voucher_no?$po_info->voucher_no:get_voucher_id($model_info->voucher_no));
        $view_data['voucher_id_dropdown'] = $voucher_id_dropdown;

        
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("Loan", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $this->load->view('loan/modal_form', $view_data);
    }

     /* add or edit an estimate item */
private function _get_users_dropdown_select2_data($show_header = false) {
        $luts = $this->Users_model->get_all()->result();
        $lut_dropdown = array(array("id" => "", "text" => "-"));

        

        foreach ($luts as $code) {
            $lut_dropdown[] = array("id" => $code->id, "text" => $code->first_name." ".$code->last_name);
        }
        return $lut_dropdown;
    }

    
    //save an expense
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "loan_date" => "required",
            "category_id" => "required",
            "amount" => "required"
        ));

        $id = $this->input->post('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "loan");
        $new_files = unserialize($files_data);

    
   
    
    $member_type=$this->input->post('member_type');

    if($member_type=='tm'){
        $team_member=$this->input->post('loan_user_id');
        $member_type=$this->input->post('member_type');
        $company="";
        $vendor_company="";
    $phone=0;
    }elseif($member_type=='om'){
        $team_member=$this->input->post('loan_user_ids');
    $member_type=$this->input->post('member_type');
    $phone=0;
    $company="";
    $vendor_company="";
    }elseif($member_type=='others'){
        $team_member=0;
    $member_type=$this->input->post('member_type');
    $phone=$this->input->post('loan_user_idss');
    $company="";
    $vendor_company="";

    }elseif($member_type=='vendors'){
    $team_member=$this->input->post('vendor_contact');
    $member_type=$this->input->post('member_type');
    $phone=0;
    $company="";
    $vendor_company=$this->input->post('vendor_member');
    }elseif($member_type=='clients'){
        $team_member=$this->input->post('client_contact');
    $member_type=$this->input->post('member_type');
    $phone=0;
    $company=$this->input->post('client_member');
    $vendor_company="";
    }else{
         $team_member=$this->input->post('loan_user_id');
 
        $phone="";
        $member_type="";
    }
 
   $amount = unformat_currency($this->input->post('amount'));
  $interest = $this->input->post('interest');
  $interest_amount =  $amount*($interest/100);
 
  $total = $amount+$interest_amount;
 
        $data = array(
            "loan_date" => $this->input->post('loan_date'),
            "due_date" => $this->input->post('due_date'),
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "amount" => $amount,
            "project_id" => $this->input->post('loan_project_id'),
            "user_id" => $team_member,
            
           
           
           
          "interest_amount"=> $interest_amount,
            "total"=> $total,
            "voucher_no" => $this->input->post('voucher_no'),
             "currency"=>$this->input->post('currency'),
             "currency_symbol"=>$this->input->post('currency_symbol'),
             "payment_status" => $this->input->post('payment_status'),
           
            "interest" => $this->input->post('interest'),
            "member_type" => $member_type,
           "phone"=>$phone,
           "company"=>$company,
           "vendor_company"=>$vendor_company
        );
   


        //is editing? update the files if required
        if ($id) {
            $loan_info = $this->Loan_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $loan_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);
if($data["files"]=='a:0:{}'){
    echo json_encode(array("success" => false, 'message' => '*Uploading files are required'));
    exit();
}

           $data["last_activity_user"] =$this->login_user->id;
            $data["last_activity"] = get_current_utc_time();

        $save_id = $this->Loan_model->save($data, $id);

        // upadate voucher status
        if($this->input->post('payment_status')=="1"){
            $status='payment_in_progress';
        }else if($this->input->post('payment_status')=="2"){
            $status='payment_done';
      }else if($this->input->post('payment_status')=="3"){
            $status='payment_hold';
        }else if($this->input->post('payment_status')=="4"){
            $status='payment_received';
       }else if($this->input->post('payment_status')=="5"){
            $status='closed';
        }
        /*if ($save_id) {
            save_custom_fields("loan", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }*/
        if ($save_id) {
                    $estimate_data = array("status" => $status,"payments_handler"=>$this->login_user->id);
                        log_notification("voucher_application_".$status, array("voucher_id" => $this->input->post('voucher_no')));
            $estmate_info = $this->Voucher_model->get_one($this->input->post('voucher_no'));

$description='Changed the status from "'.lang($estmate_info->status).'" to "'.lang($status).'"';

    $comment_data = array(
            "created_by" =>  $this->login_user->id,
            "voucher_id" => $this->input->post('voucher_no'),
            "created_at" =>get_current_utc_time(),
            "description"=>$description,
            "files"=>'a:0:{}'
        );
    $comment_id = $this->Voucher_comments_model->save($comment_data);
            $estimate_id = $this->Voucher_model->save($estimate_data, $this->input->post('voucher_no'));
                 
            save_custom_fields("loan", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an expense
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $loan_info = $this->Loan_model->get_one($id);
        
        $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Loan_model->save($data, $id);

        if ($this->Loan_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            if ($loan_info->files) {
                $files = unserialize($loan_info->files);

                foreach ($files as $file) {
                    $source_path = $file_path . get_array_value($file, "file_name");
                    delete_file_from_directory($source_path);
                }
            }

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    //get the expnese list data
    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $project_id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');
        $user_ids = $this->input->post('user_ids');
        $client_id = $this->input->post('client_id');
        $vendor_id = $this->input->post('vendor_id');
        $status   = $this->input->post("status");
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("loan", $this->login_user->is_admin, $this->login_user->user_type);
        if ($user_ids) {
            
        
        $options = array("start_date" => $start_date, "end_date" => $end_date, "category_id" => $category_id, "project_id" => $project_id, "user_id" => $user_ids,"client_id" => $client_id,"vendor_id" => $vendor_id,"status" => $status, "custom_fields" => $custom_fields);
    }else{
         $options = array("start_date" => $start_date, "end_date" => $end_date, "category_id" => $category_id, "project_id" => $project_id, "user_id" => $user_id,"vendor_id" => $vendor_id,"client_id" => $client_id,"status" => $status,"custom_fields" => $custom_fields);
    }
        $list_data = $this->Loan_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of expnese list
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("loan", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Loan_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }


    //prepare a row of expnese list
    private function _make_row($data, $custom_fields) {

        $description = $data->description;
        if ($data->project_title) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("project") . ": " . $data->project_title;
        }
if($data->member_type=='tm'){
        if ($data->linked_user_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("team_member") . ": " . $data->linked_user_name;
        }
    }else if($data->member_type=='om'){
        if ($data->linked_user_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("outsource_member") . ": " . $data->linked_user_name;
        }
    }else if ($data->member_type=='clients'){
if ($data->client_company) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("client_company") . ": " . $data->client_company."<br>"; 
            $description .= lang("client_contact_member") . ": " . $data->linked_user_name;
        }

    }else if ($data->member_type=='vendors'){
if ($data->vendor_company) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("vendor_company") . ": " . $data->vendor_company."<br>"; 
            $description .= lang("vendor_contact_member") . ": " . $data->linked_user_name;
        }

    }elseif ($data->member_type=='others') {
if ($data->phone) {
            if ($description) {
                $description .= "<br /> ";
            }
             
            $description .= lang("other_contact") . ": " . $data->phone." ". $data->l_name;
        }

    }

    $due = 0;
        if ($data->total) {
            $due = ignor_minor_value($data->total - $data->paid_amount);
        }


    $title = modal_anchor(get_uri("loan/loan_view"), $data->category_title . $icon, array("title" => lang('loan_info') . " #$data->id", "data-post-id" => $data->id, "class" => $unread_comments_class));
    $item = "<div style='color:#4e5e6a;font-size:12px;'>$description</div>";

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("loan/file_preview/" . $file_name)));
                }
            }
        }

         $files_links = "";
       
            $payment_files = $this->Loan_payments_list_model->get_details(array("loan_id" => $data->id))->result();
            foreach ($payment_files as $payment_file) {
                # code...
             if ($payment_file->files) {
            $files = unserialize($payment_file->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_links .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("notes/file_preview/" . $file_name)));
                }
            }
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

       // voucher no 
if($data->voucher_no){
    $voucher_info = $this->Voucher_model->get_one($data->voucher_no);
     $voucher_order_url = anchor(get_uri("voucher/view/" . $data->voucher_no), $voucher_info->voucher_no?$voucher_info->voucher_no:get_voucher_id($data->voucher_no));
 }else{
    $voucher_order_url = "-";
 }

        $row_data = array(
            $voucher_order_url,
            $data->loan_date,
            
            format_to_date($data->loan_date, false),
            //$data->category_title,
            $data->due_date,
            $title,
            $data->title,
            $item,
            //nl2br($description),
            to_currency($data->amount,$data->currency_symbol),
            $data->interest,
            to_currency($data->interest_amount,$data->currency_symbol),
            to_currency($data->total,$data->currency_symbol),
            to_currency($data->paid_amount,$data->currency_symbol),
           to_currency($due,$data->currency_symbol),
           $files_link.$files_links,
           $this->_get_loan_status_label($data),
           $this->_get_loan_voucher_status_label($data),
            $last_activity_by_user_name,
            $last_activity_date,

            //to_currency($data->igst_tax),
            //to_currency($data->total)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("loan/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_loan'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_loan'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("loan/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    private function _get_loan_status_label($data, $return_html = true) {
        return get_loan_status_label($data, $return_html);
    }

    private function _get_loan_voucher_status_label($estimate_info, $return_html = true) {
        $estimate_status_class = "label-default";

        //don't show sent status to client, change the status to 'new' from 'sent'

         $payment_status_info = $this->Payment_status_model->get_one($estimate_info->payment_status);
       
        

        $estimate_status = "<span class='label $estimate_status_class large'>" .  $payment_status_info->title . "</span>";
        if ($return_html) {
            return $estimate_status;
        } else {
            return $estimate_info->status;
        }
    }

    function file_preview($file_name = "") {
        if ($file_name) {

            $view_data["file_url"] = get_file_uri(get_setting("timeline_file_path") . $file_name);
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);

            $this->load->view("loan/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for ticket */

    function validate_loan_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    //load the expenses yearly chart view
    function yearly_chart() {
        $this->load->view("loan/yearly_chart");
    }

    function yearly_chart_data() {

        $months = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
        $result = array();

        $year = $this->input->post("year");
        if ($year) {
            $loan = $this->Loan_model->get_yearly_loan_chart($year);
            $values = array();
            foreach ($loan as $value) {
                $values[$value->month - 1] = $value->total; //in array the month january(1) = index(0)
            }

            foreach ($months as $key => $month) {
                $value = get_array_value($values, $key);
                $result[] = array(lang("short_" . $month), $value ? $value : 0);
            }

            echo json_encode(array("data" => $result));
        }
    }


    
function get_voucher_details() {
        $item = $this->Loan_model->get_voucher_expense_details($this->input->post("item_name"));
        // voucher files
        $files_link = "";
        if ($item->files) {
            $files = unserialize($item->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("voucher/file_preview/" . $file_name)));
                }
            }
        }$files_link .= "<a title='Voucher pdf' href='".get_uri("voucher/download_pdf/" . $item->estimate_id)."'class='pull-left font-22 mr10 fa fa-file-pdf-o'></a>";
        $items = $this->Voucher_model->get_one($this->input->post("item_name"));

        $status=lang($items->status);

        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item,"item_files" => $files_link,"item_status"=>$status));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    function get_voucher_id() {

        $options = array("user_id" => $_REQUEST["team_member"] );
$list_data = $this->Loan_model->get_details($options)->result();
if($list_data){
        $loan_items = array();
foreach ($list_data as $code) {
            $loan_items[] = $code->voucher_no;
        }
$aa=json_encode($loan_items);
$vv=str_ireplace("[","(",$aa);
$loan_voucher_no=str_ireplace("]",")",$vv);
       
}else{
    $loan_voucher_no="('empty')";
}
        $itemss = $this->Loan_model->get_voucher_id($this->input->post("team_member"),$loan_voucher_no);
         $suggestions = array();
      foreach ($itemss as $items) {
        $po_info = $this->Voucher_model->get_one($items->estimate_id); 
           $suggestions[] = array("id" => $items->estimate_id, "text" => $po_info->voucher_no?$po_info->voucher_no:get_voucher_id($items->estimate_id)/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }
function get_voucher_id_others() {
    $options = array("user_id" => $_REQUEST["team_member"] );
$list_data = $this->Loan_model->get_details($options)->result();
if($list_data){
        $loan_items = array();
foreach ($list_data as $code) {
            $loan_items[] = $code->voucher_no;
        }
$aa=json_encode($loan_items);
$vv=str_ireplace("[","(",$aa);
$loan_voucher_no=str_ireplace("]",")",$vv);
       
}else{
    $loan_voucher_no="('empty')";
}
        $itemss = $this->Loan_model->get_voucher_id_for_others($this->input->post("phone"),$loan_voucher_no);
         $suggestions = array();


      foreach ($itemss as $items) {
        $po_info = $this->Voucher_model->get_one($items->estimate_id);
           $suggestions[] = array("id" => $items->estimate_id, "text" => $po_info->voucher_no?$po_info->voucher_no:get_voucher_id($items->estimate_id)/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }

    function get_client_voucher_id() {

        $options = array("user_id" => $_REQUEST["team_member"] );
$list_data = $this->Loan_model->get_details($options)->result();
if($list_data){
        $loan_items = array();
foreach ($list_data as $code) {
            $loan_items[] = $code->voucher_no;
        }
$aa=json_encode($loan_items);
$vv=str_ireplace("[","(",$aa);
$loan_voucher_no=str_ireplace("]",")",$vv);
       
}else{
    $loan_voucher_no="('empty')";
}
        $itemss = $this->Loan_model->get_client_voucher_id($this->input->post("team_member"),$loan_voucher_no);
         $suggestions = array();
      foreach ($itemss as $items) {
        $po_info = $this->Voucher_model->get_one($items->estimate_id);
           $suggestions[] = array("id" => $items->estimate_id, "text" => $po_info->voucher_no?$po_info->voucher_no:get_voucher_id($items->estimate_id)/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }


function get_client_contacts() {
        $itemss = $this->Loan_model->get_client_contacts($this->input->post("team_member"));
         $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->first_name." ".$items->last_name/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }
    function get_vendor_contacts() {
        $itemss = $this->Loan_model->get_vendor_contacts($this->input->post("team_member"));
         $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->first_name." ".$items->last_name/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }

function loan_view() {

        $loan_id = $this->input->post('id');
        $model_info = $this->Loan_model->get_details(array("id" => $loan_id))->row();
        if (!$model_info->id) {
            show_404();
        }
    

        $view_data['model_info'] = $model_info;
       
        $view_data['loan_id'] = $loan_id;

        

        //get checklist items
    /* $checklist_items_array = array();
        $checklist_items = $this->Loan_payments_list_model->get_details(array("task_id" => $task_id))->result();
        foreach ($checklist_items as $checklist_item) {
            $checklist_items_array[] = $this->_make_payment_row($checklist_item);
        }
       $view_data["checklist_items"] = json_encode($checklist_items_array); 

      */
       $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));

        $this->load->view('loan/view', $view_data);
    }


/* checklist */

    function save_checklist_item() {

        $id = $this->input->post("id");

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

       $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "loan_payment");
        $new_files = unserialize($files_data);

        $data = array(
            "loan_id" => $id,
            "title" => $this->input->post("checklist-add-item"),
             "payment_date" => $this->input->post("checklist-add-item-date"),
              "payment_method_id"=>$this->input->post("payment_method_id"),
             "description"=>$this->input->post("description"),
             "reference_number"=>$this->input->post("reference_number"),
        );

         $note_info = $this->Loan_payments_list_model->get_one($id);
          $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $note_info->files, $new_files);

            $data["files"] = serialize($new_files);
        if($data["files"]=='a:0:{}'){
    echo json_encode(array("success" => false, 'message' => '*Uploading files are required'));
    exit();
}

        $save_id = $this->Loan_payments_list_model->save($data);

        if ($save_id) {
            $item_info = $this->Loan_payments_list_model->get_one($save_id);
            echo json_encode(array("success" => true, "data" => $this->_make_payment_row($item_info), 'id' => $save_id));
        } else {
            echo json_encode(array("success" => false));
        }
    }


/* list of invoice payments, prepared for datatable  */

    function loan_payment_list_data($id = 0) {
        //$this->access_only_allowed_members();

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        //$payment_method_id = $this->input->post('payment_method_id');
        $options = array("loan_id" => $id);

        $list_data = $this->Loan_payments_list_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

/* prepare a row of invoice payment list table */

    private function _make_payment_row($data) {
        $invoice_url = "";

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
        //$this->access_only_allowed_members_or_client_contact($data->client_id);

        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        }

     //$delete=   js_anchor("<i class='fa fa-times pull-right p3'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("loan/delete_payment"), "data-action" => "delete-confirmation"));
        return array(
            $data->payment_date,
             $data->loan_payment_name,
             $data->reference_number,
             $data->title,
             $files_link,

          js_anchor("<i class='fa fa-times'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("loan/delete_payment"), "data-action" => "delete-confirmation"))
            //format_to_date($data->payment_date, false),
           
            
            
        );
    }


    /* delete or undo a payment */

    function delete_payment() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

         $id = $this->input->post('id');
      
            if ($this->Loan_payments_list_model->delete($id)) {
               
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
      
        }
    }


function get_vendors_invoice_paid_suggestion() {
        $item = $this->Loan_payments_list_model->get_vendors_invoice_paid_amount_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }





}

/* End of file expenses.php */
/* Location: ./application/controllers/expenses.php */