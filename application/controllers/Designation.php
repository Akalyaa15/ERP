 <?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Designation extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        //$this->init_permission_checker("master_data");
        $this->init_permission_checker("designation");
        //$this->access_only_allowed_members();
    }

    function index() {
//$this->check_module_availability("module_master_data");
        $this->check_module_availability("module_designation");
        //$this->template->rander("designation/index");
           if ($this->login_user->is_admin == "1")
        {
            //$this->template->rander("designation/index");
            $this->template->rander_scroll("designation/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander_scroll("designation/index");
        }else {


        $this->template->rander_scroll("designation/index");
    } 
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Designation_model->get_one($this->input->post('id'));
        $view_data['department_dropdown'] = array("0" => "-") + $this->Department_model->get_dropdown_list(array("title"), "department_code", array("deleted" => '0'));

        $this->load->view('designation/modal_form', $view_data);
    }

    function save() {
         $id = $this->input->post('id');
        if(!$id){
if ($this->Designation_model->is_designation_exists($this->input->post('department_code'),$this->input->post("designation_code"))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_designation')));
            exit();
        }}
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "designation_code" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "designation_code" => $this->input->post('designation_code'),
            "department_code" => $this->input->post('department_code'),"description" => $this->input->post('description'),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
        $save_id = $this->Designation_model->save($data, $id);
        $options = array("department_code" => $this->input->post('department_code'));
        $list_dep_title = $this->Department_model->get_details($options)->row();
            $role_data = array(
            "title" => $list_dep_title->title.'-'.$this->input->post('title'),
        );
 $save_role_data = $this->Roles_model->save($role_data);
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
         $save_id = $this->Designation_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Designation_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Designation_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }
function list_data() {        
        $list_data = $this->Designation_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Designation_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
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
            $data->designation_code, $data->department_title,$data->description,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("designation/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_designation'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("designation/delete"), "data-action" => "delete-confirmation"))
        );
    }
function get_designation() {
        $item = $this->Designation_model->get_designation_details($this->input->post("dep_code"));
        $suggestions = array();
      foreach ($item as $items) {
           $suggestions[] = array("id" => $items->designation_code, "text" => $items->title/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }
}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */