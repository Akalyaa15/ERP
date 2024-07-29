<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends MY_Controller {

    function __construct() {
        parent::__construct();

        //this module is accessible only to team members 
        $this->access_only_team_members();

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();

        //initialize managerial permission
        $this->init_permission_checker("attendance");
    }

    //check ip restriction for none admin users
    private function check_allowed_ip() {
        if (!$this->login_user->is_admin) {
          if ($this->login_user->work_mode=='0') {
  
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) {
                    redirect("forbidden_attendance");
                }
        
           }   
         }
        }
    }

    //only admin or assigend members can access/manage other member's attendance
    protected function access_only_allowed_members($user_id = 0) {
        if ($this->access_type !== "all") {
            if ($user_id === $this->login_user->id || !array_search($user_id, $this->allowed_members)) {
                redirect("forbidden");
            }
        }
    }

    //show attendance list view
    function index() {
        $this->check_module_availability("module_attendance");

        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
        $this->template->rander("attendance/index", $view_data);
    }
    function forbid() {
                           redirect("forbidden_attendance");

    }
    //show add/edit attendance modal
    function modal_form() {
        $user_id = 0;

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $view_data['model_info'] = $this->Attendance_model->get_one($this->input->post('id'));
        if ($view_data['model_info']->id) {
            $user_id = $view_data['model_info']->user_id;

            $this->access_only_allowed_members($user_id, true);
        }

        if ($user_id) {
            //edit mode. show user's info
            $view_data['team_members_info'] = $this->Users_model->get_one($user_id);
        } else {
            //new add mode. show users dropdown
            //don't show none allowed members in dropdown
            if ($this->access_type === "all") {
                $where = array("user_type" => "staff");
            } else {
                if (!count($this->allowed_members)) {
                    redirect("forbidden");
                }
                $where = array("user_type" => "staff", "id !=" => $this->login_user->id, "where_in" => array("id" => $this->allowed_members));
            }

            $view_data['team_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);
        }

        $this->load->view('attendance/modal_form', $view_data);
    }

    //show attendance note modal
    function note_modal_form() {

        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $view_data["clock_out"] = $this->input->post("clock_out"); //trigger clockout after submit?

        $todo_id = $this->input->post('id');
       
      /* if (!$model_info-> $id) {
            show_404();
        } */
    

        
       
        $view_data['todo_id'] = $todo_id;
        $view_data['project_id'] = 0;
        $projects = $this->Tasks_model->get_my_projects_dropdown_list($this->login_user->id)->result();
        $projects_dropdown = array(array("id" => "", "text" => "- " . lang("project") . " -"));
        foreach ($projects as $project) {
            if ($project->project_id && $project->project_title) {
                $projects_dropdown[] = array("id" => $project->project_id, "text" => $project->project_title);
            }
        }
//tasks team members dropdown
$optionss = array(
            "id" =>$todo_id,
                   );
        $clock_in_data = $this->Attendance_model->get_details($optionss)->row();

$clock_user_id= $clock_in_data->user_id;
        
        $team_members_dropdown = array(array("id" => "", "text" => "- " . lang("team_member") . " -"));
        $assigned_to_list = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        foreach ($assigned_to_list as $key => $value) {

           // if ($key == $this->login_user->id) {
              if ($key == $clock_user_id) {  
                $team_members_dropdown[] = array("id" => $key, "text" => $value, "isSelected" => true);
            } else {
                $team_members_dropdown[] = array("id" => $key, "text" => $value);
            }
        }

        /*pending tasks list */
         $tasks_pending_options = array(
            "specific_user_id" => $clock_user_id,
            
            //"project_status" => "open",
            "status_ids" => implode(",", array('1','2')), //dedault the status value in todo , progress
            
        );

        /*$Attendance_savetask_todo_options = array("todo_id" => $todo_id,"user_id" => $clock_user_id);

        $Attendance_savetask_todo = $this->Attendance_task_todo_model->get_details($Attendance_savetask_todo_options)->result();
        $Attendance_savetask_items_array = array();
        $Attendance_savetask_id_items_array =array();
       foreach ($Attendance_savetask_todo as $Attendance_savetask_item) {
            $Attendance_savetask_items_array[] = $this->_make_Attendance_savetask_item_row($Attendance_savetask_item);
            $Attendance_savetask_id_items_array[]  = $Attendance_savetask_item->title;
        }
        $view_data["Attendance_savetask_items"] = json_encode($Attendance_savetask_items_array);

        $checklist_items = $this->Tasks_model->get_details($tasks_pending_options)->result();

        $checklist_items_array = array();

       
       foreach ($checklist_items as $checklist_item) {
        if(!in_array($checklist_item->id, $Attendance_savetask_id_items_array)){
           $checklist_items_array[] = $this->_make_checklist_item_row($checklist_item,$todo_id,$clock_user_id);
        }
        }
        $view_data["checklist_items"] = json_encode($checklist_items_array);*/
        /* end task list */

        $view_data['team_members_dropdown'] = json_encode($team_members_dropdown);
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("tasks", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['task_statuses'] = $this->Task_status_model->get_details()->result();

        $view_data['projects_dropdown'] = json_encode($projects_dropdown);

        $view_data['model_info'] = $this->Attendance_model->get_one($this->input->post('id'));

        /* already exit task task */
       $Attendance_savetask_todo_options = array("todo_id" => $todo_id,"user_id" => $clock_user_id);
       $Attendance_savetask_todo = $this->Attendance_todo_model->get_details($Attendance_savetask_todo_options)->result();
        $Attendance_savetask_id_items_array = array();
       
       foreach ($Attendance_savetask_todo as $Attendance_savetask_item) {
           
            $Attendance_savetask_id_items_array[]  = $Attendance_savetask_item->task_id;
        }



         $tasks_list = $this->Tasks_model->get_details($tasks_pending_options)->result();
        $tasks_list_dropdown = array(array("id" => "", "text" => "- " . lang("task_list") . " -"));
        foreach ($tasks_list as $task_list) {
            if ($task_list->id && $task_list->project_title) {
                 if(!in_array($task_list->id, $Attendance_savetask_id_items_array)){
                $tasks_list_dropdown[] = array("id" => $task_list->id, "text" => $task_list->title."  - " . lang("project") . ": " . $task_list->project_title . "");
            }
        }
        }
        $view_data['tasks_list_dropdown'] = json_encode($tasks_list_dropdown);


        $this->load->view('attendance/note_modal_form', $view_data);
    }

    /* tasks save and list */

    private function _make_Attendance_savetask_item_row($data = array(), $return_type = "row") {
        $checkbox_class = "checkbox-blank";
        $title_class = "";
        $is_checked_value = 1;

 $task_title_list_data = $this->Tasks_model->get_one($data->title);
 $task_title = $task_title_list_data->title;
        if ($data->is_checked == 0) {
            $is_checked_value = 0;
            $checkbox_class = "checkbox-checked";
            //$title_class = "text-line-through text-off";
        }

        $status = "<span class='$checkbox_class'></span>";
       /*$status = js_anchor("<span class='$checkbox_class'></span>", array('title' => "", "data-id" => $data->id, "data-value" => $is_checked_value, "data-act" => "update-checklist-item-status-checkbox"));*/
       $status = ajax_anchor(get_uri("attendance/delete_savechecklist_item/$data->id/"), "<span class='$checkbox_class'></span>", array("class" => "delete-checklist-item", "title" => lang("delete"), "data-fade-out-on-success" => "#checklist-item-rows-$data->id"));

        $title = "<span class='font-13 $title_class'>" . $task_title. "</span>";

        $delete = ajax_anchor(get_uri("attendance/delete_savechecklist_item/$data->id/"), "<i class='fa fa-times pull-right p3'></i>", array("class" => "delete-checklist-item", "title" => lang("delete"), "data-fade-out-on-success" => "#checklist-item-rows-$data->id"));
       

        if ($return_type == "data") {
            return $status . $title . $delete;
        }

        return "<div id='checklist-item-rows-$data->id' class='list-group-item mb5 checklist-item-rows' data-id='$data->id'>" . $status . $title . $delete . "</div>";
    }

    function delete_savechecklist_item($id) {

        $task_title_list_data = $this->Attendance_task_todo_model->get_one($id);
        $get_todo_id = $task_title_list_data->todo_id;

        $task_options = array("todo_id" =>$get_todo_id);
        $atttask_table_list = $this->Attendance_task_todo_model->get_details($task_options)->num_rows();

        if($atttask_table_list>1){


       
         if ($this->Attendance_task_todo_model->delete($id)) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false));
        }
    }else{
        echo json_encode(array("success" => false));
    }
    }

    private function _make_checklist_item_row($data = array(),$todo_id=0,$clock_user_id, $return_type = "row") {
        $checkbox_class = "checkbox-blank";
        $title_class = "";
        $is_checked_value = 1;

        if ($data->is_checked == 1) {
            $is_checked_value = 0;
            $checkbox_class = "checkbox-checked";
           // $title_class = "text-line-through text-off";
        }

        /*$status = js_anchor("<span class='$checkbox_class'></span>", array('title' => "", "data-id" => $data->id, "data-value" => $is_checked_value, "data-act" => "update-checklist-item-status-checkbox"));*/

       $status = ajax_anchor(get_uri("attendance/save_checklist_item/$data->id/$todo_id/$clock_user_id"), "<span class='$checkbox_class'></span>", array("class" => "delete-checklist-item", "title" => lang("save"), "data-fade-out-on-success" => "#checklist-item-row-$data->id"));

        $title = "<span class='font-13 $title_class'>" . $data->title. "</span>";

        $delete = ajax_anchor(get_uri("attendance/save_checklist_item/$data->id/$todo_id/$clock_user_id"), "<i class='fa fa-check-circle pull-right p3'></i>", array("class" => "delete-checklist-item", "title" => lang("save"), "data-fade-out-on-success" => "#checklist-item-row-$data->id"));
        /*if (!$this->can_edit_tasks()) {
            $delete = "";
        }*/

        if ($return_type == "data") {
            return $status . $title . $delete;
        }

        return "<div id='checklist-item-row-$data->id' class='list-group-item mb5 checklist-item-row' data-id='$data->id'>" . $status . $title . $delete . "</div>";
    }

       
