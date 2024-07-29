<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class States extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        //$this->init_permission_checker("master_data");
        //$this->access_only_allowed_members();
         $this->init_permission_checker("state");
    }

    function index() {
        //$this->check_module_availability("module_master_data");
        $this->check_module_availability("module_state");
       // $this->template->rander("states/index");
               if ($this->login_user->is_admin == "1")
        {
           //$this->template->rander("states/index");
            $this->template->rander_scroll("states/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander_scroll("states/index");
        }else {


        $this->template->rander_scroll("states/index");
    } 
    }

     function modal_form() {
    
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->States_model->get_one($this->input->post('id'));
           $view_data['country_dropdown'] = $this->_get_countries_dropdown();

        $this->load->view('states/modal_form', $view_data);
    }
 private function _get_countries_dropdown() {
        $country_dropdown = array(
            //"0" => lang('team_member'),
            //"admin" => lang('admin') //static role
        );
        $countries = $this->Countries_model->get_details()->result();
        foreach ($countries as $country) {
            $country_dropdown[$country->numberCode] = $country->countryName;
        }
        return $country_dropdown;
    }
    function save() {

        validate_submitted_data(array(
            "id" => "numeric"
          
        ));

        $id = $this->input->post('id');
        if($id){
            $ree=$this->States_model->get_one($this->input->post('id'));
            if($ree->state_code!=$this->input->post('state_code')){
             if ($this->States_model->is_state_exists($this->input->post('state_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_state')));
            exit();
        }}
             
            if(strtoupper($ree->title)!=strtoupper($this->input->post('title'))){
             if ($this->States_model->is_state_name_exists($this->input->post('title'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_state_name')));
            exit();
        }}
        }
        if(!$id){
if ($this->States_model->is_state_exists($this->input->post('state_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_state')));
            exit();
        }
    if ($this->States_model->is_state_name_exists($this->input->post('title'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_state_name')));
            exit();
        }
    }
        $data = array(
            "title" => $this->input->post('title'),
            "country_code" => $this->input->post('country_code'),
            "state_code" => $this->input->post('state_code'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
        $save_id = $this->States_model->save($data, $id);
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
         $save_id = $this->States_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->States_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->States_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->States_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->States_model->get_details($options)->row();
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
            $data->countryName,$data->state_code,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("states/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_state'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_state'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("states/delete"), "data-action" => "delete-confirmation"))
        );
    }

    

    
}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */