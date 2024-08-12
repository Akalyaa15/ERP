<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_id_generation extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("production_data");
        //$this->access_only_allowed_members();
    }

    function index() {
     /*  $this->check_module_availability("module_production_data");
        //$this->template->rander("product_id_generation/index");
        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("product_id_generation/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("product_id_generation/index");
        }else {


        $this->template->rander("product_id_generation/index");
    } */
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $team_members = $this->Part_no_generation_model->get_all_where(array("deleted" => 0))->result();
        $part_no_dropdown = array();

        foreach ($team_members as $team_member) {
            $part_no_dropdown[] = array("id" => $team_member->id, "text" => $team_member->title."(".$team_member->description.")" );
        }

        $view_data['model_info'] = $this->Product_id_generation_model->get_one($this->input->post('id'));
        $view_data['part_no_dropdown'] = json_encode($part_no_dropdown);
       /* $view_data['make_dropdown'] = array("" => "-") + $this->Manufacturer_model->get_dropdown_list(array("title"),"id",array("status" => "active"));*/
       $make_dropdowns = $this->Manufacturer_model->get_all_where(array("deleted" => 0,"status" => "active"))->result();
        $make_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($make_dropdowns as $make) {
            $make_dropdown[] = array("id" => $make->id, "text" => $make->title );

        }

        $make_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_manufacturer"));

         $view_data['make_dropdown'] =json_encode($make_dropdown);

         //product categories
         $product_categories_dropdowns = $this->Product_categories_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();
        $product_categories_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title );

        }

        $product_categories_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_category"));

        
         $view_data['product_categories_dropdown'] =json_encode($product_categories_dropdown);
        $this->load->view('product_id_generation/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
            
        )); 

        $id = $this->input->post('id');
        $rate = $this->input->post('associated_with_part_no');
        /*$sum = array_sum( explode( ',', $rate ) );*/
        $product_id = $this->input->post('title');
    
        $data = array(
            "title" => $this->input->post('title'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            //"total" => $sum,
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),

        );
            if(!$id){     
         $options = array("product_id" => $product_id);
            $item_info = $this->Product_id_generation_model->get_details($options)->row();
            if($item_info){
             echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
            exit();
                        }
            }elseif($id){
              $options = array("id" => $id);
                $item_infos = $this->Product_id_generation_model->get_details($options)->row();
            if($product_id!=$item_infos->title)
            {
$options = array("product_id" => $product_id);
            $item_info = $this->Product_id_generation_model->get_details($options)->row();
if($item_info){
    echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
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

        //category 
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


        $save_id = $this->Product_id_generation_model->save($data, $id);
if($id){
        $options = array("product_generation_id" => $id);
            $item_info_inventory = $this->Items_model->get_details($options)->row();
             if($item_info_inventory){
                $data = array(
            "title" => $this->input->post('title'),
            
            "rate" => $rate,
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),

        );
                $inven_id = $item_info_inventory->id;
                $savee_id = $this->Items_model->save($data, $inven_id);
             }
    }         
        if ($save_id) {
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
             
             $this->Product_id_generation_model->save($product_make_data, $save_id);
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
               "category" => $save_category_generation,
               

                    );
             
             $this->Product_id_generation_model->save($product_category_data, $save_id);
    }
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
         $save_id = $this->Product_id_generation_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Product_id_generation_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            /*$options = array("id" => $id);
            $product_id_table = $this->Product_id_generation_model->get_details($options)->row();
            $product_id_table_title = $product_id_table->title;*/
            $optionss = array("product_generation_id" => $id);
            $item_info_inventory_id = $this->Items_model->get_details($optionss)->row();
             if($item_info_inventory_id){

              $inven_id = $item_info_inventory_id->id;
        
                $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Items_model->save($data, $inven_id);
                $savee_id = $this->Items_model->delete($inven_id);
             }
            if ($this->Product_id_generation_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        
        $list_data = $this->Product_id_generation_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Product_id_generation_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
                 
      $group_list = "";
        if ($data->associated_with_part_no) {
            $groups = explode(",", $data->associated_with_part_no);
            foreach ($groups as $group) {
                if ($group) {
                     $options = array("id" => $group);
                    $list_group = $this->Part_no_generation_model->get_details($options)->row(); 
                    $group_list += $list_group->rate;
                }
            }
        }  

        $make_name = $this->Manufacturer_model->get_one($data->make);
        $category_name = $this->Product_categories_model->get_one($data->category); 
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
        return array($data->title,$data->description,
            //to_decimal_format($data->quantity),

             //$data->category,
            /* $data->make,*/
            $category_name->title?$category_name->title:"-",
             $make_name->title?$make_name->title:"-",
             to_currency($group_list),
             //$data->total,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("product_id_generation/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_product'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("product_id_generation/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */