<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Countries extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        //$this->init_permission_checker("master_data");
        //$this->access_only_allowed_members();
        $this->init_permission_checker("country");
    }

    function index() {
        //$this->check_module_availability("module_master_data");
        $this->check_module_availability("module_country");
        //$this->access_only_allowed_members();
        //$this->template->rander("countries/index");
        if ($this->login_user->is_admin == "1")
        {
            //$this->template->rander("countries/index");
            $this->template->rander_scroll("countries/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander_scroll("countries/index");
        }else {


        $this->template->rander_scroll("countries/index");
    } 
    }

    function modal_form() {
//$this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Countries_model->get_one($this->input->post('id'));
         $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $view_data['timezone_dropdown'] = array();
        foreach ($tzlist as $zone) {
            $view_data['timezone_dropdown'][$zone] = $zone;
        }
               $view_data['vat_dropdown'] = array("" => "-") + $this->Vat_types_model->get_dropdown_list(array("title"),"id", array("status"=> "active"));

         $view_data['language_dropdown'] = get_language_list();

         $view_data['holiday_of_week_dropdown'] = json_encode(array(array("id" => 0, "text" => "Sunday"),array("id" => 1, "text" => "Monday"),array("id" => 2, "text" => "Tuesday"),array("id" => 3, "text" => "Wednesday"),array("id" => 4, "text" => "Thursday"),array("id" => 5, "text" => "Friday"),array("id" => 6, "text" => "Saturday")));
        $this->load->view('countries/modal_form', $view_data);
    }

    function save() {
//$this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "numeric"
          
        ));

        $id = $this->input->post('id');
         if($id){
            $ree=$this->Countries_model->get_one($this->input->post('id'));
            if(strtoupper($ree->iso)!=strtoupper($this->input->post('iso_code'))){
             if ($this->Countries_model->is_country_iso_exists($this->input->post('iso_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_iso')));
            exit();
        }
             }
             
               if($ree->numberCode!=$this->input->post('number_code')){
             if ($this->Countries_model->is_country_exists($this->input->post('number_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_code')));
            exit();
        }
             }
             if($ree->countryName!=strtoupper($this->input->post('country_name'))){
             if ($this->Countries_model->is_country_name_exists(strtoupper($this->input->post('country_name')))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_name')));
            exit();
        }
             }
        }
        if(!$id){
        if ($this->Countries_model->is_country_iso_exists($this->input->post('iso_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_iso')));
            exit();
        }
         if ($this->Countries_model->is_country_exists($this->input->post('number_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_code')));
            exit();
        }
         if ($this->Countries_model->is_country_name_exists(strtoupper($this->input->post('country_name')))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_name')));
            exit();
        }

    
    }
        $data = array(
            "iso" => $this->input->post('iso_code'),
            "countryName" => strtoupper($this->input->post('country_name')),
            "numberCode" => $this->input->post('number_code'),
            "currency_symbol" => $this->input->post('currency_symbol'),
            "currency" => $this->input->post('currency'),
            "currency_name"=>$this->input->post('currency_name'),
            "timezone" => $this->input->post('timezone'),
            "date_format" => $this->input->post('date_format'),
            "time_format" => $this->input->post('time_format'),
            "first_day_of_week" => $this->input->post('first_day_of_week'),
             "language" => $this->input->post('language'),
            "vat_type" => $this->input->post('vat_type'),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),

         
        );
        $save_id = $this->Countries_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        //$this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Countries_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Countries_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Countries_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        //$this->access_only_allowed_members();
        $list_data = $this->Countries_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Countries_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        //country logo 
if($data->image){
 $image_url = get_file_uri(get_general_file_path("country_profile_image", $data->id) . $data->image);
}else{
    $image_url = get_avatar($data->image); 
}
$user_avatar = "<span class='avatar avatar-xs'><img src='$image_url' alt='...'></span>";


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
            //$data->countryName,
            $user_avatar,
             anchor(get_uri("countries/view/" . $data->id), $data->countryName),
            $data->numberCode,
            $data->iso,
            $data->currency_name,
            $data->currency,
            $data->currency_symbol,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("countries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_country'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("countries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    //show country details details view
    function view($id = 0, $tab = "") {
        //we have an id. view the team_member's profie
            $options = array("id" => $id);
            $country_info = $this->Countries_model->get_details($options)->row();
            if ($country_info) {

                //check which tabs are viewable for current logged in user
               
            $view_data['show_general_info'] = $country_info;
            $view_data['tab'] = $tab; //selected tab
             $view_data['country_info'] = $country_info;
               
                $this->template->rander("countries/view", $view_data);
            
        } 
    }

    //show general information of a team member
    function country_info($country_id) {
        //$this->update_only_allowed_members($user_id);

        $view_data['country_info'] = $this->Countries_model->get_one($country_id);
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $view_data['timezone_dropdown'] = array();
        foreach ($tzlist as $zone) {
            $view_data['timezone_dropdown'][$zone] = $zone;
        }

        $view_data['language_dropdown'] = get_language_list();
         $view_data['holiday_of_week_dropdown'] = json_encode(array(array("id" => 0, "text" => "Sunday"),array("id" => 1, "text" => "Monday"),array("id" => 2, "text" => "Tuesday"),array("id" => 3, "text" => "Wednesday"),array("id" => 4, "text" => "Thursday"),array("id" => 5, "text" => "Friday"),array("id" => 6, "text" => "Saturday")));
        //$view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

          $view_data['vat_dropdown'] = array("" => "-") + $this->Vat_types_model->get_dropdown_list(array("title"),"id", array("status"=> "active"));

        $this->load->view("countries/country_info", $view_data);
    }

    //save counntry info
    function save_country_info($country_id) {
        //$this->update_only_allowed_members($user_id);

        /*validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required"
        ));*/

        $id = $country_id;
         if($id){
            $ree=$this->Countries_model->get_one($id);
            if(strtoupper($ree->iso)!=strtoupper($this->input->post('iso_code'))){
             if ($this->Countries_model->is_country_iso_exists($this->input->post('iso_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_iso')));
            exit();
        }
             }
             
               if($ree->numberCode!=$this->input->post('number_code')){
             if ($this->Countries_model->is_country_exists($this->input->post('number_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_code')));
            exit();
        }
             }
             if($ree->countryName!=strtoupper($this->input->post('country_name'))){
             if ($this->Countries_model->is_country_name_exists(strtoupper($this->input->post('country_name')))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_country_name')));
            exit();
        }
             }
        }
        


        $county_data = array(
             "iso" => $this->input->post('iso_code'),
            "countryName" => $this->input->post('country_name'),
            "numberCode" => $this->input->post('number_code'),
            "currency_symbol" => $this->input->post('currency_symbol'),
            "currency" => $this->input->post('currency'),
            "currency_name"=>$this->input->post('currency_name'),
            "timezone" => $this->input->post('timezone'),
            "date_format" => $this->input->post('date_format'),
            "time_format" => $this->input->post('time_format'),
            "first_day_of_week" => $this->input->post('first_day_of_week'),
             "language" => $this->input->post('language'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
            "vat_type" => $this->input->post('vat_type'),
            

        );

        $county_data = clean_data($county_data);

        $country_info_updated = $this->Countries_model->save($county_data, $country_id);

        

        if ($country_info_updated) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    //show the payslip settings information of a country
    function payslip_info($country_id) {
       // $this->only_admin_or_own($user_id);

        $options = array("id" => $country_id);
        $country_info = $this->Countries_model->get_details($options)->row();

        $view_data['country_id'] = $country_id;
        //$view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
       $view_data['members_and_teams_dropdown'] =  json_encode(get_payslip_user_country_select2_data_list($country_info->numberCode));
       // $view_data['job_info'] = $this->Users_model->get_job_info($user_id);
        $view_data['country_info'] = $country_info;
         //annual dropdown  
        $annual_leave_dropdown = array();
        $no_annual_dropdown = range(0,365);
        foreach ($no_annual_dropdown  as $key => $value) {
         $annual_leave_dropdown[$key] = $value;
        }
        $view_data['annual_leave_dropdown'] = $annual_leave_dropdown;
        $this->load->view("countries/payslip_info", $view_data);
    }

    //save payslip  information of a country
    function save_payslip_info() {
        //$this->access_only_admin();

        validate_submitted_data(array(
            "country_id" => "required|numeric"
        ));

        $country_id= $this->input->post('country_id');
        /*client logo store directory */
        $client_logo = $this->input->post('site_logo');
        $target_path = getcwd() . "/" . get_general_file_path("country", $country_id);
        $value = move_temp_file("country-logo.png", $target_path, "", $client_logo);

        

        $payslip_data = array(
            "payslip_color" => $this->input->post('payslip_color'),
            "payslip_footer" => decode_ajax_post_data($this->input->post('payslip_footer')),  
            "payslip_prefix" => $this->input->post('payslip_prefix'), 
            //"payslip_style" => $this->input->post('payslip_style'), 
            //"payslip_logo" => $value,
            "maximum_no_of_casual_leave_per_month" => $this->input->post('maximum_no_of_casual_leave_per_month'),
            "payslip_ot_status"=> $this->input->post('payslip_ot_status'),
            "payslip_generate_date"=> $this->input->post('payslip_generate_date'),
            "company_working_hours_for_one_day"=> $this->input->post('company_working_hours_for_one_day'),
            "ot_permission"=> $this->input->post('ot_permission'),
            "ot_permission_specific"=> $this->input->post('ot_permission_specific'),
            "payslip_created_status"=> $this->input->post('payslip_created_status'),
        );

         $client_info_logo = $this->Countries_model->get_one($country_id);
        $client_logo_file =   $client_info_logo->payslip_logo; 
        if ($client_logo && !$client_logo_file) {
            
            $payslip_data["payslip_logo"] = $value;
        }else if ($client_logo && $client_logo_file) {
            
            $new_files =delete_file_from_directory(get_general_file_path("country", $country_id) . $client_logo_file);
            $payslip_data["payslip_logo"] = $value;
        }

       

       // $payslip_data["payslip_logo"] = $value;

        /*$payslip_info_logo = $this->Countries_model->get_one($country_id);
        $payslip_logo_file =   $payslip_info_logo->payslip_logo; 
        if ($payslip_logo && !$payslip_logo_file) {
            
            $payslip_data["payslip_logo"] = $value;
        }else if ($payslip_logo && $payslip_logo_file) {
            
            $new_files =delete_file_from_directory(get_general_file_path("payslip", $country_id) . $payslip_logo_file);
            $payslip_data["payslip_logo"] = $value;
        }*/

        

        $payslip_save =$this->Countries_model->save($payslip_data, $country_id);
        if ($payslip_save) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    function payslip_earnings_info($country_id) {

        //$this->update_only_allowed_members($user_id);

        /*$options = array("country_id" => $country_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();*/
        $view_data['country_id'] = $country_id;
        $this->load->view("countries/payslip_earnings/index", $view_data);
    }

        /* file upload modal */

    function earnings_modal_form() {
        $view_data['model_info'] = $this->Country_earnings_model->get_one($this->input->post('id'));
        //$user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $view_data['model_info']->user_id;
        $country_id = $this->input->post('country_id') ? $this->input->post('country_id') : $view_data['model_info']->country_id;

       // $this->update_only_allowed_members($user_id);

        $view_data['country_id'] = $country_id;
        $this->load->view('countries/payslip_earnings/modal_form', $view_data);
    }

     function save_earnings() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "percentage" => "required"
        ));

        $id = $this->input->post('id');
         $country_id = $this->input->post('country_id');
        $percentage = $this->input->post('percentage');
         $status = $this->input->post('status');
        $data = array(
            "title" =>  $this->input->post('title'),
            "percentage" => unformat_currency($this->input->post('percentage')),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
            "country_id" =>  $country_id 
        );
        if ($status == 'active') {
            # code...
    
        if(!$id){     
         /*$options = array("product_id" => $product_id);*/
            //$item_info = $this->Earnings_model->get_details()->result();
            $basic_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary","country_id"=>$country_id))->row();
            $other_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"","country_id"=>$country_id))->result();
            //$basic_percentage_value = $basic_percentage->percentage;
            $salary_default = 10000;
            $salary = $salary_default/100;
            $basic_salary_value = $salary*$basic_percentage->percentage;
            $c = $basic_salary_value/100; 
$total=0;
            foreach($other_percentage as $other_per){
 $a=$c * $other_per->percentage;
 $total+=$a;

     }
$current_percentage =  $c*$percentage;    
$g = $basic_salary_value+$total+$current_percentage;            

if($g>$salary_default){
             echo json_encode(array("success" => false, 'message' => lang('earnings_percentage')));
            exit();
                        }
            }
if($id){
$country_payslip_key_name = $this->Country_earnings_model->get_one($id);
            if($country_payslip_key_name->key_name != "basic_salary"){
$basic_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary","country_id"=>$country_id))->row();
            $options = array("id" => $id,"country_id"=>$country_id);
            $other_percentage = $this->Country_earnings_model->get_detailss($options)->result();
            $basic_percentage_value = $basic_percentage->percentage;
            $salary_default = 10000;
            $salary = $salary_default/100;
            $basic_salary_value = $salary*$basic_percentage_value;
            $c = $basic_salary_value/100; 
$total=0;
            foreach($other_percentage as $other_per){
 $a=$c * $other_per->percentage;
 $total+=$a;

     }
$current_percentage =  $c*$percentage;    
$g = $basic_salary_value+$total+$current_percentage;  
 if($g>$salary_default){
             echo json_encode(array("success" => false, 'message' => lang('earnings_percentage')));
            exit();
                        } 

}else if($country_payslip_key_name->key_name == "basic_salary"){

$basic_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary","country_id"=>$country_id))->row();
            $options = array("id" => $id,"country_id"=>$country_id);
            $other_percentage = $this->Country_earnings_model->get_detailss($options)->result();
            //$basic_percentage_value = $basic_percentage->percentage;
            $salary_default = 10000;
            $salary = $salary_default/100;
            $basic_salary_value = $salary*$percentage;
            $c = $basic_salary_value/100; 
$total=0;
            foreach($other_percentage as $other_per){
 $a=$c * $other_per->percentage;
 $total+=$a;

     }
//$current_percentage =  $c*$percentage;    
$g = $basic_salary_value+$total; 
 if($g>$salary_default){
             echo json_encode(array("success" => false, 'message' => lang('earnings_percentage')));
            exit();
                        }             

}
        
} 
}
        $save_id = $this->Country_earnings_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_earnings_row($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* list of files, prepared for datatable  */

    function earnings_list_data($country_id = 0) {
        $options = array("country_id" => $country_id);

        //$this->update_only_allowed_members($user_id);

        $list_data = $this->Country_earnings_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_earnings_row($data);
        }
        echo json_encode(array("data" => $result));
    }


  
        private function _make_earnings_row($data) {
        $delete = "";
        $edit = "";
        if ($data->key_name) {
            $edit = modal_anchor(get_uri("countries/earnings_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            
        }
        if (!$data->key_name) {
            $edit = modal_anchor(get_uri("countries/earnings_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("countries/delete_earnings"), "data-action" => "delete-confirmation"));
        }
        return array($data->title,
            $data->description ? $data->description : "-",
            to_decimal_format($data->percentage)."%",
            lang($data->status),
            /*modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tax'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("earnings/delete"), "data-action" => "delete-confirmation")) */
            $edit.$delete,
        );
    }

    

    /* delete a file */

   

     function delete_earnings() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Country_earnings_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Country_earnings_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }


    //deductions 
    function payslip_deductions_info($country_id) {

        //$this->update_only_allowed_members($user_id);

        /*$options = array("country_id" => $country_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();*/
        $view_data['country_id'] = $country_id;
        $this->load->view("countries/payslip_deductions/index", $view_data);
    }

        /* file upload modal */

    function deductions_modal_form() {
        $view_data['model_info'] = $this->Country_deductions_model->get_one($this->input->post('id'));
        //$user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $view_data['model_info']->user_id;
        $country_id = $this->input->post('country_id') ? $this->input->post('country_id') : $view_data['model_info']->country_id;

       // $this->update_only_allowed_members($user_id);

        $view_data['country_id'] = $country_id;
        $this->load->view('countries/payslip_deductions/modal_form', $view_data);
    }

    function save_deductions() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "percentage" => "required"
        ));

        $id = $this->input->post('id');
        $country_id = $this->input->post('country_id');
        $percentage = $this->input->post('percentage');
        $status = $this->input->post('status');
        $data = array(
           "title" => $this->input->post('title'),
            "percentage" => unformat_currency($this->input->post('percentage')),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
            "country_id" =>  $country_id 
        );
        
        $save_id = $this->Country_deductions_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_deductions_row($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }



        /* list of files, prepared for datatable  */

    function deductions_list_data($country_id = 0) {
        $options = array("country_id" => $country_id);

        //$this->update_only_allowed_members($user_id);

        $list_data = $this->Country_deductions_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_deductions_row($data);
        }
        echo json_encode(array("data" => $result));
    }


  
        private function _make_deductions_row($data) {
        $delete = "";
        $edit = "";
        if ($data->key_name) {
            $edit = modal_anchor(get_uri("countries/deductions_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            
        }
        if (!$data->key_name) {
            $edit = modal_anchor(get_uri("countries/deductions_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("countries/delete_deductions"), "data-action" => "delete-confirmation"));
        }
        return array($data->title,
            $data->description ? $data->description : "-",
            to_decimal_format($data->percentage)."%",
            lang($data->status),
            /*modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tax'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("earnings/delete"), "data-action" => "delete-confirmation")) */
            $edit.$delete,
        );
    }

    

    /* delete a file */

   

     function delete_deductions() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Country_deductions_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Country_deductions_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //country logo 
    function save_profile_image($country_id = 0) {
        

        $client_logo = str_replace("~", ":", $this->input->post("profile_image"));;
        $target_path = getcwd() . "/" . get_general_file_path("country_profile_image", $country_id);
        $value = move_temp_file("country-logo.png", $target_path, "", $client_logo);

        //$image_data = array("image" => $value);

        $client_info_logo = $this->Countries_model->get_one($country_id);
        $client_logo_file =   $client_info_logo->image; 
        if ($client_logo && !$client_logo_file) {
            
            //$payslip_data["payslip_logo"] = $value;
            $image_data = array("image" => $value);
        }else if ($client_logo && $client_logo_file) {
            
            $new_files =delete_file_from_directory(get_general_file_path("country_profile_image", $country_id) . $client_logo_file);
            /*$payslip_data["payslip_logo"] = $value;*/
             $image_data = array("image" => $value);
        }

       

       $payslip_save =$this->Countries_model->save($image_data, $country_id);
            
        if ($payslip_save) {
            echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
     

     }


 
}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */