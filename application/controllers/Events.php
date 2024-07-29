<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Events extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    //load calendar view
    function index($encrypted_event_id = "") {
        $this->check_module_availability("module_event");
        $view_data['encrypted_event_id'] = $encrypted_event_id;
if ($this->login_user->user_type !== "vendor"){
        $this->template->rander("events/index", $view_data);
       } else if($this->login_user->user_type === "vendor") {
            //vendor view
            $this->template->rander("events/indexs", $view_data);
        }
    }

    private function can_share_events() {
        if ($this->login_user->user_type === "staff") {
            return get_array_value($this->login_user->permissions, "disable_event_sharing") == "1" ? false : true;
        }
    }

    //show add/edit event modal form
    function modal_form() {
        $event_id = decode_id($this->input->post('encrypted_event_id'), "event_id");
        $model_info = $this->Events_model->get_one($event_id);

        $model_info->start_date = $model_info->start_date ? $model_info->start_date : $this->input->post('start_date');
        $model_info->end_date = $model_info->end_date ? $model_info->end_date : $this->input->post('end_date');
        $model_info->start_time = $model_info->start_time ? $model_info->start_time : $this->input->post('start_time');
        $model_info->end_time = $model_info->end_time ? $model_info->end_time : $this->input->post('end_time');

        //for a specific share, we have to find that if it's been shared with team member or client's contact
        $model_info->share_with_specific = "";
        if ($model_info->share_with && $model_info->share_with != "all"&& $model_info->share_with != "all_vendors"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_resource"&& $model_info->share_with != "all_partners") {
            $share_with_explode = explode(":", $model_info->share_with);
            $model_info->share_with_specific = $share_with_explode[0];
        }
$view_data['vendor_id'] = $this->input->post('vendor_id');
        $view_data['client_id'] = $this->input->post('client_id');

        $view_data['model_info'] = $model_info;
        $view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
        $view_data['outsource_members_and_teams_dropdown'] = json_encode(get_outsource_members_and_teams_select2_data_list());
        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;

$vendor_access_info = $this->get_access_info("vendor");
        $vendors_dropdown = array();
        $view_data['vendors_dropdown'] = $vendors_dropdown;
        //prepare clients dropdown, check if user has permission to access the client
        $client_access_info = $this->get_access_info("client");

        $partners_dropdown = array();
        $view_data['partners_dropdown'] = $partners_dropdown;
        if ($this->login_user->is_admin || $client_access_info->access_type == "all") {
            $partners = $this->Partners_model->get_dropdown_list(array("company_name"));


            if (count($partners)) {
                $partners_dropdown[] = array("id" => "", "text" => "-");
                foreach ($partners as $id => $name) {
                    $partners_dropdown[] = array("id" => $id, "text" => $name);
                }
            }
        }

        

        $clients_dropdown = array();
        $view_data['clients_dropdown'] = $clients_dropdown;
        if ($this->login_user->is_admin || $client_access_info->access_type == "all") {
            $clients = $this->Clients_model->get_dropdown_list(array("company_name"));


            if (count($clients)) {
                $clients_dropdown[] = array("id" => "", "text" => "-");
                foreach ($clients as $id => $name) {
                    $clients_dropdown[] = array("id" => $id, "text" => $name);
                }
            }
        }

        if ($this->login_user->is_admin || $vendor_access_info->access_type == "all") {
            $vendors = $this->Vendors_model->get_dropdown_list(array("company_name"));


            if (count($vendors)) {
                $vendors_dropdown[] = array("id" => "", "text" => "-");
                foreach ($vendors as $id => $name) {
                    $vendors_dropdown[] = array("id" => $id, "text" => $name);
                }
            }
        }
     $view_data['vendors_dropdown'] = $vendors_dropdown;
$view_data['partners_dropdown'] = $partners_dropdown;
        $view_data['clients_dropdown'] = $clients_dropdown;

        $view_data["can_share_events"] = $this->can_share_events();

        //prepare label suggestion dropdown
        $labels = explode(",", $this->Events_model->get_label_suggestions());
        $label_suggestions = array();
        foreach ($labels as $label) {
            if ($label && !in_array($label, $label_suggestions)) {
                $label_suggestions[] = $label;
            }
        }
        if (!count($label_suggestions)) {
            $label_suggestions = array("0" => "");
        }
        $view_data['label_suggestions'] = $label_suggestions;

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("events", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        //country dropdown 
         $company_setup_country = $this->Countries_model->get_all()->result();
         $company_setup_country_dropdown = array();
         foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
         $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);

         //branch dropdown 
         //$company_branch = $this->Branches_model->get_dropdown_list(array("title"), "company_setup_country", array("company_setup_country" => $view_data['model_info']->country));
         $company_branch = $this->Branches_model->get_all_where(array("deleted" => 0,"company_setup_country"=>$view_data['model_info']->country))->result();
        
        $company_branch_dropdown = array();
        foreach ($company_branch as $branch) {
             $company_info =  $this->Companys_model->get_one_where(array("deleted" => 0 , "cr_id" => $branch->company_name));
            $company_branch_dropdown[] = array("id" => $branch->buid, "text" =>$branch->title."  - " . lang("employer") . ": " . $company_info->company_name . " " );
        }
         $view_data['company_branch_dropdown'] = json_encode($company_branch_dropdown);

        $this->load->view('events/modal_form', $view_data);
    }

    //save an event
    function save() {
        validate_submitted_data(array(
            "title" => "required",
            "description" => "required",
            "start_date" => "required",
            "end_date" => "required"
        ));

        $id = $this->input->post('id');

        //convert to 24hrs time format
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');

        if (get_setting("time_format") != "24_hours") {
            $start_time = convert_time_to_24hours_format($start_time);
            $end_time = convert_time_to_24hours_format($end_time);
        }




        //prepare share with data

        $share_with = $this->input->post('share_with');
        if ($share_with == "specific") {
            $share_with = $this->input->post('share_with_specific');
        }else if ($share_with == "resource_specific") {
            $share_with = $this->input->post('share_with_resource_specific');
        }else if ($share_with == "specific_partner_contacts") {
            $share_with = $this->input->post('share_with_specific_partner_contact');
        }else if ($share_with == "specific_client_contacts") {
            $share_with = $this->input->post('share_with_specific_client_contact');
        }else if ($share_with == "specific_vendor_contacts") {
            $share_with = $this->input->post('share_with_specific_vendor_contact');
        }

        $start_date = $this->input->post('start_date');

        $recurring = $this->input->post('recurring') ? 1 : 0;
        $repeat_every = $this->input->post('repeat_every');
        $repeat_type = $this->input->post('repeat_type');
        $no_of_cycles = $this->input->post('no_of_cycles');
        $partner_id = $this->input->post('partner_id');
        $client_id = $this->input->post('client_id');
        $vendor_id = $this->input->post('vendor_id');
        $official_leave = $this->input->post('official_leave') ? 1 : 0;
        $end_date = $this->input->post('end_date');

/*$days= 0;
if($official_leave==1){
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $d_start = new DateTime($start_date);
            $d_end = new DateTime($end_date);
            $d_diff = $d_start->diff($d_end);

            $days = $d_diff->days + 1;
            //$hours = $days * $hours_per_day;
        }*/

// Add the  official leave for team memebers
$start_date_same =Date('Y-m', strtotime($start_date));
$end_date_same =Date('Y-m', strtotime($end_date));
$days= 0;
$country = '';
$branch = '';
$country_permission='';
if($official_leave==1){
    if($start_date_same == $end_date_same){
            
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $d_start = new DateTime($start_date);
            $d_end = new DateTime($end_date);
            $d_diff = $d_start->diff($d_end);

            //$days = $d_diff->days + 1;
            $dayss = $d_diff->days + 1;
            //$hours = $days * $hours_per_day;

//no of sunday 
$num_sundays='';                
for ($i = 0; $i <= ((strtotime($end_date) - strtotime(
    $start_date)) / 86400); $i++)
{
    if(date('l',strtotime($start_date) + ($i * 86400)) == 'Sunday')
    {
            $num_sundays++;
    }   
}
//end no of sunday 
         
    $days = $dayss -$num_sundays; //total official $start_date excepted sunday  days 
        }else{
    echo json_encode(array("success" => false, 'message' => lang('the_month_must_be_same')));
                exit();
  }

  $country_permission = $this->input->post('country_permission');
  if($country_permission == "specific"){
    $country = $this->input->post('country');
    $branch =  $this->input->post('branch');
  }else{
    $country = '';
    $branch =  '';
  }

}

// end official offical leave for team members 


        $data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "start_date" => $start_date,
            "end_date" => $this->input->post('end_date'),
            "start_time" => $start_time,
            "end_time" => $end_time,
            "location" => $this->input->post('location'),
            "labels" => $this->input->post('labels'),
            "color" => $this->input->post('color'),
            "created_by" => $this->login_user->id,
            "share_with" => $share_with,
            "recurring" => $recurring,
            "repeat_every" => $repeat_every,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
            "partner_id" => $partner_id ? $partner_id : 0,
            "client_id" => $client_id ? $client_id : 0,
            "vendor_id" => $vendor_id ? $vendor_id : 0,
            "official_leave" => $official_leave,
            "total_days" => $days,
            "country"=> $country,
            "branch" => $branch,
            "country_permission" => $country_permission,
        );

        if (!$id) {
            $data["confirmed_by"] = 0;
            $data["rejected_by"] = 0;
        }

        //prepare a comma sepearted dates of start date.
        $recurring_dates = "";
        $last_start_date = NULL;

        if ($recurring) {
            $no_of_cycles = $this->Events_model->get_no_of_cycles($repeat_type, $no_of_cycles);

            for ($i = 1; $i <= $no_of_cycles; $i++) {
                $start_date = add_period_to_date($start_date, $repeat_every, $repeat_type);
                $recurring_dates .= $start_date . ",";

                $last_start_date = $start_date; //collect the last start date
            }
        }

        $data["recurring_dates"] = $recurring_dates;
        $data["last_start_date"] = $last_start_date;


        if (!$this->can_share_events()) {
            $data["share_with"] = "";
        }


        //only admin can edit other team members events
        //non-admin team members can edit only their own events
        if ($id && !$this->login_user->is_admin) {
            $event_info = $this->Events_model->get_one($id);
            if ($event_info->created_by != $this->login_user->id) {
                redirect("forbidden");
            }
        }

        $data = clean_data($data);


        $save_id = $this->Events_model->save($data, $id);
        if ($save_id) {

            save_custom_fields("events", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));

            if (!$id && $share_with) {
                //new event added and shared with others, log the notificaiton
                log_notification("new_event_added_in_calendar", array("event_id" => $save_id));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an event
    function delete() {
        validate_submitted_data(array(
            "encrypted_event_id" => "required"
        ));

        $id = decode_id($this->input->post('encrypted_event_id'), "event_id"); //to make is secure we'll use the encrypted id
        //only admin can delete other team members events
        //non-admin team members can delete only their own events
        if ($id && !$this->login_user->is_admin) {
            $event_info = $this->Events_model->get_one($id);
            if ($event_info->created_by != $this->login_user->id) {
                redirect("forbidden");
            }
        }


        if ($this->Events_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('event_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    //get calendar event
    function calendar_events($client_id=0) {

        $start = $_GET["start"];
        $end = $_GET["end"];

        $result = array();

        /* get all events */

        $is_partner = false;
        if ($this->login_user->partner_id) {
            $is_partner = true;
        }


        $is_client = false;
        if ($this->login_user->user_type == "client") {
            $is_client = true;
        }

        /*$is_vendor = false;
        if ($this->login_user->user_type == "vendor") {
            $is_vendor = true;
        } */

        $is_resource = false;
        if ($this->login_user->user_type == "resource") {
            $is_resource = true;
        }

        $options_of_events = array("user_id" => $this->login_user->id, "team_ids" => $this->login_user->team_ids, "client_id" => $client_id,"start_date" => $start, "end_date" => $end, "include_recurring" => true, "is_client" => $is_client,"is_resource" => $is_resource,"is_partner" => $is_partner);

        $list_data_of_events = $this->Events_model->get_details($options_of_events)->result();
        
        foreach ($list_data_of_events as $data) {

            //check if this recurring event, generate recurring evernts based on the condition

            $data->cycle = 0; //it's required to calculate the recurring events

            $result[] = $this->_make_calendar_event($data); //add regular event

            if ($data->recurring) {
                $no_of_cycles = $this->Events_model->get_no_of_cycles($data->repeat_type, $data->no_of_cycles);

                for ($i = 1; $i <= $no_of_cycles; $i++) {
                    $data->start_date = add_period_to_date($data->start_date, $data->repeat_every, $data->repeat_type);
                    $data->end_date = add_period_to_date($data->end_date, $data->repeat_every, $data->repeat_type);
                    $data->cycle = $i;

                    $result[] = $this->_make_calendar_event($data);
                }
            }
        }

        /* get all approved leaves */

        $leave_access_info = $this->get_access_info("leave");
        $options_of_leaves = array("start_date" => $start, "end_date" => $end, "login_user_id" => $this->login_user->id, "access_type" => $leave_access_info->access_type, "allowed_members" => $leave_access_info->allowed_members, "status" => "approved");

        $list_data_of_leaves = $this->Leave_applications_model->get_list($options_of_leaves)->result();
        
        foreach ($list_data_of_leaves as $leave) {
            $result[] = $this->_make_leave_event($leave);
        }

        echo json_encode($result);
    }

    //get calendar event
    function calendar_eventss($vendor_id=0) {

        $start = $_GET["start"];
        $end = $_GET["end"];

        $result = array();

        /* get all events */

        

        $is_vendor = false;
        if ($this->login_user->user_type == "vendor") {
            $is_vendor = true;
        }

        $options_of_events = array("user_id" => $this->login_user->id, "team_ids" => $this->login_user->team_ids,  "vendor_id" => $vendor_id, "start_date" => $start, "end_date" => $end, "include_recurring" => true, "is_vendor" => $is_vendor);

        $list_data_of_events = $this->Events_model->get_detailss($options_of_events)->result();
        
        foreach ($list_data_of_events as $data) {

            //check if this recurring event, generate recurring evernts based on the condition

            $data->cycle = 0; //it's required to calculate the recurring events

            $result[] = $this->_make_calendar_event($data); //add regular event

            if ($data->recurring) {
                $no_of_cycles = $this->Events_model->get_no_of_cycles($data->repeat_type, $data->no_of_cycles);

                for ($i = 1; $i <= $no_of_cycles; $i++) {
                    $data->start_date = add_period_to_date($data->start_date, $data->repeat_every, $data->repeat_type);
                    $data->end_date = add_period_to_date($data->end_date, $data->repeat_every, $data->repeat_type);
                    $data->cycle = $i;

                    $result[] = $this->_make_calendar_event($data);
                }
            }
        }

        /* get all approved leaves */

        $leave_access_info = $this->get_access_info("leave");
        $options_of_leaves = array("start_date" => $start, "end_date" => $end, "login_user_id" => $this->login_user->id, "access_type" => $leave_access_info->access_type, "allowed_members" => $leave_access_info->allowed_members, "status" => "approved");

        $list_data_of_leaves = $this->Leave_applications_model->get_list($options_of_leaves)->result();
        
        foreach ($list_data_of_leaves as $leave) {
            $result[] = $this->_make_leave_event($leave);
        }

        echo json_encode($result);
    }

    //prepare calendar event
    private function _make_calendar_event($data) {

        return array(
            "title" => $data->title,
            "icon" => get_event_icon($data->share_with),
            "start" => $data->start_date . " " . $data->start_time,
            "end" => $data->end_date . " " . $data->end_time,
            "encrypted_event_id" => encode_id($data->id, "event_id"), //to make is secure we'll use the encrypted id
            "backgroundColor" => $data->color,
            "borderColor" => $data->color,
            "cycle" => $data->cycle,
            "event_type" => "event"
        );
    }

    //prepare approved leave event
    private function _make_leave_event($data) {

        return array(
            "title" => $data->applicant_name,
            "icon" => "fa fa-sign-out",
            "start" => $data->start_date . " " . "00:00:00", 
            "end" => $data->end_date . " " . "23:59:59", //show leave applications for the full day
            "encrypted_event_id" => $data->id, //to make is secure we'll use the encrypted id
            "backgroundColor" => $data->leave_type_color,
            "borderColor" => $data->leave_type_color,
            "cycle" => 0,
            "event_type" => "leave"
        );
    }

    //view an evnet
    function view() {
        $encrypted_event_id = $this->input->post('id');
        $cycle = $this->input->post('cycle');

        validate_submitted_data(array(
            "id" => "required"
        ));

        $view_data = $this->_make_view_data($encrypted_event_id, $cycle);

        $this->load->view('events/view', $view_data);
    }

    private function _make_view_data($encrypted_event_id, $cycle = "0") {
        $event_id = decode_id($encrypted_event_id, "event_id");

        $model_info = $this->Events_model->get_details(array("id" => $event_id))->row();
$model_infos = $this->Events_model->get_detailss(array("id" => $event_id))->row();

        if ($event_id && $model_info->id) {

            $model_info->cycle = $cycle * 1;

            if ($model_info->recurring && $cycle) {
                $model_info->start_date = add_period_to_date($model_info->start_date, $model_info->repeat_every * $cycle, $model_info->repeat_type);
                $model_info->end_date = add_period_to_date($model_info->end_date, $model_info->repeat_every * $cycle, $model_info->repeat_type);
            }


            $view_data['encrypted_event_id'] = $encrypted_event_id; //to make is secure we'll use the encrypted id 
            $view_data['editable'] = $this->input->post('editable');
            $view_data['model_info'] = $model_info;
            $view_data['model_infos'] = $model_infos;
            
            $view_data['event_icon'] = get_event_icon($model_info->share_with);
            $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("events", $event_id, $this->login_user->is_admin, $this->login_user->user_type)->result();


            $confirmed_by_array = explode(",", $model_info->confirmed_by);
            $rejected_by_array = explode(",", $model_info->rejected_by);


            //prepare event lable
            $event_labels = "";
            if ($model_info->labels) {
                $labels = explode(",", $model_info->labels);
                foreach ($labels as $label) {
                    $event_labels .= "<span class='label large' style='background-color:$model_info->color;' title=" . lang("label") . ">" . $label . "</span> ";
                }
            }
            $view_data['labels'] = $event_labels;


            //prepare status lable and status buttons
            $status = "";
            $status_button = "";

            $status_confirm = modal_anchor(get_uri("events/save_event_status/"), "<i class='fa fa-check-circle-o'></i> " . lang('confirm'), array("class" => "btn btn-success pull-left", "data-post-encrypted_event_id" => $encrypted_event_id, "title" => lang('event_details'), "data-post-status" => "confirmed", "data-post-editable" => "1"));
            $status_reject = modal_anchor(get_uri("events/save_event_status/"), "<i class='fa fa-times-circle-o'></i> " . lang('reject'), array("class" => "btn btn-danger pull-left", "data-post-encrypted_event_id" => $encrypted_event_id, "title" => lang('event_details'), "data-post-status" => "rejected", "data-post-editable" => "1"));

            if (in_array($this->login_user->id, $confirmed_by_array)) {
                $status = "<span class='label large' style='background-color:#5CB85C;' title=" . lang("event_status") . ">" . lang("confirmed") . "</span> ";
                $status_button = $status_reject;
            } else if (in_array($this->login_user->id, $rejected_by_array)) {
                $status = "<span class='label large' style='background-color:#D9534F;' title=" . lang("event_status") . ">" . lang("rejected") . "</span> ";
                $status_button = $status_confirm;
            } else {
                $status_button = $status_confirm . $status_reject;
            }

            $view_data["status"] = $status;
            $view_data['status_button'] = $status_button;


            //prepare confimed/rejected user's list
            $confimed_rejected_users = $this->_get_confirmed_and_rejected_users_list($confirmed_by_array, $rejected_by_array);

            $view_data['confirmed_by'] = get_array_value($confimed_rejected_users, 'confirmed_by');
            $view_data['rejected_by'] = get_array_value($confimed_rejected_users, 'rejected_by');


            return $view_data;
        } else {
            show_404();
        }
    }

    private function _get_confirmed_and_rejected_users_list($confirmed_by_array, $rejected_by_array) {

        $confirmed_by = "";
        $rejected_by = "";


        $response_by_users = $this->Events_model->get_response_by_users(($confirmed_by_array + $rejected_by_array));
        if ($response_by_users) {
            foreach ($response_by_users->result() as $user) {
                $image_url = get_avatar($user->image);
                $response_by_user = "<span data-toggle='tooltip' title='" . $user->member_name . "' class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span>";

                if ($user->user_type === "client") {
                    $profile_link = get_client_contact_profile_link($user->id, $response_by_user);
                } else if ($user->user_type === "vendor") {
                    $profile_link = get_vendor_contact_profile_link($user->id, $response_by_user);
                }else if($user->user_type === "resource") {
                    $profile_link = get_rm_member_profile_link($user->id, $response_by_user);
                }else {
                    $profile_link = get_team_member_profile_link($user->id, $response_by_user);
                }

                if (in_array($user->id, $confirmed_by_array)) {
                    $confirmed_by .= $profile_link;
                } else {
                    $rejected_by .= $profile_link;
                }
            }
        }

        return array("confirmed_by" => $confirmed_by, "rejected_by" => $rejected_by);
    }

    function save_event_status() {
        $encrypted_event_id = $this->input->post('encrypted_event_id');
        $event_id = decode_id($encrypted_event_id, "event_id");

        $status = $this->input->post('status');
        $user_id = $this->login_user->id;

        $this->Events_model->save_event_status($event_id, $user_id, $status);

        $view_data = $this->_make_view_data($encrypted_event_id);

        $this->load->view('events/view', $view_data);
    }

    function get_all_contacts_of_partner($partner_id) {

        $client_access_info = $this->get_access_info("client");
        if ($partner_id && ($this->login_user->is_admin || $client_access_info->access_type == "all")) {
            $partner_contacts = $this->Users_model->get_all_where(array("user_type" => "client", "status" => "active", "partner_id" => $partner_id, "deleted" => 0))->result();
            $partner_contacts_array = array();

            if ($partner_contacts) {
                foreach ($partner_contacts as $contacts) {
                    $partner_contacts_array[] = array("type" => "partner_contact", "id" => "partner_contact:" . $contacts->id, "text" => $contacts->first_name . " " . $contacts->last_name);
                }
            }
            echo json_encode($partner_contacts_array);
        }
    }

    //get all contacts of a selected client
    function get_all_contacts_of_client($client_id) {

        $client_access_info = $this->get_access_info("client");
        if ($client_id && ($this->login_user->is_admin || $client_access_info->access_type == "all")) {
            $client_contacts = $this->Users_model->get_all_where(array("user_type" => "client", "status" => "active", "client_id" => $client_id, "deleted" => 0))->result();
            $client_contacts_array = array();

            if ($client_contacts) {
                foreach ($client_contacts as $contacts) {
                    $client_contacts_array[] = array("type" => "contact", "id" => "contact:" . $contacts->id, "text" => $contacts->first_name . " " . $contacts->last_name);
                }
            }
            echo json_encode($client_contacts_array);
        }
    }

    function get_all_contacts_of_vendor($vendor_id) {

        $vendor_access_info = $this->get_access_info("vendor");
        if ($vendor_id && ($this->login_user->is_admin || $vendor_access_info->access_type == "all")) {
            $vendor_contacts = $this->Users_model->get_all_where(array("user_type" => "vendor", "status" => "active", "vendor_id" => $vendor_id, "deleted" => 0))->result();
            $vendor_contacts_array = array();

            if ($vendor_contacts) {
                foreach ($vendor_contacts as $contacts) {
                    $vendor_contacts_array[] = array("type" => "vendor_contact", "id" => "vendor_contact:" . $contacts->id, "text" => $contacts->first_name . " " . $contacts->last_name);
                }
            }
            echo json_encode($vendor_contacts_array);
        }
    }

     // get branches for official holidays
     function get_branches_suggestion() {
        $key = $_REQUEST["q"];
        $ss=$_REQUEST["ss"];
        $itemss =  $this->Branches_model->get_item_suggestions_branch_name($key,$ss);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      foreach ($itemss as $items) {
           $company_info =  $this->Companys_model->get_one_where(array("deleted" => 0 , "cr_id" => $items->company_name));
           $suggestions[] = array("id" => $items->buid, "text" => $items->title ."  - " . lang("employer") . ": " . $company_info->company_name . "");
       }
        echo json_encode($suggestions);
    }

}

/* End of file events.php */
    /* Location: ./application/controllers/events.php */