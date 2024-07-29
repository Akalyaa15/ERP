<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Outsource_jobs extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }

    protected function validate_access_to_items() {
        $access_invoice = $this->get_access_info("work_order");
        //$access_estimate = $this->get_access_info("estimate");

        //don't show the items if invoice/estimate module is not enabled
        if(!(get_setting("module_work_order") == "1")){
            redirect("forbidden");
        }
        
        if ($this->login_user->is_admin) {
            return true;
        } else if ($access_invoice->access_type === "all" ) {
            return true;
        } else {
            redirect("forbidden");
        }
    }

    //load note list view
     function index() {
        $this->validate_access_to_items();

        $this->template->rander("outsource_jobs/index");
    }

    /* load item modal */

    function modal_form() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Outsource_jobs_model->get_one($this->input->post('id'));
        //$view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
       $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
       $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));
       $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $view_data['model_info']->client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        $view_data['projects_suggestion'] = $suggestion;
        $this->load->view('outsource_jobs/modal_form', $view_data);
    }

    private function _get_unit_type_dropdown_select2_data() {
        //$unit_types = $this->Unit_type_model->get_all()->result();
         $unit_types = $this->Unit_type_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $unit_type_dropdown = array();

        

        foreach ($unit_types as $code) {
            $unit_type_dropdown[] = array("id" => $code->title, "text" => $code->title);
        }
        return $unit_type_dropdown;
    }

    /* add or edit an item */

    function save() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $client_id = $this->input->post('client_id');

        $item_data = array(
            "title" => $this->input->post('title'),
            "category" => $this->input->post('category'),
           // "make" => $this->input->post('make'),
          "description" => $this->input->post('description'),
          "hsn_description" => $this->input->post('hsn_description'),
            "unit_type" => $this->input->post('unit_type'),
           
            "hsn_code" => $this->input->post('hsn_code'),
            "gst" => $this->input->post('gst'),
            "rate" => unformat_currency($this->input->post('item_rate')),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
            "client_id" => $client_id,
            "project_id" => $this->input->post('project_id') ? $this->input->post('project_id') : 0,
        );


if (!$id) {
    // check the same inventory product     
        $item_data["title"] =$this->input->post('title');
        if ($this->Outsource_jobs_model->is_outsource_job_exists($item_data["title"])) {
                echo json_encode(array("success" => false, 'message' => lang('job_id_already')));
                exit();
            }

        }
        if ($id) {
    // check the same inventory product     
        $item_data["title"] =$this->input->post('title');
        $item_data["id"] =$this->input->post('id');
       if ($this->Outsource_jobs_model->is_outsource_job_exists($item_data["title"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('job_id_already')));
                exit();
            }

        }

         $item_id = $this->Outsource_jobs_model->save($item_data, $id);
        if ($item_id) {

            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "hsn_code" => $this->input->post('hsn_code'),
                    "gst" => $this->input->post('gst'),
                    "hsn_description" => $this->input->post('hsn_description')

                    
                );
                $this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $item_id);
            $item_info = $this->Outsource_jobs_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    /* delete or undo an item */

    function delete() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
         $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Outsource_jobs_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Outsource_jobs_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Outsource_jobs_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Outsource_jobs_model->delete($id)) {
                $item_info = $this->Outsource_jobs_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of items, prepared for datatable  */

    function list_data() {
        $this->validate_access_to_items();

        $list_data = $this->Outsource_jobs_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of item list table */

    private function _make_item_row($data) {
        $type = $data->unit_type ? $data->unit_type : "";

         $client_info = $this->Clients_model->get_one($data->client_id);
        $project_info = $this->Projects_model->get_one($data->project_id);

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

        return array(
            $data->title,
            $client_info->company_name ?anchor(get_uri("clients/view/" . $data->client_id), $client_info->company_name) : "-",
            $project_info->title ? anchor(get_uri("projects/view/" . $data->project_id), $project_info->title) : "-",
            nl2br($data->description),
            $data->category,
            //$data->make,
            $data->hsn_code,
            $type,
            $data->gst."%",
            $data->rate,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("outsource_jobs/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("outsource_jobs/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function get_invoice_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Hsn_sac_code_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->hsn_code, "text" => $item->hsn_code);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_hsn_code"));

        echo json_encode($suggestion);
    }

    function get_invoice_item_info_suggestion() {
        $item = $this->Hsn_sac_code_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }


}

/* End of file items.php */
/* Location: ./application/controllers/items.php */