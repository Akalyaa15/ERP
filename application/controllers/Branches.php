<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branches extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        //$this->init_permission_checker("master_data");
        //$this->access_only_allowed_members();
        $this->init_permission_checker("branch");
    }

    function index() {
        //$this->check_module_availability("module_master_data");
      $this->check_module_availability("module_branch");
        //$this->template->rander("branches/index");
        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("branches/index");
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("branches/index");
        }else {


        $this->template->rander("branches/index");
    } 
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Branches_model->get_one($this->input->post('id'));

        //banches address for payslip 
        $gst_code = $this->Gst_state_code_model->get_all()->result();
        $company_gst_state_code_dropdown = array();

        

        foreach ($gst_code as $code) {
            $company_gst_state_code_dropdown[] = array("id" => $code->gstin_number_first_two_digits, "text" => $code->title);
        }

        $company_setup_country = $this->Countries_model->get_all()->result();
        $company_setup_country_dropdown = array();

        

        foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
         

        //$company_state = $this->States_model->get_all()->result();
        //$company_state_dropdown = array();
         /*foreach ($company_state as $state) {
            $company_state_dropdown[] = array("id" => $state->id, "text" => $state->title);
        }*/
        $company_state = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $view_data['model_info']->company_setup_country));
        $company_state_dropdown = array(array("id" => "", "text" => "-"));
        foreach ($company_state as $key => $value) {
            $company_state_dropdown[] = array("id" => $key, "text" => $value);
        }


        $company_name =$this->Companys_model->get_all()->result();
        $company_name_dropdown = array();
        foreach ($company_name as $country) {
            $company_name_dropdown[] = array("id" => $country->cr_id, "text" => $country->company_name);
        }
         $view_data['company_name_dropdown'] = json_encode($company_name_dropdown);
         
         $view_data['company_state_dropdown'] = json_encode($company_state_dropdown);

         $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);

         $view_data['company_gst_state_code_dropdown'] = json_encode($company_gst_state_code_dropdown);

         $view_data['holiday_of_week_dropdown'] = json_encode(array(array("id" => 0, "text" => "Sunday"),array("id" => 1, "text" => "Monday"),array("id" => 2, "text" => "Tuesday"),array("id" => 3, "text" => "Wednesday"),array("id" => 4, "text" => "Thursday"),array("id" => 5, "text" => "Friday"),array("id" => 6, "text" => "Saturday")));
        $this->load->view('branches/modal_form', $view_data);
    }

    function save() { 
        $id = $this->input->post('id');
        if($id){
            $ree=$this->Branches_model->get_one($this->input->post('id'));
            //$re2=$this->Branches_model->is_branch_exists($ree->branch_code);
            if($ree->branch_code!=$this->input->post('branch_code')){
             if ($this->Branches_model->is_branch_exists($this->input->post('branch_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_branch')));
            exit();
        }}
            //$re2=$this->Branches_model->is_branch_name_exists($ree->title);  
            if(strtoupper($ree->title)!=strtoupper($this->input->post('title'))){
             if ($this->Branches_model->is_branch_name_exists($this->input->post('title'),$this->input->post('company_name'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_branch_name')));
            exit();
        }}
        }
        if(!$id){
        if ($this->Branches_model->is_branch_exists($this->input->post('branch_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_branch')));
            exit();
        }
             if ($this->Branches_model->is_branch_name_exists($this->input->post('title'),$this->input->post('company_name'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_branch_name')));
            exit();
        }
    }
  $k=  $this->Branches_model->branch_count($this->input->post('company_name'));
  if(!$id){
    if(!$k){
$branch_code="01";
 }else{
   $branch_code=$k+1;
    if($branch_code<=9){
        $branch_code="0".$branch_code;
    }else{
      $branch_code=$branch_code;  
    }
 } 
  }

//  $company_name=$this->input->post('company_name');
//  if($company_name<=9){
//         $company_name="00".$company_name;
//     }else if($company_name<=99){
//         $company_name="0".$company_name;
//     }else{
//       $company_name=$company_name;  
//     }
// $buid='CR'.$company_name.$branch_code;
              //$cr_code=$this->Companys_model->get_one($this->input->post('company_name'));
  $cr_code=$this->input->post('company_name');

 // $buid=$cr_code->cr_id.$branch_code;
  $buid=$cr_code.$branch_code;
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
        ));

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "company_name" =>$this->input->post('company_name'),
            "company_address" =>$this->input->post('company_address'),
            "company_phone" =>$this->input->post('company_phone'),
            "company_email" =>$this->input->post('company_email'),
            "company_website" =>$this->input->post('company_website'),
            "company_gst_number" =>$this->input->post('company_gst_number'),
            "company_gstin_number_first_two_digits" =>$this->input->post('company_gstin_number_first_two_digits'),
             "company_state" =>$this->input->post('company_state'),
             "company_setup_country" =>$this->input->post('company_setup_country'),
            "company_city" =>$this->input->post('company_city'),
            "company_pincode" =>$this->input->post('company_pincode'),
            "holiday_of_week" =>$this->input->post('holiday_of_week'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
             "state_mandatory"=>$this->input->post('state_mandatory'),
        );
        if(!$id){
            $data["branch_code"]=$branch_code;
            $data["buid"]=$buid;
        }
        $save_id = $this->Branches_model->save($data, $id);
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
         $save_id = $this->Branches_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Branches_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Branches_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Branches_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Branches_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {

        if($data->company_setup_country){
      $country_no = is_numeric($data->company_setup_country);
 if(!$country_no){
   $data->company_setup_country = 0;
 }
 $options = array(
            "numberCode" => $data->company_setup_country,
                   );
        $country_id_name = $this->Countries_model->get_details($options)->row();
        $country_dummy_name =$country_id_name->countryName;
}

//bra\nch logo 
if($data->image){
 $image_url = get_file_uri(get_general_file_path("branch_profile_image", $data->id) . $data->image);
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
            //$data->title,
            $user_avatar,
          $data->buid,
          $data->company,
            anchor(get_uri("branches/view/" . $data->id), $data->title),
            $data->branch_code,$data->description,
            $country_dummy_name,
            $data->company_email,
            $data->company_phone,
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("branches/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_branch'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("branches/delete"), "data-action" => "delete-confirmation"))
        );
    }

     function get_state_suggestion() {
        $key = $_REQUEST["q"];
        $ss=$_REQUEST["ss"];
        $itemss =  $this->Branches_model->get_item_suggestions_country_name($key,$ss);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->title);
       }
        echo json_encode($suggestions);
    }



    //show team member's details view
    function view($id = 0, $tab = "") {
       //we have an id. view the branches profie
            $options = array("id" => $id);
            $branch_info = $this->Branches_model->get_details($options)->row();
            if ($branch_info) {

               $view_data['show_general_info'] = $branch_info;
                $view_data['tab'] = $tab; //selected tab
                $view_data['branch_info'] = $branch_info;
               
                $this->template->rander("branches/view", $view_data);
            
        } 
    }

    //show general information of a team member
    function branch_info($branch_id) {
        //$this->update_only_allowed_members($user_id);

        $view_data['branch_info'] = $this->Branches_model->get_one($branch_id);
       
         $gst_code = $this->Gst_state_code_model->get_all()->result();
        $company_gst_state_code_dropdown = array();

        

        foreach ($gst_code as $code) {
            $company_gst_state_code_dropdown[] = array("id" => $code->gstin_number_first_two_digits, "text" => $code->title);
        }

        $company_setup_country = $this->Countries_model->get_all()->result();
        $company_setup_country_dropdown = array();

        

        foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
         

        

        $company_state = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $view_data['branch_info']->company_setup_country));
        
        $company_state_dropdown = array(array("id" => "", "text" => "-"));
        foreach ($company_state as $key => $value) {
            $company_state_dropdown[] = array("id" => $key, "text" => $value);
        }

         $company_name =$this->Companys_model->get_all()->result();
        $company_name_dropdown = array();
        foreach ($company_name as $country) {
            $company_name_dropdown[] = array("id" => $country->cr_id, "text" => $country->company_name);
        }
         $view_data['company_name_dropdown'] = json_encode($company_name_dropdown);

       
         
         $view_data['company_state_dropdown'] = json_encode($company_state_dropdown);

         $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);

         $view_data['company_gst_state_code_dropdown'] = json_encode($company_gst_state_code_dropdown);

         $view_data['holiday_of_week_dropdown'] = json_encode(array(array("id" => 0, "text" => "Sunday"),array("id" => 1, "text" => "Monday"),array("id" => 2, "text" => "Tuesday"),array("id" => 3, "text" => "Wednesday"),array("id" => 4, "text" => "Thursday"),array("id" => 5, "text" => "Friday"),array("id" => 6, "text" => "Saturday")));

        $this->load->view("branches/branch_info", $view_data);
    }

    //save general information of a team member
    function save_branch_info($branch_id) {
   
    $id = $branch_id;
        if($id){
            $ree=$this->Branches_model->get_one($branch_id);
            //$re2=$this->Branches_model->is_branch_exists($ree->branch_code);
            if($ree->branch_code!=$this->input->post('branch_code')){
             if ($this->Branches_model->is_branch_exists($this->input->post('branch_code'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_branch')));
            exit();
        }}
            //$re2=$this->Branches_model->is_branch_name_exists($ree->title);  
            if(strtoupper($ree->title)!=strtoupper($this->input->post('title'))){
             if ($this->Branches_model->is_branch_name_exists($this->input->post('title'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_branch_name')));
            exit();
        }}
        }
        
       $data = array(
            "title" => $this->input->post('title'),
            "branch_code" => $this->input->post('branch_code'),
            "description" => $this->input->post('description'),
            "company_name" =>$this->input->post('company_name'),
            "company_address" =>$this->input->post('company_address'),
            "company_phone" =>$this->input->post('company_phone'),
            "company_email" =>$this->input->post('company_email'),
            "company_website" =>$this->input->post('company_website'),
            "company_gst_number" =>$this->input->post('company_gst_number'),
            "company_gstin_number_first_two_digits" =>$this->input->post('company_gstin_number_first_two_digits'),
             "company_state" =>$this->input->post('company_state'),
             "company_setup_country" =>$this->input->post('company_setup_country'),
            "company_city" =>$this->input->post('company_city'),
            "company_pincode" =>$this->input->post('company_pincode'),
            "holiday_of_week" =>$this->input->post('holiday_of_week'),
             "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
             "state_mandatory"=>$this->input->post('state_mandatory'),
        );
        $save_id = $this->Branches_model->save($data, $branch_id);
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }    }


function save_profile_image($branch_id = 0) {
        

        $client_logo = str_replace("~", ":", $this->input->post("profile_image"));;
        $target_path = getcwd() . "/" . get_general_file_path("branch_profile_image", $branch_id);
        $value = move_temp_file("country-logo.png", $target_path, "", $client_logo);

        //$image_data = array("image" => $value);

        $client_info_logo = $this->Branches_model->get_one($branch_id);
        $client_logo_file =   $client_info_logo->image; 
        if ($client_logo && !$client_logo_file) {
            
            //$payslip_data["payslip_logo"] = $value;
            $image_data = array("image" => $value);
        }else if ($client_logo && $client_logo_file) {
            
            $new_files =delete_file_from_directory(get_general_file_path("branch_profile_image", $branch_id) . $client_logo_file);
            /*$payslip_data["payslip_logo"] = $value;*/
             $image_data = array("image" => $value);
        }
       $payslip_save =$this->Branches_model->save($image_data, $branch_id);
        if ($payslip_save) {
            echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
      }


//show the job information of a team member
    function payslip_info($branch_id) {
       // $this->only_admin_or_own($user_id);

        $options = array("id" => $branch_id);
        $branch_info = $this->Branches_model->get_details($options)->row();

        $view_data['branch_id'] = $branch_id;
        //$view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
       $view_data['members_and_teams_dropdown'] =  json_encode(get_payslip_user_branch_select2_data_list($branch_info->branch_code,$branch_info->company_name));
       // $view_data['job_info'] = $this->Users_model->get_job_info($user_id);
        $view_data['branch_info'] = $branch_info;
         //annual dropdown  
        $annual_leave_dropdown = array("-"=>"-");
        $no_annual_dropdown = range(1,365);
        foreach ($no_annual_dropdown  as $key => $value) {
         $annual_leave_dropdown[$value] = $value;
        }
        $view_data['annual_leave_dropdown'] = $annual_leave_dropdown;
        $this->load->view("branches/payslip_info", $view_data);
    }

    //save job information of a team member
    function save_payslip_info() {
        //$this->access_only_admin();

        validate_submitted_data(array(
            "branch_id" => "required|numeric"
        ));

        $branch_id= $this->input->post('branch_id');
        /*client logo store directory */
        $client_logo = $this->input->post('site_logo');
        $target_path = getcwd() . "/" . get_general_file_path("branch", $branch_id);
        $value = move_temp_file("branch-logo.png", $target_path, "", $client_logo);
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

         $client_info_logo = $this->Branches_model->get_one($branch_id);
        $client_logo_file =   $client_info_logo->payslip_logo; 
        if ($client_logo && !$client_logo_file) {
            
            $payslip_data["payslip_logo"] = $value;
        }else if ($client_logo && $client_logo_file) {
            
            $new_files =delete_file_from_directory(get_general_file_path("branch", $branch_id) . $client_logo_file);
            $payslip_data["payslip_logo"] = $value;
        }

        $payslip_save =$this->Branches_model->save($payslip_data, $branch_id);
        if ($payslip_save) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    


function payslip_earnings_info($branch_id) {

        //$this->update_only_allowed_members($user_id);

        /*$options = array("country_id" => $country_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();*/
        $view_data['branch_id'] = $branch_id;
        $this->load->view("branches/payslip_earnings/index", $view_data);
    }

        /* file upload modal */

    function earnings_modal_form() {
        $view_data['model_info'] = $this->Country_earnings_model->get_one($this->input->post('id'));
        //$user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $view_data['model_info']->user_id;
        $branch_id = $this->input->post('branch_id') ? $this->input->post('branch_id') : $view_data['model_info']->country_id;

       // $this->update_only_allowed_members($user_id);

        $view_data['branch_id'] = $branch_id;
        $this->load->view('branches/payslip_earnings/modal_form', $view_data);
    }

     function save_earnings() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "percentage" => "required"
        ));

        $id = $this->input->post('id');
        $branch_id = $this->input->post('branch_id');
        $percentage = $this->input->post('percentage');
        $status = $this->input->post('status');
        $data = array(
            "title" =>  $this->input->post('title'),
            "percentage" => unformat_currency($this->input->post('percentage')),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
            "country_id" =>  $branch_id 
        );
        if ($status == 'active') {
            # code...
    
        if(!$id){     
         /*$options = array("product_id" => $product_id);*/
            //$item_info = $this->Earnings_model->get_details()->result();
            $basic_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary","country_id"=>$branch_id))->row();
            $other_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"","country_id"=>$branch_id))->result();
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
$basic_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary","country_id"=>$branch_id))->row();
            $options = array("id" => $id,"country_id"=>$branch_id);
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

$basic_percentage = $this->Country_earnings_model->get_all_where(array("deleted" => 0, "status" => "active" ,"key_name"=>"basic_salary","country_id"=>$branch_id))->row();
            $options = array("id" => $id,"country_id"=>$branch_id);
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

    function earnings_list_data($branch_id = 0) {
        $options = array("country_id" => $branch_id);

        //$this->update_only_allowed_members($user_id);

        $list_data = $this->Country_earnings_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_earnings_row($data);
        }
        echo json_encode(array("data" => $result));
    }


  
    private function _make_earnings_row($data) {
        if (!is_object($data)) {
            // Handle the error if $data is not an object
            return array("Invalid data", "", "", "", "");
        }
    
        // Ensure that all required properties exist
        $id = property_exists($data, 'id') ? $data->id : '';
        $key_name = property_exists($data, 'key_name') ? $data->key_name : '';
        $title = property_exists($data, 'title') ? $data->title : '';
        $description = property_exists($data, 'description') ? $data->description : '-';
        $percentage = property_exists($data, 'percentage') ? $data->percentage : '';
        $status = property_exists($data, 'status') ? $data->status : '';
    
        $delete = "";
        $edit = "";
    
        if ($key_name) {
            $edit = modal_anchor(get_uri("branches/earnings_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $id));
        } else {
            $edit = modal_anchor(get_uri("branches/earnings_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $id));
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $id, "data-action-url" => get_uri("branches/delete_earnings"), "data-action" => "delete-confirmation"));
        }
    
        return array(
            $title,
            $description,
            to_decimal_format($percentage) . "%",
            lang($status),
            $edit . $delete
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
    function payslip_deductions_info($branch_id) {

        //$this->update_only_allowed_members($user_id);

        /*$options = array("country_id" => $country_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();*/
        $view_data['branch_id'] = $branch_id;
        $this->load->view("branches/payslip_deductions/index", $view_data);
    }

        /* file upload modal */

    function deductions_modal_form() {
        $view_data['model_info'] = $this->Country_deductions_model->get_one($this->input->post('id'));
        //$user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $view_data['model_info']->user_id;
    $branch_id = $this->input->post('branch_id') ? $this->input->post('branch_id') : $view_data['model_info']->country_id;

       // $this->update_only_allowed_members($user_id);

        $view_data['branch_id'] = $branch_id;
        $this->load->view('branches/payslip_deductions/modal_form', $view_data);
    }

    function save_deductions() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "percentage" => "required"
        ));

        $id = $this->input->post('id');
        $branch_id = $this->input->post('branch_id');
        $percentage = $this->input->post('percentage');
        $status = $this->input->post('status');
        $data = array(
           "title" => $this->input->post('title'),
            "percentage" => unformat_currency($this->input->post('percentage')),
            "status" => $this->input->post('status'),
            "description" => $this->input->post('description'),
            "country_id" =>  $branch_id 
        );
        
        $save_id = $this->Country_deductions_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_deductions_row($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }



        /* list of files, prepared for datatable  */

    function deductions_list_data($branch_id = 0) {
        $options = array("country_id" => $branch_id);

        //$this->update_only_allowed_members($user_id);

        $list_data = $this->Country_deductions_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_deductions_row($data);
        }
        echo json_encode(array("data" => $result));
    }
    private function _make_deductions_row($data) {
        // Initialize empty strings for edit and delete actions
        $delete = "";
        $edit = "";
    
        // Check if $data is an object
        if (!is_object($data)) {
            return array("Invalid data", "", "", "", "");
        }
    
        // Ensure that all required properties exist
        $id = property_exists($data, 'id') ? $data->id : '';
        $key_name = property_exists($data, 'key_name') ? $data->key_name : '';
        $title = property_exists($data, 'title') ? $data->title : '';
        $description = property_exists($data, 'description') ? $data->description : '-';
        $percentage = property_exists($data, 'percentage') ? $data->percentage : '';
        $status = property_exists($data, 'status') ? $data->status : '';
    
        // Generate edit and delete actions
        if ($key_name) {
            $edit = modal_anchor(get_uri("branches/deductions_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $id));
        } else {
            $edit = modal_anchor(get_uri("branches/deductions_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $id));
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $id, "data-action-url" => get_uri("branches/delete_deductions"), "data-action" => "delete-confirmation"));
        }
    
        // Return the row data
        return array(
            $title,
            $description,
            to_decimal_format($percentage) . "%",
            lang($status),
            $edit . $delete
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

















     

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */