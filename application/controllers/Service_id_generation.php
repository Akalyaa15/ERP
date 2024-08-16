<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service_id_generation extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("production_data");
        //$this->access_only_allowed_members();
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

    function index() {
        $this->check_module_availability("module_production_data");
        //$this->template->rander("product_id_generation/index");
        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("service_id_generation/index");
        }
        else if ($this->login_user->user_type == "staff")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("service_id_generation/index");
        }else {


        $this->template->rander("service_id_generation/index");
    } 
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $team_members = $this->Job_id_generation_model->get_all_where(array("deleted" => 0))->result();
        $part_no_dropdown = array();

        foreach ($team_members as $team_member) {
            $part_no_dropdown[] = array("id" => $team_member->id, "text" => $team_member->title."(".$team_member->description.")" );
        }

        $view_data['model_info'] = $this->Service_id_generation_model->get_one($this->input->post('id'));
        $view_data['part_no_dropdown'] = json_encode($part_no_dropdown);
       
       $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();

         //product categories
         $product_categories_dropdowns = $this->Service_categories_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();
        $product_categories_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title );

        }

        $product_categories_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_category"));

        
         $view_data['product_categories_dropdown'] =json_encode($product_categories_dropdown);
        $this->load->view('service_id_generation/modal_form', $view_data);
    }

    function save() {
        // Validate incoming data
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        )); 
    
        // Retrieve inputs from POST data
        $id = $this->input->post('id');
        $service_id = $this->input->post('title');
        $rate = $this->input->post('associated_with_part_no');
    
        // Prepare data array for saving
        $data = array(
            "title" => $this->input->post('title'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "hsn_description" => $this->input->post('hsn_description'),
            "unit_type" => $this->input->post('unit_type'),
            "hsn_code" => $this->input->post('hsn_code'),
            "gst" => $this->input->post('gst'),
            "last_activity_user" => $this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
    
        // Check for duplicate service ID if it's a new entry
        if (!$id) {     
            $options = array("service_id" => $service_id);
            $item_info = $this->Service_id_generation_model->get_details($options)->row();
            if ($item_info) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_service_id')));
                exit();
            }
        } elseif ($id) { // Check for duplicate service ID for updates
            $options = array("id" => $id);
            $item_infos = $this->Service_id_generation_model->get_details($options)->row();
            if ($service_id != $item_infos->title) {
                $options = array("service_id" => $service_id);
                $item_info = $this->Service_id_generation_model->get_details($options)->row();
                if ($item_info) {
                    echo json_encode(array("success" => false, 'message' => lang('duplicate_service_id')));
                    exit();
                }
            }
        }
    
        // Check if adding a new category to the library
        $add_new_category_to_library = $this->input->post('add_new_category_to_library');
        if ($add_new_category_to_library) {
            $library_category_data = array(
                "title" => $this->input->post('category'),
            );
    
            // Check if the service category already exists
            if ($this->Service_categories_model->is_service_category_list_exists($library_category_data["title"])) {
                echo json_encode(array("success" => false, 'message' => lang("service_category_already")));
                exit();
            }
        }
    
        // Save the service data
        $save_id = $this->Service_id_generation_model->save($data, $id);
        
        if ($save_id) {
            // Check if adding a new item to the library
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "hsn_code" => $this->input->post('hsn_code'),
                    "gst" => $this->input->post('gst'),
                    "hsn_description" => $this->input->post('hsn_description')
                );
    
                // Check if the HSN code already exists
                if ($this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                    echo json_encode(array("success" => false, 'message' => lang("hsn_code_already")));
                    exit();
                }
    
                // Save the HSN code data
                $this->Hsn_sac_code_model->save($library_item_data);
            }
    
            // Save new product category if needed
            if ($add_new_category_to_library) {
                $library_category_data["last_activity_user"] = $this->login_user->id;
                $library_category_data["last_activity"] = get_current_utc_time();
    
                // Save the new service category
                $save_category_generation = $this->Service_categories_model->save($library_category_data);
    
                // Associate category with the saved service item
                $product_category_data = array(
                    "category" => $save_category_generation,
                );
    
                $this->Service_id_generation_model->save($product_category_data, $save_id);
            }
    
            // Return success message and updated data
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            // Return error message if save operation fails
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    private function _row_data($id) {
        // Fetch data for the row with the given $id and return it
        $data = $this->Service_id_generation_model->get_one($id);
        if ($data) {
            return $this->_make_row($data); // Assuming _make_row() is also defined
        }
        return null; // Handle case where data is not found
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
         $save_id = $this->Service_id_generation_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Service_id_generation_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            /*$options = array("id" => $id);
            $product_id_table = $this->Product_id_generation_model->get_details($options)->row();
            $product_id_table_title = $product_id_table->title;*/
            
            if ($this->Service_id_generation_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }



    function list_data() {
        
        $list_data = $this->Service_id_generation_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $type = $data->unit_type ? $data->unit_type : ""; 
        $group_list = "";
        
        if ($data->associated_with_part_no) {
            $groups = explode(",", $data->associated_with_part_no);
            foreach ($groups as $group) {
                if ($group) {
                    $options = array("id" => $group);
                    $list_group = $this->Job_id_generation_model->get_details($options)->row(); 
                    $group_list .= to_currency($list_group->rate); // Corrected concatenation
                }
            }
        }  
    
        //$make_name = $this->Manufacturer_model->get_one($data->make);
        $category_name = $this->Service_categories_model->get_one($data->category); 
        // Last activity user name and date start 
        $last_activity_by_user_name = "-";
        if ($data->last_activity_user) {
            $last_activity_user_data = $this->Users_model->get_one($data->last_activity_user);
            $last_activity_image_url = get_avatar($last_activity_user_data->image);
            $last_activity_user = "<span class='avatar avatar-xs mr10'><img src='$last_activity_image_url' alt='...'></span> $last_activity_user_data->first_name $last_activity_user_data->last_name";
    
            if ($last_activity_user_data->user_type == "resource") {
                $last_activity_by_user_name = get_rm_member_profile_link($data->last_activity_user, $last_activity_user);   
            } else if ($last_activity_user_data->user_type == "client") {
                $last_activity_by_user_name = get_client_contact_profile_link($data->last_activity_user, $last_activity_user);
            } else if ($last_activity_user_data->user_type == "staff") {
                $last_activity_by_user_name = get_team_member_profile_link($data->last_activity_user, $last_activity_user); 
            } else if ($last_activity_user_data->user_type == "vendor") {
                $last_activity_by_user_name = get_vendor_contact_profile_link($data->last_activity_user, $last_activity_user); 
            }
        }
          
        $last_activity_date = "-";
        if ($data->last_activity) {
            $last_activity_date = format_to_relative_time($data->last_activity);
        }
        // End last activity   
         
        return array(
            $data->title,
            $data->description,
            $category_name->title ? $category_name->title : "-",
            $data->hsn_code,
            $data->gst . "%",
            $type,
            $group_list, // Changed to use the concatenated $group_list
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("service_id_generation/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_service'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("service_id_generation/delete"), "data-action" => "delete-confirmation"))
        );
    }
    
    function get_invoice_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Hsn_sac_code_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->hsn_code, "text" => $item->hsn_code." (".$item->hsn_description.")");
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

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */