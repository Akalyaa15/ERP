<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messages extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    private function is_my_message($message_info) {
        if ($message_info->from_user_id == $this->login_user->id || $message_info->to_user_id == $this->login_user->id) {
            return true;
        }
    }

    private function check_clients_permission(){
        if($this->login_user->user_type=="client" && !get_setting("client_message_users")){
            redirect("forbidden");
        }
    }
    
    
    function index() {
        $this->check_clients_permission();
        redirect("messages/inbox");
    }

    /* show new message modal */

    function modal_form($user_id = 0) {
        /*
         * team members can send message to all team members
         * clients can only send message to team members (as defined on Client settings)
         * team members can send message to clients (as defined on Client settings)
         */
        $client_message_users = get_setting("client_message_users");
        if ($this->login_user->user_type === "staff") {
            //user is team member
            $client_message_users_array = explode(",", $client_message_users);
            if (in_array($this->login_user->id, $client_message_users_array)) {
                //user can send message to clients
                $users = $this->Users_model->get_team_members_and_clients("", "", $this->login_user->id)->result();
            } else {
                //user can send message only to team members
                $users = $this->Users_model->get_team_members_and_clients("staff", "", $this->login_user->id)->result();
            }
        } else {
            //user is a client contact
            if ($client_message_users) {
                $users = $this->Users_model->get_team_members_and_clients("staff", $client_message_users)->result();
            } else {
                //client can't send message to any team members
                redirect("forbidden");
            }
        }

/* orignal dropdown */
        /*$view_data['users_dropdown'] = array("" => "-");
        if ($user_id) {
            $view_data['message_user_info'] = $this->Users_model->get_one($user_id);
        } else {
            foreach ($users as $user) {
                $client_tag = "";
                if ($user->user_type === "client" && $user->company_name) {
                    $client_tag = "  - " . lang("client") . ": " . $user->company_name . "";
                }
                $view_data['users_dropdown'][$user->id] = $user->first_name . " " . $user->last_name . $client_tag;
            }
            /// $view_data['users_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("user_type" => "staff", "id !=" => $this->login_user->id));
        }*/

        // select multiple ser  dropdown 
        $view_data['users_dropdown'] = array();
        if ($user_id) {
            $view_data['message_user_info'] = $this->Users_model->get_one($user_id);
        } else {
            foreach ($users as $user) {
                $client_tag = "";
                if ($user->user_type === "client" && $user->company_name) {
                    $client_tag = "  - " . lang("client") . ": " . $user->company_name . "";
                }
                $view_data['users_dropdown'][] = array("id" => $user->id, "text" => $user->first_name." ".$user->last_name." ". $client_tag);
            }
            /// $view_data['users_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("user_type" => "staff", "id !=" => $this->login_user->id));
        }

        $this->load->view('messages/modal_form', $view_data);
    }


     /* show forard message modal */

    function forward_modal_form($user_id = 0) {
        /*
         * team members can send message to all team members
         * clients can only send message to team members (as defined on Client settings)
         * team members can send message to clients (as defined on Client settings)
         */
        $client_message_users = get_setting("client_message_users");
        if ($this->login_user->user_type === "staff") {
            //user is team member
            $client_message_users_array = explode(",", $client_message_users);
            if (in_array($this->login_user->id, $client_message_users_array)) {
                //user can send message to clients
                $users = $this->Users_model->get_team_members_and_clients("", "", $this->login_user->id)->result();
            } else {
                //user can send message only to team members
                $users = $this->Users_model->get_team_members_and_clients("staff", "", $this->login_user->id)->result();
            }
        } else {
            //user is a client contact
            if ($client_message_users) {
                $users = $this->Users_model->get_team_members_and_clients("staff", $client_message_users)->result();
            } else {
                //client can't send message to any team members
                redirect("forbidden");
            }
        }

        //check if estimate_id posted. if found estimate_id, so, we'll show the estimate info to copy the estimate 
        $reply_info_id = $this->input->post('reply_info_id');
        $view_data['reply_info_id'] = $reply_info_id;

        $message_info_id = $this->input->post('message_info_id');
        $view_data['message_info_id'] = $message_info_id;
        if ($reply_info_id) {
            $estimate_info = $this->Messages_model->get_one($reply_info_id);
            //$now = get_my_local_time("Y-m-d");
            $message_info_list = $this->Messages_model->get_one($estimate_info->message_id);
            $model_info->message = $estimate_info->message;
           // $model_info->subject = $estimate_info->subject;
            $model_info->subject = $message_info_list->subject;
            $model_info->files = $estimate_info->files;
            $model_info->message_id = $estimate_info->id;
            
        }else if($message_info_id){
            $estimate_info = $this->Messages_model->get_one($message_info_id);
            //$now = get_my_local_time("Y-m-d");
            
            $model_info->message = $estimate_info->message;
           // $model_info->subject = $estimate_info->subject;
            $model_info->subject = $estimate_info->subject;
            $model_info->files = $estimate_info->files;
            $model_info->message_id = $estimate_info->id;

        }

// orignal dropdown only single
       /* $view_data['users_dropdown'] = array("" => "-");
        if ($user_id) {
            $view_data['message_user_info'] = $this->Users_model->get_one($user_id);
        } else {
            foreach ($users as $user) {
                $client_tag = "";
                if ($user->user_type === "client" && $user->company_name) {
                    $client_tag = "  - " . lang("client") . ": " . $user->company_name . "";
                }
                $view_data['users_dropdown'][$user->id] = $user->first_name . " " . $user->last_name . $client_tag;
            }
            /// $view_data['users_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("user_type" => "staff", "id !=" => $this->login_user->id));
        }
*/

// select multiple ser  dropdown 
        $view_data['users_dropdown'] = array();
        if ($user_id) {
            $view_data['message_user_info'] = $this->Users_model->get_one($user_id);
        } else {
            foreach ($users as $user) {
                $client_tag = "";
                if ($user->user_type === "client" && $user->company_name) {
                    $client_tag = "  - " . lang("client") . ": " . $user->company_name . "";
                }
                $view_data['users_dropdown'][] = array("id" => $user->id, "text" => $user->first_name." ".$user->last_name." ". $client_tag);
            }
            /// $view_data['users_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("user_type" => "staff", "id !=" => $this->login_user->id));
        }

        /*$client_groups = $this->Users_model->get_all()->result();
        $groups_dropdown = array();

       

        foreach ($client_groups as $group) {
            $groups_dropdown[] = array("id" => $group->id, "text" => $group->first_name);
        }


        $view_data['users_dropdown'] = $groups_dropdown;*/
        $view_data['model_info'] = $model_info;

        $this->load->view('messages/forward_modal_form', $view_data);
    }

    /* show inbox */

    function inbox($auto_select_index = "") {
        $this->check_clients_permission();
        $this->check_module_availability("module_message");

        $view_data['mode'] = "inbox";
        $view_data['auto_select_index'] = $auto_select_index;
        $this->template->rander("messages/index", $view_data);
    }

    /* show sent items */

    function sent_items($auto_select_index = "") {
        $this->check_clients_permission();
        $this->check_module_availability("module_message");

        $view_data['mode'] = "sent_items";
        $view_data['auto_select_index'] = $auto_select_index;
        $this->template->rander("messages/index", $view_data);
    }

    /* list of messages, prepared for datatable  */

    function list_data($mode = "inbox") {
        $this->check_clients_permission();
        if ($mode !== "inbox") {
            $mode = "sent_items";
        }

        $options = array("user_id" => $this->login_user->id, "mode" => $mode);
        $list_data = $this->Messages_model->get_list($options)->result();

        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $mode);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a message details */

    function view($message_id = 0, $mode = "", $reply = 0) {


        $message_mode = $mode;
        if ($reply == 1 && $mode == "inbox") {
            $message_mode = "sent_items";
        } else if ($reply == 1 && $mode == "sent_items") {
            $message_mode = "inbox";
        }

        $options = array("id" => $message_id, "user_id" => $this->login_user->id, "mode" => $message_mode);
        $view_data["message_info"] = $this->Messages_model->get_details($options)->row;

        if (!$this->is_my_message($view_data["message_info"])) {
            redirect("forbidden");
        }

        //change message status to read
        $this->Messages_model->set_message_status_as_read($view_data["message_info"]->id, $this->login_user->id);

        $replies_options = array("message_id" => $message_id, "user_id" => $this->login_user->id, "limit" => 4);
        $messages = $this->Messages_model->get_details($replies_options);

        $view_data["replies"] = $messages->result;
        $view_data["found_rows"] = $messages->found_rows;

        $view_data["mode"] = $mode;
        $view_data["is_reply"] = $reply;
        echo json_encode(array("success" => true, "data" => $this->load->view("messages/view", $view_data, true), "message_id" => $message_id));
    }

    /* prepare a row of message list table */

    private function _make_row($data, $mode = "", $return_only_message = false, $online_status = false) {
        $image_url = get_avatar($data->user_image);
        $created_at = format_to_relative_time($data->created_at);
        $message_id = $data->main_message_id;
        $label = "";
        $reply = "";
        $status = "";
        $attachment_icon = "";
        $subject = $data->subject;
        if ($mode == "inbox") {
            $status = $data->status;
        }

        if ($data->reply_subject) {
            $label = " <label class='label label-success inline-block'>" . lang('reply') . "</label>";
            $reply = "1";
            $subject = $data->reply_subject;
        }

        if ($data->files && count(unserialize($data->files))) {
            $attachment_icon = "<i class='fa fa-paperclip font-16 mr15'></i>";
        }


        //prepare online status
        $online = "";
        if ($online_status && is_online_user($data->last_online)) {
            $online = "<i class='online'></i>";
        }

        $message = "<div class='pull-left message-row $status' data-id='$message_id' data-index='$data->main_message_id' data-reply='$reply'><div class='media-left'>
                        <span class='avatar avatar-xs'>
                            <img src='$image_url' />
                                $online
                        </span>
                    </div>
                    <div class='media-body'>
                        <div class='media-heading'>
                            <strong> $data->user_name</strong>
                                  <span class='text-off pull-right time'>$attachment_icon $created_at</span>
                        </div>
                        $label $subject
                    </div></div>
                  
                ";
        if ($return_only_message) {
            return $message;
        } else {
            return array(
                $message,
                $data->created_at,
                $status
            );
        }
    }

    /* send new message */

    function send_message() {

        validate_submitted_data(array(
            "message" => "required",
            "to_user_id" => "required"
        ));
 
        $to_user_id = $this->input->post('to_user_id');
        
        //team member can send message to any team member
        //client can send messages to only allowed members
       
       // $this->validate_client_message($to_user_id);
        
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "message");
 
        $multipe_users = explode(",", $to_user_id);
       foreach ($multipe_users as $multipe_user ){
        $message_data = array(
            "from_user_id" => $this->login_user->id,
            //"to_user_id" => $to_user_id,
            "to_user_id" => $multipe_user,
            "subject" => $this->input->post('subject'),
            "message" => $this->input->post('message'),
            "created_at" => get_current_utc_time(),
            "deleted_by_users" => "",
        );

        $message_data = clean_data($message_data);

        $message_data["files"] = $files_data; //don't clean serilized data

        $save_id = $this->Messages_model->save($message_data);
        
        }

        if ($save_id) {
            log_notification("new_message_sent", array("actual_message_id" => $save_id));
            echo json_encode(array("success" => true, 'message' => lang('message_sent'), "id" => $save_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    
    //check messages between client and team members.
    private function validate_client_message($to_user_id){
        if ($this->login_user->user_type === "client") {
            //sending message from client
            $this->check_message_sending_permission($to_user_id);
            
        }else {
            //from team member to client messages, check who can communicate with client
            $to_user_info = $this->Users_model->get_one($to_user_id);
            if($to_user_info && $to_user_info->user_type=="client"){
                //sending message from team mebers to client. check the permission
                $this->check_message_sending_permission($this->login_user->id); //check login user
            }
        }
    }
    
    //we have to check permission between clent and team members message.
    private function check_message_sending_permission($user_id){
        $client_message_users = get_setting("client_message_users");
        if(!$client_message_users){
            redirect("forbidden");
        }

        $client_message_users_array = explode(",", $client_message_users);

        if (!in_array($user_id, $client_message_users_array)) {
             redirect("forbidden");
        }
    }

/*send  forward message  */
function forward_send_message() {

        validate_submitted_data(array(
            "message" => "required",
            "to_user_id" => "required"
        ));
 
        $to_user_id = $this->input->post('to_user_id');
        
        //team member can send message to any team member
        //client can send messages to only allowed members
       
        //$this->validate_client_message($to_user_id);
        
       
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "message");
       
       $multipe_users = explode(",", $to_user_id);
       foreach ($multipe_users as $multipe_user ){

        $new_files = unserialize($files_data);
        $message_data = array(
            "from_user_id" => $this->login_user->id,
            //"to_user_id" => $to_user_id,
           "to_user_id" => $multipe_user,
            "subject" => $this->input->post('subject'),
            "message" => $this->input->post('message'),
            "created_at" => get_current_utc_time(),
            "deleted_by_users" => "",
        );

       $message_data = clean_data($message_data);

        //$message_data["files"] = $files_data; //don't clean serilized data
        $message_id = $this->input->post('forward_files');
        $message_id_info = $this->Messages_model->get_one($message_id);
        $timeline_file_path = get_setting("timeline_file_path");
        $new_files = update_saved_files($timeline_file_path, $message_id_info->files, $new_files);
        $message_data["files"] = serialize($new_files);
         //$message_data["files"] = $this->input->post('forward_files');

        $save_id = $this->Messages_model->save($message_data);
    }

        if ($save_id) {
            log_notification("new_message_sent", array("actual_message_id" => $save_id));
            echo json_encode(array("success" => true, 'message' => lang('message_sent'), "id" => $save_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }




    /* reply to an existing message */

    function reply($is_chat = 0) {
        $message_id = $this->input->post('message_id');

        validate_submitted_data(array(
            "reply_message" => "required",
            "message_id" => "required|numeric"
        ));


        $message_info = $this->Messages_model->get_one($message_id);

        if (!$this->is_my_message($message_info)) {
            redirect("forbidden");
        }


        if ($message_info->id) {
            //check, where we have to send this message
            $to_user_id = 0;
            if ($message_info->from_user_id === $this->login_user->id) {
                $to_user_id = $message_info->to_user_id;
            } else {
                $to_user_id = $message_info->from_user_id;
            }

            $this->validate_client_message($to_user_id);
            
            
            $target_path = get_setting("timeline_file_path");
            $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "message");

            $message_data = array(
                "from_user_id" => $this->login_user->id,
                "to_user_id" => $to_user_id,
                "message_id" => $message_id,
                "subject" => "",
                "message" => $this->input->post('reply_message'),
                "created_at" => get_current_utc_time(),
                "deleted_by_users" => "",
            );

            $message_data = clean_data($message_data);
            $message_data["files"] = $files_data; //don't clean serilized data


            $save_id = $this->Messages_model->save($message_data);

            if ($save_id) {


                //we'll not send notification, if the user is online

                if ($this->input->post("is_user_online") !== "1") {
                    log_notification("message_reply_sent", array("actual_message_id" => $save_id, "parent_message_id" => $message_id));
                }

                //clear the delete status, if the mail deleted
                $this->Messages_model->clear_deleted_status($message_id);

                if ($is_chat) {
                    echo json_encode(array("success" => true, 'data' => $this->_load_messages($message_id, $this->input->post("last_message_id"), 0, true, $to_user_id)));
                } else {
                    $options = array("id" => $save_id, "user_id" => $this->login_user->id);
                    $view_data['reply_info'] = $this->Messages_model->get_details($options)->row;
                    echo json_encode(array("success" => true, 'message' => lang('message_sent'), 'data' => $this->load->view("messages/reply_row", $view_data, true)));
                }

                return;
            }
        }
        echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
    }

    //load messages right panel when clicking load more button
    function view_messages() {
    
        $this->check_clients_permission();
        validate_submitted_data(array(
            "message_id" => "required|numeric",
            "last_message_id" => "numeric",
            "top_message_id" => "numeric"
        ));

        $message_id = $this->input->post("message_id");

        $this->_load_more_messages($message_id, $this->input->post("last_message_id"), $this->input->post("top_message_id"));
    }

    //prepare the chat box messages 
    private function _load_more_messages($message_id, $last_message_id, $top_message_id, $load_as_data = false) {

        $replies_options = array("message_id" => $message_id, "last_message_id" => $last_message_id, "top_message_id" => $top_message_id, "user_id" => $this->login_user->id, "limit" => 10);

        $view_data["replies"] = $this->Messages_model->get_details($replies_options)->result;
        $view_data["message_id"] = $message_id;

        $this->Messages_model->set_message_status_as_read($message_id, $this->login_user->id);

        return $this->load->view("messages/reply_rows", $view_data, $load_as_data);
    }

    /* prepare notifications */

    function get_notifications() {
        validate_submitted_data(array(
            "active_message_id" => "numeric"
        ));

          
        $notifiations = $this->Messages_model->get_notifications($this->login_user->id, $this->login_user->message_checked_at, $this->input->post("active_message_id"));
        $view_data['notifications'] = $notifiations->result();
        echo json_encode(array("success" => true, "active_message_id"=>$this->input->post("active_message_id"), 'total_notifications' => $notifiations->num_rows(), 'notification_list' => $this->load->view("messages/notifications", $view_data, true)));
    }

    function update_notification_checking_status() {
        $now = get_current_utc_time();
        $user_data = array("message_checked_at" => $now);
        $this->Users_model->save($user_data, $this->login_user->id);
    }
    /* prepare notifications */

    function get_g_notifications() {
        validate_submitted_data(array(
            "active_message_id" => "numeric"
        ));

          
        $notifiations = $this->Messages_model->get_g_notifications($this->login_user->id, $this->login_user->g_message_checked_at, $this->input->post("active_message_id"));
        $view_data['notifications'] = $notifiations->result();
        echo json_encode(array("success" => true, "active_message_id"=>$this->input->post("active_message_id"), 'total_notifications' => $notifiations->num_rows(), 'notification_list' => $this->load->view("messages/notifications", $view_data, true)));
    }

    function update_notification_checking_statuss() {
        $now = get_current_utc_time();
        $user_data = array("g_message_checked_at" => $now);
        $this->Users_model->save($user_data, $this->login_user->id);
    }
    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for message */

    function validate_message_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    /* download files by zip */

    function download_message_files($message_id = "") {
        $model_info = $this->Messages_model->get_one($message_id);
        if (!$this->is_my_message($model_info)) {
            redirect("forbidden");
        }

        $files = $model_info->files;


        $timeline_file_path = get_setting("timeline_file_path");
        download_app_files($timeline_file_path, $files);
    }

    function delete_my_messages($id = 0) {

        if (!$id) {
            exit();
        }

        //delete messages for current user.
        $this->Messages_model->delete_messages_for_user($id, $this->login_user->id);
    }

    //prepare chat inbox list
    function chat_list() {

        $view_data['show_users_list'] = false;
        $view_data['show_clients_list'] = false;

        $client_message_users = get_setting("client_message_users");
        if ($this->login_user->user_type === "staff") {
            //user is team member
            $client_message_users_array = explode(",", $client_message_users);
            if (in_array($this->login_user->id, $client_message_users_array)) {
                //user can send message to clients
                $view_data['show_clients_list'] = true;
            }

            //user can send message to team members
            $view_data['show_users_list'] = true;
        } else {
             $this->check_clients_permission();
            //user is a client contact and can send messages
            if ($client_message_users) {
                $view_data['show_users_list'] = true;
            }
        }

        $options = array("login_user_id" => $this->login_user->id);

        $view_data['messages'] = $this->Messages_model->get_chat_list($options)->result();

        $this->load->view("messages/chat/tabs", $view_data);
    }

    function users_list($type) {

        /*
         * team members can send message to all team members
         * clients can only send message to team members (as defined on Client settings)
         * team members can send message to clients (as defined on Client settings)
         */

        $users = "";
        $client_message_users = get_setting("client_message_users");
        if ($this->login_user->user_type === "staff") {
            //user is team member
            $client_message_users_array = explode(",", $client_message_users);
            if (in_array($this->login_user->id, $client_message_users_array) && $type === "client") {
                //user can send message to clients
                $users = $this->Users_model->get_team_members_and_clients("client", "", $this->login_user->id)->result();
            } else if ($type === "staff") {
                //user can send message only to team members
                $users = $this->Users_model->get_team_members_and_clients("staff", "", $this->login_user->id)->result();
            }
        } else if ($this->login_user->user_type === "client" && $type === "staff") {
            //user is a client contact
            if ($client_message_users) {
                $users = $this->Users_model->get_team_members_and_clients("staff", $client_message_users)->result();
            }
        }

        $view_data["users"] = $users;

        $page_type = "";
        if ($type === "staff") {
            $page_type = "team-members-tab";
        } else {
            $page_type = "clients-tab";
        }

        $view_data["page_type"] = $page_type;

        $this->load->view("messages/chat/team_members", $view_data);
    }

    //load messages in chat view
    function view_chat() {

        $this->check_clients_permission();
         
        validate_submitted_data(array(
            "message_id" => "required|numeric",
            "last_message_id" => "numeric",
            "top_message_id" => "numeric",
            "another_user_id" => "numeric"
        ));

        $message_id = $this->input->post("message_id");

        $another_user_id = $this->input->post("another_user_id");

        if ($this->input->post("is_first_load") == "1") {
            $view_data["first_message"] = $this->Messages_model->get_details(array("id" => $message_id, "user_id" => $this->login_user->id))->row;
            $this->load->view("messages/chat/message_title", $view_data);
        }

        $this->_load_messages($message_id, $this->input->post("last_message_id"), $this->input->post("top_message_id"), false, $another_user_id);
    }

    //prepare the chat box messages 
    private function _load_messages($message_id, $last_message_id, $top_message_id, $load_as_data = false, $another_user_id = "") {

        $replies_options = array("message_id" => $message_id, "last_message_id" => $last_message_id, "top_message_id" => $top_message_id, "user_id" => $this->login_user->id);

        $view_data["replies"] = $this->Messages_model->get_details($replies_options)->result;
        $view_data["message_id"] = $message_id;

        $this->Messages_model->set_message_status_as_read($message_id, $this->login_user->id);

        $is_online = false;
        if ($another_user_id) {
            $last_online = $this->Users_model->get_one($another_user_id)->last_online;
            if ($last_online) {
                $is_online = is_online_user($last_online);
            }
        }

        $view_data['is_online'] = $is_online;

        return $this->load->view("messages/chat/message_items", $view_data, $load_as_data);
    }

    function get_active_chat() {

        validate_submitted_data(array(
            "message_id" => "required|numeric"
        ));

        $message_id = $this->input->post("message_id");

        $options = array("id" => $message_id, "user_id" => $this->login_user->id);
        $view_data["message_info"] = $this->Messages_model->get_details($options)->row;

        if (!$this->is_my_message($view_data["message_info"])) {
            redirect("forbidden");
        }

        //$this->Messages_model->set_message_status_as_read($view_data["message_info"]->id, $this->login_user->id);

        $view_data["message_id"] = $message_id;
        $this->load->view("messages/chat/active_chat", $view_data);
    }

    function get_chatlist_of_user() {

        $this->check_clients_permission();
         
        validate_submitted_data(array(
            "user_id" => "required|numeric"
        ));

        $user_id = $this->input->post("user_id");

        $options = array("user_id" => $user_id, "login_user_id" => $this->login_user->id);
        $view_data["messages"] = $this->Messages_model->get_chat_list($options)->result();


        $user_info = $this->Users_model->get_one_where(array("id" => $user_id, "status" => "active", "deleted" => "0"));
        $view_data["user_name"] = $user_info->first_name . " " . $user_info->last_name;

        $view_data["user_id"] = $user_id;
        $view_data["tab_type"] = $this->input->post("tab_type");

        $this->load->view("messages/chat/get_chatlist_of_user", $view_data);
    }

/* reply messsage delete in individual */
function delete($id = 0) {

        if (!$id) {
            exit();
        }

        $post_info = $this->Messages_model->get_one($id);

        //only admin and creator can delete the post
        if (!($this->login_user->is_admin || $post_info->from_user_id == $this->login_user->id)) {
            redirect("forbidden");
        }


        //delete the post and files
        if ($this->Messages_model->delete($id) && $post_info->files) {

            //delete the files
            /*$timeline_file_path = get_setting("timeline_file_path");
            $files = unserialize($post_info->files);

            foreach ($files as $file) {
                $source_path = $timeline_file_path . get_array_value($file, "file_name");
                delete_file_from_directory($source_path);
            }*/
        }
    }


 //groups message module 

/* show grups items */

    function groups_items($auto_select_index = "") {
       // $this->check_clients_permission();
        $this->check_module_availability("module_message");

        $view_data['mode'] = "group_items";
        $view_data['auto_select_index'] = $auto_select_index;
        $this->template->rander("messages/groups/index", $view_data);
    }



    function group_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff","status"=>"active"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $view_data['members_dropdown'] = json_encode($members_dropdown);
        $view_data['model_info'] = $this->Groups_model->get_one($this->input->post('id'));
        $this->load->view('messages/groups/modal_form', $view_data);
    }

     /* add/edit a team */

    function group_save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "members" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "members" => $this->input->post('members')
        );

        if (!$id) {
    // check the title exists    
        $data["title"] =$this->input->post('title');
        if ($this->Groups_model->is_group_title_exists($data["title"])) {
                echo json_encode(array("success" => false, 'message' => lang('group_name_already')));
                exit();
            }

        }
        if ($id) {
    // check the title exists     
        $data["title"] =$this->input->post('title');
        $data["id"] =$this->input->post('id');
       if ($this->Groups_model->is_group_title_exists($data["title"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('group_name_already')));
                exit();
            }

        }

        $save_id = $this->Groups_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_group_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete/undo a team */

    function group_delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Groups_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_group_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Groups_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of group prepared for datatable */

    function group_list_data() {
       // $list_data = $this->Groups_model->get_details()->result()
        if ($this->login_user->user_type === "staff" && $this->login_user->is_admin) {
           $list_data = $this->Groups_model->get_details()->result();
        }else if($this->login_user->user_type === "staff" && !$this->login_user->is_admin){
            $options =array("user_id"=> $this->login_user->id);
            $list_data = $this->Groups_model->get_details($options)->result();
        }
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_group_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* reaturn a row of group list table */

    private function _group_row_data($id) {
        $options = array("id" => $id);
        $data = $this->Groups_model->get_details($options)->row();
        return $this->_group_make_row($data);
    }

    /* prepare a row of group list table */

    private function _group_make_row($data) {
        $total_members = "<span class='label label-light w100'><i class='fa fa-users'></i> " . count(explode(",", $data->members)) . "</span>";
        if($this->login_user->is_admin){
             $delete_option=  modal_anchor(get_uri("messages/group_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_group'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_group'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("messages/group_delete"), "data-action" => "delete"));
        }else{
            $delete_option = "";
        }

        $get_unread_group_message = $this->Groups_comments_model->count_group_unread_message($this->login_user->id,$data->id);
        if($get_unread_group_message){
        $count_unread  ="&nbsp"."<span class='badge badge-secondary' style='background-color: #1672b9;'>" . $get_unread_group_message . "</span>";
    }else{
        $count_unread = "";
    }
        return array(
          "<a href='#' data-id='$data->id' data-index='$data->id' class='message-row link'>" . $data->title. $count_unread . "</a>",
            //$data->title,
            modal_anchor(get_uri("messages/group_members_list"), $total_members, array("title" => lang('team_members'), "data-post-members" => $data->members)),
            /*modal_anchor(get_uri("messages/group_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_group'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_group'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("messages/group_delete"), "data-action" => "delete"))*/
            $delete_option,
        );
    }

     function group_members_list() {
        $view_data['team_members'] = $this->Users_model->get_team_members($this->input->post('members'))->result();
        $this->load->view('messages/groups/members_list', $view_data);
    }


    //get permisissions of a role
    function group_view($project_id) {
        if ($project_id) {
        $options = array("project_id" => $project_id);
        $view_data['comments'] = $this->Groups_comments_model->get_details($options)->result();
        $view_data['project_id'] = $project_id;
        //change message status to read
        $this->Groups_comments_model->set_group_message_status_as_read($project_id, $this->login_user->id);
        $this->load->view("messages/groups/comments/index", $view_data);
        }
    }

/* save project comments */

    function save_group_comment() {
        $id = $this->input->post('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "group_comment");

        $project_id = $this->input->post('project_id');
        $task_id = $this->input->post('task_id');
        $file_id = $this->input->post('file_id');
        $customer_feedback_id = $this->input->post('customer_feedback_id');
        $comment_id = $this->input->post('comment_id');
        $description = $this->input->post('description');
        $group_members = $this->Groups_model->get_one($project_id);
        $convert_array = explode(",",$group_members->members);
        if (($key = array_search($this->login_user->id, $convert_array)) !== false) {
               unset($convert_array[$key]);
         }
        $array_string_group_members =  implode(",",$convert_array);

        $data = array(
            "created_by" => $this->login_user->id,
            "created_at" => get_current_utc_time(),
            "project_id" => $project_id,
            "file_id" => $file_id ? $file_id : 0,
            "task_id" => $task_id ? $task_id : 0,
            "customer_feedback_id" => $customer_feedback_id ? $customer_feedback_id : 0,
            "comment_id" => $comment_id ? $comment_id : 0,
            "description" => $description,
            "group_members" => $array_string_group_members
        );

        $data = clean_data($data);

        $data["files"] = $files_data; //don't clean serilized data

        $save_id = $this->Groups_comments_model->save_comment($data, $id);
        if ($save_id) {
            $response_data = "";
            $options = array("id" => $save_id);

            if ($this->input->post("reload_list")) {
                $view_data['comments'] = $this->Groups_comments_model->get_details($options)->result();
                $response_data = $this->load->view("messages/groups/comments/comment_list", $view_data, true);
            }
            echo json_encode(array("success" => true, "data" => $response_data, 'message' => lang('comment_submited')));


            $comment_info = $this->Groups_comments_model->get_one($save_id);

            $notification_options = array("group_id" => $comment_info->project_id, "group_comment_id" => $save_id);

            if ($comment_info->file_id) { //file comment
                $notification_options["project_file_id"] = $comment_info->file_id;
                log_notification("project_file_commented", $notification_options);
            } else if ($comment_info->task_id) { //task comment
                $notification_options["task_id"] = $comment_info->task_id;
                log_notification("project_task_commented", $notification_options);
            } else if ($comment_info->customer_feedback_id) {  //customer feedback comment
                if ($comment_id) {
                    log_notification("project_customer_feedback_replied", $notification_options);
                } else {
                    log_notification("project_customer_feedback_added", $notification_options);
                }
            } else {  //project comment
                if ($comment_id) {
                    //log_notification("group_comment_replied", $notification_options);
                } else {
                    //log_notification("group_comment_added", $notification_options);
                }
            }
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* show comment reply form */

    function group_comment_reply_form($comment_id, $type = "project", $type_id = 0) {
        $view_data['comment_id'] = $comment_id;

        if ($type === "project") {
            $view_data['project_id'] = $type_id;
        } else if ($type === "task") {
            $view_data['task_id'] = $type_id;
        } else if ($type === "file") {
            $view_data['file_id'] = $type_id;
        } else if ($type == "customer_feedback") {
            $view_data['project_id'] = $type_id;
        }
        $this->load->view("messages/groups/comments/reply_form", $view_data);
    }


 /* load all replies of a comment */

    function group_view_comment_replies($comment_id) {
        $view_data['reply_list'] = $this->Groups_comments_model->get_details(array("comment_id" => $comment_id))->result();
        $this->load->view("messages/groups/comments/reply_list", $view_data);
    }

     function group_delete_comment($id = 0) {

        if (!$id) {
            exit();
        }

        $comment_info = $this->Groups_comments_model->get_one($id);

        //only admin and creator can delete the comment
        if (!($this->login_user->is_admin || $comment_info->created_by == $this->login_user->id)) {
            redirect("forbidden");
        }


        //delete the comment and files
        if ($this->Groups_comments_model->delete($id) && $comment_info->files) {

            //delete the files
            $file_path = get_setting("timeline_file_path");
            $files = unserialize($comment_info->files);

            foreach ($files as $file) {
                $source_path = $file_path . get_array_value($file, "file_name");
                delete_file_from_directory($source_path);
            }
        }
    }

     /* download files by zip */

    function group_download_comment_files($id) {

        $info = $this->Groups_comments_model->get_one($id);

       // $this->init_project_permission_checker($info->project_id);
        if ($this->login_user->user_type == "client") {

            redirect("forbidden");
        } else if ($this->login_user->user_type == "user") {
            redirect("forbidden");
        }

        download_app_files(get_setting("timeline_file_path"), $info->files);
    }

    /* group message module */








}

/* End of file messages.php */
/* Location: ./application/controllers/messages.php */