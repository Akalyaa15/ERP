<?php 
    
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Voucher extends MY_Controller {
 
    function __construct() {
        parent::__construct();
        $this->init_permission_checker("voucher");
         //$this->access_only_allowed_members();
    }
 /* load estimate list view */
     function index() {
        $this->check_module_availability("module_voucher");
       // $view_data['can_request_estimate'] = false;

         $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("voucher", $this->login_user->is_admin, $this->login_user->user_type);
         $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
         $view_data['line_manager_dropdown'] = $this->_get_line_members_dropdown();
         /*if ($this->login_user->user_type === "staff") {
           // $this->access_only_allowed_members();

            $this->template->rander("voucher/index", $view_data);
        } */
        $access_info = $this->get_access_info("voucher");http://localhost:8000/index.php/voucher
                $voucher_access_all = $access_info->access_type;
                $voucher_access = $access_info->allowed_members;
                 if($this->login_user->is_admin ||in_array($this->login_user->id,$voucher_access)||$voucher_access_all=="all"){
           // $this->access_only_allowed_members();

            $this->template->rander("voucher/index", $view_data);
        } else{
            //redirect('voucher/voucher_info');
           $this->template->rander("voucher/index", $view_data);

        }
       
    }     //get team members dropdown
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("team_members") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }

    //get team members dropdown
    private function _get_line_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("line_manager") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }

    //load the yearly view of estimate list
    function yearly() {
        $this->load->view("estimates/yearly_estimates");
    } 
   /* load new estimate modal */
function get_payment_method_dropdown() {
        $this->access_only_team_members();

        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0))->result();

        $payment_method_dropdown = array(array("id" => "", "text" => "- " . lang("payment_methods") . " -"));
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[] = array("id" => $value->id, "text" => $value->title);
        }

        return json_encode($payment_method_dropdown);
    }

//load voucher info view
    function voucher_info() {
        $this->check_module_availability("module_voucher");

        $view_data['user_id'] = $this->login_user->id;
        if ($this->input->is_ajax_request()) {
            $this->load->view("voucher/voucher_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            $this->template->rander("voucher/voucher_info", $view_data);
        }
    }
 public function modal_form() {
        // Load models if not already loaded
        $this->load->model('Voucher_model');
        $this->load->model('Voucher_types_model');
        $this->load->model('Payment_methods_model');
        $this->load->model('Users_model');
        $this->load->model('Custom_fields_model');
        
        // Validate submitted data
        validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric"
        ));
        
        $client_id = $this->input->post('client_id');
        $view_data['model_info'] = $this->Voucher_model->get_one($this->input->post('id'));
    
        if (!$view_data['model_info']) {
            show_error('Voucher not found.');
            return;
        }
    
        $view_data['voucher_types_dropdown'] = $this->Voucher_types_model->get_dropdown_list(array("title"), "id", array("deleted" => 0, "status" => "active"));
        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
        
        $project_client_id = $client_id;
        if (isset($view_data['model_info']->client_id)) {
            $project_client_id = $view_data['model_info']->client_id;
        }
        
        $view_data['clients_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), 'id', array("user_type" => "staff"));
        $view_data['client_id'] = $client_id;
    
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("estimates", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
    
        // Ensure the view file exists
        if (file_exists(APPPATH . 'views/voucher/modal_form.php')) {
            $this->load->view('voucher/modal_form', $view_data);
        } else {
            show_error('The view file does not exist.');
        }
    }
    
    
    /* add or edit an estimate */

    function save() {
        //$this->access_only_allowed_members();
    
        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_client_id" => "required|numeric",
            "estimate_date" => "required",
            "valid_until" => "required"
        ));
    
        $client_id = $this->input->post('estimate_client_id');
        $id = $this->input->post('id');
    
        if ($this->input->post('line_manager') == '0') {
            echo json_encode(array("success" => false, 'message' => lang('lien_manager_not_assign')));
            exit();
        }
    
        $estimate_data = array(
            "payment_method_id" => $client_id,
            "estimate_date" => $this->input->post('estimate_date'),
            "voucher_type_id" => $this->input->post('voucher_type_id'),
            "valid_until" => $this->input->post('valid_until'),
            "line_manager" => $this->input->post('line_manager'),
            "note" => $this->input->post('estimate_note'),
            "status" => 'draft'
        );
    
        if (!$id) {
            $estimate_data["created_user_id"] = $this->input->post('created_user_id');
        }
    
        if ($id) {
            // check if the invoice number already exists when updating
            $estimate_data["voucher_no"] = $this->input->post('voucher_no');
            if ($this->Voucher_model->is_estimate_no_exists($estimate_data["voucher_no"], $id)) {
                echo json_encode(array("success" => false, 'message' => lang('vo_no_already')));
                exit();
            }
        }
    
        // create new invoice number if it doesn't already exist
        if (!$id) {
            $get_last_estimate_id = $this->Voucher_model->get_last_estimate_id_exists();
            $estimate_no_last_id = ($get_last_estimate_id->id + 1);
            $estimate_prefix = get_voucher_id($estimate_no_last_id);
    
            if ($this->Voucher_model->is_estimate_no_exists($estimate_prefix)) {
                echo json_encode(array("success" => false, 'message' => $estimate_prefix . " " . lang('vo_no_already')));
                exit();
            }
        }
    
        $estimate_id = $this->Voucher_model->save($estimate_data, $id);
    
        // Check if the estimate ID exists before proceeding
        if (!$estimate_id) {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            return;
        }
    
        $options = array(
            "estimate_id" => $estimate_id,
        );
    
        $ve_id = $this->Voucher_expenses_model->get_details($options)->row();
    
        // Check if $ve_id is not null before accessing its id property
        if ($ve_id) {
            $s = $this->Voucher_expenses_model->delete($ve_id->id);
        }
    
        // Save the new invoice number
        if (!$id) {
            $estimate_prefix = get_voucher_id($estimate_id);
            $estimate_prefix_data = array(
                "voucher_no" => $estimate_prefix
            );
            $estimate_prefix_id = $this->Voucher_model->save($estimate_prefix_data, $estimate_id);
        }
    
        save_custom_fields("voucher", $estimate_id, $this->login_user->is_admin, $this->login_user->user_type);
    
        echo json_encode(array("success" => true, "data" => $this->_row_data($estimate_id), 'id' => $estimate_id, 'message' => lang('record_saved')));
    }
    
    function remarks($voucher_id=0,$status) {
//$view_data['model_info'] = $this->Tickets_model->get_one($ticket_id);

        $view_data['voucher_id'] = $voucher_id;       
        $view_data['status'] = $status;       
        //prepare assign to list
//need to change(temporary change)
http://localhost:8000/index.php/voucher

        $this->load->view('voucher/remarks', $view_data);
    }
function save_remarks() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $description = $this->input->post('description');


$voucher_data = array(

            "created_by" =>  $this->login_user->id,
            "voucher_id" => $id,
            "created_at" =>get_current_utc_time(),
            "description"=>$description,
            "files"=>'a:0:{}'

        );
        $voucher_id = $this->Voucher_comments_model->save($voucher_data,$voucher_id);
 if ($voucher_id) {
        $this->update_voucher_status($id,$status);
echo json_encode(array("success" => true,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    //update estimate status
function update_voucher_status($estimate_id, $status) {
        if ($estimate_id && $status) {
            $estmate_info = $this->Voucher_model->get_one($estimate_id);
            //$this->access_only_allowed_members_or_client_contact($estmate_info->client_id);


            if ($this->login_user->user_type == "client") {
                //updating by client
                //client can only update the status once and the value should be either accepted or declined
                if ($estmate_info->status == "sent" && ($status == "accepted" || $status == "modified")) {

                    $estimate_data = array("status" => $status);
                    $estimate_id = $this->Estimates_model->save($estimate_data, $estimate_id);

                    //create notification
                    if ($status == "accepted") {
                        log_notification("estimate_accepted", array("estimate_id" => $estimate_id));
                    } else if ($status == "declined") {
                        log_notification("estimate_rejected", array("estimate_id" => $estimate_id));
                    }
                }
            } else {
                //updating by team members

                if ($status == "paid" || $status == "accepted" || $status == "verified_by_manager" || $status == "rejected_by_manager" || $status == "approved_by_accounts" || $status == "rejected_by_accounts" || $status == "modified"|| $status == "resubmitted") {
                    
                    $estimate_data = array("status" => $status);
                    if( $status == "approved_by_accounts" ){
                    $estimate_data = array("status" => $status,"accounts_handler"=>$this->login_user->id);
                        log_notification("voucher_application_approved_by_accounts", array("voucher_id" => $estimate_id));
                    }
                    if( $status == "rejected_by_accounts"){
                        log_notification("voucher_application_rejected_by_accounts", array("voucher_id" => $estimate_id));
                    }                    
                    if( $status == "resubmitted"){
                        log_notification("voucher_application_resubmitted", array("voucher_id" => $estimate_id));
                    }
                                             if ($status == "paid") {
                    $estimate_data = array("status" => $status,"payments_handler"=>$this->login_user->id);
                        log_notification("voucher_application_paid", array("voucher_id" => $estimate_id));
                    }
if ($status == "verified_by_manager" || $status == "rejected_by_manager") {
 $estimate_data["line_manager"]=$this->login_user->id;
}
                 $estimate_id = $this->Voucher_model->save($estimate_data, $estimate_id);
$description='Changed the status from "'.lang($estmate_info->status).'" to "'.lang($status).'"';

    $comment_data = array(
            "created_by" =>  $this->login_user->id,
            "voucher_id" => $estimate_id,
            "created_at" =>get_current_utc_time(),
            "description"=>$description,
            "files"=>'a:0:{}'
        );
    $comment_id = $this->Voucher_comments_model->save($comment_data);
                    //create notification
                    if ($status == "verified_by_manager") {
                        log_notification("voucher_application_approved_by_manager", array("voucher_id" => $estimate_id));
                    }

                    if ($status == "rejected_by_manager") {
                        log_notification("voucher_application_rejected_by_manager", array("voucher_id" => $estimate_id));                    }
                }
            }
        }
    }                    
    /* delete or undo an estimate */

    function delete() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        if ($this->input->post('undo')) {
            if ($this->Voucher_model->delete($id, true)) {
         
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Voucher_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimates, prepared for datatable  */

    function list_data() {
        //$this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("voucher", $this->login_user->is_admin, $this->login_user->user_type);
$voucher=$this->input->post("voucher");
if($this->login_user->is_admin&&$voucher=="line_manager"){
    $voucher="line_manager_admin";
}
        $options = array(
            $voucher => $this->login_user->id,         
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "team_member" => $this->input->post('team_member'),
            "line_manager_dropdown" => $this->input->post('line_manager_dropdown'),
            "custom_fields" => $custom_fields
        );
if($this->login_user->department==='09' &&$voucher=='line_manager'){
     $options = array(
            "is_accountant" => '1',
            "created_by" =>$this->login_user->id,         
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "team_member" => $this->input->post('team_member'),
            "line_manager_dropdown" => $this->input->post('line_manager_dropdown'),
            "custom_fields" => $custom_fields
        );
}
if($this->login_user->is_admin &&$voucher=='all'){
     $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "team_member" => $this->input->post('team_member'),
            "line_manager_dropdown" => $this->input->post('line_manager_dropdown'),
            "custom_fields" => $custom_fields
        );
}        $list_data = $this->Voucher_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
if($this->login_user->department==='09' &&$voucher=='line_manager'){
     $options = array(
            $voucher => $this->login_user->id,         
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "team_member" => $this->input->post('team_member'),
            "line_manager_dropdown" => $this->input->post('line_manager_dropdown'),
            "custom_fields" => $custom_fields
        );
     $list_data = $this->Voucher_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
}
        echo json_encode(array("data" => $result));
    }

    /* list of estimate of a specific client, prepared for datatable  */

    function estimate_list_data_of_client($client_id) {
        $this->access_only_allowed_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("client_id" => $client_id, "status" => $this->input->post("status"), "custom_fields" => $custom_fields);

        if ($this->login_user->user_type == "client") {
            //don't show draft estimates to clients.
            $options["exclude_draft"] = true;
        }

        $list_data = $this->Estimates_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of estimate list table */

    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("voucher", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Voucher_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of estimate list table */

    private function _make_row($data, $custom_fields) {
        $estimate_no_value = $data->voucher_no ? $data->voucher_no : get_voucher_id($data->id);
        $estimate_no_url = "";
        if ($this->login_user->user_type == "staff") {
             $estimate_no_url = anchor(get_uri("voucher/view/" . $data->id), $estimate_no_value);
        } else {
             $estimate_no_url = anchor(get_uri("voucher/preview/" . $data->id), $estimate_no_value);
        }
    
        // Ensure $data->first_name, $data->last_name, and $data->client_id are set
        $first_name = isset($data->first_name) ? $data->first_name : '';
        $last_name = isset($data->last_name) ? $data->last_name : '';
        $client_id = isset($data->client_id) ? $data->client_id : '';
    
        $row_data = array(
            $estimate_no_url,
            anchor(get_uri("team_members/view/" . $client_id), $first_name . " " . $last_name),
            $data->estimate_date,
            format_to_date($data->valid_until, false),
            $data->note,
            $data->type_title,
            $data->title,
            $this->_get_estimate_status_label($data),
        );
    
        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => isset($data->$cf_id) ? $data->$cf_id : ''), true);
        }
    
        if ($data->status == 'draft' || $data->status == 'modified') {
            $row_data[] = modal_anchor(get_uri("voucher/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_voucher'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_voucher'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("voucher/delete"), "data-action" => "delete-confirmation"));
        } else {
            $row_data[] = '-';
        }
    
        return $row_data;
    }
    
    //prepare estimate status label 
    private function _get_estimate_status_label($estimate_info, $return_html = true) {
        $estimate_status_class = "label-default";

        //don't show sent status to client, change the status to 'new' from 'sent'

        if ($this->login_user->user_type == "client") {
            if ($estimate_info->status == "sent") {
                $estimate_info->status = "new";
            } else if ($estimate_info->status == "declined") {
                $estimate_info->status = "rejected";
            }
        }

        if ($estimate_info->status == "draft") {
            $estimate_status_class = "label-default";
        } else if ($estimate_info->status == "accepted" || $estimate_info->status == "rejected") {
            $estimate_status_class = "label-success";
        } else if ($estimate_info->status == "applied") {
            $estimate_status_class = "label-danger";
        } else if ($estimate_info->status == "sold") {
            $estimate_status_class = "label-warning";
        } else if ($estimate_info->status == "new") {
            $estimate_status_class = "label-warning";
        }else if ($estimate_info->status == "modified") {
            $estimate_status_class = "label-warning";
        }

        $estimate_status = "<span class='label $estimate_status_class large'>" . lang($estimate_info->status) . "</span>";
        if ($return_html) {
            return $estimate_status;
        } else {
            return $estimate_info->status;
        }
    }

    /* load estimate details view */

function view($estimate_id = 0) {
        //$this->access_only_allowed_members();

        if ($estimate_id) {

            $view_data = get_voucher_making_data($estimate_id);

            if ($view_data) {
                $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
                $view_data['estimate_status'] = $this->_get_estimate_status_label($view_data["estimate_info"], false);

                $access_info = $this->get_access_info("voucher");
                $view_data["voucher_access_all"] = $access_info->access_type;
                $view_data["voucher_access"] = $access_info->allowed_members;
           $options = array("estimate_id" => $estimate_id);     
                $view_data["voucher_expense"] = $this->Voucher_expenses_model->get_details($options)->row();
                
                $view_data["can_create_projects"] = $this->can_create_projects();
                $sort_as_decending = get_setting("show_recent_ticket_comments_at_the_top");
                $comments_options = array(
                    "voucher_id" => $estimate_id,
                    "sort_as_decending" => $sort_as_decending
                );
                $view_data['comments'] = $this->Voucher_comments_model->get_details($comments_options)->result();

                $view_data["sort_as_decending"] = $sort_as_decending;

                $this->template->rander("voucher/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* estimate total section */
    private function _get_estimate_total_view($estimate_id = 0) {
        
        return $this->load->view('estimates/estimate_total_section', $view_data, true);
    }

    /* load item modal */

    function item_modal_form() {
        // $this->access_only_allowed_members();
    
        validate_submitted_data(array(
            "id" => "numeric"
        ));
    
        $estimate_id = $this->input->post('estimate_id');
        $view_data['model_info'] = $this->Voucher_expenses_model->get_one($this->input->post('id'));
        $view_data['country_dropdown'] = $this->_get_country_dropdown_select2_data();
        $view_data['categories_dropdown'] = $this->Expense_categories_model->get_dropdown_list(array("title"));
        $view_data['vendors_dropdown'] = $this->Vendors_model->get_dropdown_list(array("company_name"), 'id');
        $view_data['clients_dropdown'] = $this->Clients_model->get_dropdown_list(array("company_name"), 'id');
    
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();
    
        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }
    
        $view_data['client_members_dropdown'] = $this->_get_users_dropdown_select2_data();
        $view_data['vendor_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), 'id', array("user_type" => "vendor"));
        $view_data['members_dropdown'] = array("0" => "") + $members_dropdown;
    
        $rm_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"))->result();
        $rm_members_dropdown = array();
    
        foreach ($rm_members as $rm_member) {
            $rm_members_dropdown[$rm_member->id] = $rm_member->first_name . " " . $rm_member->last_name;
        }
    
        $view_data['rm_members_dropdown'] = array("" => "-") + $rm_members_dropdown;
        $view_data['members_dropdown'] = array("" => "-") + $members_dropdown;
    
        // Project dropdown check for the current logged-in user's projects
        if ($this->login_user->is_admin) {
            $view_data['projects_dropdown'] = array("0" => "-") + $this->Projects_model->get_dropdown_list(array("title"));
        } else {
            $project_options = array(
                "user_id" => $this->login_user->id,
            );
    
            $project_data = $this->Projects_model->get_details($project_options)->result();
            $projects_dropdown = array("" => "-");
    
            foreach ($project_data as $project) {
                $projects_dropdown[$project->id] = $project->title;
            }
    
            $view_data['projects_dropdown'] = $projects_dropdown;
        }
    
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
    
        // Ensure model_info is defined before using it
        $model_info = $view_data['model_info'];
        $model_info->project_id = $model_info->project_id ? $model_info->project_id : $this->input->post('project_id');
        $model_info->user_id = $model_info->user_id ? $model_info->user_id : $this->input->post('user_id');
        if (!$estimate_id) {
            $estimate_id = $model_info->estimate_id;
        }
    
        $view_data['estimate_id'] = $estimate_id;
    
        // Fetch custom fields data
        $view_data['custom_fields'] = $this->Custom_fields_model->get_custom_fields_for_context('voucher_expense');
    
        // Load the view with the data
        $this->load->view('voucher/voucher_expense_form', $view_data);
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

     /* add or edit an estimate item */
private function _get_country_dropdown_select2_data($show_header = false) {
        $countrys = $this->Countries_model->get_all()->result();
        $country_dropdown = array(array("id" => "", "text" => "-"));

        

        foreach ($countrys as $country) {
            $country_dropdown[] = array("id" => $country->id, "text" => $country->countryName);
        }
        return $country_dropdown;
    }

     /*function get_currency_convert($currency_name) {
       
    $default_curr =get_setting("default_currency");
    


$currency= $currency_name."_".$default_curr;
if($currency_name !== $default_curr){              

     $connected = @fsockopen("www.google.com", 80);            
if ($connected){
        $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
    $response_value   =   $cur_val->$currency;
    }
}
return $response_value;
       
    }*/
    function save_item() {
        //$this->access_only_allowed_members();

    

        $estimate_id = $this->input->post('estimate_id');

        validate_submitted_data(array(
            "id" => "numeric",
            "expense_date" => "required",
            "category_id" => "required",
            "amount" => "required",
        ));

        $id = $this->input->post('id');
         $default_curr =get_setting("default_currency");
$default_country = get_setting("company_country");
$voucher_country = $this->input->post('country_id');
$currency_name = $this->input->post('currency');
$currency= $currency_name."_".$default_curr;



if($voucher_country !== $default_country){              

     $connected = @fsockopen("www.google.com", 80);            
if ($connected){
       $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
$cur_val = json_decode($currency_rate);
$response_value   =   $cur_val->$currency; 
if ($response_value==null) {
  echo json_encode(array("success" => false, 'message' => "Check Your Currency Code"));
  exit();
}
    }else{
        echo json_encode(array("success" => false, 'message' => lang('please_check_your_internet')));
                exit();
    } 
}else if($voucher_country == $default_country){
              
$response_value   = 1;
     
}
$amount = unformat_currency($this->input->post('amount'));
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "voucher");
        $new_files = unserialize($files_data);
$client_id = $this->input->post('estimate_client_id');
$outsource = $this->input->post('estimate_client_idss');
$member=$this->input->post('member_type');
$receive_member=$this->input->post('r_member_type');
        if(($this->input->post('expense_project_id')==0)&&($receive_member=="clients"||$receive_member=="vendors"||$member=="clients")){
        echo json_encode(array("success" => false, 'message' => lang('Select_the_project')));
                exit();
    } 
if($this->input->post('expense_project_id')>0){
    if($this->input->post('line_manager')==0){
        echo json_encode(array("success" => false, 'message' => lang('Select_the_manager')));
                exit();
    }
    } 
    if($receive_member=='tm'){
    $r_user_id=$this->input->post('r_estimate_client_id');
    $r_f_name="";
    $r_l_name="";
    $r_phone="";
    $r_address="";
    $r_represent="";
}elseif($receive_member=='om'){
    $r_user_id=$this->input->post('r_estimate_client_idss');
    $r_f_name="";
    $r_l_name="";
    $r_phone="";
    $r_address="";
    $r_represent="";
}elseif($receive_member=='others'){
    $r_user_id=0;
    $r_f_name=$this->input->post('r_f_name');
    $r_l_name=$this->input->post('r_l_name');
     $r_phone=$this->input->post('r_phone');
    $r_address=$this->input->post('r_address');
    $r_represent="";
}elseif($receive_member=='clients'){
    $r_user_id=$this->input->post('r_client_member');
    $r_f_name="";
    $r_l_name="";
     $r_phone="";
    $r_address="";
    $r_represent=$this->input->post('r_represent');
}elseif($receive_member=='vendors'){
    $r_user_id=$this->input->post('r_vendor_member');
    $r_f_name="";
    $r_l_name="";
     $r_phone="";
    $r_address="";
    $r_represent=$this->input->post('r_represents');
    
}
if($member=='others'){
        $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "drawn_on" => $this->input->post('drawn_on'),
            "cheque_no" => $this->input->post('cheque_no'),
            "amount" => unformat_currency($this->input->post('amount')),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => 0,
            "f_name" => $this->input->post('first_name'),
            "l_name" => $this->input->post('last_name'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
           "member_type" => $this->input->post('member_type'),
           "r_member_type" => $this->input->post('r_member_type'),
           "i_represent" => $r_represent,
           "r_represent" =>$r_represent,
            "r_user_id" => $r_user_id,
            "r_f_name" =>$r_f_name,
            "r_l_name" => $r_l_name,
            "r_address" => $r_address,
            "r_phone" => $r_phone,
            "convert_amount"  => $response_value*$amount,
"currency"=>$this->input->post('currency'),
"currency_symbol"=>$this->input->post('currency_symbol'),
"country_id"=>$this->input->post('country_id'),
        ); 
    }else if($member=='tm'){
          $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "drawn_on" => $this->input->post('drawn_on'),
            "cheque_no" => $this->input->post('cheque_no'),
            "amount" => unformat_currency($this->input->post('amount')),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('estimate_client_id'),
"member_type" => $this->input->post('member_type'),
"r_member_type" => $this->input->post('r_member_type'),
           "i_represent" => "",
           "r_represent" =>$r_represent,
            "r_user_id" => $r_user_id,
            "r_f_name" =>$r_f_name,
            "r_l_name" => $r_l_name,
            "r_address" => $r_address,
            "r_phone" => $r_phone,
        "convert_amount"  => $response_value*$amount,
"currency"=>$this->input->post('currency'),
"currency_symbol"=>$this->input->post('currency_symbol'),
"country_id"=>$this->input->post('country_id'),


    );
          
        }else if($member=='om'){
$data = array(
            "expense_date" => $this->input->post('expense_date'),
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "drawn_on" => $this->input->post('drawn_on'),
            "cheque_no" => $this->input->post('cheque_no'),
            "amount" => unformat_currency($this->input->post('amount')),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('estimate_client_idss'),
"member_type" => $this->input->post('member_type'),
"r_member_type" => $this->input->post('r_member_type'),
           "i_represent" => "",
           "r_represent" =>$r_represent,
            "r_user_id" => $r_user_id,
            "r_f_name" =>$r_f_name,
            "r_l_name" => $r_l_name,
            "r_address" => $r_address,
            "r_phone" => $r_phone,
            "convert_amount"  => $response_value*$amount,
            "currency"=>$this->input->post('currency'),
            "currency_symbol"=>$this->input->post('currency_symbol'),
            "country_id"=>$this->input->post('country_id'),


        );
        }elseif($member=='clients'){
        $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "drawn_on" => $this->input->post('drawn_on'),
            "cheque_no" => $this->input->post('cheque_no'),
            "amount" => unformat_currency($this->input->post('amount')),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('client_member'),
            "f_name" => $this->input->post('first_name'),
            "l_name" => $this->input->post('last_name'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
           "member_type" => $this->input->post('member_type'),
           "r_member_type" => $this->input->post('r_member_type'),
           "i_represent" => $this->input->post('i_represent'),
           "r_represent" =>$r_represent,
            "r_user_id" => $r_user_id,
            "r_f_name" =>$r_f_name,
            "r_l_name" => $r_l_name,
            "r_address" => $r_address,
            "r_phone" => $r_phone,
            "convert_amount"  => $response_value*$amount,
            "currency"=>$this->input->post('currency'),
            "currency_symbol"=>$this->input->post('currency_symbol'),
         "country_id"=>$this->input->post('country_id'),
        ); 
    }if($member=='vendors'){
        $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "drawn_on" => $this->input->post('drawn_on'),
            "cheque_no" => $this->input->post('cheque_no'),
            "amount" => unformat_currency($this->input->post('amount')),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('vendor_member'),
            "f_name" => $this->input->post('first_name'),
            "l_name" => $this->input->post('last_name'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
           "member_type" => $this->input->post('member_type'),
           "r_member_type" => $this->input->post('r_member_type'),
           "i_represent" => $this->input->post('i_represents'),
           "r_represent" =>$r_represent,
            "r_user_id" => $r_user_id,
            "r_f_name" =>$r_f_name,
            "r_l_name" => $r_l_name,
            "r_address" => $r_address,
            "r_phone" => $r_phone,
            "convert_amount"  => $response_value*$amount,
"currency"=>$this->input->post('currency'),
"currency_symbol"=>$this->input->post('currency_symbol'),
"country_id"=>$this->input->post('country_id'),
        ); 
    }

        //is editing? update the files if required
       if ($id) {
            $expense_info = $this->Voucher_expenses_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $expense_info->files, $new_files);
        }

         $data["files"] = serialize($new_files);


        $save_id = $this->Voucher_expenses_model->save($data, $id);
        if($this->input->post('expense_project_id')>0){
                     $estimate_data = array("line_manager" => $this->input->post('line_manager'));
                 $estimate_id = $this->Voucher_model->save($estimate_data, $estimate_id);      
        }else{
            $estimate_data = array("line_manager" => $this->login_user->line_manager);
                 $estimate_id = $this->Voucher_model->save($estimate_data, $estimate_id); 
        }
                $options = array("id" => $estimate_id);
      $vou_status=$this->Voucher_model->get_details($options)->row(); 
      if($vou_status->status=="draft"){
         $DB2 = $this->load->database('default', TRUE);
      
   $DB2->set('status', "applied");
    $DB2->where('id' , $estimate_id);
    $DB2->where('deleted' , '0');
    $DB2->update('voucher'); 
    }
        if ($save_id) {

            save_custom_fields("expenses", $save_id, $this->login_user->is_admin, $this->login_user->user_type);
            log_notification("voucher_application_submitted", array("voucher_id" => $estimate_id));

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id,"estimate_id"=>$this->input->post('estimate_id'), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an estimate item */

    function delete_item() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Voucher_expenses_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Voucher_expenses_model->get_details($options)->row();
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Voucher_expenses_model->delete($id)) {
                $item_info = $this->Voucher_expenses_model->get_one($id);
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimate items, prepared for datatable  */

    function item_list_data($estimate_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Voucher_expenses_model->get_details(array("estimate_id" => $estimate_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of estimate item list table */

    private function _make_item_row($data) {
        $item = "<b>$data->category_title</b>";
        if ($data->description) {
            $item .= "<br /><span>" . nl2br($data->description) . "</span>";
        }
    if($data->member_type=='tm'||$data->member_type=='om'){
        $paid_to=$data->linked_user_name;
    }elseif ($data->member_type=='others') {
         $paid_to=$data->f_name." ". $data->l_name;
    }elseif ($data->member_type=='clients') {
         $paid_to=$data->client_name;
    }elseif ($data->member_type=='vendors') {
         $paid_to=$data->vendor_name;
    } 

if($data->r_member_type=='tm'||$data->r_member_type=='om'){
        $received_by=$data->receiver_name;
    }elseif ($data->r_member_type=='others') {
         $received_by=$data->r_f_name." ". $data->r_l_name;
    }elseif ($data->r_member_type=='clients') {
         $received_by=$data->receiver_client_name;
    }elseif ($data->r_member_type=='vendors') {
         $received_by=$data->receiver_vendor_name;
    }

$files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("voucher/file_preview/" . $file_name)));
                }
            }
        }
          return array(
            $data->estimate_id,
            $item,
            to_currency($data->amount, $data->currency_symbol),
            to_currency($data->convert_amount),
            $paid_to ,
            $received_by,
            $data->expense_date ,
            $data->project_title ,
            $files_link,
            modal_anchor(get_uri("voucher/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_voucher'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("voucher/delete_item"), "data-action" => "delete"))
        );

    }

    /* prepare suggestion of estimate item */

    function get_delivery_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Tools_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_delivery_item_info_suggestion() {
        $item = $this->Tools_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    function get_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Invoice_items_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    //view html is accessable to client only.
    public function preview($estimate_id = 0, $show_close_preview = false) {
        $view_data = array();
    
        if ($estimate_id) {
            $estimate_data = get_voucher_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);
    
            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $estimate_items = get_array_value($estimate_data, "estimate_items");
            $client_info = get_array_value($estimate_data, "client_info"); // Ensure this is fetched
            $color = get_array_value($estimate_data, "color");
    
            // Debugging output to check if $client_info is set
            if (empty($client_info)) {
                log_message('error', 'Client information is missing for estimate ID: ' . $estimate_id);
            } else {
                log_message('info', 'Client information loaded successfully for estimate ID: ' . $estimate_id);
            }
    
            $estimate_data['estimate_status_label'] = $this->_get_estimate_status_label($estimate_info);
    
            $view_data['estimate_preview'] = prepare_voucher_pdf($estimate_data, "html");
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff";
            $view_data['estimate_id'] = $estimate_id;
            $view_data['estimate_info'] = $estimate_info;
            $view_data['estimate_items'] = $estimate_items;
            $view_data['client_info'] = $client_info; // Ensure this is set
            $view_data['color'] = $color;
    
            $this->template->rander("voucher/voucher_preview", $view_data);
        } else {
            show_404();
        }
    }
    
    
      function download_pdf($estimate_id = 0) {
        if ($estimate_id) {
            // Fetch the estimate data
            $estimate_data = get_voucher_making_data($estimate_id);
    
            // Check if the user has access permission (commented out, but should be enabled if needed)
            //$this->_check_estimate_access_permission($estimate_data);
    
            // Clear the output buffer if any data exists
            if (ob_get_length()) {
                ob_end_clean();
            }
    
            // Prepare and download the PDF
            prepare_voucher_pdf($estimate_data, "download");
        } else {
            // Show a 404 error if the estimate ID is invalid
            show_404();
        }
    }
    private function _check_estimate_access_permission($estimate_data) {
        //check for valid estimate
        if (!$estimate_data) {
            show_404();
        }

        //check for security
        $estimate_info = get_array_value($estimate_data, "estimate_info");
        if ($this->login_user->user_type == "client") {
            if ($this->login_user->client_id != $estimate_info->client_id) {
                redirect("forbidden");
            }
        } else {
           // $this->access_only_allowed_members();
        }
    }

    function get_delivery_status_bar($estimate_id = 0) {
       // $this->access_only_allowed_members();

        $view_data["estimate_info"] = $this->Delivery_model->get_details(array("id" => $estimate_id))->row();
        $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
        $this->load->view('voucher/voucher_status_bar', $view_data);
    }
function get_client_contacts() {
        $itemss = $this->Users_model->get_client_contacts($this->input->post("team_member"));
         $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->first_name." ".$items->last_name/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }
    function get_vendor_contacts() {
        $itemss = $this->Users_model->get_vendor_contacts($this->input->post("team_member"));
         $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->first_name." ".$items->last_name/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }  
      function get_vendor_invoice_list() {
        $itemss = $this->Vendors_invoice_list_model->get_details(array("vendor_id"=>$this->input->post("team_member")));
         $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->company_name/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }
    function get_project_manager() {
                $options = array(
            "project_id" =>  $this->input->post("project_id"),
             "project_manager" =>  1,
          );
        $itemss = $this->Project_members_model->get_details($options)->row();


       
              if(!$itemss){
            echo json_encode(array("success" => true,"id" =>0, "text" => 'Not Assigned'));
        }else{
             echo json_encode(array("success" => true,"id" => $itemss->user_id, "text" => $itemss->member_name));
           
        }      
    }
    function get_purchase_manager() {
                $options = array(
            "project_id" =>  $this->input->post("project_id"),
             "purchase_manager" =>  1,
          );
        $itemss = $this->Project_members_model->get_details($options)->row();
        if(!$itemss){
            echo json_encode(array("success" => true,"id" =>0, "text" => 'Not Assigned'));
        }else{
        echo json_encode(array("success" => true,"id" => $itemss->user_id, "text" => $itemss->member_name));
        }          
            

    }        function file_preview($file_name = "") {
        if ($file_name) {

            $view_data["file_url"] = get_file_uri(get_setting("timeline_file_path") . $file_name);
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);

            $this->load->view("expenses/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */
    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for ticket */

    function validate_expense_file() {
        return validate_post_file($this->input->post("file_name"));
    }
}

/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */