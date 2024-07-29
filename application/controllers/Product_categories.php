<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_categories extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("production_data");
    }

    //load expense categories list view
    function index() {
        //$this->template->rander("product_categories/index");
        $this->check_module_availability("module_production_data");
        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("product_categories/index");
        }
        else if ($this->login_user->user_type == "staff")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("product_categories/index");
        }else {


        $this->template->rander("product_categories/index");
    } 
    }

    //load expense category add/edit modal form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $view_data['model_info'] = $this->Product_categories_model->get_one($this->input->post('id'));
        $this->load->view('product_categories/modal_form', $view_data);
    }

    //save expense category
    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );


        //check duplicate product
 if (!$id) {
    // check the invoice product no     
        $data["title" ] =$this->input->post('title');
        //$invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        
        if ($this->Product_categories_model->is_product_category_list_exists($data["title" ])) {
                echo json_encode(array("success" => false, 'message' => lang('product_category_already')));
                exit();
            }

        }
        if ($id) {
    // check the  invoice product no     
       $data["title" ] =$this->input->post('title');
        $data["id"] =$this->input->post('id');
       if ($this->Product_categories_model->is_product_category_list_exists($data["title" ],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('product_category_already')));
                exit();
            }

        }

//end check duplicate product
        $save_id = $this->Product_categories_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an expense category
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Product_categories_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Product_categories_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get data for expenses category list
    function list_data() {
        $list_data = $this->Product_categories_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Product_categories_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare an expense category list row
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
            $data->description ? $data->description : "-",
            lang($data->status),
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("product_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_product_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_products_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("product_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file expense_categories.php */
/* Location: ./application/controllers/expense_categories.php */