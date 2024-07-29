<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Credentials extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("assets_data");
         $this->access_only_allowed_members();
    }

     function index() {
        $this->check_module_availability("module_assets_data");
        //$this->template->rander("credentials/index");
        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("credentials/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("credentials/index");
        }else {


        $this->template->rander("credentials/index");
    } 
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Credentials_model->get_one($this->input->post('id'));
         $labels = explode(",", $this->Notes_model->get_label_suggestions($this->login_user->id));
 $label_suggestions = array();
        foreach ($labels as $label) {
            if ($label && !in_array($label, $label_suggestions)) {
                $label_suggestions[] = $label;
            }
        }
        if (!count($label_suggestions)) {
            $label_suggestions = array("0" => "Important");
        }
        $view_data['label_suggestions'] = $label_suggestions;
        $this->load->view('credentials/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "username" => "required",
            "password" => "required"
        )); 

        $id = $this->input->post('id');
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "credentials");
        $new_files = unserialize($files_data);
        $data = array(
            "title" => $this->input->post('title'),
            "username" => $this->input->post('username'),
            "description" => $this->input->post('description'),
            "password" => $this->input->post('password'),
            "url" => $this->input->post('url'),
            "labels" => $this->input->post('labels'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );

        if ($id) {
            $note_info = $this->Notes_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $note_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);
        if (!$id)
{
            $data['created_date'] = get_current_utc_time();
        }
        $save_id = $this->Credentials_model->save($data, $id);
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
         $save_id = $this->Credentials_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Credentials_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Credentials_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Credentials_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Credentials_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
  $title = $data->title;
        $note_labels = "";
        if ($data->labels) {
            $labels = explode(",", $data->labels);
            foreach ($labels as $label) {
                $note_labels .= "<span class='label label-info clickable'>" . $label . "</span> ";
            }
            $title .= "<br />" . $note_labels;
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

       $website_link ="";
       if($data->url){
       $website_address = to_url($data->url); //check http or https in url
        $website_link.="<a target='_blank' href='$website_address'>$data->url</a>";
    }

    $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("credentials/file_preview/" . $file_name)));
                }
            }
        }
        return array($data->created_date,$title
            ,
             $data->username,
            $data->password,
            $data->description,
            //$data->url,
            $website_link ? $website_link : "-",
            $files_link,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("credentials/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_credential'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_credential'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("credentials/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function file_preview($file_name = "") {
        if ($file_name) {
            $view_data["file_url"] = get_file_uri(get_setting("timeline_file_path") . $file_name);
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);

            $this->load->view("credentials/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for notes */

    function validate_notes_file() {
        return validate_post_file($this->input->post("file_name"));
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */