
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payslip extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->init_permission_checker("payslip");

        //$this->access_only_team_members();
    }

    protected function access_only_allowed_members($user_id = 0) {
        if ($this->access_type !== "all") {
            if ($user_id === $this->login_user->id || !array_search($user_id, $this->allowed_members)) {
                redirect("forbidden");
            }
        }
    }
    //load the payslip list view
    function index() {
        $this->check_module_availability("module_payslip");

/*        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        
        $this->template->rander("payslip/index", $view_data);*/
        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['access_info'] = $this->get_access_info("payslip");
        $view_data['payslip_access_all'] = $view_data['access_info']->access_type;
        $view_data['payslip_access'] = $view_data['access_info']->allowed_members;
        $access_info = $this->get_access_info("payslip");
        $payslip_access_all = $access_info->access_type;
        $payslip_access = $access_info->allowed_members;
                 if($this->login_user->is_admin ||in_array($this->login_user->id,$payslip_access)||$payslip_access_all=="all"){
           // $this->access_only_allowed_members();
                  //country dropdown 
         $company_setup_country = $this->Countries_model->get_all()->result();
         $company_setup_country_dropdown = array(array("id" => "", "text" => "- " . lang("country") . " -"));
         foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
         $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);

            $this->template->rander("payslip/index", $view_data);
        } else{
            redirect('payslip/payslip_info');
        }
    }

     function pays()
    {
        $this->load->view('payslip/pay.php');
    }

    //load the yearly view of estimate list
    function yearly() {
        $this->load->view("payslip/yearly_payslip");
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

   //load the add/edit payslip form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Payslip_model->get_one($this->input->post('id'));
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name.'('.$team_member->employee_id.')';
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;
        $model_info->user_id = $model_info->user_id ? $model_info->user_id : $this->input->post('user_id');
         $view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());

        $view_data['model_info'] = $model_info;

         //country dropdown 
         $company_setup_country = $this->Countries_model->get_all()->result();
         $company_setup_country_dropdown = array();
         foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
         $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);

         //branch dropdown 
         //$company_branch = $this->Branches_model->get_dropdown_list(array("title"), "company_setup_country", array("company_setup_country" => $view_data['model_info']->country));
         if($model_info->country){
         $company_branch = $this->Branches_model->get_all_where(array("deleted" => 0,"company_setup_country"=>$view_data['model_info']->country))->result();
       }else {
         $company_branch = $this->Branches_model->get_all_where(array("deleted" => 0))->result();

       }
        $company_branch_dropdown = array(array("id" => "", "text" => "-"));
        foreach ($company_branch as $branch) {
            $company_branch_dropdown[] = array("id" => $branch->branch_code, "text" =>$branch->title );
        }
         $view_data['company_branch_dropdown'] = json_encode($company_branch_dropdown);

          //annual dropdown  
        $annual_leave_dropdown = array("" =>"-");
        $no_annual_dropdown = range(0,365);
        foreach ($no_annual_dropdown  as $key => $value) {
         $annual_leave_dropdown[$key] = $value;
        }
        $view_data['annual_leave_dropdown'] = $annual_leave_dropdown;
        $this->load->view('payslip/modal_form', $view_data);
    }

    //save an payslip
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "payslip_date" => "required"
            ));

        $id = $this->input->post('id');
       $user_id = $this->input->post('payslip_user_id');
       $user_salary_info = $this->Payslip_model->get_payslip_user_salary($user_id);
        $data = array(
           
        "payslip_date" => $this->input->post('payslip_date'),
        "user_id" => $this->input->post('payslip_user_id'),
        "salary" =>$user_salary_info->salary?$user_salary_info->salary:0,
        "country"  =>  $this->input->post('country'),
         "branch"  =>  $this->input->post('branch'),   
        );
        if(!$id){
           $payslip_ot_permission_input = $this->input->post('payslip_ot_permission');
           $payslip_ot_permission_specific_input = $this->input->post('payslip_ot_permission_specific');
           $payslip_working_hours = $this->input->post('payslip_working_hours');
           $payslip_casual_leave = $this->input->post('payslip_casual_leave');
           if($payslip_ot_permission_input==="no"||$payslip_ot_permission_input==="all"){
             $payslip_ot_permission_specific =  "";
           }else if($payslip_ot_permission_input==="specific"){
              $payslip_ot_permission_specific =  $payslip_ot_permission_specific_input;
           }
           $data["payslip_created_status"] = $this->input->post('payslip_created_status');
           $data["payslip_ot_permission"]  = $payslip_ot_permission_input?$payslip_ot_permission_input:"";
           $data["payslip_ot_permission_specific"]  = $payslip_ot_permission_specific?$payslip_ot_permission_specific:"";
           $data["payslip_casual_leave"]  =  $this->input->post('payslip_casual_leave');
           $data["payslip_working_hours"]  =  $payslip_working_hours ? $payslip_working_hours :"";
       }else if ($id){
          $payslip_ot_permission_input = $this->input->post('payslip_ot_permission');
           $payslip_ot_permission_specific_input = $this->input->post('payslip_ot_permission_specific');
           $payslip_working_hours = $this->input->post('payslip_working_hours');
           $payslip_casual_leave = $this->input->post('payslip_casual_leave');
        if($payslip_ot_permission_input==="no"||$payslip_ot_permission_input==="all"){
             $payslip_ot_permission_specific =  "";
           }else if($payslip_ot_permission_input==="specific"){
              $payslip_ot_permission_specific =  $payslip_ot_permission_specific_input;
           }
           $data["payslip_created_status"] = $this->input->post('payslip_created_status') ;
           $data["payslip_ot_permission"]  = $payslip_ot_permission_input?$payslip_ot_permission_input:"";
           $data["payslip_ot_permission_specific"]  = $payslip_ot_permission_specific?$payslip_ot_permission_specific:"";
           $data["payslip_casual_leave"]  =  $this->input->post('payslip_casual_leave');
           $data["payslip_working_hours"]  =  $payslip_working_hours ? $payslip_working_hours :"";
       }
        //$save_id = $this->Payslip_attendance_model->save($data, $id);

        //$save_id = $this->Payslip_earnings_model->save($data, $id);

       if($id){
        $user_id_check_info = $this->Payslip_model->get_one($this->input->post('id'));
        $user_id = $this->input->post('payslip_user_id');
        if($user_id != $user_id_check_info->user_id){

         $data["no_of_paid_leave"]  ='NULL';
        }
       }