function save_checklist_item($id,$todo_id,$clock_user_id) {

        /*$task_id = $this->Checklist_items_model->get_one($id)->task_id;
        $project_id = $this->Tasks_model->get_one($task_id)->project_id;*/
$todo_model_info =$this->Attendance_model->get_one($todo_id);
       
        $now = get_current_utc_time();
      $start_date = date("Y-m-d",strtotime($todo_model_info->in_time));
      $check_options = array(
           "title" => $id,
           "start_date" =>$start_date,
           "todo_id" =>  $todo_id,
           "user_id" => $clock_user_id

);

$check_exits_todo =  $this->Attendance_task_todo_model->get_details($check_options)->result();
if(!$check_exits_todo){
   $data = array(
           "title" => $id,
           "description" => $this->input->post('description') ? $this->input->post('description') : "",
           // "created_by" => $this->login_user->id,
           // "labels" => $this->input->post('labels') ? $this->input->post('labels') : "",
             //"start_date" => $this->input->post('start_date'),
             "start_date" =>$start_date,
             "todo_id" =>  $todo_id,
             "user_id" => $clock_user_id
                     );

        $save_id = $this->Attendance_task_todo_model->save($data); 
}
       

        if ($save_id) {
            /*$item_info = $this->Attendance_task_todo_model->get_one($save_id);
            echo json_encode(array("success" => true, "data" => $this->_make_checklist_item_row($item_info, "data"), 'id' => $save_id));*/
            echo json_encode(array("success" => true,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    /* end tasks save task todo  and list */

       

       function log_sor_time() {

        validate_submitted_data(array(
            "id" => "numeric",
            //"hsn_code" => "required",
            //"gst" => "required"
        ));

        $id = $this->input->post('id');
       // $now = get_current_utc_time();
         $now = date("Y-m-d"); 
        $data = array(
            "note" => $this->input->post('sor_note'),
            "in_time" => $now,
             
             "status" => "incomplete",
             "user_id" => $this->login_user->id,
             "clockin_location" => $this->input->post('result')

        );
        $save_id = $this->Attendance_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved'), "clock_widget" => clock_widget(true)));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
//show attendance note modal
    function start_day_report_modal_form() {

        /*validate_submitted_data(array(
            "id" => "numeric|required"
        )); */

        $view_data["clock_in"] = $this->input->post("clock_in"); //trigger clockout after submit?

        $view_data['model_info'] = $this->Attendance_model->get_one($this->input->post('id'));
        $this->load->view('attendance/add_todo/start_day_report_modal', $view_data);
    }

    function todo_view() {

        $todo_id = $this->input->post('id');
        $model_info = $this->Attendance_model->get_details(array("id" => $todo_id))->row();
      /* if (!$model_info-> $id) {
            show_404();
        } */
    

        $view_data['model_info'] = $model_info;
       
        $view_data['todo_id'] = $todo_id;

        $view_data['project_id'] = 0;
        $projects = $this->Tasks_model->get_my_projects_dropdown_list($this->login_user->id)->result();
        $projects_dropdown = array(array("id" => "", "text" => "- " . lang("project") . " -"));
        foreach ($projects as $project) {
            if ($project->project_id && $project->project_title) {
                $projects_dropdown[] = array("id" => $project->project_id, "text" => $project->project_title);
            }
        }

        $team_members_dropdown = array(array("id" => "", "text" => "- " . lang("team_member") . " -"));
        $assigned_to_list = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        foreach ($assigned_to_list as $key => $value) {

            if ($key == $this->login_user->id) {
                $team_members_dropdown[] = array("id" => $key, "text" => $value, "isSelected" => true);
            } else {
                $team_members_dropdown[] = array("id" => $key, "text" => $value);
            }
        }


/*  pending user tasks list  */

$optionss = array(
            "id" =>$todo_id,
                   );
        $clock_in_data = $this->Attendance_model->get_details($optionss)->row();

$clock_user_id= $clock_in_data->user_id;


 $tasks_pending_options = array(
            "specific_user_id" => $clock_user_id,
            
            //"project_status" => "open",
            "status_ids" => implode(",", array('1','2')), //dedault the status value in todo , progress
            
        );

       /* $Attendance_savetask_todo_options = array("todo_id" => $todo_id,"user_id" => $clock_user_id);

        $Attendance_savetask_todo = $this->Attendance_task_todo_model->get_details($Attendance_savetask_todo_options)->result();
        $Attendance_savetask_items_array = array();
        $Attendance_savetask_id_items_array =array();
       foreach ($Attendance_savetask_todo as $Attendance_savetask_item) {
            $Attendance_savetask_items_array[] = $this->_make_Attendance_savetask_item_row($Attendance_savetask_item);
            $Attendance_savetask_id_items_array[]  = $Attendance_savetask_item->title;
        }
        $view_data["Attendance_savetask_items"] = json_encode($Attendance_savetask_items_array);

        $checklist_items = $this->Tasks_model->get_details($tasks_pending_options)->result();

        $checklist_items_array = array();

       
       foreach ($checklist_items as $checklist_item) {
        if(!in_array($checklist_item->id, $Attendance_savetask_id_items_array)){
           $checklist_items_array[] = $this->_make_checklist_item_row($checklist_item,$todo_id,$clock_user_id);
        }
        }
        $view_data["checklist_items"] = json_encode($checklist_items_array);*/

        /* end pendinng task list */


        $view_data['team_members_dropdown'] = json_encode($team_members_dropdown);
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("tasks", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['task_statuses'] = $this->Task_status_model->get_details()->result();

        $view_data['projects_dropdown'] = json_encode($projects_dropdown);

        /* already exit task task */
       $Attendance_savetask_todo_options = array("todo_id" => $todo_id,"user_id" => $clock_user_id);
       $Attendance_savetask_todo = $this->Attendance_todo_model->get_details($Attendance_savetask_todo_options)->result();
        $Attendance_savetask_items_array = array();
       
       foreach ($Attendance_savetask_todo as $Attendance_savetask_item) {
           
            $Attendance_savetask_id_items_array[]  = $Attendance_savetask_item->task_id;
        }
      


        /*pending  task dropdown */
        $tasks_list = $this->Tasks_model->get_details($tasks_pending_options)->result();
        $tasks_list_dropdown = array(array("id" => "", "text" => "- " . lang("task_list") . "-"));
        foreach ($tasks_list as $task_list) {
            if ($task_list->id && $task_list->project_title) {
                if(!in_array($task_list->id, $Attendance_savetask_id_items_array)){
                $tasks_list_dropdown[] = array("id" => $task_list->id, "text" => $task_list->title."  - " . lang("project") . ": " . $task_list->project_title . "");
            }
        }
        }
        $view_data['tasks_list_dropdown'] = json_encode($tasks_list_dropdown);
        

       

        $this->load->view('attendance/add_todo/add_todo', $view_data);
    }

    function todo_save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        //$now = get_current_utc_time();
        $todo_model_info =$this->Attendance_model->get_one($id);
        $start_date = date("Y-m-d",strtotime($todo_model_info->in_time));

        //set user timezone current time
        $usertime_model_info =$this->Users_model->get_one($todo_model_info->user_id);
        $user_timezone_date_crate = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $user_timezone_date_crate->setTimeZone(new DateTimeZone($usertime_model_info->user_timezone));
        $now = $user_timezone_date_crate->format("Y-m-d H:i:s");
        
        $check_options = array(
           "task_id" => $this->input->post('task_user_id'),
           "start_date" =>$start_date,
           "todo_id" => $id,
           "user_id" => $todo_model_info->user_id,


         );

      $check_exits_todo =  $this->Attendance_todo_model->get_details($check_options)->result();
if(!$check_exits_todo){
        $data = array(
            "title" => $this->input->post('title'),
           // "description" => $this->input->post('description') ? $this->input->post('description') : "",
           // "created_by" => $this->login_user->id,
           // "labels" => $this->input->post('labels') ? $this->input->post('labels') : "",
             //"start_date" => $this->input->post('start_date'),
            "start_date" =>$now,
             "todo_id" => $id,
             "user_id" => $this->login_user->id,
             "task_id" => $this->input->post('task_user_id'),
        );

        $save_id = $this->Attendance_todo_model->save($data);
    }else if($check_exits_todo){
         echo json_encode(array("success" => false, 'message' => 'This Task is already exists'));
                exit();
    }

$options = array("id" =>  $id );
            //$earnings_info = $this->Payslip_earnings_model->get_details($options)->row();
            //$item_info = $this->Attendance_todo_model->get_one($save_id);
            $attendance_user_info = $this->Attendance_model->get_details($options)->row();
$attendance_user_infos = $attendance_user_info->in_time;
$timestamp = $attendance_user_infos;
$splitTimeStamp = explode(" ",$timestamp);
$date = $splitTimeStamp[0];
$time = $splitTimeStamp[1];
if($time == '00:00:00') {
$DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $id);
//$start= $DB4->update('attendance', array('user_id' => $this->login_user->id,"in_time" => $now));
$start= $DB4->update('attendance', array("in_time" => $now));
}
        if ($save_id) {
            $item_info = $this->Attendance_todo_model->get_one($save_id);
            echo json_encode(array("success" => true, "data" => $this->_todo_make_row($item_info), 'id' => $save_id,"clock_widget" => clock_widget(true),'message' => lang('record_saved')));

            } else {
            echo json_encode(array("success" => false));
        }
    }


    function add_todo_modal_save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "todo_id" => "required"
        ));

        $id = $this->input->post('id');
        $todo_id = $this->input->post('todo_id');
        $now = get_current_utc_time();
        $data = array(
            "title" => $this->input->post('title'),
           "description" => $this->input->post('description') ? $this->input->post('description') : "",
           // "created_by" => $this->login_user->id,
           // "labels" => $this->input->post('labels') ? $this->input->post('labels') : "",
             //"start_date" => $this->input->post('start_date'),
             "start_date" =>$this->input->post('start_date')?$this->input->post('start_date'):$now,
             "todo_id" =>  $todo_id,
             "user_id" => $this->login_user->id
        );

        $save_id = $this->Attendance_todo_model->save($data,$id);

        if ($save_id) {
             $options = array("id" => $todo_id);
            //$earnings_info = $this->Payslip_earnings_model->get_details($options)->row();
            //$item_info = $this->Attendance_todo_model->get_one($save_id);
            $item_info = $this->Attendance_todo_model->get_details($options)->row();
            echo json_encode(array("success" => true, "todo_id" => $item_info->todo_id, "data" => $this->_todo_make_row($item_info), 'id' => $save_id,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    /* upadate a task status */

    function todo_save_status() {

        validate_submitted_data(array(
            "id" => "numeric|required",
            "status" => "required"
        ));
$id = $this->input->post('id');
        //$this->access_only_team_members();
        $data = array(
            "status" => $this->input->post('status')
        );

        $save_id = $this->Attendance_todo_model->save($data, $id);
         
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => 
                $this->_todo_row_data($save_id), 'id' => $save_id, "message" => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }


    function todo_title_view() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $model_info = $this->Attendance_todo_model->get_one($this->input->post('id'));

      //  $this->validate_access($model_info);

        $view_data['model_info'] = $model_info;
        $this->load->view('attendance/add_todo/todo_title_view', $view_data);
    }

    function add_todo_modal_form() {
        $view_data['model_info'] = $this->Attendance_todo_model->get_one($this->input->post('id'));
        if (!$todo_id) {
            $todo_id = $view_data['model_info']->todo_id;
        }
       $todo_id = $view_data['model_info']->todo_id;
       $view_data['todo_id'] = $todo_id;
        //$labels = explode(",", $this->Attendance_todo_model->get_label_suggestions($this->login_user->id));

        //check permission for saved todo list
      /*  if ($view_data['model_info']->id) {
            $this->validate_access($view_data['model_info']);
        } 

        $label_suggestions = array();
        foreach ($labels as $label) {
            if ($label && !in_array($label, $label_suggestions)) {
                $label_suggestions[] = $label;
            }
        }
        if (!count($label_suggestions)) {
            $label_suggestions = array("0" => "Important");
        } 
        $view_data['label_suggestions'] = $label_suggestions; */
        $this->load->view('attendance/add_todo/add_todo_modal_form', $view_data);
    }

    function todo_delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

       // $todo_info = $this->Attendance_todo_model->get_one($id);
        //$this->validate_access($todo_info);

      /*  if ($this->input->post('undo')) {
            if ($this->Attendance_todo_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {  */
            if ($this->Attendance_todo_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        /*} */
    }

    function todo_list_data($id =0) {

       $status = $this->input->post('status') ? implode(",", $this->input->post('status')) : "";
       /* $options = array(
            "created_by" => $this->login_user->id,
            "status" => $status
        ); */
        $options = array("todo_id" => $id,"user_id" => $this->login_user->id,"status" => $status);

        $list_data = $this->Attendance_todo_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_todo_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _todo_row_data($id) {
        $options = array("id" => $id);
        $data = $this->Attendance_todo_model->get_details($options)->row();
        return $this->_todo_make_row($data);
    }

    private function _todo_make_row($data) {
        $title = modal_anchor(get_uri("attendance/todo_title_view/" . $data->id), $data->title, array("class" => "edit", "title" => lang('todo'), "data-post-id" => $data->id));
      /* $todo_labels = "";
        if ($data->labels) {
            $labels = explode(",", $data->labels);
            foreach ($labels as $label) {
                $todo_labels.="<span class='label label-info clickable'>" . $label . "</span> ";
            }
            $title.="<span class='pull-right'>" . $todo_labels . "</span>";
        }  */


     $status_class = "";
        $checkbox_class = "checkbox-blank";
        if ($data->status === "to_do") {
            $status_class = "b-warning";
        } else {
            $checkbox_class = "checkbox-checked";
            $status_class = "b-success";
        } 

     $check_status = js_anchor("<span class='$checkbox_class'></span>", array('title' => "", "class" => "", "data-id" => $data->id, "data-value" => $data->status === "done" ? "to_do" : "done", "data-act" => "update-todo-status-checkbox"));

        $start_date_text = "";
        if (is_date_exists($data->start_date)) {
            //$start_date_text = format_to_date($data->start_date, false);
            $start_date_text = attendance_format_to_date($data->start_date, false);
            if (get_my_local_time("Y-m-d") > $data->start_date && $data->status != "done") {
                $start_date_text = "<span class='text-danger'>" . $start_date_text . "</span> ";
            } else if (get_my_local_time("Y-m-d") == $data->start_date && $data->status != "done") {
                $start_date_text = "<span class='text-warning'>" . $start_date_text . "</span> ";
            }
        }  

$edit_button="";
if($data->status != "done") {
   $edit_button = modal_anchor(get_uri("attendance/add_todo_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            .js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("attendance/todo_delete"), "data-action" => "delete-confirmation"));
}

 $task_id_details = $this->Tasks_model->get_one($data->task_id);
 $task_id_client = $this->Clients_model->get_one($task_id_details->client_id);
 $task_id_project = $this->Projects_model->get_one($task_id_details->project_id);
 $task_id_status = $this->Task_status_model->get_one($task_id_details->status_id);


  $task_title = "-";
  if($task_id_details->title){
  $task_title = modal_anchor(get_uri("projects/task_view"), $task_id_details->title, array("title" => lang('task_info') . " #$data->id", "data-post-id" => $task_id_details->id));
}
  $task_client = "-";
  if($task_id_client->company_name){
  $task_client = anchor(get_uri("clients/view/" . $task_id_client->id), $task_id_client->company_name);
}
$task_project_title = "-";
if (isset($task_id_project->title)) {
    if (isset($task_id_project->project_id)) {
        $task_project_title = anchor(get_uri("projects/view/" . $task_id_project->project_id), $task_id_project->title);
    } else {
        // Handle missing project_id
        log_message('error', 'Missing project_id for task with title: ' . $task_id_project->title);
        $task_project_title = $task_id_project->title . " (No project assigned)";
    }
} else {
    // Handle case where title itself is missing (if necessary)
    log_message('error', 'Missing title for task.');
}

return array(
  $status_class,
  "<i class='hide'>" . $data->id . "</i>" . $check_status,
  $title,
  $task_project_title,
  // Other elements...

            //$data->title,
            $data->start_date,
            $start_date_text,
            $task_title,
            $task_id_details->start_date,
            $task_id_details->deadline,
            $task_client,
            $task_project_title,
            $task_id_status->title,
           // modal_anchor(get_uri("attendance/add_todo_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            //.js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("attendance/todo_delete"), "data-action" => "delete-confirmation"))
            $edit_button 
        );
    }

    //add/edit attendance record
    function save() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric",
            "in_date" => "required",
            "out_date" => "required",
            "in_time" => "required",
            "out_time" => "required"
        ));

        //convert to 24hrs time format
        $in_time = $this->input->post('in_time');
        $out_time = $this->input->post('out_time');

        if (get_setting("time_format") != "24_hours") {
            $in_time = convert_time_to_24hours_format($in_time);
            $out_time = convert_time_to_24hours_format($out_time);
        }

        //join date with time
        $in_date_time = $this->input->post('in_date') . " " . $in_time;
        $out_date_time = $this->input->post('out_date') . " " . $out_time;

        //add time offset
        /*$in_date_time = convert_date_local_to_utc($in_date_time);
        $out_date_time = convert_date_local_to_utc($out_date_time);*/

        $data = array(
            "in_time" => $in_date_time,
            "out_time" => $out_date_time,
            "status" => "pending",
            "note" => $this->input->post('note')
        );

        //save user_id only on insert and it will not be editable
        if ($id) {
            $info = $this->Attendance_model->get_one($id);
            $user_id = $info->user_id;
        } else {
            $user_id = $this->input->post('user_id');
            $data["user_id"] = $user_id;
        }

        $this->access_only_allowed_members($user_id);


        $save_id = $this->Attendance_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => $id ? true : false, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //edit attendance note
    function save_note() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $data = array(
            "note" => $this->input->post('note')
        );


        $save_id = $this->Attendance_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //clock in / clock out
     function log_time() {
        $note = $this->input->post('note');
        $result = $this->input->post('result');

        $this->Attendance_model->log_time($this->login_user->id, $note,$result);
        if ($this->input->post("clock_out")) {
            echo json_encode(array("success" => true, "clock_widget" => clock_widget(true)));
        } else {
            clock_widget();
        }
    }

    //delete/undo attendance record
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->access_type !== "all") {
            $info = $this->Attendance_model->get_one($id);
            $this->access_only_allowed_members($info->user_id);
        }

        if ($this->input->post('undo')) {
            if ($this->Attendance_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Attendance_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* get all attendance of a given duration */

    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
$user_ids = $this->input->post('userr_id');
 if($user_id){
        $options = array("start_date" => $start_date, "end_date" => $end_date, "login_user_id" => $this->login_user->id, "user_id" => $user_id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $list_data = $this->Attendance_model->get_details($options)->result();
        }else if($user_ids){
        $options = array("start_date" => $start_date, "end_date" => $end_date, "login_user_id" => $this->login_user->id, "user_id" => $user_ids, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $list_data = $this->Attendance_model->get_details($options)->result();
    }else{
        $options = array("start_date" => $start_date, "end_date" => $end_date, "login_user_id" => $this->login_user->id, "user_id" => $user_id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $list_data = $this->Attendance_model->get_details($options)->result();
    }
        
        
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load attendance attendance info view
    function attendance_info() {
        $this->check_module_availability("module_attendance");

        $view_data['user_id'] = $this->login_user->id;
        if ($this->input->is_ajax_request()) {
            $this->load->view("team_members/attendance_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            $this->template->rander("team_members/attendance_info", $view_data);
        }
    }

    //get a row of attendnace list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Attendance_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare a row of attendance list
    private function _make_row($data) {
        $image_url = get_avatar($data->created_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";
        $out_time = $data->out_time;
        if (!is_date_exists($out_time)) {
            $out_time = "";
        }

        $to_time = strtotime($data->out_time);
        if (!$out_time) {
            $to_time = strtotime($data->in_time);
        }
        $from_time = strtotime($data->in_time);

        $option_links = modal_anchor(get_uri("attendance/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_attendance'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_attendance'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("attendance/delete"), "data-action" => "delete-confirmation"));

        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        $note_link = modal_anchor(get_uri("attendance/note_modal_form"), "<i class='fa fa-comment-o p10'></i>", array("class" => "edit text-muted", "title" => lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note_link = modal_anchor(get_uri("attendance/note_modal_form"), "<i class='fa fa-comment p10'></i>", array("class" => "edit text-muted", "title" => $data->note, "data-modal-title" => lang("note"), "data-post-id" => $data->id));
        }
if($data->user_user_type=="staff"){
        return array(
            get_team_member_profile_link($data->user_id, $user),
            $data->in_time,
            /*format_to_date($data->in_time),
            format_to_time($data->in_time),*/
            attendance_format_to_date($data->in_time),
            attendance_format_to_time($data->in_time),
            $out_time ? $out_time : 0,
            //$out_time ? format_to_date($out_time) : "-",
            //$out_time ? format_to_time($out_time) : "-",
            $out_time ? attendance_format_to_date($out_time) : "-",
            $out_time ? attendance_format_to_time($out_time) : "-",
            $data->clockin_location,
            $data->clockout_location,
            convert_seconds_to_time_format(abs($to_time - $from_time)),
            $note_link,
            $option_links
        );
    }
    if($data->user_user_type=="resource"){
        return array(
            get_rm_member_profile_link($data->user_id, $user),
            $data->in_time,
            //format_to_date($data->in_time),
            //format_to_time($data->in_time),
            attendance_format_to_date($data->in_time),
            attendance_format_to_time($data->in_time),
            $out_time ? $out_time : 0,
           /* $out_time ? format_to_date($out_time) : "-",
            $out_time ? format_to_time($out_time) : "-",*/
            $out_time ? attendance_format_to_date($out_time) : "-",
            $out_time ? attendance_format_to_time($out_time) : "-",
            $data->clockin_location,
            $data->clockout_location,
            convert_seconds_to_time_format(abs($to_time - $from_time)),
            $note_link,
            $option_links
        );
    }
        }

    //load the custom date view of attendance list 
    function custom() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
        $this->load->view("attendance/custom_list", $view_data);
    }

    //load the clocked in members list view of attendance list 
    function members_clocked_in() {
        $this->load->view("attendance/members_clocked_in");
    }

    private function _get_members_dropdown_list_for_filter() {

        //prepare the dropdown list of members
        //don't show none allowed members in dropdown

        if ($this->access_type === "all") {
            $where = array("user_type" => "staff");
        } else {
            if (!count($this->allowed_members)) {
                $where = array("user_type" => "nothing"); //don't show any users in dropdown
            } else {
                //add login user in dropdown list
                $allowed_members = $this->allowed_members;
                $allowed_members[] = $this->login_user->id;

                $where = array("user_type" => "staff", "where_in" => array("id" => $allowed_members));
            }
        }

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdown[] = array("id" => $id, "text" => $name);
        }
        return $members_dropdown;
    }
private function _get_rm_members_dropdown_list_for_filter() {

        //prepare the dropdown list of members
        //don't show none allowed members in dropdown

        if ($this->access_type === "all") {
            $where = array("user_type" => "resource");
        } else {
            if (!count($this->allowed_members)) {
                $where = array("user_type" => "nothing"); //don't show any users in dropdown
            } else {
                //add login user in dropdown list
                $allowed_members = $this->allowed_members;
                $allowed_members[] = $this->login_user->id;

                $where = array("user_type" => "staff", "where_in" => array("id" => $allowed_members));
            }
        }

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdowns = array(array("id" => "", "text" => "- " . lang("outsource_member") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdowns[] = array("id" => $id, "text" => $name);
        }
        return $members_dropdowns;
    }

    //load the custom date view of attendance list 
    function summary() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
        $this->load->view("attendance/summary_list", $view_data);
    }

    /* get all attendance of a given duration */

    function summary_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $options = array("start_date" => $start_date, "end_date" => $end_date, "login_user_id" => $this->login_user->id, "user_id" => $user_id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $list_data = $this->Attendance_model->get_summary_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $image_url = get_avatar($data->created_by_avatar);
            $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";

            $duration = convert_seconds_to_time_format(abs($data->total_duration));

            $result[] = array(
                get_team_member_profile_link($data->user_id, $user),
                $duration,
                to_decimal_format(convert_time_string_to_decimal($duration))
            );
        }

        echo json_encode(array("data" => $result));
    }

    //load the attendance summary details tab
    function summary_details() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
        $this->load->view("attendance/summary_details_list", $view_data);
    }

    /* get data the attendance summary details tab */

    function summary_details_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "user_id" => $user_id,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
            "summary_details" => true
        );

        $list_data = $this->Attendance_model->get_summary_details($options)->result();

        //group the list by users

        $result = array();
        $last_key = 0;
        $last_user = "";
        $last_total_duration = 0;
        $last_created_by = "";
        $has_data = false;

        foreach ($list_data as $data) {
            $image_url = get_avatar($data->created_by_avatar);
            $user = "<span class='avatar avatar-xs mr10'><img src='$image_url'></span> $data->created_by_user";


            $duration = convert_seconds_to_time_format(abs($data->total_duration));

            //found a new user, add new row for the total
            if ($last_user != $data->user_id) {
                $last_user = $data->user_id;

                $result[] = array(
                    $data->created_by_user,
                    get_team_member_profile_link($data->user_id, $user),
                    "",
                    "",
                    "",
                    ""
                );

                $result[$last_key][0] = $last_created_by;
                $result[$last_key][3] = "<b>" . convert_seconds_to_time_format($last_total_duration) . "</b>";
                $result[$last_key][4] = "<b>" . to_decimal_format(convert_time_string_to_decimal(convert_seconds_to_time_format($last_total_duration))) . "</b>";
                $result[$last_key][5] = "<b>-</b>";
                $result[$last_key][6] = "<b>-</b>";
                $result[$last_key][7] = "<b>-</b>";
                //$result[$last_key][8] = "<b>-</b>";

                $last_total_duration = 0;
                $last_key = count($result) - 1;
            }


            $last_total_duration += abs($data->total_duration);
            $last_created_by = $data->created_by_user;
            $has_data = true;

            $duration = convert_seconds_to_time_format(abs($data->total_duration));
            $options = array(
           // "start_date" =>format_to_date($data->start_date, false),
            //"start_date" =>attendance_format_to_date($data->start_date, false),
            "start_date" =>date("Y-m-d", strtotime($data->start_date)),   
            "user_id" => $data->user_id      
        ); 
           $list_data = $this->Attendance_todo_model->get_details($options)->result();
                   $group_list = "";
$i=0;
        if ($list_data) {
            foreach ($list_data as $group) {
                if ($group->start_date) {
                    $i++;
                    $task_id_details = $this->Tasks_model->get_one($group->task_id);
                    if($task_id_details->title){
                        $task_id_title ='<br>'.' <span style ="font-weight:bold">&nbsp&nbsp&nbsp&nbsp&nbspTask :</span>'.modal_anchor(get_uri("projects/task_view"), $task_id_details->title, array("title" => lang('task_info') . " #$data->id", "data-post-id" => $task_id_details->id));
                     }else{
                         $task_id_title = '<br>'.' <span style ="font-weight:bold">&nbsp&nbsp&nbsp&nbsp&nbspTask :</span>'.lang("not_specified");
                     }
                     if($group->title){
                        $group_todo_title = $group->title; 
                     }else{
                        $group_todo_title = lang("not_specified");
                     }
                    $group_list .= "<ul style='text-align:left'>" .$i.') '.'<span style ="font-weight:bold">Todo do :</span>'.$group_todo_title.$task_id_title.'&nbsp&nbsp&nbsp' . "</ul>"."<br>";
                }
            }
        } if ($group_list) {
            $group_list = "<ol class='pl15'>" . $group_list . "</ol>";
        }


/* attendance task todo */

        /*$attendance_task_list_data = $this->Attendance_task_todo_model->get_details($options)->result();

        //  remove duplicate value 
$task_array = array();

foreach ($attendance_task_list_data as $group) {
                
        $task_array[] = $group->title;
        
        }
        $task_unique = array_unique($task_array);
        
// end duplicate
                   $attendance_task_group_list = "";
$attendance_task_no=0;
        if ($attendance_task_list_data) {
            foreach ( $task_unique as $attendance_task_todo) {
              
                    $attendance_task_no++;
                    $attendance_task_group_list_data = $this->Tasks_model->get_one($attendance_task_todo);
                    $attendance_task_group_list .= "<ul style='text-align:left'>" .$attendance_task_no.')'.$attendance_task_group_list_data->title .'&nbsp&nbsp&nbsp' . "</ul>";
               
            }
        } if ($attendance_task_group_list) {
            $attendance_task_group_list = "<ol class='pl15'>" . $attendance_task_group_list . "</ol>";
        }*/

/* end attendance task todo  */   



            $result[] = array(
                $data->created_by_user,
                "",
               // format_to_date($data->start_date, false),
                attendance_format_to_date($data->start_date, false),
                $duration,
                //to_decimal_format(convert_time_string_to_decimal($duration)),$data->clock_in,$data->clock_out,$attendance_task_group_list,$group_list
                to_decimal_format(convert_time_string_to_decimal($duration)),$data->clock_in,$data->clock_out,$group_list
            );
        }

        if ($has_data) {
            $result[$last_key][0] = $data->created_by_user;
            $result[$last_key][3] = "<b>" . convert_seconds_to_time_format($last_total_duration) . "</b>";
            $result[$last_key][4] = "<b>" . to_decimal_format(convert_time_string_to_decimal(convert_seconds_to_time_format($last_total_duration))) . "</b>";
                $result[$last_key][5] = "<b>-</b>";
                $result[$last_key][6] = "<b>-</b>";
                $result[$last_key][7] = "<b>-</b>";
                //$result[$last_key][8] = "<b>-</b>";

        }



        echo json_encode(array("data" => $result));
    }


    /* get clocked in members list */

    function clocked_in_members_list_data() {

        $options = array("login_user_id" => $this->login_user->id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members, "only_clocked_in_members" => true);
        $list_data = $this->Attendance_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

//check status how many todo list 
    function get_status_suggestion() {
        $item = $this->Attendance_todo_model->get_status_info_suggestion($this->input->post("item_name"));
        
        if($item){
            $result = array();
        foreach($item as $i) {
           $result[]= $i->status;
           //$result[] = array("success" => true, "item_info" => $result);
    } 
        echo json_encode(array("success" => true, "item_info" => $result));
    } else {
            echo json_encode(array("success" => false));
        }
    }


   //OT handler module
    function ot_handler() {
        $this->check_module_availability("module_attendance");

        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
       // $this->load->view("ot_handler/index", $view_data);
        $this->template->rander("ot_handler/index", $view_data);
    }


    function summary_ot_handler_details() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
       // $this->load->view("ot_handler/index", $view_data);
        $this->load->view("ot_handler/summary_ot_handler", $view_data);
    }
     function monthly_ot_handler() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
       // $this->load->view("ot_handler/index", $view_data);
        $this->load->view("ot_handler/monthly_ot_handler", $view_data);
    }


function yearly_ot_handler() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
       // $this->load->view("ot_handler/index", $view_data);
        $this->load->view("ot_handler/yearly_ot_handler", $view_data);
    }

    function weekly_ot_handler() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
         $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
       // $this->load->view("ot_handler/index", $view_data);
        $this->load->view("ot_handler/weekly_ot_handler", $view_data);
    }


    /* get data the ot handler summary details tab */

    function summary_details_list_ot_handler_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        $user_ids = $this->input->post('userr_id');
        if($user_id){
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "user_id" => $user_id,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
            "summary_details" => true
        );
    }else if ($user_ids){
$options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "user_id" => $user_ids,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
            "summary_details" => true
        );
    }else {
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "user_id" => $user_id,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
            "summary_details" => true
        );
    }

        $list_data = $this->Attendance_model->get_summary_details($options)->result();

        //group the list by users

        $result = array();
        $last_key = 0;
        $last_user = "";
        $last_total_duration = 0;
        $last_created_by = "";
        $has_data = false;

        foreach ($list_data as $data) {

            $one_day_working_hours = get_setting('company_working_hours_for_one_day');
            $one_day_working_seconds = $one_day_working_hours*60*60;
            $ot_handler = $data->total_duration-$one_day_working_seconds;
            if($ot_handler>=0){ // ot handler the greater than 0 value if condition start  
            $ot_handler_duration = $ot_handler;

            $image_url = get_avatar($data->created_by_avatar);
            $user = "<span class='avatar avatar-xs mr10'><img src='$image_url'></span> $data->created_by_user";


            $duration = convert_seconds_to_time_format(abs($ot_handler_duration));

            //found a new user, add new row for the total
            if ($last_user != $data->user_id) {
                $last_user = $data->user_id;

                $result[] = array(
                    $data->created_by_user,
                    get_team_member_profile_link($data->user_id, $user),
                    "",
                    "",
                    ""
                );

                $result[$last_key][0] = $last_created_by;
                $result[$last_key][3] = "<b>" . convert_seconds_to_time_format($last_total_duration) . "</b>";
                $result[$last_key][4] = "<b>" . to_decimal_format(convert_time_string_to_decimal(convert_seconds_to_time_format($last_total_duration))) . "</b>";
                $result[$last_key][5] = "<b>-</b>";
                $result[$last_key][6] = "<b>-</b>";
                $result[$last_key][7] = "<b>-</b>";
                //$result[$last_key][8] = "<b>-</b>";

                $last_total_duration = 0;
                $last_key = count($result) - 1;
            }


            $last_total_duration += abs($ot_handler_duration);
            $last_created_by = $data->created_by_user;
            $has_data = true;

            $duration = convert_seconds_to_time_format(abs($ot_handler_duration));
            $options = array(
            //"start_date" =>format_to_date($data->start_date, false),
            //"start_date" =>attendance_format_to_date($data->start_date, false), 
            "start_date" =>date("Y-m-d", strtotime($data->start_date)),  
            "user_id" => $data->user_id      
        ); 
           $list_data = $this->Attendance_todo_model->get_details($options)->result();
                   $group_list = "";
$i=0;
        if ($list_data) {
            foreach ($list_data as $group) {
                if ($group->start_date) {
                    $i++;
                   // $group_list .= "<ul style='text-align:left'>" .$i.')'.$group->title .'&nbsp&nbsp&nbsp' . "</ul>";
                    $task_id_details = $this->Tasks_model->get_one($group->task_id);
                    if($task_id_details->title){
                        $task_id_title ='<br>'.' <span style ="font-weight:bold">&nbsp&nbsp&nbsp&nbsp&nbspTask :</span>'.modal_anchor(get_uri("projects/task_view"), $task_id_details->title, array("title" => lang('task_info') . " #$data->id", "data-post-id" => $task_id_details->id));
                     }else{
                         $task_id_title = '<br>'.' <span style ="font-weight:bold">&nbsp&nbsp&nbsp&nbsp&nbspTask :</span>'.lang("not_specified");
                     }
                     if($group->title){
                        $group_todo_title = $group->title; 
                     }else{
                        $group_todo_title = lang("not_specified");
                     }
                    $group_list .= "<ul style='text-align:left'>" .$i.') '.'<span style ="font-weight:bold">Todo do :</span>'.$group_todo_title.$task_id_title.'&nbsp&nbsp&nbsp' . "</ul>"."<br>";
                }
            }
        } if ($group_list) {
            $group_list = "<ol class='pl15'>" . $group_list . "</ol>";
        }


         /* attendance task todo */

        /*$attendance_task_list_data = $this->Attendance_task_todo_model->get_details($options)->result();



        //  remove duplicate value 
$task_array = array();

foreach ($attendance_task_list_data as $group) {
                
        $task_array[] = $group->title;
        
        }
        $task_unique = array_unique($task_array);
        
// end duplicate
$attendance_task_group_list = "";
$attendance_task_no=0;
        if ($attendance_task_list_data) {
            foreach ( $task_unique as $attendance_task_todo) {
               
                    $attendance_task_no++;
                    $attendance_task_group_list_data = $this->Tasks_model->get_one($attendance_task_todo);
                    $attendance_task_group_list .= "<ul style='text-align:left'>" .$attendance_task_no.')'.$attendance_task_group_list_data->title .'&nbsp&nbsp&nbsp' . "</ul>";
              
            }
        } if ($attendance_task_group_list) {
            $attendance_task_group_list = "<ol class='pl15'>" . $attendance_task_group_list . "</ol>";
        }*/

/* end attendance task todo  */

            $result[] = array(
                $data->created_by_user,
                "",
                //format_to_date($data->start_date, false),
                attendance_format_to_date($data->start_date, false),
                $duration,
               // to_decimal_format(convert_time_string_to_decimal($duration)),$data->clock_in,$data->clock_out,$attendance_task_group_list,$group_list
                 to_decimal_format(convert_time_string_to_decimal($duration)),$data->clock_in,$data->clock_out,$group_list
            );
        }

    }// ot handler greater than 0 value end if

        if ($has_data) {
            $result[$last_key][0] = $data->created_by_user;
            $result[$last_key][3] = "<b>" . convert_seconds_to_time_format($last_total_duration) . "</b>";
            $result[$last_key][4] = "<b>" . to_decimal_format(convert_time_string_to_decimal(convert_seconds_to_time_format($last_total_duration))) . "</b>";
                $result[$last_key][5] = "<b>-</b>";
                $result[$last_key][6] = "<b>-</b>";
                $result[$last_key][7] = "<b>-</b>";
               // $result[$last_key][8] = "<b>-</b>";

        }



        echo json_encode(array("data" => $result));

    }
      /* get data the ot handler daily summary details tab */

      function daily_details_list_ot_handler_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_ids = $this->input->post('userr_id');
    
        // Initialize options array with common parameters
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
            "summary_details" => true
        );
    
        // Determine user_id to use based on input
        if ($this->input->post('user_id')) {
            $options['user_id'] = $this->input->post('user_id');
        } elseif ($user_ids) {
            $options['user_id'] = $user_ids;
        } else {
            // Handle case when neither user_id nor userr_id is set
            $options['user_id'] = null; // or any default value as per your application logic
        }
    
        // Retrieve data from Attendance_model based on options
        $list_data = $this->Attendance_model->get_summary_details($options)->result();
    
        // Initialize result array to store processed data
        $result = array();
    
        foreach ($list_data as $data) {
            // Calculate OT handler duration
            $one_day_working_hours = get_setting('company_working_hours_for_one_day');
            $one_day_working_seconds = $one_day_working_hours * 60 * 60;
            $ot_handler = $data->total_duration - $one_day_working_seconds;
    
            // Process data only if OT handler duration is greater than or equal to 0
            if ($ot_handler >= 0) {
                $ot_handler_duration = $ot_handler;
                $image_url = get_avatar($data->created_by_avatar);
                $user = "<span class='avatar avatar-xs mr10'><img src='$image_url'></span> $data->created_by_user";
    
                // Calculate duration in human-readable format
                $duration = convert_seconds_to_time_format(abs($ot_handler_duration));
    
                // Prepare options for Attendance_todo_model
                $todo_options = array(
                    "start_date" => date("Y-m-d", strtotime($data->start_date)),
                    "user_id" => $data->user_id
                );
    
                // Retrieve todo list from Attendance_todo_model
                $todo_list = $this->Attendance_todo_model->get_details($todo_options)->result();
    
                // Initialize group list
                $group_list = "";
                $i = 0;
    
                // Process todo list data
                if ($todo_list) {
                    foreach ($todo_list as $todo) {
                        if ($todo->start_date) {
                            $i++;
                            $task_id_details = $this->Tasks_model->get_one($todo->task_id);
                            $task_id_title = $task_id_details->title ? '<br><span style="font-weight:bold">&nbsp&nbsp&nbsp&nbsp&nbspTask :</span> ' . modal_anchor(get_uri("projects/task_view"), $task_id_details->title, array("title" => lang('task_info') . " #$data->id", "data-post-id" => $task_id_details->id)) : '<br><span style="font-weight:bold">&nbsp&nbsp&nbsp&nbsp&nbspTask :</span> ' . lang("not_specified");
                            $group_todo_title = $todo->title ?: lang("not_specified");
                            $group_list .= "<ul style='text-align:left'>$i) <span style='font-weight:bold'>Todo do :</span> $group_todo_title $task_id_title&nbsp&nbsp&nbsp</ul><br>";
                        }
                    }
                }
    
                // Format group list
                if ($group_list) {
                    $group_list = "<ol class='pl15'>$group_list</ol>";
                }
    
                // Build result array with formatted data
                $result[] = array(
                    get_team_member_profile_link($data->user_id, $user),
                    attendance_format_to_date($data->start_date, false),
                    $duration,
                    to_decimal_format(convert_time_string_to_decimal($duration)),
                    $data->clock_in,
                    $data->clock_out,
                    $group_list
                );
            }
        }
    
        // Output result as JSON
        echo json_encode(array("data" => $result));
    }
    
 //updated user timezone 
 function update_user_timezone() {
        validate_submitted_data(array(
            "login_user_id" => "required",
            "login_user_timezone"=>"required"
        ));

        $login_user_id = $this->input->post("login_user_id");
        $login_user_timezone = $this->input->post("login_user_timezone");
        
         
        
  $data = array(
            "user_timezone" => $login_user_timezone
             
        );
        $save_id = $this->Users_model->save($data, $login_user_id);

        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => "record cannot be updated"));
        }
    }




}

/* End of file attendance.php */
/* Location: ./application/controllers/attendance.php */