<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("tools");
        //$this->access_only_allowed_members();
    }

    function index() {
        $this->check_module_availability("module_assets_data"); 
        //$this->template->rander("tools/index");
        if ($this->login_user->is_admin == "1")
        {
            $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());
        $view_data['vendors_dropdown'] = json_encode($this->_get_vendors_dropdown());
        $view_data['rm_members_dropdown'] = $this->_get_rm_members_dropdown();
            $this->template->rander("tools/index" , $view_data);
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
              $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());
        $view_data['vendors_dropdown'] = json_encode($this->_get_vendors_dropdown());
        $view_data['rm_members_dropdown'] = $this->_get_rm_members_dropdown();
            $this->template->rander("tools/index",$view_data);
        }else {
$view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());
        $view_data['vendors_dropdown'] = json_encode($this->_get_vendors_dropdown());
        $view_data['rm_members_dropdown'] = $this->_get_rm_members_dropdown();

        $this->template->rander("tools/index",$view_data);
    } 
    }

    //get clients dropdown
    private function _get_clients_dropdown() {
        $clients_dropdown = array(array("id" => "", "text" => "- " . lang("client") . " -"));
        $clients = $this->Clients_model->get_dropdown_list(array("company_name"));
        foreach ($clients as $key => $value) {
            $clients_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $clients_dropdown;
    }

     //get clients dropdown
    private function _get_vendors_dropdown() {
        $vendors_dropdown = array(array("id" => "", "text" => "- " . lang("vendor") . " -"));
        $vendors = $this->Vendors_model->get_dropdown_list(array("company_name"));
        foreach ($vendors as $key => $value) {
            $vendors_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $vendors_dropdown;
    }


    //get team members dropdown
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }
private function _get_rm_members_dropdown() {
        $rm_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"), 0, 0, "first_name")->result();

        $rm_members_dropdown = array(array("id" => "", "text" => "- " . lang("outsource_member") . " -"));
        foreach ($rm_members as $rm_member) {
            $rm_members_dropdown[] = array("id" => $rm_member->id, "text" => $rm_member->first_name . " " . $rm_member->last_name);
        }

        return json_encode($rm_members_dropdown);
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Tools_model->get_one($this->input->post('id'));
        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff" ,"status" =>"active"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;
         /*$others = $this->Voucher_expenses_model->get_all_where(array("deleted" => 0, "member_type" => "others"))->result();
        $others_dropdown = array();

        foreach ($others as $other) {
            $others_dropdown[$other->phone] = $other->f_name . " " . $other->l_name;
        }

        $view_data['others_dropdown'] = array("0" => "-") + $others_dropdown;*/
        $rm_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"))->result();
        $rm_members_dropdown = array();

        foreach ($rm_members as $rm_member) {
            $rm_members_dropdown[$rm_member->id] = $rm_member->first_name . " " . $rm_member->last_name;
        }

        $view_data['rm_members_dropdown'] = array("0" => "-") + $rm_members_dropdown ;
        //add the clients and vendors 
$view_data['vendors_dropdown'] = array("" => "-")+ $this->Vendors_model->get_dropdown_list(array("company_name"),'id');
   $view_data['clients_dropdown'] =  array("" => "-")+$this->Clients_model->get_dropdown_list(array("company_name"),'id');

$view_data['client_members_dropdown'] = $this->_get_users_dropdown_select2_data();
$view_data['vendor_members_dropdown'] = $this->_get_users_dropdown_select2_data();
//$view_data['make_dropdown'] = array("" => "-") + $this->Manufacturer_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
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

        $this->load->view('tools/modal_form', $view_data);
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

//clients contact and vendor contact
    private function _get_users_dropdown_select2_data($show_header = false) {
        $luts = $this->Users_model->get_all()->result();
        $lut_dropdown = array(array("id" => "", "text" => "-"));

        

        foreach ($luts as $code) {
            $lut_dropdown[] = array("id" => $code->id, "text" => $code->first_name." ".$code->last_name);
        }
        return $lut_dropdown;
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "quantity" => "required"
        )); 
$member_type=$this->input->post('member_type');

    if($member_type=='tm'){
        $team_member=$this->input->post('income_user_id');
        $member_type=$this->input->post('member_type');
        $company="";
    $phone=0;
    $vendor_company="";
    }elseif($member_type=='om'){
        $team_member=$this->input->post('income_user_ids');
    $member_type=$this->input->post('member_type');
    $phone=0;
    $company="";
    $vendor_company="";
    }elseif($member_type=='others'){
        $team_member=0;
    $member_type=$this->input->post('member_type');
    $phone=$this->input->post('others_name');
    $company="";
    $vendor_company="";

    }elseif($member_type=='vendors'){
    $team_member=$this->input->post('vendor_contact');
    $member_type=$this->input->post('member_type');
    $phone=0;
    $vendor_company=$this->input->post('vendor_member');
    $company="";
    }elseif($member_type=='clients'){
        $team_member=$this->input->post('client_contact');
    $member_type=$this->input->post('member_type');
    $phone=0;
    $company=$this->input->post('client_member');
    $vendor_company="";
    }else{
         $team_member=$this->input->post('income_user_id');
 
        $phone="";
        $member_type="";
    }
        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "quantity" => unformat_currency($this->input->post('quantity')),
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
"unit_type" => $this->input->post('unit_type'),
"tool_location" => $this->input->post('tool_location'),
"rate" => unformat_currency($this->input->post('item_rate')),
 "user_id" => $team_member,
 "member_type" => $member_type,
"others_name"=>$phone,
"company"=>$company,
"vendor_company"=>$vendor_company,
 "last_activity_user"=>$this->login_user->id,
"last_activity" => get_current_utc_time(),
        );

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
        $save_id = $this->Tools_model->save($data, $id);
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
             
             $this->Tools_model->save($product_make_data, $save_id);
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
             
             $this->Tools_model->save($product_category_data, $save_id);
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
         $save_id = $this->Tools_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Tools_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Tools_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        
        $user_id = $this->input->post('user_id');
        $user_ids = $this->input->post('user_ids');
        $client_id = $this->input->post('client_id');
        $vendor_id = $this->input->post('vendor_id');
        if ($user_ids) {
            
        
        $options = array( "user_id" => $user_ids,"client_id" => $client_id,"vendor_id" => $vendor_id);
    }else{
         $options = array( "user_id" => $user_id,"client_id" => $client_id,"vendor_id" => $vendor_id);
    }
    $list_data = $this->Tools_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Tools_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
                $type = $data->unit_type ? $data->unit_type : "";
$handled_by = "";
                if($data->member_type=='tm'){
        if ($data->linked_user_name) {
            if ($handled_by) {
                $handled_by .= "<br /> ";
            }
            $handled_by .= lang("team_member") . ": " . $data->linked_user_name;
        }
    }else if($data->member_type=='om'){
        if ($data->linked_user_name) {
            if ($handled_by) {
                $handled_by .= "<br /> ";
            }
            $handled_by .= lang("outsource_member") . ": " . $data->linked_user_name;
        }
    }else if ($data->member_type=='clients'){
if ($data->client_company) {
            if ($handled_by) {
                $handled_by .= "<br /> ";
            }
            $handled_by .= lang("client_company") . ": " . $data->client_company."<br>"; 
            $handled_by .= lang("client_contact_member") . ": " . $data->linked_user_name;
        }

    }else if ($data->member_type=='vendors'){
if ($data->vendor_company) {
            if ($handled_by) {
                $handled_by .= "<br /> ";
            }
            $handled_by .= lang("vendor_company") . ": " . $data->vendor_company."<br>"; 
            $handled_by .= lang("vendor_contact_member") . ": " . $data->linked_user_name;
        }

    }elseif ($data->member_type=='others') {
if ($data->others_name) {
            if ($handled_by) {
                $handled_by .= "<br /> ";
            }
             
            $handled_by .= lang("other_contact") . ": " . $data->others_name;
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
            $handled_by,
             $data->tool_location,
            to_decimal_format($data->quantity),
             //$data->category,
            //$data->make,
            $category_name->title?$category_name->title:"-",
            $make_name->title?$make_name->title:"-",
            $type,
            to_currency($data->rate),
            to_currency($data->quantity*$data->rate),
            //("₹".$data->quantity*$data->rate),
             $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("tools/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tool'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tool'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("tools/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */