<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron_job {

    private $today = null;
    private $ci = null;

    function run() {
        $this->today = get_today_date();
        $this->ci = get_instance();

        $this->create_recurring_invoices();
        $this->event_announcement();

        $this->send_invoice_due_pre_reminder();
        $this->send_invoice_due_after_reminder();
        $this->send_recurring_invoice_creation_reminder();
        $this->attendance_warning();
        $this->delete_attendance();
        $this->project_timer_warning();
        $this->delete_project_timer();
        $this->generate_payslip();
        $this->send_purchase_order_due_pre_reminder();
        $this->send_purchase_order_due_after_reminder();
        $this->send_purchase_order_repeat_due_after_reminder();
        $this->send_purchase_order_repeat_due_pre_reminder();
        
    }

    private function send_invoice_due_pre_reminder() {

        $reminder_date = get_setting("send_invoice_due_pre_reminder");

        if ($reminder_date) {

            //prepare invoice due date accroding to the setting
            $start_date = add_period_to_date($this->today, get_setting("send_invoice_due_pre_reminder"), "days");

            $invoices = $this->ci->Invoices_model->get_details(array(
                        "status" => "not_paid", //find all invoices which are not paid yet but due date not expired
                        "start_date" => $start_date,
                        "end_date" => $start_date, //both should be same
                        "exclude_due_reminder_date" => $this->today //don't find invoices which reminder already sent today
                    ))->result();

            foreach ($invoices as $invoice) {
                log_notification("invoice_due_reminder_before_due_date", array("invoice_id" => $invoice->id), "0");
            }
        }
    }

    private function send_invoice_due_after_reminder() {

        $reminder_date = get_setting("send_invoice_due_after_reminder");

        if ($reminder_date) {

            //prepare invoice due date accroding to the setting
            $start_date = subtract_period_from_date($this->today, get_setting("send_invoice_due_after_reminder"), "days");

            $invoices = $this->ci->Invoices_model->get_details(array(
                        "status" => "overdue", //find all invoices where due date has expired
                        "start_date" => $start_date,
                        "end_date" => $start_date, //both should be same
                        "exclude_due_reminder_date" => $this->today //don't find invoices which reminder already sent today
                    ))->result();

            foreach ($invoices as $invoice) {
                log_notification("invoice_overdue_reminder", array("invoice_id" => $invoice->id), "0");
            }
        }
    }
    
     private function send_recurring_invoice_creation_reminder() {

        $reminder_date = get_setting("send_recurring_invoice_reminder_before_creation");

        if ($reminder_date) {

            //prepare invoice due date accroding to the setting
            $start_date = add_period_to_date($this->today, get_setting("send_recurring_invoice_reminder_before_creation"), "days");

            $invoices = $this->ci->Invoices_model->get_details(array(
                        "status" => "not_paid", //non-draft invoices
                        "recurring"=>1,
                        "next_recurring_start_date" => $start_date,
                        "next_recurring_end_date" => $start_date, //both should be same
                        "exclude_recurring_reminder_date" => $this->today //don't find invoices which reminder already sent today
                    ))->result();

            foreach ($invoices as $invoice) {
                log_notification("recurring_invoice_creation_reminder", array("invoice_id" => $invoice->id), "0");
            }
        }
    }
    

    private function create_recurring_invoices() {
        $recurring_invoices = $this->ci->Invoices_model->get_renewable_invoices($this->today);
        if ($recurring_invoices->num_rows()) {
            foreach ($recurring_invoices->result() as $invoice) {
                $this->_create_new_invoice($invoice);
            }
        }
    }
    //create new invoice from a recurring invoice 
    private function _create_new_invoice($invoice) {

        //don't update the next recurring date when updating invoice manually?
        //stop backdated recurring invoice creation.
        //check recurring invoice once/hour?
        //settings: send invoice to client


        $bill_date = $invoice->next_recurring_date;
        $diff_of_due_date = get_date_difference_in_days($invoice->due_date, $invoice->bill_date); //calculate the due date difference of the original invoice
        $due_date = add_period_to_date($bill_date, $diff_of_due_date, "days");



        $new_invoice_data = array(
            "client_id" => $invoice->client_id,
            "project_id" => $invoice->project_id,
            "bill_date" => $bill_date,
            "due_date" => $due_date,
            "note" => $invoice->note,
            "status" => "draft",
            "tax_id" => $invoice->tax_id,
            "tax_id2" => $invoice->tax_id2,
            "recurring_invoice_id" => $invoice->id,


            "invoice_delivery_address" => $invoice->invoice_delivery_address,
            "delivery_note_date" => $invoice->delivery_note_date,
            "supplier_ref" => $invoice->supplier_ref,
            "other_references" => $invoice->other_references,
            "terms_of_payment" => $invoice->terms_of_payment,
            "buyers_order_no" => $invoice->buyers_order_no,
            "buyers_order_date"  => $invoice->buyers_order_date,
            "destination" => $invoice->destination,
            "dispatch_document_no" => $invoice->dispatch_document_no,
            "dispatched_through" =>$invoice->dispatched_through,
            "terms_of_delivery" =>$invoice->terms_of_delivery,
            "delivery_address" =>$invoice->delivery_address,
            "delivery_address_state" => $invoice->delivery_address_state,
            "delivery_address_city" => $invoice->delivery_address_city,
            "delivery_address_country" =>$invoice->delivery_address_country,
            "delivery_address_zip" => $invoice->delivery_address_zip,



            "lut_number" => $invoice->lut_number, 
            "lc_no" => $invoice->lc_no,
            "lc_date" => $invoice->lc_date, 
            "dispatch_docket" => $invoice->dispatch_docket, 
            "dispatch_name" => $invoice->dispatch_name, 
            "waybill_no" => $invoice->waybill_no,

//Add Dispatch By 
            "dispatch_user_id" =>$invoice->dispatch_user_id,        
            "member_type" => $invoice->member_type,
            "f_name" => $invoice->f_name,
            "l_name" => $invoice->l_name,
            "phone" => $invoice->phone,

//freight amount add from profroma invoices
            "amount" => $invoice->amount,
            "hsn_code" => $invoice->hsn_code,
            "hsn_description" => $invoice->hsn_description,
            "gst" => $invoice->gst,
            "with_inclusive_tax" => $invoice->with_inclusive_tax,
            "with_gst" => $invoice->with_gst,
            "freight_tax_amount" => $invoice->freight_tax_amount,
            "freight_amount" => $invoice->freight_amount,
            "warranty" => $invoice->warranty,
            "warranty_type" => $invoice->warranty_type,
            "warranty_expiry_date" => $invoice->warranty_expiry_date
        );

        //create new invoice
        $new_invoice_id = $this->ci->Invoices_model->save($new_invoice_data);

        //create invoice items
        $items = $this->ci->Invoice_items_model->get_details(array("invoice_id" => $invoice->id))->result();
        foreach ($items as $item) {
            //create invoice items for new invoice
            $new_invoice_item_data = array(
                "title" => $item->title,
                "description" => $item->description,
                "quantity" => $item->quantity,
                "unit_type" => $item->unit_type,
                "rate" => $item->rate,
                "total" => $item->total,
                "invoice_id" => $new_invoice_id,
                
                "category" => $item->category,
                "make" => $item->make,
                "hsn_code" => $item->hsn_code,
                "gst" => $item->gst,
                "hsn_description" => $item->hsn_description,
                "discount_percentage" => $item->discount_percentage,
                "tax_amount" =>$item->tax_amount,
                "net_total" => $item->net_total,

                "discount_amount"=> $item->discount_amount,
                "with_gst" => $item->with_gst,
                "quantity_total"=>$item->quantity_total,
             
            
            
                "profit_percentage" => $item->profit_percentage,
                "associated_with_part_no" => $item->associated_with_part_no,
                "profit_value"=>$item->profit_value,
                "actual_value" => $item->actual_value,
                "MRP" => $item->MRP,
 
//installatio add 

               "with_installation"=>$item->with_installation,
               "with_installation_gst"=>$item->with_installation_gst,
               "installation_gst"=>$item->installation_gst,
               "installation_rate"=>$item->installation_rate,
               "installation_hsn_code"=>$item->installation_hsn_code,
               "installation_hsn_code_description"=>$item->installation_hsn_code_description,
               "installation_total"=>$item->installation_total,
               "subtotal"=>$item->subtotal,
               "installation_tax_amount"=> $item->installation_tax_amount,
               "client_profit_margin"=>$item->client_profit_margin,
            );
            $this->ci->Invoice_items_model->save($new_invoice_item_data);
        }


        //update the main recurring invoice
        $no_of_cycles_completed = $invoice->no_of_cycles_completed + 1;
        $next_recurring_date = add_period_to_date($bill_date, $invoice->repeat_every, $invoice->repeat_type);


        $recurring_invoice_data = array(
            "next_recurring_date" => $next_recurring_date,
            "no_of_cycles_completed" => $no_of_cycles_completed
        );
        $this->ci->Invoices_model->save($recurring_invoice_data, $invoice->id);

        //finally send notification
        log_notification("recurring_invoice_created_vai_cron_job", array("invoice_id" => $new_invoice_id), "0");
    }

    /*//create new invoice from a recurring invoice 
    private function _create_new_invoice($invoice) {

        //don't update the next recurring date when updating invoice manually?
        //stop backdated recurring invoice creation.
        //check recurring invoice once/hour?
        //settings: send invoice to client


        $bill_date = $invoice->next_recurring_date;
        $diff_of_due_date = get_date_difference_in_days($invoice->due_date, $invoice->bill_date); //calculate the due date difference of the original invoice
        $due_date = add_period_to_date($bill_date, $diff_of_due_date, "days");



        $new_invoice_data = array(
            "client_id" => $invoice->client_id,
            "project_id" => $invoice->project_id,
            "bill_date" => $bill_date,
            "due_date" => $due_date,
            "note" => $invoice->note,
            "status" => "draft",
            "tax_id" => $invoice->tax_id,
            "tax_id2" => $invoice->tax_id2,
            "recurring_invoice_id" => $invoice->id
        );

        //create new invoice
        $new_invoice_id = $this->ci->Invoices_model->save($new_invoice_data);

        //create invoice items
        $items = $this->ci->Invoice_items_model->get_details(array("invoice_id" => $invoice->id))->result();
        foreach ($items as $item) {
            //create invoice items for new invoice
            $new_invoice_item_data = array(
                "title" => $item->title,
                "description" => $item->description,
                "quantity" => $item->quantity,
                "unit_type" => $item->unit_type,
                "rate" => $item->rate,
                "total" => $item->total,
                "invoice_id" => $new_invoice_id,
            );
            $this->ci->Invoice_items_model->save($new_invoice_item_data);
        }


        //update the main recurring invoice
        $no_of_cycles_completed = $invoice->no_of_cycles_completed + 1;
        $next_recurring_date = add_period_to_date($bill_date, $invoice->repeat_every, $invoice->repeat_type);


        $recurring_invoice_data = array(
            "next_recurring_date" => $next_recurring_date,
            "no_of_cycles_completed" => $no_of_cycles_completed
        );
        $this->ci->Invoices_model->save($recurring_invoice_data, $invoice->id);

        //finally send notification
        log_notification("recurring_invoice_created_vai_cron_job", array("invoice_id" => $new_invoice_id), "0");
    }*/
 /*  private function delete_attendance() {
    $time=date("H:i");
                
                if($time=='23:30'){
        $list_data = $this->ci->Attendance_model->get_co_details()->result();
        $gst_code_dropdown = array();
foreach ($list_data as $code) {
            $gst_code_dropdown[] = $code->id;
        }
        //log_notification("new_announcement_created", array("announcement_id" => 44));
        foreach ($gst_code_dropdown as $id) {
            $this->ci->Attendance_model->delete($id);
        }
    }
    } */


private function delete_attendance() {
    $time=date("H:i");
                
                if($time=='23:30'){
        $list_data = $this->ci->Attendance_model->get_co_details()->result();
        $gst_code_dropdown = array();
foreach ($list_data as $code) {
            $gst_code_dropdown[] = $code->id;
        }
        //log_notification("new_announcement_created", array("announcement_id" => 44));
        foreach ($gst_code_dropdown as $id) {
            $this->ci->Attendance_model->update_clock_out($id);
        }
    }
    }


    private function attendance_warning() {
        $time=date("H:i");
         if($time=='23:00'){       
         $list_data = $this->ci->Attendance_model->get_co_details()->result();
        $gst_code_dropdown = array();
foreach ($list_data as $code) {
            $users[] = $code->user_id;
        }       
          foreach ($users as $id) {

         $data = array(
            "user_id" => 0,
            "description" => "",
            "created_at" => get_current_utc_time(),
            "notify_to" => $id,
            "read_by" => "",
            "event" => 'attendance_warning',
            "project_id" => $project_id ? $project_id : "",
            "task_id" => $task_id ? $task_id : "",
            "project_comment_id" => $project_comment_id ? $project_comment_id : "",
            "ticket_id" => $ticket_id ? $ticket_id : "",
            "ticket_comment_id" => $ticket_comment_id ? $ticket_comment_id : "",
            "project_file_id" => $project_file_id ? $project_file_id : "",
            "leave_id" => $leave_id ? $leave_id : "",
            "post_id" => $post_id ? $post_id : "",
            "to_user_id" => $to_user_id ? $to_user_id : "",
            "activity_log_id" => $activity_log_id ? $activity_log_id : "",
            "client_id" => $client_id ? $client_id : "",
            "invoice_payment_id" => $invoice_payment_id ? $invoice_payment_id : "",
            "invoice_id" => $invoice_id ? $invoice_id : "",
            "estimate_request_id" => $estimate_request_id ? $estimate_request_id : "",
            "estimate_id" => $estimate_id ? $estimate_id : "",
            "actual_message_id" => $actual_message_id ? $actual_message_id : "",
            "parent_message_id" => $parent_message_id ? $parent_message_id : "",
            "event_id" => $event_id ? $event_id : "",
            "announcement_id" => $announcement_id ? $announcement_id : ""
        );


        $notification_id = $this->ci->Notifications_model->save($data);
}


 //Send the mail to did't clock team members 
      $email_data = $this->ci->Attendance_model->get_co_details()->result();
        $email_users = array();
       foreach ($email_data as $email_id) {
            $email_users[] = $email_id->user_emails;
        }       
       foreach ($email_users as $email_address) {
       $email_template = $this->ci->Email_templates_model->get_final_template("general_notification");
            $parser_data["EVENT_TITLE"] = "Dear Gems,";
            $parser_data["APP_TITLE"] = 'Attendance Warning';

            $parser_data["NOTIFICATION_URL"] = 'https://gems.gemicates.com';


            $parser_data["EVENT_DETAILS"] = 'You are warned for not closing the Attendance yet in Gems Manager.Your attendance for today will be deleted,if you fail to close the attendance before 23:59';
        

        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["LOGO_URL"] = get_logo_url();
        $message = $this->ci->parser->parse_string($email_template->message, $parser_data, TRUE);

        
        $subject = $this->ci->parser->parse_string($email_template->subject, $parser_data, TRUE);
send_app_mail($email_address, $subject, $message);
          }

    $phone_data = $this->ci->Attendance_model->get_co_details()->result();
        
       foreach ($phone_data as $phone_id) {
            $phone_users = $phone_id->user_phone;
       
          // Authorisation details.
        
    $username = "arunkumar170497@gmail.com";
    $hash = "cf29cb8ca3bbab99f669ff020a5f98f709002232aaf52f5247dc838c54435600";

    // Config variables. Consult http://api.textlocal.in/docs for more info.
    $test = "0";

    // Data for text message. This is the text message data.
    $sender = "TXTLCL"; // This is who the message appears to be from.
    //$numbers = array(918526719794,916380474037);
    //$numbers = $numbers;
     // A single number or a comma-seperated list of numbers
    //$numbers = implode(',', $numbers);

    $message = "You are warned for not closing the attendance!!!";
    // 612 chars or less
    // A single number or a comma-seperated list of numbers
    $message = urlencode($message);
    $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$phone_users."&test=".$test;
    $ch = curl_init('http://api.textlocal.in/send/?');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch); // This is the result from the API
    curl_close($ch);

  }       
    
}
}

private function event_announcement() {
       $time=date("H:i:00");
       $options = array("start_time" => $time);
         $list_data = $this->ci->Events_model->get_details($options)->result();

foreach ($list_data as $code) {
                log_notification("event_started", array("event_id" => $code->id), "0");
        }       


}

private function generate_payslip(){
  /*$time=date("H:i");
         if($time=='10:15'){ */
            /*$payslip_generate_date = get_setting("payslip_generate_date");
            $date=date("d");
         if($date== $payslip_generate_date){*/

//$current_date = date("m");
$db_current_date = $this->ci->load->database('default', TRUE);
        $db_current_date->select('user_id');
        $db_current_date->from('payslip');
        
        $db_current_date->where('deleted',0);
        $db_current_date->like('payslip_date',date('Y-m'));
       
        $querydb_current_date=$db_current_date->get();
        $cur_date = $querydb_current_date->result();


         $payslip_curdate_id= array();
  foreach($cur_date as $row){
     $payslip_curdate_id[] = $row->user_id;
   }
  $curmonth_payslip_user = implode(",",$payslip_curdate_id);
  $curmonth_payslip_user_id = explode(",", 
    $curmonth_payslip_user);







if(empty($cur_date)) {

       $DBUSERS = $this->ci->load->database('default', TRUE);
        $DBUSERS->select("id");
        $DBUSERS->from('users');
        //$DBUSERS->select('id');
        //$DBUSERS->limit(1);
        $DBUSERS->where('deleted',0);
        $DBUSERS->where('user_type','staff');
        $DBUSERS->where('status','active');
        $queryusers=$DBUSERS->get();
        $queryusers->result();  
    foreach ($queryusers->result() as $usersrows)
      {
         $users_payslip_id=$usersrows->id; 

      
$current_date = date("Y-m-d");
$date = date('Y-m-d H:i:s');
$user_salary_info = $this->ci->Payslip_model->get_payslip_user_salary($users_payslip_id);
/*$data[] = array(
                       
                'user_id'     =>  $users_payslip_id,
                'deleted'          => 0,
                'rate'                => 0,
                'total'       =>  0,
                'payslip_date' => $current_date,
                'payslip_time'=>$date,
                "payslip_created_status"=>get_setting("payslip_created_status"),
                "payslip_ot_permission"=>get_setting("ot_permission"),
                "payslip_ot_permission_specific"=>get_setting("ot_permission_specific"),
                "payslip_working_hours"=>get_setting("company_working_hours_for_one_day"),
                "payslip_casual_leave"=>get_setting("maximum_no_of_casual_leave_per_month"),
                "salary"=>$user_salary_info->salary?$user_salary_info->salary:0,
                        );*/
$user_table = $this->ci->Users_model->get_one($users_payslip_id);
$user_country = $user_table->buid;
if($user_country ){
    $user_country_options = array("buid"=> $user_country);
     $get_user_country_info = $this->ci->Branches_model->get_details($user_country_options)->row();

    //OT spefic 
     if($get_user_country_info->ot_permission =="specific"){
     $user_ot_specific_info = $this->ci->Payslip_model->get_country_payslip_user_ot_specific_info_suggestion_cron(
        $users_payslip_id,$get_user_country_info->ot_permission,$get_user_country_info->ot_permission_specific);
     $ot_permission_result = $user_ot_specific_info;
     }else{
       $ot_permission_result = $get_user_country_info->ot_permission;     
      }



     $get_user_country_working_hours = $get_user_country_info->company_working_hours_for_one_day;
    // $get_user_country_ot_permission_specific = $get_user_country_info->ot_permission_specific;
     //$get_user_country_ot_permission = $get_user_country_info->ot_permission;
     $get_user_country_ot_permission = $ot_permission_result;
     $get_user_country_payslip_created_status = $get_user_country_info->payslip_created_status;
     //$get_user_country_maximum_no_of_casual_leave_per_month = $get_user_country_info->maximum_no_of_casual_leave_per_month;
    $get_user_country_maximum_no_of_casual_leave_per_month =  $user_table->annual_leave?$user_table->annual_leave:$get_user_country_info->maximum_no_of_casual_leave_per_month;
     $get_user_country_generate_date = $get_user_country_info->payslip_generate_date;
     $get_user_country_name = $user_table->country;
     $get_user_branch_name = $user_country;
     $get_user_company_name = $user_table->company_id;
     $get_user_holiday_of_week = $get_user_country_info->holiday_of_week;

    

}else{
      
      //OT spefic permission
     if(get_setting("ot_permission") =="specific"){
     $user_ot_specific_info = $this->ci->Payslip_model->get_country_payslip_user_ot_specific_info_suggestion_cron(
        $users_payslip_id,get_setting("ot_permission"),get_setting("ot_permission_specific"));
     $ot_permission_result = $user_ot_specific_info;
     }else{
       $ot_permission_result = get_setting("ot_permission");     
      }



     $get_user_country_working_hours = get_setting("company_working_hours_for_one_day");
     //$get_user_country_ot_permission_specific =get_setting("ot_permission_specific");
    // $get_user_country_ot_permission = get_setting("ot_permission");
      $get_user_country_ot_permission = $ot_permission_result;
     $get_user_country_payslip_created_status = get_setting("payslip_created_status");
     $get_user_country_maximum_no_of_casual_leave_per_month = $user_table->annual_leave?$user_table->annual_leave:get_setting("maximum_no_of_casual_leave_per_month");
      $get_user_country_generate_date = get_setting("payslip_generate_date");
      $get_user_country_name = "";
     $get_user_branch_name = "";
     $get_user_company_name = "";
     $get_user_holiday_of_week = "";
}

if($get_user_country_generate_date==date("d")) { 
$data[] = array(
                       
                'user_id'     =>  $users_payslip_id,
                'deleted'          => 0,
                'rate'                => 0,
                'total'       =>  0,
                'payslip_date' => $current_date,
                'payslip_time'=>$date,
                "payslip_created_status"=>$get_user_country_payslip_created_status,
                "payslip_ot_permission"=>$get_user_country_ot_permission,
               // "payslip_ot_permission_specific"=> $get_user_country_ot_permission_specific,
                "payslip_working_hours"=>$get_user_country_working_hours,
                "payslip_casual_leave"=>$get_user_country_maximum_no_of_casual_leave_per_month,
                "salary"=>$user_salary_info->salary?$user_salary_info->salary:0,
                "country" => $get_user_country_name,
                "branch" => $get_user_branch_name,
                "company" => $get_user_company_name,
                "holiday_of_week"=> $get_user_holiday_of_week,
                        );
}

}

 $this->ci->Payslip_model->insert($data);
$current_date = date("Y-m-d");
$DBPAYUSERS = $this->ci->load->database('default', TRUE);
        $DBPAYUSERS->select('user_id,id');
        $DBPAYUSERS->from('payslip');
        //$DBUSERS->select('id');
        //$DBUSERS->limit(1);
        $DBPAYUSERS->where('deleted',0);
       $DBPAYUSERS->where('DATE(payslip_date)',$current_date);
        //$DBPAYUSERS->where('status','active');
        $queryuserspay=$DBPAYUSERS->get();
       $s = $queryuserspay->result(); 

foreach ($s as $usersrowss)
      {
         $payslip_users_id=$usersrowss->user_id; 
         $payslips_id=$usersrowss->id;

         //upadate new payslip no in payslip table
         //update users net salary and deductions amount
    $get_user_deductions_list= $this->ci->Payslip_model->get_payslip_user_per_month_total_duration($payslips_id);
    $get_user_salary_info = $this->ci->Payslip_model->get_payslip_user_salary($payslip_users_id);
     $get_user_branch_deduction_list =$this->ci->Payslip_model->get_payslip_user_deductions_from_branch_settings($payslips_id);
    $get_user_deductions_amount = $get_user_deductions_list->deductions_amount+$get_user_branch_deduction_list->branch_deductions_amount;
    if($get_user_deductions_amount<0){
       $get_user_deductions_amount = 0;
    }
    $get_user_ot_amount = $get_user_deductions_list->over_time_amount;
    if($get_user_ot_amount<0)
    {
    $get_user_ot_amount =0;
    }
    
    $get_user_net_salary = ($get_user_salary_info->salary-$get_user_deductions_amount);
    if($get_user_net_salary<0){
       $get_user_net_salary = 0+$get_user_ot_amount;
    }else if($get_user_net_salary>=0){
     $get_user_net_salary =number_format(round($get_user_net_salary+$get_user_ot_amount), 2, ".", "");
    } 
    //end update users net salary and deductions amount 
$paypreficDB4 = $this->ci->load->database('default', TRUE);
         $paypreficDB4->where('id', $payslips_id);
         $paypreficDB4->update('payslip', array('payslip_no' => get_payslip_id($payslips_id),'rate' => $get_user_deductions_amount,'over_time_amount'=>$get_user_ot_amount,'total'=>$get_user_net_salary,'no_of_paid_leave'=>NULL));
 //end update new payslip in payslip table

$current_date = date("Y-m-d");
$datas[] = array(
                       
                    'user_id' => $payslip_users_id,
                    'deleted'          => 0,
                    'payslip_id' => $payslips_id,
                    'payslip_date' => $current_date,
                    );
log_notification("generate_employee_payslip", array("payslip_id" => $payslips_id, "to_user_id" => $payslip_users_id),"0");
}
$this->ci->Payslip_earnings_model->insert($datas);
$this->ci->Payslip_attendance_model->insert($datas);

}else if(!empty($cur_date)) {

       $DBUSERS = $this->ci->load->database('default', TRUE);
        $DBUSERS->select("id");
        $DBUSERS->from('users');
        //$DBUSERS->select('id');
        //$DBUSERS->limit(1);
        $DBUSERS->where('deleted',0);
        $DBUSERS->where('user_type','staff');
        $DBUSERS->where('status','active');
        $DBUSERS->where_not_in('id', 
            $curmonth_payslip_user_id);
        $queryusers=$DBUSERS->get();
        $queryusers->result();  
    foreach ($queryusers->result() as $usersrows)
      {
         $users_payslip_id=$usersrows->id; 

      
$current_date = date("Y-m-d");
$date = date('Y-m-d H:i:s');
$user_salary_info = $this->ci->Payslip_model->get_payslip_user_salary($users_payslip_id);
/*$data[] = array(
                       
                'user_id'     =>  $users_payslip_id,
                'deleted'          => 0,
                'rate'                => 0,
                'total'       =>  0,
                'payslip_date' => $current_date,
                'payslip_time'=>$date,
                "payslip_created_status"=>get_setting("payslip_created_status"),
                "payslip_ot_permission"=>get_setting("ot_permission"),
                "payslip_ot_permission_specific"=>get_setting("ot_permission_specific"),
                "payslip_working_hours"=>get_setting("company_working_hours_for_one_day"),
                "payslip_casual_leave"=>get_setting("maximum_no_of_casual_leave_per_month"),
                "salary"=>$user_salary_info->salary?$user_salary_info->salary:0,
                        );*/
                        $user_table = $this->ci->Users_model->get_one($users_payslip_id);
$user_country = $user_table->buid;
if($user_country ){
    $user_country_options = array("buid"=> $user_country);
     $get_user_country_info = $this->ci->Branches_model->get_details($user_country_options)->row();

    //OT spefic 
     if($get_user_country_info->ot_permission =="specific"){
     $user_ot_specific_info = $this->ci->Payslip_model->get_country_payslip_user_ot_specific_info_suggestion_cron(
        $users_payslip_id,$get_user_country_info->ot_permission,$get_user_country_info->ot_permission_specific);
     $ot_permission_result = $user_ot_specific_info;
     }else{
       $ot_permission_result = $get_user_country_info->ot_permission;     
      }

     $get_user_country_working_hours = $get_user_country_info->company_working_hours_for_one_day;
    // $get_user_country_ot_permission_specific = $get_user_country_info->ot_permission_specific;
     //$get_user_country_ot_permission = $get_user_country_info->ot_permission;
     $get_user_country_ot_permission = $ot_permission_result;
     $get_user_country_payslip_created_status = $get_user_country_info->payslip_created_status;
     $get_user_country_maximum_no_of_casual_leave_per_month = $user_table->annual_leave?$user_table->annual_leave:$get_user_country_info->maximum_no_of_casual_leave_per_month;
     $get_user_country_generate_date = $get_user_country_info->payslip_generate_date;
      $get_user_country_name = $user_table->country;
     $get_user_branch_name = $user_country;
     $get_user_company_name = $user_table->company_id;
      $get_user_holiday_of_week = $get_user_country_info->holiday_of_week;

    

}else{

     //OT spefic permission
     if(get_setting("ot_permission") =="specific"){
     $user_ot_specific_info = $this->ci->Payslip_model->get_country_payslip_user_ot_specific_info_suggestion_cron(
        $users_payslip_id,get_setting("ot_permission"),get_setting("ot_permission_specific"));
     $ot_permission_result = $user_ot_specific_info;
     }else{
       $ot_permission_result = get_setting("ot_permission");     
      }


     $get_user_country_working_hours = get_setting("company_working_hours_for_one_day");
     //$get_user_country_ot_permission_specific =get_setting("ot_permission_specific");
     //$get_user_country_ot_permission = get_setting("ot_permission");
      $get_user_country_ot_permission = $ot_permission_result;
     $get_user_country_payslip_created_status = get_setting("payslip_created_status");
     $get_user_country_maximum_no_of_casual_leave_per_month = $user_table->annual_leave?$user_table->annual_leave:get_setting("maximum_no_of_casual_leave_per_month");
      $get_user_country_generate_date = get_setting("payslip_generate_date");
      $get_user_country_name = "";
     $get_user_branch_name = "";
     $get_user_company_name = "";
      $get_user_holiday_of_week = "";
}

if($get_user_country_generate_date==date("d")) { 
$data[] = array(
                       
                'user_id'     =>  $users_payslip_id,
                'deleted'          => 0,
                'rate'                => 0,
                'total'       =>  0,
                'payslip_date' => $current_date,
                'payslip_time'=>$date,
                "payslip_created_status"=>$get_user_country_payslip_created_status,
                "payslip_ot_permission"=>$get_user_country_ot_permission,
               // "payslip_ot_permission_specific"=> $get_user_country_ot_permission_specific,
                "payslip_working_hours"=>$get_user_country_working_hours,
                "payslip_casual_leave"=>$get_user_country_maximum_no_of_casual_leave_per_month,
                "salary"=>$user_salary_info->salary?$user_salary_info->salary:0,
                "country" => $get_user_country_name,
                "branch" => $get_user_branch_name,
                "company" => $get_user_company_name,
                "holiday_of_week"=> $get_user_holiday_of_week,
                        );
}

}

 $this->ci->Payslip_model->insert($data);
 //payslip earnings table current month user 
$db_current_dates = $this->ci->load->database('default', TRUE);
        $db_current_dates->select('payslip_id');
        $db_current_dates->from('payslip_earnings');
//$db_current_dates->join('payslip', 'payslip.id = payslip_earnings.payslip_id','left');
        $db_current_dates->where('deleted',0);
        $db_current_dates->like('payslip_date',date('Y-m'));
       
        $querydb_current_dates=$db_current_dates->get();
        $cur_dates = $querydb_current_dates->result();

         
         $payslip_curdate_ids= array();
  foreach($cur_dates as $rows){
     $payslip_curdate_ids[] = $rows->payslip_id;

   }
  $curmonth_payslip_users = implode(",",$payslip_curdate_ids);
  $curmonth_payslip_user_ids = explode(",",$curmonth_payslip_users);
  
$current_date = date("Y-m-d");


$DBPAYUSERS = $this->ci->load->database('default', TRUE);
        $DBPAYUSERS->select('user_id,id');
        $DBPAYUSERS->from('payslip');
        //$DBUSERS->select('id');
        //$DBUSERS->limit(1);
        $DBPAYUSERS->where('deleted',0);
        $DBPAYUSERS->where('DATE(payslip_date)',$current_date);
//$DBPAYUSERS->where_not_in('user_id',$curmonth_payslip_user_ids);
$DBPAYUSERS->where_not_in('id',$curmonth_payslip_user_ids);
        //$DBPAYUSERS->where('status','active');
        $queryuserspay=$DBPAYUSERS->get();
       $s = $queryuserspay->result(); 

foreach ($s as $usersrowss)
      {
         $payslip_users_id=$usersrowss->user_id; 
         $payslips_id=$usersrowss->id;

         //upadate new payslip no in payslip table
         $get_user_deductions_list= $this->ci->Payslip_model->get_payslip_user_per_month_total_duration($payslips_id);
    $get_user_salary_info = $this->ci->Payslip_model->get_payslip_user_salary($payslip_users_id);
     $get_user_branch_deduction_list =$this->ci->Payslip_model->get_payslip_user_deductions_from_branch_settings($payslips_id);
    $get_user_deductions_amount = $get_user_deductions_list->deductions_amount+$get_user_branch_deduction_list->branch_deductions_amount;
    if($get_user_deductions_amount<0){
       $get_user_deductions_amount = 0;
    }
    $get_user_ot_amount = $get_user_deductions_list->over_time_amount;
    if($get_user_ot_amount<0)
    {
    $get_user_ot_amount =0;
    }
    
    $get_user_net_salary = ($get_user_salary_info->salary-$get_user_deductions_amount);
    if($get_user_net_salary<0){
       $get_user_net_salary = 0+$get_user_ot_amount;
    }else if($get_user_net_salary>=0){
     $get_user_net_salary =number_format(round($get_user_net_salary+$get_user_ot_amount), 2, ".", "");
    } 
    //end update users net salary and deductions amount  
$paypreficDB4 = $this->ci->load->database('default', TRUE);
         $paypreficDB4->where('id', $payslips_id);
         $paypreficDB4->update('payslip', array('payslip_no' => get_payslip_id($payslips_id),'rate' => $get_user_deductions_amount,'over_time_amount'=>$get_user_ot_amount,'total'=>$get_user_net_salary,'no_of_paid_leave'=>NULL));
 //end update new payslip in payslip table

$current_date = date("Y-m-d");
$datas[] = array(
                       
                    'user_id' => $payslip_users_id,
                     'deleted'          => 0,
                    'payslip_id' => $payslips_id,
                    'payslip_date' => $current_date,
                    );
log_notification("generate_employee_payslip", array("payslip_id" => $payslips_id, "to_user_id" => $payslip_users_id),"0");
/*$notification_data = array(
            "user_id" => 1,
            "description" => "",
            "created_at" => get_current_utc_time(),
            "notify_to" => $payslip_users_id,
            "payslip_id" => $payslips_id,
            "read_by" => "",
            "event" => 'generate_employee_payslip',
            "project_id" => $project_id ? $project_id : "",
            "task_id" => $task_id ? $task_id : "",
            "project_comment_id" => $project_comment_id ? $project_comment_id : "",
            "ticket_id" => $ticket_id ? $ticket_id : "",
            "ticket_comment_id" => $ticket_comment_id ? $ticket_comment_id : "",
            "project_file_id" => $project_file_id ? $project_file_id : "",
            "leave_id" => $leave_id ? $leave_id : "",
            "post_id" => $post_id ? $post_id : "",
            "to_user_id" => $payslip_users_id ? $payslip_users_id : "",
            "activity_log_id" => $activity_log_id ? $activity_log_id : "",
            "client_id" => $client_id ? $client_id : "",
            "invoice_payment_id" => $invoice_payment_id ? $invoice_payment_id : "",
            "invoice_id" => $invoice_id ? $invoice_id : "",
            "estimate_request_id" => $estimate_request_id ? $estimate_request_id : "",
            "estimate_id" => $estimate_id ? $estimate_id : "",
            "actual_message_id" => $actual_message_id ? $actual_message_id : "",
            "parent_message_id" => $parent_message_id ? $parent_message_id : "",
            "event_id" => $event_id ? $event_id : "",
            "announcement_id" => $announcement_id ? $announcement_id : ""
        );


        $notification_id = $this->ci->Notifications_model->save($notification_data);*/
}
$this->ci->Payslip_earnings_model->insert($datas);
$this->ci->Payslip_attendance_model->insert($datas);
   
   }
/* }*/
/*}*/


} 


 //Purchase Order due reminder 

      private function send_purchase_order_due_pre_reminder() {

        $reminder_date = get_setting("send_purchase_order_due_pre_reminder");

        if ($reminder_date) {

            //prepare invoice due date accroding to the setting
            $start_date = add_period_to_date($this->today, get_setting("send_purchase_order_due_pre_reminder"), "days");

            $purchase_orders = $this->ci->Purchase_orders_model->get_details(array(
                        "status" => array("not_paid","partially_paid"), //find all purchase_orders which are not paid yet but due date not expired
                        "start_date" => $start_date,
                        "end_date" => $start_date, //both should be same
                        "exclude_due_reminder_date" => $this->today //don't find purchase_orders which reminder already sent today
                    ))->result();
            //print_r($purchase_orders);

            foreach ($purchase_orders as $purchase_order) {
                log_notification("purchase_order_due_reminder_before_due_date", array("purchase_order_id" => $purchase_order->id), "0");
            }
        }
    }
         
    private function send_purchase_order_due_after_reminder() {

        $reminder_date = get_setting("send_purchase_order_due_after_reminder");

        if ($reminder_date) {

            //prepare invoice due date accroding to the setting
            $start_date = subtract_period_from_date($this->today, get_setting("send_purchase_order_due_after_reminder"), "days");

            $purchase_orders = $this->ci->Purchase_orders_model->get_details(array(
                        "status" => "overdue", //find all purchase_orders which are not paid yet but due date not expired
                        "start_date" => $start_date,
                        "end_date" => $start_date, //both should be same
                        "exclude_due_reminder_date" => $this->today //don't find purchase_orders which reminder already sent today
                    ))->result();
            //print_r($purchase_orders);

            foreach ($purchase_orders as $purchase_order) {
                log_notification("purchase_order_overdue_reminder", array("purchase_order_id" => $purchase_order->id), "0");
            }
        }
    }

    //repeat purchase order reminder notifications
     private function send_purchase_order_repeat_due_after_reminder() {
$repeat = get_setting("purchase_order_due_repeat");
if($repeat) {
        
$time=date("H:i");
         if($time=='10:00'){ 
            

            $purchase_orders = $this->ci->Purchase_orders_model->get_details(array(
                        "status" => "overdue", //find all purchase_orders which are not paid yet but due date not expired
                       // "start_date" => $start_date,
                        //"end_date" => $start_date, //both should be same
                        "exclude_due_reminder_date" => $this->today //don't find purchase_orders which reminder already sent today
                    ))->result();
            //print_r($purchase_orders);

            foreach ($purchase_orders as $purchase_order) {
                log_notification("purchase_order_overdue_reminder", array("purchase_order_id" => $purchase_order->id), "0");
            }
        }
    }
      
}


    //Purchase Order due reminder 

      private function send_purchase_order_repeat_due_pre_reminder() {

         $repeat = get_setting("purchase_order_due_repeat");
if($repeat) {
       
       $time=date("H:i");
         if($time=='10:00'){ 

            //$start_date = $this->today;

            $purchase_orders = $this->ci->Purchase_orders_model->get_details(array(
                        "status" => array("not_paid","partially_paid"), //find all purchase_orders which are not paid yet but due date not expired
                        //"start_date" => $start_date,
                        //"end_date" => $start_date, //both should be same
                        "exclude_due_reminder_date" => $this->today //don't find purchase_orders which reminder already sent today
                    ))->result();
            //print_r($purchase_orders);

            foreach ($purchase_orders as $purchase_order) {
                log_notification("purchase_order_due_reminder_before_due_date", array("purchase_order_id" => $purchase_order->id), "0");
            }
      }
  }
      
}