// new payslip aready exits checcking 
       if($id){
    // check the invoice no already exits  update    
        $data["payslip_no"] = $this->input->post('payslip_no');
        if ($this->Payslip_model->is_payslip_no_exists($data["payslip_no"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('payslip_no_already')));
                exit();
            }
}
// create new invoice no check already  exsits 
if (!$id) {
$get_last_estimate_id = $this->Payslip_model->get_last_payslip_id_exists();
$estimate_no_last_id = ($get_last_estimate_id->id+1);
$estimate_prefix = get_payslip_id($estimate_no_last_id);
 
        if ($this->Payslip_model->is_payslip_no_exists($estimate_prefix)) {
                echo json_encode(array("success" => false, 'message' => $estimate_prefix." ".lang('payslip_no_already')));
                exit();
            }
}

//end  create new invoice no check already  exsits




       $save_id = $this->Payslip_model->save($data, $id);
if($save_id){
// Save the new payslip no 
           if (!$id) {
               $estimate_prefix = get_payslip_id($save_id);
               $estimate_prefix_data = array(
                   
                    "payslip_no" => $estimate_prefix
                );
                $estimate_prefix_id = $this->Payslip_model->save($estimate_prefix_data, $save_id);
            }
// End  the new payslip no 





    if(!$id){
$attendance_data = array(
        "payslip_date" => $this->input->post('payslip_date'),
        "user_id" => $this->input->post('payslip_user_id'),
        "payslip_id" => $save_id,
            
        );
$this->Payslip_attendance_model->save($attendance_data);
$this->Payslip_earnings_model->save($attendance_data);
}
if($id){
    $payslip_data = $this->Payslip_model->get_one($save_id);
    $payslip_user_data = $payslip_data->user_id;
    $DB4 = $this->load->database('default', TRUE);
    $DB4->where('payslip_id',$save_id);
    $DB4->update('payslip_earnings', array('user_id'=>$payslip_user_data)); 
 
    $DB5 = $this->load->database('default', TRUE);
    $DB5->where('payslip_id',$save_id);
    $DB5->update('payslip_attendance', array('user_id'=>$payslip_user_data)); 
}
}

