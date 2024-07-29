<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

 class Announcements extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("announcement");
    }

    //show announcements list
    function index() {
        $this->check_module_availability("module_announcement");

        $view_data["show_add_button"] = true;
        if ($this->access_type !== "all") {
            $view_data["show_add_button"] = false;
        }
        $view_data["show_option"] = true;
        if($this->access_type !== "all"){
            $view_data["show_option"] = false;
        }
        $this->template->rander("announcements/index", $view_data);
    }

    //show add/edit announcement form
    function form($id = 0) {
        $this->access_only_allowed_members();
    
        $view_data['model_info'] = $this->Announcements_model->get_one($id);
    
        // Check if $view_data['model_info'] is not null before accessing properties
        if ($view_data['model_info']) {
            $view_data['model_info']->share_with_specific = "";
    
            if ($view_data['model_info']->share_with 
                && $view_data['model_info']->share_with != "all_members"
                && $view_data['model_info']->share_with != "all_vendors"
                && $view_data['model_info']->share_with != "all_clients"
                && $view_data['model_info']->share_with != "all_resource"
                && $view_data['model_info']->share_with != "all_partners") {
    
                $share_with_explode = explode(":", $view_data['model_info']->share_with);
                $view_data['model_info']->share_with_specific = $share_with_explode[0];
            }
        }
    
        // Prepare dropdown data
        $view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
        $view_data['outsource_members_and_teams_dropdown'] = json_encode(get_outsource_members_and_teams_select2_data_list());
    
        $vendor_access_info = $this->get_access_info("vendor");
        $vendors_dropdown = array();
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
    
        $client_access_info = $this->get_access_info("client");
        $clients_dropdown = array();
        if ($this->login_user->is_admin || $client_access_info->access_type == "all") {
            $clients = $this->Clients_model->get_dropdown_list(array("company_name"));
    
            if (count($clients)) {
                $clients_dropdown[] = array("id" => "", "text" => "-");
                foreach ($clients as $id => $name) {
                    $clients_dropdown[] = array("id" => $id, "text" => $name);
                }
            }
        }
        $view_data['clients_dropdown'] = $clients_dropdown;
    
        $partners_dropdown = array();
        if ($this->login_user->is_admin || $client_access_info->access_type == "all") {
            $partners = $this->Partners_model->get_dropdown_list(array("company_name"));
    
            if (count($partners)) {
                $partners_dropdown[] = array("id" => "", "text" => "-");
                foreach ($partners as $id => $name) {
                    $partners_dropdown[] = array("id" => $id, "text" => $name);
                }
            }
        }
        $view_data['partners_dropdown'] = $partners_dropdown;
    
        // Load the view with data
        $this->template->rander('announcements/modal_form', $view_data);
    }
    

    //show a specific announcement
    function view($id = "") {
        if ($id) {
            //show only the allowed announcement
            $options = array("id" => $id);

            $options = $this->_prepare_access_options($options);

            $announcement = $this->Announcements_model->get_details($options)->row();
            if ($announcement) {
                $view_data['announcement'] = $announcement;

                //mark the announcement as read for loged in user
                $this->Announcements_model->mark_as_read($id, $this->login_user->id);
                return $this->template->rander("announcements/view", $view_data);
            }
        }

        //not matched the requirement. show 404 page
        show_404();
    }

    private function _prepare_access_options($options) {
        if ($this->access_type !== "all") {
            if ($this->login_user->user_type === "staff") {
                $options["share_with"] = "all_members";
            } else if ($this->login_user->user_type === "client") {
                $options["share_with"] = "all_clients";
            } else if ($this->login_user->user_type === "vendor") {
                $options["share_with"] = "all_vendors";
            }else {
                $options["share_with"] = "none";
            }
        }
        return $options;
    }

    //mark the announcement as read for loged in user
    function mark_as_read($id) {
        $this->Announcements_model->mark_as_read($id, $this->login_user->id);
    }

    //add/edit an announcement
    function save() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "start_date" => "required",
            "end_date" => "required"
        ));

        $id = $this->input->post('id');
        $share_with = $this->input->post('share_with');
               
        if ($share_with == "specific") {
            $share_with = $this->input->post('share_with_specific');
        }else if ($share_with == "resource_specific") {
            $share_with = $this->input->post('share_with_resource_specific');
        } else if ($share_with == "specific_partner_contacts") {
            $share_with = $this->input->post('share_with_specific_partner_contact');
        }else if ($share_with == "specific_client_contacts") {
            $share_with = $this->input->post('share_with_specific_client_contact');
        }else if ($share_with == "specific_vendor_contacts") {
            $share_with = $this->input->post('share_with_specific_vendor_contact');
        }

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "announcement");
        $new_files = unserialize($files_data);
        $partner_id = $this->input->post('partner_id');
        $client_id = $this->input->post('client_id');
        $vendor_id = $this->input->post('vendor_id');

        $data = array(
            "title" => $this->input->post('title'),
            "description" => decode_ajax_post_data($this->input->post('description')),
            "start_date" => $this->input->post('start_date'),
            "end_date" => $this->input->post('end_date'),
            "created_by" => $this->login_user->id,
            "created_at" => get_current_utc_time(),
            //"share_with" => $this->input->post('share_with') ? implode(",", $this->input->post('share_with')) : "",
             "share_with" => $share_with,
            "partner_id" => $partner_id ? $partner_id : 0,
            "client_id" => $client_id ? $client_id : 0,
            "vendor_id" => $vendor_id ? $vendor_id : 0
        );

        //is editing? update the files if required
        if ($id) {
            $expense_info = $this->Announcements_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $expense_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);


        if (!$id) {
            $data["read_by"] = 0; //set default value
        }


        $save_id = $this->Announcements_model->save($data, $id);


        //send log notification
        if (!$id) {
            if ($data["share_with"]) {
                log_notification("new_announcement_created", array("announcement_id" => $save_id));
            }
        }
        /*//send log notification
        if ($id) {
            if ($data["share_with"]) {
                log_notification("new_announcement_created", array("announcement_id" => $save_id));
            }
        }*/


        if ($save_id) {
            //$this->session->set_flashdata("success_message", lang('record_saved'));
            redirect(get_uri("announcements/form/" . $save_id));
        } else {
            $this->session->set_flashdata("error_message", lang('error_occurred'));
            redirect(get_uri("announcements/form/"));
        }
    }

    // upload a file 
    function upload_file() {
        $this->access_only_allowed_members();

        upload_file_to_temp();
    }

    // check valid file for ticket 

    function validate_announcement_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    // download files 
    function download_announcement_files($id = 0) {

        $options = array("id" => $id);
        $options = $this->_prepare_access_options($options);

        $info = $this->Announcements_model->get_details($options)->row();

        download_app_files(get_setting("timeline_file_path"), $info->files);
    }

    //delete/undo an announcement
    function delete() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Announcements_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Announcements_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //perepare the list data for announcement list
    function list_data() {
        $client_id = $this->input->post('client_id'); // Initialize $client_id
        $start = $this->input->post('start_date');   // Initialize $start
        $end = $this->input->post('end_date');       // Initialize $end
    
        //show only the allowed announcements
        /* $options = array();
        if ($this->access_type !== "all") {
            if ($this->login_user->user_type === "staff") {
                $options["share_with"] = "all_members";
            } else if ($this->login_user->user_type === "client") {
                $options["share_with"] = "all_clients";
            } else if ($this->login_user->user_type === "vendor") {
                $options["share_with"] = "all_vendors";
            } else {
                $options["share_with"] = "none";
            }
        } */
    
        $is_client = false;
        if ($this->login_user->user_type == "client") {
            $is_client = true;
        }
        $is_vendor = false;
        if ($this->login_user->user_type == "vendor") {
            $is_vendor = true;
        }
    
        $is_resource = false;
        if ($this->login_user->user_type == "resource") {
            $is_resource = true;
        }
    
        $is_partner = false;
        if ($this->login_user->partner_id) {
            $is_partner = true;
        }
    
        // Prepare options array with initialized variables
        $options = array(
            "user_id" => $this->login_user->id,
            "team_ids" => $this->login_user->team_ids,
            "client_id" => $client_id,
            "start_date" => $start,
            "end_date" => $end,
            "is_client" => $is_client,
            "is_vendor" => $is_vendor,
            "is_resource" => $is_resource,
            "is_partner" => $is_partner
        );
    
        $list_data = $this->Announcements_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }
    
    //get a row of announcement list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Announcements_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //make a row of announcement list
    private function _make_row($data) {
        $image_url = get_avatar($data->created_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";
        $confirmed_by_array = explode(",", $data->read_by);
        $confimed_rejected_users = $this->_get_confirmed_and_rejected_users_list($confirmed_by_array);
        $confirmed_by = get_array_value($confimed_rejected_users, 'confirmed_by');
        $option = "";
        if ($this->access_type === "all") {
            $option = anchor(get_uri("announcements/form/" . $data->id), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_announcement')))
                    . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_announcement'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("announcements/delete"), "data-action" => "delete-confirmation"));
        }
        return array(
            $data->id,
            anchor(get_uri("announcements/view/" . $data->id), $data->title, array("class" => "", "title" => lang('view'))),
            get_team_member_profile_link($data->created_by, $user),
            $confirmed_by,
            $data->start_date,
            format_to_date($data->start_date, false),
            $data->end_date,
            format_to_date($data->end_date, false),
            $option
        );
    }

    private function _get_confirmed_and_rejected_users_list($confirmed_by_array) {

        $confirmed_by = "";
       // $rejected_by = "";


        $response_by_users = $this->Announcements_model->get_response_by_users($confirmed_by_array);
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

        return array("confirmed_by" => $confirmed_by);
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

}

/* End of file announcements.php */
/* Location: ./application/controllers/announcements.php */