//project  timer


private function project_timer_warning() {
       $time=date("H:i");
         if($time=='23:00'){      
         $list_data = $this->ci->Timesheets_model->get_co_details()->result();
        $gst_code_dropdown = array();
foreach ($list_data as $code) {
            $users[] = $code->user_id;
        }       
          foreach ($users as $id) {

         $data = array(
            "user_id" => 0,
            "description" => "",
            "created_at" => get_current_utc_time(),
            "notify_to" => $id,
            "read_by" => "",
            "event" => 'project_timer_warning',
            "project_id" => $project_id ? $project_id : "",
            "task_id" => $task_id ? $task_id : "",
            "project_comment_id" => $project_comment_id ? $project_comment_id : "",
            "ticket_id" => $ticket_id ? $ticket_id : "",
            "ticket_comment_id" => $ticket_comment_id ? $ticket_comment_id : "",
            "project_file_id" => $project_file_id ? $project_file_id : "",
            "leave_id" => $leave_id ? $leave_id : "",
            "post_id" => $post_id ? $post_id : "",
            "to_user_id" => $to_user_id ? $to_user_id : "",
            "activity_log_id" => $activity_log_id ? $activity_log_id : "",
            "client_id" => $client_id ? $client_id : "",
            "invoice_payment_id" => $invoice_payment_id ? $invoice_payment_id : "",
            "invoice_id" => $invoice_id ? $invoice_id : "",
            "estimate_request_id" => $estimate_request_id ? $estimate_request_id : "",
            "estimate_id" => $estimate_id ? $estimate_id : "",
            "actual_message_id" => $actual_message_id ? $actual_message_id : "",
            "parent_message_id" => $parent_message_id ? $parent_message_id : "",
            "event_id" => $event_id ? $event_id : "",
            "announcement_id" => $announcement_id ? $announcement_id : ""
        );


        $notification_id = $this->ci->Notifications_model->save($data);
}

}
}


private function delete_project_timer() {
   $time=date("H:i");
                
                if($time=='23:30'){
        $list_data = $this->ci->Timesheets_model->get_co_details()->result();
        $gst_code_dropdown = array();
foreach ($list_data as $code) {
            $gst_code_dropdown[] = $code->id;
        }
        //log_notification("new_announcement_created", array("announcement_id" => 44));
        foreach ($gst_code_dropdown as $id) {
            $this->ci->Timesheets_model->update_clock_out($id);
        }
    }
    }
//end project timer



/*// project timer timezone alert
    private function project_timer_warning() {
       
             
         $list_data = $this->ci->Timesheets_model->get_co_details()->result();
         $users = array();
        $users_emails =array();
foreach ($list_data as $code) {
    if($code->user_timezone && $code->user_id){
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone($code->user_timezone));
        $now = $d->format("H:i");
        if($now == "23:00") { 
            $users[] = $code->user_id;
            $users_emails[] = $code->user_emails;

            

            
        }
        }
        }       
          foreach ($users as $id) {

         $data = array(
            "user_id" => 0,
            "description" => "",
            "created_at" => get_current_utc_time(),
            "notify_to" => $id,
            "read_by" => "",
            "event" => 'project_timer_warning',
            "project_id" => $project_id ? $project_id : "",
            "task_id" => $task_id ? $task_id : "",
            "project_comment_id" => $project_comment_id ? $project_comment_id : "",
            "ticket_id" => $ticket_id ? $ticket_id : "",
            "ticket_comment_id" => $ticket_comment_id ? $ticket_comment_id : "",
            "project_file_id" => $project_file_id ? $project_file_id : "",
            "leave_id" => $leave_id ? $leave_id : "",
            "post_id" => $post_id ? $post_id : "",
            "to_user_id" => $to_user_id ? $to_user_id : "",
            "activity_log_id" => $activity_log_id ? $activity_log_id : "",
            "client_id" => $client_id ? $client_id : "",
            "invoice_payment_id" => $invoice_payment_id ? $invoice_payment_id : "",
            "invoice_id" => $invoice_id ? $invoice_id : "",
            "estimate_request_id" => $estimate_request_id ? $estimate_request_id : "",
            "estimate_id" => $estimate_id ? $estimate_id : "",
            "actual_message_id" => $actual_message_id ? $actual_message_id : "",
            "parent_message_id" => $parent_message_id ? $parent_message_id : "",
            "event_id" => $event_id ? $event_id : "",
            "announcement_id" => $announcement_id ? $announcement_id : ""
        );


        $notification_id = $this->ci->Notifications_model->save($data);
}
foreach($users_emails as $email){
$email_template = $this->ci->Email_templates_model->get_final_template("general_notification");
            $parser_data["EVENT_TITLE"] = "Dear Gems,";
            $parser_data["APP_TITLE"] = 'Project Warning';

            $parser_data["NOTIFICATION_URL"] = 'https://gems.gemicates.com';


            $parser_data["EVENT_DETAILS"] = 'You are warned for not closing the Project timer yet in Gems Manager.Your attendance for today will be deleted,if you fail to close the attendance before 23:59';
        

        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["LOGO_URL"] = get_logo_url();
        $message = $this->ci->parser->parse_string($email_template->message, $parser_data, TRUE);

        
        $subject = $this->ci->parser->parse_string($email_template->subject, $parser_data, TRUE);
        send_app_mail($email, $subject, $message);
    }


}


private function delete_project_timer() {
   
        $list_data = $this->ci->Timesheets_model->get_co_details()->result();
        $gst_code_dropdown = array();
foreach ($list_data as $code) {
    if($code->user_timezone && $code->user_id){
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone($code->user_timezone));
        $now = $d->format("H:i");
        if($now == "23:30") { 
            $gst_code_dropdown[] = $code->id;
        }
        }
        }
        //log_notification("new_announcement_created", array("announcement_id" => 44));
        foreach ($gst_code_dropdown as $id) {
            $this->ci->Timesheets_model->update_clock_out($id);
        }
  
    }

//end project timer time zone alert concept

//attendance timezone alert 
    private function attendance_warning() {
           
         $list_data = $this->ci->Attendance_model->get_co_details()->result();
        $users = array();
        $users_emails =array();
        foreach ($list_data as $code) {
    if($code->user_timezone && $code->user_id){
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone($code->user_timezone));
        $now = $d->format("H:i");
        if($now == "23:00") { 
            $users[] = $code->user_id;
            $users_emails[] = $code->user_emails;

        }
        }
        }
          foreach ($users as $id) {

         $data = array(
            "user_id" => 0,
            "description" => "",
            "created_at" => get_current_utc_time(),
            "notify_to" => $id,
            "read_by" => "",
            "event" => 'attendance_warning',
            "project_id" => $project_id ? $project_id : "",
            "task_id" => $task_id ? $task_id : "",
            "project_comment_id" => $project_comment_id ? $project_comment_id : "",
            "ticket_id" => $ticket_id ? $ticket_id : "",
            "ticket_comment_id" => $ticket_comment_id ? $ticket_comment_id : "",
            "project_file_id" => $project_file_id ? $project_file_id : "",
            "leave_id" => $leave_id ? $leave_id : "",
            "post_id" => $post_id ? $post_id : "",
            "to_user_id" => $to_user_id ? $to_user_id : "",
            "activity_log_id" => $activity_log_id ? $activity_log_id : "",
            "client_id" => $client_id ? $client_id : "",
            "invoice_payment_id" => $invoice_payment_id ? $invoice_payment_id : "",
            "invoice_id" => $invoice_id ? $invoice_id : "",
            "estimate_request_id" => $estimate_request_id ? $estimate_request_id : "",
            "estimate_id" => $estimate_id ? $estimate_id : "",
            "actual_message_id" => $actual_message_id ? $actual_message_id : "",
            "parent_message_id" => $parent_message_id ? $parent_message_id : "",
            "event_id" => $event_id ? $event_id : "",
            "announcement_id" => $announcement_id ? $announcement_id : ""
        );


        $notification_id = $this->ci->Notifications_model->save($data);
}

foreach($users_emails as $email){
$email_template = $this->ci->Email_templates_model->get_final_template("general_notification");
            $parser_data["EVENT_TITLE"] = "Dear Gems,";
            $parser_data["APP_TITLE"] = 'Attendance Warning';

            $parser_data["NOTIFICATION_URL"] = 'https://gems.gemicates.com';


            $parser_data["EVENT_DETAILS"] = 'You are warned for not closing the Attendance timer yet in Gems Manager.Your attendance for today will be deleted,if you fail to close the attendance before 23:59';
        

        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["LOGO_URL"] = get_logo_url();
        $message = $this->ci->parser->parse_string($email_template->message, $parser_data, TRUE);

        
        $subject = $this->ci->parser->parse_string($email_template->subject, $parser_data, TRUE);
        send_app_mail($email, $subject, $message);
    }

}


private function delete_attendance() {
    
                
                
        $list_data = $this->ci->Attendance_model->get_co_details()->result();
        $gst_code_dropdown = array();

        foreach ($list_data as $code) {
    if($code->user_timezone && $code->user_id){
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone($code->user_timezone));
        $now = $d->format("H:i");
        if($now == "23:30") { 
            $gst_code_dropdown[] = $code->id;
        }
        }
        }
        //log_notification("new_announcement_created", array("announcement_id" => 44));
        foreach ($gst_code_dropdown as $id) {
            $this->ci->Attendance_model->update_clock_out($id);
        }
    
    }
    //end attendance timezone alert*/



}