/*if($id){
        $DB1 = $this->load->database('default', TRUE);
        $DB1->select ("id","user_id","payslip_time");
        $DB1->from('payslip');
        $DB1->order_by('payslip_time','desc');
        $DB1->limit(1);
        $query1=$DB1->get();
        $query1->result();  
    foreach ($query1->result() as $rows)
      {
         $b=$rows->id;
      }

       $DB9 = $this->load->database('default', TRUE);
       $DB9->select ("user_id","payslip_time");
       $DB9->from('payslip');
       $DB9->order_by('payslip_time','desc');
       $DB9->limit(1);
       $query3=$DB9->get();
       $query3->result();  
       foreach ($query3->result() as $rows)
       {
          $c=$rows->user_id;
        }
    
      $DB7 = $this->load->database('default', TRUE);
      $DB7->select ("payslip_id");
      $DB7->from('payslip_earnings');
      $DB7->where('payslip_id',$b);
      $query2=$DB7->get();
      $name=$query2->num_rows();
     if($name)
     {

    $DB4 = $this->load->database('default', TRUE);
    $DB4->where('payslip_id',$b);
    $DB4->update('payslip_earnings', array('user_id'=>$c)); 
 
    $DB5 = $this->load->database('default', TRUE);
    $DB5->where('payslip_id',$b);
    $DB5->update('payslip_attendance', array('user_id'=>$c)); 
   }else{
    
    $DB4 = $this->load->database('default', TRUE);
    $DB4->select ("id");
    $DB4->from('payslip_earnings');
    $DB4->order_by('id','desc');
    $DB4->limit(1);

    $DB4->update('payslip_earnings', array('payslip_id' => $b)); 
    $DB5 = $this->load->database('default', TRUE);
    $DB5->select ("id");
    $DB5->from('payslip_attendance');
    $DB5->order_by('id','desc');
    $DB5->limit(1);

    $DB5->update('payslip_attendance', array('payslip_id' => $b)); 
}

}*/
   if ($save_id) {
           
    log_notification("generate_employee_payslip", array("payslip_id" => $save_id, "to_user_id" => $user_id));
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    function get_emp_monthly_payslip_info() {
   
        $month=Date('m', strtotime($this->input->post("payslip_date")));
        $item = ($this->Payslip_model->get_emp_monthly_payslip_info_suggestion($month,$this->input->post("user_id")));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

   

    function delete() {
       

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Payslip_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Payslip_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get the expnese list data
    function list_data() {
       
        $user_id = $this->input->post('user_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
         $country = $this->input->post('country');
        $options = array("start_date" => $start_date ,"end_date" => $end_date,"user_id" => $user_id,"login_user_id" => $this->login_user->id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members,"country"=>$country);
        $list_data = $this->Payslip_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of expnese list
    private function _row_data($id) {
        
        $options = array("id" => $id);
        $data = $this->Payslip_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare a row of expnese list
    private function _make_row($data) {
         
        /* $payslip_url = "";
        if ($this->login_user->user_type == "staff") {
            $payslip_url = anchor(get_uri("payslip/view/" . $data->id), get_payslip_id($data->id));
        }else {
            $payslip_url = anchor(get_uri("payslip/preview/" . $data->id), get_payslip_id($data->id));
        }*/


        // $user_table = $this->Users_model->get_one($data->user_id);
        $user_table = $this->Payslip_model->get_one($data->id);
        if($user_table->country){
          $country_options = array("numberCode"=>$user_table->country);
          $country_table = $this->Countries_model->get_details($country_options)->row();

        }
        $country_currency_symbol =  $country_table->currency_symbol ? $country_table->currency_symbol :  get_setting("currency_symbol");

        $payslip_no_value = $data->payslip_no ? $data->payslip_no: get_payslip_id($data->id);
        $payslip_no_url = "";
        if ($this->login_user->user_type == "staff") {
             $payslip_no_url = anchor(get_uri("payslip/view/" . $data->id), $payslip_no_value);
        } else {
             $payslip_no_url = anchor(get_uri("payslip/preview/" . $data->id), $payslip_no_value);
        }

       

       $user_id = "-";
        if ($data->user_id && $this->login_user->user_type == "staff") {
            $image_url = get_avatar($data->user_id_avatar);
            $user_id_user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->linked_user_name";
            $user_id = get_team_member_profile_link($data->user_id, $user_id_user);
            /* if ($data->assigned_to_user_type=="resource") {
            $user_id = get_rm_member_profile_link($data->user_id, $user_id_user);
            } */
        }

        $edit_links = modal_anchor(get_uri("payslip/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payslip'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_payslip'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payslip/delete"), "data-action" => "delete-confirmation"));
        
        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                //$payslip_url = anchor(get_uri("payslip/preview/" . $data->id), get_payslip_id($data->id));
                $payslip_no_url = anchor(get_uri("payslip/preview/" . $data->id), $payslip_no_value);
                $edit_links= "";
            }
        }


        return array(
            //$payslip_url,
            $data->id,
            $payslip_no_url,
            $data->payslip_date,
            $user_id,
            //to_currency($data->earnings_value, $data->currency_symbol),
           // to_currency($data->deductions_value, $data->currency_symbol),
            to_currency($data->earnings_payslip_value?$data->earnings_payslip_value:$data->earnings_value, $country_currency_symbol),
            to_currency($data->dele,$country_currency_symbol),
            to_currency($data->over_time_amount,$country_currency_symbol),
            to_currency($data->netsalary,$country_currency_symbol),
            $edit_links
           // to_currency($data->attendance_value, $data->currency_symbol),
        );

       /*$delete_link = "";
  if ($this->login_user->is_admin){
        $delete_link = js_anchor("<i class='fa fa-times fa-fw'></i>",array('title' => lang('delete_payslip'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payslip/delete"), "data-action" => "delete"));
 }
        $row_data[] = $delete_link;
        return $row_data; */
        

     
    }

    //load attendance attendance info view
    function payslip_info() {
        $this->check_module_availability("module_payslip");

        $view_data['user_id'] = $this->login_user->id;
        if ($this->input->is_ajax_request()) {
            $this->load->view("team_members/payslip_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            $this->template->rander("team_members/payslip_info", $view_data);
        }
    }

    /* load payslip details view */

    function view($payslip_id = 0) {
        //$this->access_only_allowed_members();

        if ($payslip_id) {
            $view_data = get_payslip_making_data($payslip_id);

           

                $this->template->rander("payslip/view", $view_data);
            } else {
                show_404();
            
        }
    }

    private function _get_earnings_total_view($payslip_id = 0) {
        $view_data["earnings_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
        $view_data["hra_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
        $view_data["conveyance_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
        $view_data["medical_allowance_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);

        $view_data["payslip_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);

        return $this->load->view('payslip/earnings_total_section', $view_data, true);
    }

    private function _get_earningsadd_total_view($payslip_id = 0) {
        $view_data["earningsadd_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
        $view_data["payslip_user_total_duration"] = $this->Payslip_model->get_payslip_user_per_month_total_duration($payslip_id);
        
        return $this->load->view('payslip/earningsadd_total_section', $view_data, true);
    }


    /* load earnings ammount modal */

    function earnings_modal_form() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $payslip_id = $this->input->post('payslip_id');
        
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;

        $view_data['model_info'] = $this->Payslip_earnings_model->get_one($this->input->post('id'));
        if (!$payslip_id) {
            $payslip_id = $view_data['model_info']->payslip_id;
        }
        $view_data['payslip_id'] = $payslip_id;
        $this->load->view('payslip/earnings_modal_form', $view_data);
    }

    function save_earnings() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "payslip_id" => "required|numeric"
        ));
        
        $payslip_id = $this->input->post('payslip_id');

        $id = $this->input->post('id');
        
       

        $payslip_earnings_data = array(
            "payslip_id" => $payslip_id,
           // "title" => $this->input->post('payslip_earnings_title'),
            "user_id" => $this->input->post('payslip_earnings_user_id'),
           
            //"rate" => unformat_currency($this->input->post('payslip_earnings_rate')),
           
        );

        $payslip_earnings_id = $this->Payslip_earnings_model->save($payslip_earnings_data, $id);
        if ($payslip_earnings_id) {

            $options = array("id" => $payslip_earnings_id);
            $earnings_info = $this->Payslip_earnings_model->get_details($options)->row();
            echo json_encode(array("success" => true, "payslip_id" => $earnings_info->payslip_id, "data" => $this->_make_earnings_row($earnings_info), "earnings_total_view" => $this->_get_earnings_total_view($earnings_info->payslip_id), 'id' => $payslip_earnings_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

     /* delete or undo an payslip earnings amount */

    function delete_earnings() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Payslip_earnings_model->delete($id, true)) {
                $options = array("id" => $id);
                $earnings_info = $this->Payslip_earnings_model->get_details($options)->row();
                echo json_encode(array("success" => true, "payslip_id" => $earnings_info->payslip_id, "data" => $this->_make_earnings_row($earnings_info), "earnings_total_view" => $this->_get_earnings_total_view($earnings_info->payslip_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Payslip_earnings_model->delete($id)) {
                $earnings_info = $this->Payslip_earnings_model->get_one($id);
                echo json_encode(array("success" => true, "payslip_id" => $earnings_info->payslip_id, "earnings_total_view" => $this->_get_earnings_total_view($earnings_info->payslip_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of payslip earnings, prepared for datatable  */

    function earnings_list_data($payslip_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Payslip_earnings_model->get_details(array("payslip_id" => $payslip_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_earnings_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of payslip earnings list table */

    private function _make_earnings_row($data) {
      /* $description =  $data->rate;
        

        if ($data->linked_user_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .=  to_currency($data->linked_user_name,$data->currency_symbol);
        } */
        
        
        
        //$user_table = $this->Users_model->get_one($data->payslip_user_id);
        $user_table = $this->Payslip_model->get_one($data->payslip_id);
        if($user_table->country){
          $country_options = array("numberCode"=>$user_table->country);
          $country_table = $this->Countries_model->get_details($country_options)->row();

        }
        $country_currency_symbol =  $country_table->currency_symbol ? $country_table->currency_symbol :  get_setting("currency_symbol");
        
        return array(
           
            $data->user_name,
            
            //to_currency($data->linked_user_name,$data->currency_symbol),
             to_currency(($data->payslip_salary?$data->payslip_salary:$data->linked_user_name), $country_currency_symbol),
           
            
           /* modal_anchor(get_uri("payslip/earnings_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payslip'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payslip/delete_earnings"), "data-action" => "delete")) */
        );
    }

   /* earnings add amount*/



    function earningsadd_modal_form() {
       // $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $payslip_id = $this->input->post('payslip_id');
        

        $view_data['model_info'] = $this->Payslip_earningsadd_model->get_one($this->input->post('id'));
        if (!$payslip_id) {
            $payslip_id = $view_data['model_info']->payslip_id;
        }
        $view_data['payslip_id'] = $payslip_id;
        $this->load->view('payslip/earningsadd_modal_form', $view_data);
    }
function save_earningsadd() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "payslip_id" => "required|numeric"
        ));
        
        $payslip_id = $this->input->post('payslip_id');

        $id = $this->input->post('id');
        
       

        $payslip_earnings_data = array(
            "payslip_id" => $payslip_id,
            "title" => $this->input->post('payslip_earningsadd_title'),
           
           
            "rate" => unformat_currency($this->input->post('payslip_earningsadd_rate')),
           
        );

        $payslip_earnings_id = $this->Payslip_earningsadd_model->save($payslip_earnings_data, $id);
        if ($payslip_earnings_id) {

            $options = array("id" => $payslip_earnings_id);
            $earnings_info = $this->Payslip_earningsadd_model->get_details($options)->row();
            echo json_encode(array("success" => true, "payslip_id" => $earnings_info->payslip_id, "data" => $this->_make_earningsadd_row($earnings_info), "earningsadd_total_view" => $this->_get_earningsadd_total_view($earnings_info->payslip_id), 'id' => $payslip_earnings_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    

     /* delete or undo an payslip earnings amount */

    function delete_earningsadd() {
       // $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Payslip_earningsadd_model->delete($id, true)) {
                $options = array("id" => $id);
                $earningsadd_info = $this->Payslip_earningsadd_model->get_details($options)->row();
                echo json_encode(array("success" => true, "payslip_id" => $earningsadd_info->payslip_id, "data" => $this->_make_earningsadd_row($earningsadd_info), "earningsadd_total_view" => $this->_get_earningsadd_total_view($earningsadd_info->payslip_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Payslip_earningsadd_model->delete($id)) {
                $earningsadd_info = $this->Payslip_earningsadd_model->get_one($id);
                echo json_encode(array("success" => true, "payslip_id" => $earningsadd_info->payslip_id, "earningsadd_total_view" => $this->_get_earningsadd_total_view($earningsadd_info->payslip_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of payslip earnings, prepared for datatable  */

    function earningsadd_list_data($payslip_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Payslip_earningsadd_model->get_details(array("payslip_id" => $payslip_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_earningsadd_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of payslip earnings list table */

    private function _make_earningsadd_row($data) {
     
         $payslip_table = $this->Payslip_model->get_one($data->payslip_id);
         //$user_table = $this->Users_model->get_one($payslip_table->user_id);
        if($payslip_table->country){
          $country_options = array("numberCode"=>$payslip_table->country);
          $country_table = $this->Countries_model->get_details($country_options)->row();

        }
        $country_currency_symbol =  $country_table->currency_symbol ? $country_table->currency_symbol :  get_setting("currency_symbol");

        return array(
           
            $data->title,
            
          
           to_currency($data->rate,$country_currency_symbol),
            
            modal_anchor(get_uri("payslip/earningsadd_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payslip'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payslip/delete_earningsadd"), "data-action" => "delete-confirmation")) 
        );
    }


    function download_pdf($payslip_id = 0) {
        if ($payslip_id) {
            $payslip_data = get_payslip_making_data($payslip_id);
          //$this->_check_payslip_access_permission($payslip_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_payslip_pdf($payslip_data, "download");
        } else {
            show_404();
        }
    }


     //view html is accessable to client only.
    function preview($payslip_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($payslip_id) {

            $payslip_data = get_payslip_making_data($payslip_id);
            //$this->_check_estimate_access_permission($estimate_data);

            //get the label of the estimate
            //$estimate_info = get_array_value($estimate_data, "estimate_info");
           // $estimate_data['estimate_status_label'] = $this->_get_estimate_status_label($estimate_info);

            $view_data['payslip_preview'] = prepare_payslip_pdf($payslip_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['payslip_id'] = $payslip_id;

            $this->template->rander("payslip/payslip_preview", $view_data);
        } else {
            show_404();
        }
    }



     /* load deductions ammount modal */

    function deductions_modal_form() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $payslip_id = $this->input->post('payslip_id');

        $view_data['model_info'] = $this->Payslip_deductions_model->get_one($this->input->post('id'));
        if (!$payslip_id) {
            $payslip_id = $view_data['model_info']->payslip_id;
        }
        $view_data['payslip_id'] = $payslip_id;
        $this->load->view('payslip/deductions_modal_form', $view_data);
    }

    function save_deductions() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "payslip_id" => "required|numeric"
        ));

        $payslip_id = $this->input->post('payslip_id');

        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('payslip_deductions_rate'));
       

        $payslip_deductions_data = array(
            "payslip_id" => $payslip_id,
            "title" => $this->input->post('payslip_deductions_title'),
            
            "rate" => unformat_currency($this->input->post('payslip_deductions_rate')),
          
        );

        $payslip_deductions_id = $this->Payslip_deductions_model->save($payslip_deductions_data, $id);
        if ($payslip_deductions_id) {


            


            $options = array("id" => $payslip_deductions_id);
            $deductions_info = $this->Payslip_deductions_model->get_details($options)->row();
            echo json_encode(array("success" => true, "payslip_id" => $deductions_info->payslip_id, "data" => $this->_make_deductions_row($deductions_info), "deductions_total_view" => $this->_get_deductions_total_view($deductions_info->payslip_id), 'id' => $payslip_deductions_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    


    /* delete or undo an payslip earnings amount */

    function delete_deductions() {
       // $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Payslip_deductions_model->delete($id, true)) {
                $options = array("id" => $id);
                $deductions_info = $this->Payslip_deductions_model->get_details($options)->row();
                echo json_encode(array("success" => true, "payslip_id" => $deductions_info->payslip_id, "data" => $this->_make_deductions_row($deductions_info), "deductions_total_view" => $this->_get_deductions_total_view($deductions_info->payslip_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Payslip_deductions_model->delete($id)) {
                $deductions_info = $this->Payslip_deductions_model->get_one($id);
                echo json_encode(array("success" => true, "payslip_id" => $deductions_info->payslip_id, "deductions_total_view" => $this->_get_deductions_total_view($deductions_info->payslip_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of payslip earnings, prepared for datatable  */

    function deductions_list_data($payslip_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Payslip_deductions_model->get_details(array("payslip_id" => $payslip_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_deductions_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of payslip earnings list table */

    private function _make_deductions_row($data) {
        $item = "<b>$data->title</b>";
        
        $payslip_table = $this->Payslip_model->get_one($data->payslip_id);
        // $user_table = $this->Users_model->get_one($payslip_table->user_id);
        if($payslip_table->country){
          $country_options = array("numberCode"=>$payslip_table->country);
          $country_table = $this->Countries_model->get_details($country_options)->row();

        }
        $country_currency_symbol =  $country_table->currency_symbol ? $country_table->currency_symbol :  get_setting("currency_symbol");

        return array(
            $item,
            to_currency($data->rate, $country_currency_symbol),
            
            modal_anchor(get_uri("payslip/deductions_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payslip'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payslip/delete_deductions"), "data-action" => "delete-confirmation"))
        );
    }

      

 private function _get_deductions_total_view($payslip_id = 0) {
        $view_data["deductions_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
         $view_data["payslip_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
         $view_data["earningsadd_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
        $view_data["payslip_user_total_duration"] = $this->Payslip_model->get_payslip_user_per_month_total_duration($payslip_id);
        return $this->load->view('payslip/deductions_total_section', $view_data, true);
    }



     /* load attendance day modal */

    function attendance_modal_form() {
       // $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $payslip_id = $this->input->post('payslip_id');
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;

        $view_data['model_info'] = $this->Payslip_attendance_model->get_one($this->input->post('id'));
        if (!$payslip_id) {
            $payslip_id = $view_data['model_info']->payslip_id;
        }
        $view_data['payslip_id'] = $payslip_id;
        $this->load->view('payslip/attendance_modal_form', $view_data);
    }

     


    function save_attendance() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "payslip_id" => "required|numeric"
        ));

        $payslip_id = $this->input->post('payslip_id');

        $id = $this->input->post('id');
        
       

        $payslip_attendance_data = array(
            "payslip_id" => $payslip_id,
           // "title" => $this->input->post('payslip_attendance_title'),
            
           // "rate" => $this->input->post('payslip_attendance_rate'),
            "user_id" => $this->input->post('payslip_attendance_user_id')
           
        );

        $payslip_attendance_id = $this->Payslip_attendance_model->save($payslip_attendance_data, $id);
       if ($payslip_attendance_id) {


            $options = array("id" => $payslip_attendance_id);
            $attendance_info = $this->Payslip_attendance_model->get_details($options)->row();
            echo json_encode(array("success" => true, "payslip_id" => $attendance_info->payslip_id, "data" => $this->_make_attendance_row($attendance_info), "attendance_total_view" => $this->_get_attendance_total_view($attendance_info->payslip_id), 'id' => $payslip_attendance_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an payslip earnings amount */

    function delete_attendance() {
       // $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Payslip_attendance_model->delete($id, true)) {
                $options = array("id" => $id);
                $attendance_info = $this->Payslip_attendance_model->get_details($options)->row();
                echo json_encode(array("success" => true, "payslip_id" => $attendance_info->payslip_id, "data" => $this->_make_attendance_row($attendance_info), "attendance_total_view" => $this->_get_attendance_total_view($attendance_info->payslip_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Payslip_attendance_model->delete($id)) {
                $attendance_info = $this->Payslip_attendance_model->get_one($id);
                echo json_encode(array("success" => true, "payslip_id" => $attendance_info->payslip_id, "attendance_total_view" => $this->_get_attendance_total_view($attendance_info->payslip_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of payslip earnings, prepared for datatable  */

    function attendance_list_data($payslip_id = 0) {
       // $this->access_only_allowed_members();

        $list_data = $this->Payslip_attendance_model->get_details(array("payslip_id" => $payslip_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_attendance_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of payslip earnings list table */

    private function _make_attendance_row($data) {
       // $item = "<b>$data->title</b>";
        
        

        return array(
          //  $item,
           // $data->rate,
            $data->leave_type_id_name,
            $data->leave_start_user_name,
            $data->leave_end_user_name,
            $data->attendance_user_name,
            
            //to_currency($data->total, $data->currency_symbol),
            modal_anchor(get_uri("payslip/attendance_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payslip'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payslip/delete_attendance"), "data-action" => "delete"))
        );
    }

    private function _get_attendance_total_view($payslip_id = 0) {
        $view_data["attendance_total_summary"] = $this->Payslip_model->get_deductions_total_summary($payslip_id);
        $view_data["payslip_user_total_duration"] = $this->Payslip_model->get_payslip_user_per_month_total_duration($payslip_id);
        
        return $this->load->view('payslip/attendance_total_section', $view_data, true);
    }


function get_payslip_user_id_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();
 //$month=Date('m', strtotime($_REQUEST["payslip_date"]));
      $payslip_date = date('Y-m-d', strtotime($_REQUEST["payslip_date"]));
       $month=date('m', strtotime($payslip_date));
       $year=date('Y', strtotime($payslip_date));
        $items = $this->Payslip_model->get_payslip_user_id_suggestion($key,$month,$year);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->id, "text" => $item->first_name.' '.$item->last_name.'('.$item->employee_id.')');
        }

       // $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product_id"));

        echo json_encode($suggestion);
    }

    //pais leave 
    function paid_leave_modal_form() {
       // $this->access_only_allowed_members();

      validate_submitted_data(array(
          "payslip_id" => "required|numeric"
        )); 

       $payslip_id = $this->input->post('payslip_id');

       $view_data['model_info'] = $this->Payslip_model->get_one($payslip_id);
     $view_data["payslip_user_total_duration"] = $this->Payslip_model->get_payslip_user_per_month_total_duration($payslip_id);

    $this->load->view('payslip/paid_leave_modal_form', $view_data);
    }

    function save_paid_leave() {
       // $this->access_only_allowed_members();

        validate_submitted_data(array(
            "payslip_id" => "required|numeric",
           
            "no_of_paid_leave" => "numeric"
            
        ));

        $payslip_id = $this->input->post('payslip_id');

        
        $data = array(
           
            
            "no_of_paid_leave" => $this->input->post('no_of_paid_leave')
            
            
        );
    

        $data = clean_data($data);

        $save_data = $this->Payslip_model->save($data, $payslip_id);
        if ($save_data) {

            
            echo json_encode(array("success" => true, "attendance_total_view" => $this->_get_attendance_total_view($payslip_id), 'message' => lang('record_saved'), "payslip_id" => $payslip_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

function get_country_payslip_user_info_suggestion() {
        $item = $this->Payslip_model->get_country_payslip_user_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }


        function get_country_payslip_user_ot_specific_info_suggestion() {
       // $item = $this->Payslip_model->get_country_payslip_user_info_suggestion($this->input->post("item_name"));

// OT Permission  specfic team group list 
$ot_str = $this->input->post("ot_value");
$payslip_user_id = $this->input->post("user_id");
$payslip_ot_permission = $this->input->post("ot_permission");
//$ot_str = $payslip_ot_permission_specific;
$ot_s = explode(",",$ot_str);
/*print_r($s);*/
$ot_arri =array();
foreach($ot_s as $ot_sus){
if (strpos($ot_sus, 'team') !== false) {
    $ot_arri[] = $ot_sus;
}
}
$ot_string_convert = implode(",",$ot_arri);
$ot_replace_string = str_replace("team:","",$ot_string_convert );
$ot_team_array_convert = explode(",",$ot_replace_string);
$ot_teamgroup_list = "";
foreach ($ot_team_array_convert as $ot_team_group) {
                if ($ot_team_group) {
                     $options = array("id" => $ot_team_group);
                    $list_team_group = $this->Team_model->get_details($options)->row(); 
                    $ot_teamgroup_list .= $list_team_group->members.",";
                }
            }

$ot_team_group_list_array = explode(",",
    $ot_teamgroup_list);
//OT Team members list 
  $ot_permission_user_array=explode(",",$ot_str);
//OT spefic permission teammembers and team show to team members payslip
if($payslip_ot_permission =="specific" && (in_array('member:'.$payslip_user_id,$ot_permission_user_array)||in_array($payslip_user_id,$ot_team_group_list_array))){
   // $result->over_time = $total_user_duration - $result->total_onedaycompany_duration_user;
    $ot_result = "yes";
}else{
  $ot_result = "no";
}



        if ($ot_result == "yes") {
            echo json_encode(array("success" => true, "ot_result" => $ot_result));
        }else if ($ot_result =="no") {
            echo json_encode(array("success" => true, "ot_result" => $ot_result));
        } else {
            echo json_encode(array("success" => false));
        }
    }





}

/* End of file Payslip.php */
/* Location: ./application/controllers/Payslip.php */