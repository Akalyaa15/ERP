<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Part_no_generation extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_team_members();
        $this->init_permission_checker("production_data");
        //$this->access_only_allowed_members();
    }

  /*  protected function validate_access_to_items() {
        $access_invoice = $this->get_access_info("invoice");
        $access_estimate = $this->get_access_info("estimate");
        $access_purchase_order = $this->get_access_info("purchase_order");

        //don't show the items if invoice/estimate module is not enabled
        if(!(get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" || get_setting("module_purchase_order") == "1")){
            redirect("forbidden");
        }
        
        if ($this->login_user->is_admin) {
            return true;
        } else if ($access_invoice->access_type === "all" || $access_estimate->access_type === "all" || $access_purchase_order->access_type === "all") {
            return true;
        } else {
            redirect("forbidden");
        }
    } */

    //load note list view
    function index() {
        $this->check_module_availability("module_production_data");
        //$this->validate_access_to_items();

        //$this->template->rander("part_no_generation/index");
        if ($this->login_user->is_admin == "1")
        {
            $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));
            $this->template->rander("part_no_generation/index",$view_data);
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
              $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));
            $this->template->rander("part_no_generation/index",$view_data);
        }else {

$view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));
        $this->template->rander("part_no_generation/index",$view_data);
    } 
    }

    /* load item modal */

    function modal_form() {
        //$this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Part_no_generation_model->get_one($this->input->post('id'));
        //$view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $team_members = $this->Vendors_model->get_all_where(array("deleted" => 0))->result();
        $vendors_dropdown = array();

        foreach ($team_members as $team_member) {
            $vendors_dropdown[] = array("id" => $team_member->id, "text" => $team_member->company_name );
        }
         $view_data['vendors_dropdown'] = json_encode($vendors_dropdown);

         $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
          /*$view_data['make_dropdown'] = array("" => "-") + $this->Manufacturer_model->get_dropdown_list(array("title"),"id",array("status" => "active"));*/
          $make_dropdowns = $this->Manufacturer_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();
        $make_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($make_dropdowns as $make) {
            $make_dropdown[] = array("id" => $make->id, "text" => $make->title );

        }

        $make_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_manufacturer"));
         $view_data['make_dropdown'] =json_encode($make_dropdown);

         // product category
         $product_categories_dropdowns = $this->Product_categories_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();
        $product_categories_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title );

        }

        $product_categories_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_category"));

        
         $view_data['product_categories_dropdown'] =json_encode($product_categories_dropdown);

        $this->load->view('part_no_generation/modal_form', $view_data);
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
        
        //$this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $part_no=$this->input->post('title');
        $item_data = array(
            "title" => $this->input->post('title'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
          "description" => $this->input->post('description'),
          "hsn_description" => $this->input->post('hsn_description'),
            "unit_type" => $this->input->post('unit_type'),
            "stock" => unformat_currency($this->input->post('item_stock')),
           
            "hsn_code" => $this->input->post('hsn_code'),
            "gst" => $this->input->post('gst'),
            "rate" => unformat_currency($this->input->post('item_rate')),
            "vendor_id" =>$this->input->post('vendor_id'),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        ); 
    if(!$id){     
         $options = array("part_no" => $part_no);
            $item_info = $this->Part_no_generation_model->get_details($options)->row();
if($item_info){
    echo json_encode(array("success" => false, 'message' => lang('duplicate_part_no')));
    exit();
}
}elseif($id){
    $options = array("id" => $id);
            $item_infos = $this->Part_no_generation_model->get_details($options)->row();
            if($part_no!=$item_infos->title)
            {
$options = array("part_no" => $part_no);
            $item_info = $this->Part_no_generation_model->get_details($options)->row();
if($item_info){
    echo json_encode(array("success" => false, 'message' => lang('duplicate_part_no')));
    exit();
}

            }
}



// check the make and vendors 

$add_new_make_to_library = $this->input->post('add_new_make_to_library');
            if ($add_new_make_to_library) {


                $library_make_data = array(
                    "title" => $this->input->post('make'),
            

                    
                );

                // check the manufacturer name     
        $library_make_data["title"] =$this->input->post('make');
        if ($this->Manufacturer_model->is_manufacturer_list_exists($library_make_data["title"])) {
                echo json_encode(array("success" => false, 'message' => lang('manufacturer_already')));
                exit();
            }
        }


        //check product category
            $add_new_category_to_library = $this->input->post('add_new_category_to_library');
            if ($add_new_category_to_library) {


                $library_category_data = array(
                    "title" => $this->input->post('category'),
            

                    
                );

                // check the manufacturer name     
        $library_category_data["title"] =$this->input->post('category');
       if ($this->Product_categories_model->is_product_category_list_exists($library_category_data["title"])) {
            echo json_encode(array("success" => false, 'message' => lang("product_category_already")));
            exit();
        }
    }



         $item_id = $this->Part_no_generation_model->save($item_data, $id);
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
// new make 
            $add_new_make_to_library = $this->input->post('add_new_make_to_library');
            if ($add_new_make_to_library) {


                $library_make_data = array(
                    "title" => $this->input->post('make'),
                    "last_activity_user"=>$this->login_user->id,
                    "last_activity" => get_current_utc_time(),
            

                    
                );

                // check the manufacturer name     
        $library_make_data["title"] =$this->input->post('make');
        if ($this->Manufacturer_model->is_manufacturer_list_exists($library_make_data["title"])) {
                echo json_encode(array("success" => false, 'message' => lang('manufacturer_already')));
                exit();
            }

          $save_make_generation = $this->Manufacturer_model->save($library_make_data);
         //save product id items table
     $product_make_data = array(
               "make" => $save_make_generation

                    );
             
             $this->Part_no_generation_model->save($product_make_data, $item_id);
            }

            // new product category
            //vendor name 
            $add_new_category_to_library = $this->input->post('add_new_category_to_library');
            if ($add_new_category_to_library) {


                $library_category_data = array(
                    "title" => $this->input->post('category'),
                    "last_activity_user"=>$this->login_user->id,
                    "last_activity" => get_current_utc_time(),
            

                    
                );

                // check the manufacturer name     
        $library_category_data["title"] =$this->input->post('category');
       if ($this->Product_categories_model->is_product_category_list_exists($library_category_data["title"])) {
            echo json_encode(array("success" => false, 'message' => lang("product_category_already")));
            exit();
        }

        $save_category_generation = $this->Product_categories_model->save($library_category_data);
         //save product id items table
     $product_category_data = array(
               "category" => $save_category_generation

                    );
             
             $this->Part_no_generation_model->save($product_category_data, $item_id);
    }


            $options = array("id" => $item_id);
            $item_info = $this->Part_no_generation_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an item */

    function delete() {
       // $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Part_no_generation_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Part_no_generation_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Part_no_generation_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Part_no_generation_model->delete($id)) {
                $item_info = $this->Part_no_generation_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
         
    }

/* list of items, prepared for datatable */
public function list_data() {
    // Retrieve the group ID from the POST data
    $group_id = $this->input->post("group_id");

    // Get the list of items based on the group ID
    $list_data = $this->Part_no_generation_model->get_details(array("group_id" => $group_id))->result();

    // Initialize an empty array to store the formatted data
    $result = array();

    // Loop through the list of items and format each item using _make_item_row()
    foreach ($list_data as $data) {
        $result[] = $this->_make_item_row($data);
    }

    // Encode the formatted data as JSON and echo it
    echo json_encode(array("data" => $result));
}

/* prepare a row of item list table */
private function _make_item_row($data) {
    // Determine the type of the item (unit type)
    $type = $data->unit_type ? $data->unit_type : "";

    // Initialize an empty string to store the list of groups (vendors)
    $group_list = "";

    // Check if the item has associated vendor IDs
    if ($data->vendor_ids) {
        // Split the vendor IDs into an array
        $vendors = explode(",", $data->vendor_ids);

        // Iterate through each vendor ID
        foreach ($vendors as $vendor_id) {
            // Ensure the vendor ID is not empty
            if ($vendor_id) {
                // Fetch the details of the vendor using the ID
                $vendor_details = $this->Vendors_model->get_details(array("id" => $vendor_id))->row();

                // Append the vendor's name as a link to their details page
                $group_list .= "<li>" . anchor(get_uri("vendors/view/" . $vendor_id), $vendor_details->company_name). "</li>";
            }
        }
    }

    // Format the group list as an unordered list
    if ($group_list) {
        $group_list = "<ul class='pl15'>" . $group_list . "</ul>";
    }

    // Get the make name and category name
    $make_name = $this->Manufacturer_model->get_one($data->make);
    $category_name = $this->Product_categories_model->get_one($data->category);

    // Last activity user name and date
    $last_activity_by_user_name= "-";
    if($data->last_activity_user){
        $last_activity_user_data = $this->Users_model->get_one($data->last_activity_user);
        $last_activity_image_url = get_avatar($last_activity_user_data->image);
        $last_activity_user = "<span class='avatar avatar-xs mr10'><img src='$last_activity_image_url' alt='...'></span> $last_activity_user_data->first_name $last_activity_user_data->last_name";
        
        if($last_activity_user_data->user_type=="resource"){
            $last_activity_by_user_name= get_rm_member_profile_link($data->last_activity_user, $last_activity_user );   
        } else if($last_activity_user_data->user_type=="client") {
            $last_activity_by_user_name= get_client_contact_profile_link($data->last_activity_user, $last_activity_user);
        } else if($last_activity_user_data->user_type=="staff"){
            $last_activity_by_user_name= get_team_member_profile_link($data->last_activity_user, $last_activity_user); 
        } else if($last_activity_user_data->user_type=="vendor"){
            $last_activity_by_user_name= get_vendor_contact_profile_link($data->last_activity_user, $last_activity_user); 
        }
    }

    $last_activity_date = "-";
    if($data->last_activity){
        $last_activity_date = format_to_relative_time($data->last_activity);
    }
    // End last activity 

    // Return an array representing the formatted item row
    return array(
        $data->title,
        $group_list,
        nl2br($data->description),
        $category_name->title ? $category_name->title : "-", // Category title
        $make_name->title ? $make_name->title : "-", // Make title
        $data->hsn_code,
        $data->stock,
        $type,
        to_currency($data->rate),
        $data->gst . "%",
        to_currency($data->stock * $data->rate),
        $last_activity_by_user_name,
        $last_activity_date,
        modal_anchor(get_uri("part_no_generation/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id))
        . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("part_no_generation/delete"), "data-action" => "delete-confirmation"))
    );
}

public function get_invoice_item_suggestion() {
    $key = $this->input->post("q");
    $suggestion = array();

    $items = $this->Hsn_sac_code_model->get_item_suggestion($key);

    foreach ($items as $item) {
        $suggestion[] = array("id" => $item->hsn_code, "text" => $item->hsn_code." (".$item->hsn_description.")");
    }

    $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_hsn_code"));

    echo json_encode($suggestion);
}

public function get_invoice_item_info_suggestion() {
    $item_name = $this->input->post("item_name");
    $item = $this->Hsn_sac_code_model->get_item_info_suggestion($item_name);
    if ($item) {
        echo json_encode(array("success" => true, "item_info" => $item));
    } else {
        echo json_encode(array("success" => false));
    }
}

private function _get_groups_dropdown_select2_data($show_header = false) {
    $vendor_groups = $this->Vendors_model->get_all()->result();
    $groups_dropdown = array();

    if ($show_header) {
        $groups_dropdown[] = array("id" => "", "text" => "- " . lang("vendor_groups") . " -");
    }

    foreach ($vendor_groups as $group) {
        $groups_dropdown[] = array("id" => $group->id, "text" => $group->company_name);
    }
    return $groups_dropdown;
}
}