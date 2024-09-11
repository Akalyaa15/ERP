<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }

    protected function validate_access_to_items() {
        $access_invoice = $this->get_access_info("invoice");
        $access_estimate = $this->get_access_info("estimate");
        $access_purchase_order = $this->get_access_info("purchase_order");
        $access_inventory = $this->get_access_info("inventory");

        //don't show the items if invoice/estimate module is not enabled
        if(!(get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" || get_setting("module_purchase_order") == "1"||get_setting("module_inventory") == "1")){
            redirect("forbidden");
        }
        
        if ($this->login_user->is_admin) {
            return true;
        } else if ($access_invoice->access_type === "all" || $access_estimate->access_type === "all" || $access_purchase_order->access_type === "all"|| 
            $access_inventory->access_type === "all"||in_array($this->login_user->id, $access_inventory->allowed_members)) {
            return true;
        } else {
            redirect("forbidden");
        }
    }

    //load note list view
    function index() {
        $this->validate_access_to_items();

        $this->template->rander("items/index");
    }

    /* load item modal */

    function modal_form() {
        $this->validate_access_to_items();

 $team_members = $this->Part_no_generation_model->get_all_where(array("deleted" => 0))->result();
        $part_no_dropdown = array();

        foreach ($team_members as $team_member) {
            $part_no_dropdown[] = array("id" => $team_member->id, "text" => $team_member->title );
        }
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Items_model->get_one($this->input->post('id'));
        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $view_data['part_no_dropdown'] = json_encode($part_no_dropdown);
        //$view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));

       
        $manufactures = $this->Manufacturer_model->get_all_where(array("deleted" => 0 , "status" => "active"), 0, 0, "title")->result();

        $make_dropdown = array(array("id" => "", "text" => "- " ));
        foreach ($manufactures as $manufacture) {
            $make_dropdown[] = array("id" => $manufacture->id, "text" => $manufacture->title);
        }
        $make_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_manufacturer"));
        $view_data['make_dropdown'] = json_encode($make_dropdown);

//product category
        $product_categories_dropdowns = $this->Product_categories_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();
        $product_categories_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title );

        }

        $product_categories_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_category"));

        
         $view_data['product_categories_dropdown'] =json_encode($product_categories_dropdown);

        $this->load->view('items/modal_form', $view_data);
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

    function assoc_details() {
        $rate = $this->input->post("item_name");
        $group_list = "";
        
        if ($rate) {
            $groups = explode(",", $rate);
            foreach ($groups as $group) {
                if ($group) {
                    $options = array("id" => $group);
                    $list_group = $this->Part_no_generation_model->get_details($options)->row();
                    
                    // Use . for string concatenation
                    $group_list .= $list_group->rate;
                }
            }
        }
    
        if ($group_list) {
            echo json_encode(array("success" => true, "assoc_rate" => $group_list));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    /* add or edit an item */
    function save() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        //$profit_percentage = $this->input->post('profit_percentage');
        /*$rate = unformat_currency($this->input->post('item_rate'));
        $profit = $rate*$profit_percentage/100;
        $actual_value = $rate+$profit; */
        //$gst = unformat_currency($this->input->post('gst'));
        /*$mrp = $actual_value*$gst/100;
        $mrp_value =$mrp+$actual_value;*/
        //$part_no = $this->input->post('associated_with_part_no');
        /*$sum = array_sum( explode( ',', $part_no ));
        $profits = $sum*$profit_percentage/100;
        $actual_values = $sum+$profits; 
        //$gst = $this->input->post('gst');
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;*/

//installatio rate 
      $installation_profit_percentage = $this->input->post('installation_profit_percentage');
        $installation_rate = unformat_currency($this->input->post('installation_rate'));
        $installation_gst = unformat_currency($this->input->post('installation_gst'));
        /*$installation_profit = 
        $installation_rate*$installation_profit_percentage/100;
        $installation_actual_value = $installation_rate+$installation_profit; 
         $installation_profit_value = $installation_profit; */
          $item_data = array(
            "title" => $this->input->post('title'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
           "description" => $this->input->post('description'),
           "hsn_description" => $this->input->post('hsn_description'),
            "unit_type" => $this->input->post('unit_type'),
            "stock" => unformat_currency($this->input->post('item_stock')),
           
            "hsn_code" => $this->input->post('hsn_code'),
            "profit_percentage" => $this->input->post('profit_percentage'),
            "gst" => $this->input->post('gst'),
            //"rate" => unformat_currency($this->input->post('item_rate'))?unformat_currency($this->input->post('item_rate')):$sum,
             "rate" => $this->input->post('rate_id')?$this->input->post('rate_id'):$this->input->post('associated_with_part_no'),

            //"profit_value"=>$profit?$profit:$profits,
            
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            /*"actual_value" => $actual_value?$actual_value:$actual_values,
            "MRP" => $mrp_value?$mrp_value:$mrp_values,*/
            //installation rate 
           "installation_rate" => $this->input->post('installation_rate'),
           "installation_hsn_code" => $this->input->post('installation_hsn_code'),
            "installation_profit_percentage" => $this->input->post('installation_profit_percentage'),
            "installation_gst" => $this->input->post('installation_gst'),
           

            "isnstallation_hsn_description"=>$this->input->post('installation_hsn_description'),
            /*"installation_profit_value"=> $installation_profit_value,
            "installation_actual_value"=>$installation_actual_value,*/
             "product_generation_id" => $this->input->post('product_generation_id')?$this->input->post('product_generation_id'):"",
             "last_activity_user"=>$this->login_user->id,
             "last_activity" => get_current_utc_time(),




        );

if (!$id) {
    // check the same inventory product     
        $data["title"] =$this->input->post('title');
        if ($this->Items_model->is_inventory_product_exists($data["title"])) {
                echo json_encode(array("success" => false, 'message' => lang('inventory_product_already')));
                exit();
            }

        }
        if ($id) {
    // check the same inventory product     
        $data["title"] =$this->input->post('title');
        $data["id"] =$this->input->post('id');
       if ($this->Items_model->is_inventory_product_exists($data["title"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('inventory_product_already')));
                exit();
            }

        }

        // check the make

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



         $item_id = $this->Items_model->save($item_data, $id);
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

// add installation hsn ,gst 
$add_new_installation_item_to_library = $this->input->post('add_new_installation_item_to_library');
    if ($add_new_installation_item_to_library) {
            $library_installation_item_data = array(
                    "installation_hsn_code" => $this->input->post('installation_hsn_code'),
                    "installation_gst" => $this->input->post('installation_gst'),
                    "installation_hsn_description" => $this->input->post('installation_hsn_description')

                    
                );
            $this->Hsn_sac_code_model->save($library_installation_item_data);
            }




            
            $add_new_product_id_to_library = $this->input->post('add_new_product_id_to_library');
            if ($add_new_product_id_to_library) {
/*$rate = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $rate ) );*/

                $library_product_id_data = array(
                    "title" => $this->input->post('title'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            //"total" => $sum,
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),

                    
                );
          $save_product_id_generation = $this->Product_id_generation_model->save($library_product_id_data);
         //save product id items table
     $product_geration_data = array(
               "product_generation_id" => $save_product_id_generation

                    );
             
             $this->Items_model->save($product_geration_data, $item_id);
            }

            //new make 

            $add_new_make_to_library = $this->input->post('add_new_make_to_library');
            if ($add_new_make_to_library) {
/*$rate = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $rate ) );*/

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
               "make" => $save_make_generation,
               "last_activity_user"=>$this->login_user->id,
               "last_activity" => get_current_utc_time(),

                    );
             
             $this->Items_model->save($product_make_data, $item_id);
             $this->Product_id_generation_model->save($product_make_data, $save_product_id_generation);
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
             
             $this->Items_model->save($product_category_data, $item_id);
             
             
             $this->Product_id_generation_model->save($product_category_data, $save_product_id_generation);
    }


            $options = array("id" => $item_id);
            $item_info = $this->Items_model->get_details($options)->row();
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
        //last activity
        $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Items_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Items_model->delete($id)) {
                $item_info = $this->Items_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }

        
    }

    /* list of items, prepared for datatable  */

    function list_data() {
        $this->validate_access_to_items();
$options=array("quantity"=>$this->input->post("quantity"));

        $list_data = $this->Items_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of item list table */

   /* private function _make_item_row($data) {
        $type = $data->unit_type ? $data->unit_type : "";
        
        return array(
            $data->title,
            nl2br($data->description),
            $data->category,
            $data->make,
            $data->hsn_code,
            $data->gst."%",
            $data->stock,
            $type,
            $data->rate,
            $data->profit_percentage."%",
            to_currency($data->profit_value),
            to_currency($data->actual_value),
            
            to_currency($data->MRP),
            to_currency($data->stock*$data->rate),
            $data->installation_hsn_code,
            $data->installation_gst."%",
            to_currency($data->installation_rate),
            $data->installation_profit_percentage."%",
            to_currency($data->installation_profit_value),
            to_currency($data->installation_actual_value),



            modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("items/delete"), "data-action" => "delete-confirmation"))
        );
    }*/
    private function _make_item_row($data) {
        $type = $data->unit_type ? $data->unit_type : "";
        
        $make_name = $this->Manufacturer_model->get_one($data->make);
        $category_name = $this->Product_categories_model->get_one($data->category);
        $group_list = 0; // Initialize to 0 to ensure it starts as a numeric value
        
        if ($data->rate) {
            $groups = explode(",", $data->rate);
            foreach ($groups as $group) {
                if ($group) {
                    $options = array("id" => $group);
                    $list_group = $this->Part_no_generation_model->get_details($options)->row(); 
                    if ($list_group) {
                        $group_list += floatval(to_currency($list_group->rate)); // Convert and accumulate numeric values
                    }
                }
            }
        }
    
        $profit = floatval($group_list) * floatval($data->profit_percentage) / 100;
        $actual_value = floatval($group_list) + $profit; 
        $mrp = $actual_value * floatval($data->gst) / 100;
        $mrp_value = $mrp + $actual_value;  
    
        $installation_profit = floatval($data->installation_rate) * floatval($data->installation_profit_percentage) / 100;
        $installation_actual_value = floatval($data->installation_rate) + $installation_profit; 
    
        // Description of goods
        $description_goods = lang("product_id") . ": " . "<span class='label label-info clickable'>" . $data->title . "</span> ";
        if ($data->description) {
            $description_goods .= "<br />" . lang("description") . ": " . "<span class='label label-primary clickable'>" . nl2br($data->description) . "</span> ";
        }
        if ($data->category) {
            $description_goods .= "<br />" . lang("category") . ": " . "<span class='label label-warning clickable'>" . $category_name->title . "</span> ";
        }
        if ($data->make) {
            $description_goods .= "<br />" . lang("make") . ": " . "<span class='label label-success clickable'>" . $make_name->title . "</span> ";
        }
    
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
            $description_goods,
            $data->stock,
            $type,
            to_currency($group_list),
            to_currency(floatval($data->stock) * $group_list),
            $data->profit_percentage . "%",
            to_currency($profit),
            to_currency(floatval($data->stock) * $profit),
            to_currency($actual_value),
            to_currency(floatval($data->stock) * $actual_value),
            $data->hsn_code,
            $data->gst . "%",
            to_currency($mrp_value),
            $data->installation_hsn_code,
            $data->installation_gst . "%",
            to_currency($data->installation_rate),
            $data->installation_profit_percentage . "%",
            to_currency($installation_profit),
            to_currency($installation_actual_value),
            $last_activity_by_user_name,
            $last_activity_date,
    
            modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("items/delete"), "data-action" => "delete-confirmation"))
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

    function get_inventory_product_id_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Product_id_generation_model->get_product_id_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product_id"));

        echo json_encode($suggestion);
    }

function get_inventory_product_id_info_suggestion() {
        $item = $this->Product_id_generation_model->get_product_id_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }


}

/* End of file items.php */
/* Location: ./application/controllers/items.php */