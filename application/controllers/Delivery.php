<?php
  
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery extends MY_Controller {

    function __construct() {
        parent::__construct(); 
        $this->init_permission_checker("delivery");
        //$this->access_only_allowed_members();
    } 

    /* load estimate list view */
 
    function index() {
        $this->check_module_availability("module_delivery");
       // $view_data['can_request_estimate'] = false;

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("delivery", $this->login_user->is_admin, $this->login_user->user_type); 

        if ($this->login_user->is_admin == "1")
        {
            $this->template->rander("delivery/index", $view_data);
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("delivery/index", $view_data);
        }else {


        $this->template->rander("delivery/index", $view_data);
    } 
    }

    //load the yearly view of estimate list
    function yearly() {
        $this->load->view("estimates/yearly_estimates");
    }

    /* load new estimate modal */

    function modal_form() { 
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $client_id = $this->input->post('client_id');
        $view_data['model_info'] = $this->Delivery_model->get_one($this->input->post('id'));


        $project_client_id = $client_id;
        if ($view_data['model_info']->client_id) {
            $project_client_id = $view_data['model_info']->client_id;
        }

        //make the drodown lists
        //$view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
         $view_data['clients_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"),'id',array("user_type" => "staff"));
         $view_data['rm_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"),'id',array("user_type" => "resource"));
         $view_data['client_id'] = $client_id;
         $view_data['org_clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));
         $view_data['dispatched_through_dropdown'] = array("" => "-") + $this->Mode_of_dispatch_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
         $view_data['client_id'] = $client_id;
         $view_data['voucher_dropdown'] = array("0" => "-") + $this->Invoices_model->get_dropdown_list(array("invoice_no"), "id", array("deleted" => '0'));
         $view_data['vouchers_dropdown'] = array("0" => "-") + $this->Estimates_model->get_dropdown_list(array("estimate_no"), "id", array("deleted" => '0'));
         $view_data['dc_types_dropdown'] = array("" => "-") + $this->Dc_types_model->get_dropdown_list(array("title"), "id", array( "deleted" => 0 ,"status" => "active"));
         $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("estimates", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
         $this->load->view('delivery/modal_form', $view_data);
    }

    /* add or edit an estimate */

    function save() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_date" => "required",
            "valid_until" => "required"
        ));

        $client_id = $this->input->post('estimate_client_id');
        $id = $this->input->post('id');
        
    $outsource = $this->input->post('estimate_client_idss');
$member=$this->input->post('member_type');
if($member=='others'){
        $estimate_data = array(
            "client_id" => 0,
            "estimate_date" => $this->input->post('estimate_date'),
            "valid_until" => $this->input->post('valid_until'),
            "import_from" => $this->input->post('import_from'),
            "invoice_no" => $this->input->post('invoice_no'),
            "proformainvoice_no" => $this->input->post('proformainvoice_no'),
            "dc_type_id" => $this->input->post('dc_type_id'),
            "demo_period" => $this->input->post('demo_period'),
        "invoice_for_dc" =>$this->input->post('invoice_for_dc'),
        "invoice_date" => $this->input->post('invoice_date'),
            "note" => $this->input->post('estimate_note'),
            "f_name" => $this->input->post('first_name'),
            "l_name" => $this->input->post('last_name'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
            "state" => $this->input->post('state'),
            "country" => $this->input->post('country'),
            "zip" => $this->input->post('zip'),
           "member_type" => $this->input->post('member_type'),
                       "buyers_order_no" => $this->input->post('buyers_order_no'),
            "buyers_order_date" => $this->input->post('buyers_order_date'),
"dispatched_through" =>$this->input->post('dispatched_through'),
            "lc_no" => $this->input->post('lc_no'),
            "lc_date" => $this->input->post('lc_date'),
            "dispatch_docket" => $this->input->post('dispatch_docket'),
            "dispatch_name" => $this->input->post('dispatch_name'),
            "waybill_no" => $this->input->post('waybill_no'),
            "invoice_client_id" => $this->input->post('invoice_client_id'),
            "dispatch_date" => $this->input->post('dispatch_date'),
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
            "delivery_address" => $this->input->post('delivery_address'),
             "delivery_address_state" => $this->input->post('delivery_address_state'),
              "delivery_address_city" => $this->input->post('delivery_address_city'),
              "delivery_address_country" => $this->input->post('delivery_address_country'),
               "delivery_address_zip" => $this->input->post('delivery_address_zip'),
               "invoice_delivery_address" => $this->input->post('invoice_delivery_address') ? 1 : 0

        );
}else if($member=='om'){
        $estimate_data = array(
            "client_id" => $outsource,
            "estimate_date" => $this->input->post('estimate_date'),
            "valid_until" => $this->input->post('valid_until'),
            "import_from" => $this->input->post('import_from'),
            "invoice_no" => $this->input->post('invoice_no'),
            "proformainvoice_no" => $this->input->post('proformainvoice_no'),
            "dc_type_id" => $this->input->post('dc_type_id'),
"demo_period" => $this->input->post('demo_period'),
            "note" => $this->input->post('estimate_note'),
                       "member_type" => $this->input->post('member_type'),
            "invoice_for_dc" =>$this->input->post('invoice_for_dc'),
        "invoice_date" => $this->input->post('invoice_date'),           
                       "buyers_order_no" => $this->input->post('buyers_order_no'),
            "buyers_order_date" => $this->input->post('buyers_order_date'),
"dispatched_through" =>$this->input->post('dispatched_through'),
            "lc_no" => $this->input->post('lc_no'),
            "lc_date" => $this->input->post('lc_date'),
            "dispatch_docket" => $this->input->post('dispatch_docket'),
            "dispatch_name" => $this->input->post('dispatch_name'),
            "waybill_no" => $this->input->post('waybill_no'),
            "invoice_client_id" => $this->input->post('invoice_client_id'),
            "dispatch_date" => $this->input->post('dispatch_date'),
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
            "delivery_address" => $this->input->post('delivery_address'),
             "delivery_address_state" => $this->input->post('delivery_address_state'),
              "delivery_address_city" => $this->input->post('delivery_address_city'),
              "delivery_address_country" => $this->input->post('delivery_address_country'),
               "delivery_address_zip" => $this->input->post('delivery_address_zip'),
               "invoice_delivery_address" => $this->input->post('invoice_delivery_address') ? 1 : 0

        );
}else if($member=='tm'){
        $estimate_data = array(
            "client_id" => $client_id,
            "estimate_date" => $this->input->post('estimate_date'),
            "valid_until" => $this->input->post('valid_until'),
            "import_from" => $this->input->post('import_from'),
            "invoice_no" => $this->input->post('invoice_no'),
            "proformainvoice_no" => $this->input->post('proformainvoice_no'),
            "dc_type_id" => $this->input->post('dc_type_id'),
"demo_period" => $this->input->post('demo_period'),
            "note" => $this->input->post('estimate_note'),
                       "member_type" => $this->input->post('member_type'),
                       "buyers_order_no" => $this->input->post('buyers_order_no'),
                       "invoice_for_dc" =>$this->input->post('invoice_for_dc'),
        "invoice_date" => $this->input->post('invoice_date'),
            "buyers_order_date" => $this->input->post('buyers_order_date'),
"dispatched_through" =>$this->input->post('dispatched_through'),
            "lc_no" => $this->input->post('lc_no'),
            "lc_date" => $this->input->post('lc_date'),
            "dispatch_docket" => $this->input->post('dispatch_docket'),
            "dispatch_name" => $this->input->post('dispatch_name'),
            "waybill_no" => $this->input->post('waybill_no'),
            "invoice_client_id" => $this->input->post('invoice_client_id'),
            "dispatch_date" => $this->input->post('dispatch_date'),
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
            "delivery_address" => $this->input->post('delivery_address'),
             "delivery_address_state" => $this->input->post('delivery_address_state'),
              "delivery_address_city" => $this->input->post('delivery_address_city'),
              "delivery_address_country" => $this->input->post('delivery_address_country'),
               "delivery_address_zip" => $this->input->post('delivery_address_zip'),
               "invoice_delivery_address" => $this->input->post('invoice_delivery_address') ? 1 : 0
        );
}

if($id){
    // check the invoice no already exits  update    
        $estimate_data["dc_no"] = $this->input->post('dc_no');
        if ($this->Delivery_model->is_estimate_no_exists($estimate_data["dc_no"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('dc_no_already')));
                exit();
            }
}
// create new invoice no check already  exsits 
if (!$id) {
$get_last_estimate_id = $this->Delivery_model->get_last_estimate_id_exists();
$estimate_no_last_id = ($get_last_estimate_id->id+1);
$estimate_prefix = get_delivery_id($estimate_no_last_id);
 
        if ($this->Delivery_model->is_estimate_no_exists($estimate_prefix)) {
                echo json_encode(array("success" => false, 'message' => $estimate_prefix." ".lang('dc_no_already')));
                exit();
            }
}

//end  create new invoice no check already  exsits

        $estimate_id = $this->Delivery_model->save($estimate_data, $id);
 $options = array(
            "estimate_id" =>  $estimate_id,
          );
//         $ve_id = $this->Delivery_items_model->get_details($options)->result();
//         if($ve_id){
//         foreach($ve_id as $delete_id){
// $s=  $this->Delivery_items_model->delete($delete_id->id);
// }}
        //$d_id = $this->Delivery_model->get_id()->row();
                $import = $this->input->post('import_from');

        if($import=='inv'){
        $options = array("invoice_id" => $this->input->post('invoice_no'));
        $list_data = $this->Invoice_items_model->get_details($options)->result();
        
        foreach ($list_data as $key) {
$estimate_item_data = array(
            "estimate_id" =>$estimate_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->quantity ,
            "is_tool"=>1,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate 
            );

        $estimate_item_id = $this->Delivery_items_model->save($estimate_item_data);
}
    }
    if($import=='pi'){
        $options = array("estimate_id" => $this->input->post('proformainvoice_no'));
        $list_data = $this->Estimate_items_model->get_details($options)->result();
        
        foreach ($list_data as $key) {
$estimate_item_data = array(
            "estimate_id" =>$estimate_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->quantity ,
            "is_tool"=>1,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate 
            );

        $estimate_item_id = $this->Delivery_items_model->save($estimate_item_data);

    }
    }
    
        if ($estimate_id) {


            // Save the new invoice no 
           if (!$id) {
               $estimate_prefix = get_delivery_id($estimate_id);
               $estimate_prefix_data = array(
                   
                    "dc_no" => $estimate_prefix
                );
                $estimate_prefix_id = $this->Delivery_model->save($estimate_prefix_data, $estimate_id);
            }
// End  the new invoice no 

            save_custom_fields("delivery", $estimate_id, $this->login_user->is_admin, $this->login_user->user_type);
            log_notification("delivery_chellan_submitted", array("dc_id" => $estimate_id));

            echo json_encode(array("success" => true, "data" => $this->_row_data($estimate_id), 'id' => $estimate_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //update estimate status
    function update_delivery_status($estimate_id, $status) {
        if ($estimate_id && $status) {
            $estmate_info = $this->Delivery_model->get_one($estimate_id);
            //$this->access_only_allowed_members_or_client_contact($estmate_info->client_id);


            if ($this->login_user->user_type == "client") {
                //updating by client
                //client can only update the status once and the value should be either accepted or declined
                if ($estmate_info->status == "sent" && ($status == "accepted" || $status == "declined")) {

                    $estimate_data = array("status" => $status);
                    $estimate_id = $this->Estimates_model->save($estimate_data, $estimate_id);

                    //create notification
                    if ($status == "accepted") {
                        log_notification("estimate_accepted", array("estimate_id" => $estimate_id));
                    } else if ($status == "declined") {
                        log_notification("estimate_rejected", array("estimate_id" => $estimate_id));
                    }
                }
            } else {
                //updating by team members

                if ($status == "draft" || $status == "given" || $status == "received"|| $status == "sold"|| $status == "ret_sold"|| $status == "approve_ret_sold"|| $status == "modified") {
                    if ($status == "given"){
                    $estimate_data = array("status" => $status,"delivered_date" => date("Y/m/d"));
                    }
                    if ($status == "received"){
                    $estimate_data = array("status" => $status,"received_date" => date("Y/m/d"));
                    }
                    if ($status == "sold"){
                    $estimate_data = array("status" => $status,"received_date" => date("Y/m/d"));
                    }
                    if ($status == "ret_sold"){
                    $estimate_data = array("status" => $status,"received_date" => date("Y/m/d"));
                    }if ($status == "approve_ret_sold"){
                    $estimate_data = array("status" => $status,"received_date" => date("Y/m/d"));
                    }if ($status == "modified"){
                    $estimate_data = array("status" => $status,"received_date" => date("Y/m/d"));
                    }
                    $estimate_id = $this->Delivery_model->save($estimate_data, $estimate_id);
                    if($status=="given"){
                    $DB1 = $this->load->database('default', TRUE);
                   $DB1->select ("title,quantity");
                   $DB1->from('delivery_items');
                   $DB1->where('estimate_id' , $estimate_id);
                   $DB1->where('deleted' , '0');
                  $query1=$DB1->get();
 $query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->quantity;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('quantity', "quantity-'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('tools');  
        }
        foreach ($query1->result() as $rows)
    {
    $b=$rows->quantity;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('stock', "stock-'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('items');  
        }
       }if($status=="received"){
                    $DB1 = $this->load->database('default', TRUE);
                   $DB1->select ("title,quantity");
                 $DB1->from('delivery_items');
                 $DB1->where('estimate_id' , $estimate_id);
                $DB1->where('deleted' , '0');
                  $query1=$DB1->get();
 $query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->quantity;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('quantity', "quantity+'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('tools');  
        }
        foreach ($query1->result() as $rows)
    {
    $b=$rows->quantity;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('stock', "stock+'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('items');  
        }
       }
       if($status=="approve_ret_sold"){
                    $DB1 = $this->load->database('default', TRUE);
                   $DB1->select ("title,ret_sold");
                 $DB1->from('delivery_items');
                 $DB1->where('estimate_id' , $estimate_id);
                   $DB1->where('deleted' , '0');
                  $query1=$DB1->get();
 $query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->ret_sold;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('quantity', "quantity+'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('tools');  
        }
        foreach ($query1->result() as $rows)
    {
    $b=$rows->ret_sold;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('stock', "stock+'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('items');  
        }
       }
       if($status=="modified"){
                    $DB1 = $this->load->database('default', TRUE);
                   $DB1->select ("title,quantity");
                 $DB1->from('delivery_items');
                 $DB1->where('estimate_id' , $estimate_id);
                   

                   $DB1->where('deleted' , '0');
                  $query1=$DB1->get();
 $query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->quantity;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('quantity', "quantity+'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('tools');  
        }
        foreach ($query1->result() as $rows)
    {
    $b=$rows->quantity;
   $c=$rows->title;
    $DB2 = $this->load->database('default', TRUE);
   $DB2->set('stock', "stock+'$b'", false);
    $DB2->where('title' , $c);
    $DB2->where('deleted' , '0');
    $DB2->update('items');  
        }
       }
               //create notification
                    if ($status == "sent") {
                        log_notification("estimate_sent", array("estimate_id" => $estimate_id));
                    }
                }
            }
        }
    }

    /* delete or undo an estimate */

    function delete() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Delivery_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Delivery_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimates, prepared for datatable  */

    function list_data() {
        //$this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("delivery", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Delivery_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of estimate of a specific client, prepared for datatable  */

    function estimate_list_data_of_client($client_id) {
        $this->access_only_allowed_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("client_id" => $client_id, "status" => $this->input->post("status"), "custom_fields" => $custom_fields);

        if ($this->login_user->user_type == "client") {
            //don't show draft estimates to clients.
            $options["exclude_draft"] = true;
        }

        $list_data = $this->Estimates_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of estimate list table */

    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("delivery", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Delivery_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of estimate list table */

    private function _make_row($data, $custom_fields) {
        /*$estimates_url = "";
        if ($this->login_user->user_type == "staff") {
            $estimates_url = anchor(get_uri("delivery/view/" . $data->id), get_delivery_id($data->id));
        } else {
            //for client client
            $estimate_url = anchor(get_uri("estimates/preview/" . $data->id), get_estimate_id($data->id));
        }*/

        $estimate_no_value = $data->dc_no ? $data->dc_no: get_delivery_id($data->id);
        $estimate_no_url = "";
        if ($this->login_user->user_type == "staff") {
             $estimate_no_url = anchor(get_uri("delivery/view/" . $data->id), $estimate_no_value);
        } else {
             $estimate_no_url = anchor(get_uri("delivery/preview/" . $data->id), $estimate_no_value);
        }

        if($data->client_id){
        $row_data = array(
            //$estimates_url,
            $estimate_no_url,
            anchor(get_uri("team_members/view/" . $data->client_id), $data->first_name." ". $data->last_name),
            $data->estimate_date,
            format_to_date($data->estimate_date, false),$data->delivered_date,$data->received_date,
            
            $this->_get_estimate_status_label($data),
        );}else{
          $row_data = array(
            //$estimates_url,
            $estimate_no_url,
            $data->f_name." ". $data->l_name,
            $data->estimate_date,
            format_to_date($data->estimate_date, false),$data->delivered_date,$data->received_date,
            
            $this->_get_estimate_status_label($data),
        );  
        }

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("delivery/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_delivery'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_delivery'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("delivery/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    //prepare estimate status label 
    private function _get_estimate_status_label($estimate_info, $return_html = true) {
        $estimate_status_class = "label-default";

        //don't show sent status to client, change the status to 'new' from 'sent'

        if ($this->login_user->user_type == "client") {
            if ($estimate_info->status == "sent") {
                $estimate_info->status = "new";
            } else if ($estimate_info->status == "declined") {
                $estimate_info->status = "rejected";
            }
        }

        if ($estimate_info->status == "draft") {
            $estimate_status_class = "label-default";
        } else if ($estimate_info->status == "received" || $estimate_info->status == "returned") {
            $estimate_status_class = "label-success";
        } else if ($estimate_info->status == "given") {
            $estimate_status_class = "label-danger";
        } else if ($estimate_info->status == "sold") {
            $estimate_status_class = "label-warning";
        } else if ($estimate_info->status == "ret_sold") {
            $estimate_status_class = "label-danger";
        } else if ($estimate_info->status == "approve_ret_sold") {
            $estimate_status_class = "label-primary";
        }else if ($estimate_info->status == "invoice_created") {
            $estimate_status_class = "label-final";
        }else if ($estimate_info->status == "modified") {
            $estimate_status_class = "label-warning";
        }


        $estimate_status = "<span class='label $estimate_status_class large'>" . lang($estimate_info->status) . "</span>";
        if ($return_html) {
            return $estimate_status;
        } else {
            return $estimate_info->status;
        }
    }

    /* load estimate details view */

    function view($estimate_id = 0) {
        //$this->access_only_allowed_members();

        if ($estimate_id) {

            $view_data = get_delivery_making_data($estimate_id);

            if ($view_data) {
                $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
                $view_data['estimate_status'] = $this->_get_estimate_status_label($view_data["estimate_info"], false);

                $access_info = $this->get_access_info("delivery");
                $view_data["delivery_access_all"] = $access_info->access_type;
                $view_data["delivery_access"] = $access_info->allowed_members;
                
                $view_data["can_create_projects"] = $this->can_create_projects();

                $this->template->rander("delivery/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* estimate total section */

    private function _get_estimate_total_view($estimate_id = 0) {
        
        return $this->load->view('estimates/estimate_total_section', $view_data, true);
    }

    /* load item modal */

    function item_modal_form() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');

        $view_data['model_info'] = $this->Delivery_items_model->get_one($this->input->post('id'));
        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        if (!$estimate_id) {
            $estimate_id = $view_data['model_info']->estimate_id;
        }
        $view_data['estimate_id'] = $estimate_id;
        $manufactures = $this->Manufacturer_model->get_all_where(array("deleted" => 0 , "status" => "active"), 0, 0, "title")->result();

        $make_dropdown = array(array("id" => "", "text" => "- " ));
        foreach ($manufactures as $manufacture) {
            $make_dropdown[] = array("id" => $manufacture->id, "text" => $manufacture->title);
        }
        $view_data['make_dropdown'] = json_encode($make_dropdown);

        //product categories
         $product_categories_dropdowns = $this->Product_categories_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();
        $product_categories_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title );

        }

        //$product_categories_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_category"));

        
         $view_data['product_categories_dropdown'] =json_encode($product_categories_dropdown);        
        $this->load->view('delivery/item_modal_form', $view_data);
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
    /* add or edit an estimate item */

    function save_item() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_id" => "required|numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');

        $id = $this->input->post('id');
        if(!$this->input->post('estimate_item_title')){
        $title= $this->input->post('estimate_item_titles');
        $is_tool= 1;
        }else{
             $title= $this->input->post('estimate_item_title');
             $is_tool= 0;
        }
        $quantity = unformat_currency($this->input->post('estimate_item_quantity'));
$ret_sold = $this->input->post('ret_sold_status');
if($ret_sold=='returned'){
        $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" =>$title,
            "description" => $this->input->post('estimate_item_description'),
            "quantity" => $quantity,
            "is_tool"=>$is_tool,
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
"unit_type" => $this->input->post('estimate_unit_type'),
"rate" => unformat_currency($this->input->post('estimate_item_rate')),
"ret_sold" =>$this->input->post('ret_sold'),
"sold" =>($quantity-$this->input->post('ret_sold')),
"ret_sold_status" => $this->input->post('ret_sold_status'),
"price_visibility" => $this->input->post('price_visibility')

            
        );
}else if($ret_sold=='sold'){
        $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" =>$title,
            "description" => $this->input->post('estimate_item_description'),
            "quantity" => $quantity,
            "is_tool"=>$is_tool,
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
"unit_type" => $this->input->post('estimate_unit_type'),
"rate" => unformat_currency($this->input->post('estimate_item_rate')),
"ret_sold" =>($quantity-$this->input->post('ret_sold')),
"sold" =>$this->input->post('ret_sold'),
"ret_sold_status" => $this->input->post('ret_sold_status'),
"price_visibility" => $this->input->post('price_visibility')
            
        );
}else{
$estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" =>$title,
            "description" => $this->input->post('estimate_item_description'),
            "quantity" => $quantity,
            "is_tool"=>$is_tool,
            "category" => $this->input->post('category'),
            "make" => $this->input->post('make'),
"unit_type" => $this->input->post('estimate_unit_type'),
"rate" => unformat_currency($this->input->post('estimate_item_rate')),
"price_visibility" => $this->input->post('price_visibility')
     );
}
        $estimate_item_id = $this->Delivery_items_model->save($estimate_item_data, $id);
        if ($estimate_item_id) {


            //check if the add_new_item flag is on, if so, add the item to libary. 
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
             $add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('estimate_item_title'),
                    "quantity" => $this->input->post('estimate_item_quantity'),
                    "description" => $this->input->post('estimate_item_description'),
                    "category" => $this->input->post('category'),
                    "make" => $this->input->post('make'),
"unit_type" => $this->input->post('estimate_unit_type'),
                    "rate" => unformat_currency($this->input->post('estimate_item_rate'))
                    //"unit_type" => $this->input->post('estimate_unit_type'),
                    //"rate" => unformat_currency($this->input->post('estimate_item_rate'))
                );
                $this->Tools_model->save($library_item_data);
            }
if ($add_new_item_to_librarys) {
                $library_item_datas = array(
                    "title" => $this->input->post('estimate_item_titles'),
                    "stock" => $this->input->post('estimate_item_quantity'),
                    "description" => $this->input->post('estimate_item_description'),
                    "category" => $this->input->post('category'),
                    "make" => $this->input->post('make'),
"unit_type" => $this->input->post('estimate_unit_type'),
                    "rate" => unformat_currency($this->input->post('estimate_item_rate'))
                    //"unit_type" => $this->input->post('estimate_unit_type'),
                    //"rate" => unformat_currency($this->input->post('estimate_item_rate'))
                );
                $this->Items_model->save($library_item_datas);
            }


            $options = array("id" => $estimate_item_id);
            $item_info = $this->Delivery_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info),  'id' => $estimate_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an estimate item */

    function delete_item() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Delivery_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Delivery_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Delivery_items_model->delete($id)) {
                $item_info = $this->Delivery_items_model->get_one($id);
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimate items, prepared for datatable  */

    function item_list_data($estimate_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Delivery_items_model->get_details(array("estimate_id" => $estimate_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of estimate item list table */

    private function _make_item_row($data) {
        $list_data = $this->Delivery_model->get_details(array("id" => $data->estimate_id))->row();
        $item = "<b>$data->title</b>";
        if ($data->description) {
            $item .= "<br /><span>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";
        $make_name = $this->Manufacturer_model->get_one($data->make);
        $category_name = $this->Product_categories_model->get_one($data->category); 
    
        $sold = '0';
        $return = '0';
        
        if ($list_data->status == 'ret_sold' || $list_data->status == 'approve_ret_sold') {
            if ($data->ret_sold_status) {
                $return = $data->ret_sold;
                $sold = $data->sold;
            }
        }
    
        // Ensure currency symbol is set or default to an empty string
        $currency_symbol = isset($data->currency_symbol) ? $data->currency_symbol : '';
    
        return array(
            $item,
            $category_name->title ? $category_name->title : "-",
            $make_name->title ? $make_name->title : "-",
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $currency_symbol),
            to_currency(($data->rate * $data->quantity), $currency_symbol),
            $sold,
            $return,
            modal_anchor(get_uri("delivery/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_delivery'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("delivery/delete_item"), "data-action" => "delete"))
        );
    }
    

    /* prepare suggestion of estimate item */

    function get_delivery_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();
$options = array("estimate_id" => $_REQUEST["s"] );
$list_data = $this->Delivery_items_model->get_details($options)->result();
if($list_data){
        $delivery_items = array();
foreach ($list_data as $code) {
            $delivery_items[] = $code->title;
        }
$aa=json_encode($delivery_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}      
    $items = $this->Tools_model->get_item_suggestions($key,$d_item);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

       // $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_delivery_item_info_suggestion() {
        $item = $this->Tools_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    function get_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();
$options = array("estimate_id" => $_REQUEST["s"] );
$list_data = $this->Delivery_items_model->get_details($options)->result();
if($list_data){
        $delivery_items = array();
foreach ($list_data as $code) {
            $delivery_items[] = $code->title;
        }
$aa=json_encode($delivery_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}      $items = $this->Invoice_items_model->get_item_suggestions($key,$d_item);
       

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

       // $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    //view html is accessable to client only.
    function preview($estimate_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($estimate_id) {

            $estimate_data = get_delivery_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);

            //get the label of the estimate
            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $estimate_data['estimate_status_label'] = $this->_get_estimate_status_label($estimate_info);

            $view_data['estimate_preview'] = prepare_delivery_pdf($estimate_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['estimate_id'] = $estimate_id;

            $this->template->rander("delivery/delivery_preview", $view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($estimate_id = 0) {
        if ($estimate_id) {
            $estimate_data = get_delivery_making_data($estimate_id);
            //$this->_check_estimate_access_permission($estimate_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_delivery_pdf($estimate_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_estimate_access_permission($estimate_data) {
        //check for valid estimate
        if (!$estimate_data) {
            show_404();
        }

        //check for security
        $estimate_info = get_array_value($estimate_data, "estimate_info");
        if ($this->login_user->user_type == "client") {
            if ($this->login_user->client_id != $estimate_info->client_id) {
                redirect("forbidden");
            }
        } else {
           // $this->access_only_allowed_members();
        }
    }

    function get_delivery_status_bar($estimate_id = 0) {
       // $this->access_only_allowed_members();

        $view_data["estimate_info"] = $this->Delivery_model->get_details(array("id" => $estimate_id))->row();
        $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
        $this->load->view('delivery/delivery_status_bar', $view_data);
    }
     /*   function create_dc_from_invoice($invoice_id = 0) {
      
      $invoice = $this->Invoices_model->get_details(array("id" => $invoice_id))->row();
$client_details = $this->Clients_model->get_details(array("id" => $invoice->client_id))->row();
                
                    $delivery_data = array(
            "client_id" => 0,
            "estimate_date" => $invoice->bill_date,
            "valid_until" =>  $invoice->bill_date,
            "import_from" => '-',
            "invoice_no" => $invoice_id,
            "proformainvoice_no" => 0,

            "note" => $invoice->note,
            "f_name" => $invoice->company_name,
            "phone" => $client_details->phone,
            "address" => $client_details->address,
           "member_type" => 'others',
           "status"=>'draft'
        );

                $delivery_id =$this->Delivery_model->save($delivery_data);
                if($delivery_id){

$invoice_items = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
 foreach ($invoice_items as $key) {
$delivery_item_data = array(
            "estimate_id" =>$delivery_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->quantity ,
            "is_tool"=>1,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate 
            );

        $delivery_item_id = $this->Delivery_items_model->save($delivery_item_data);
        
    }

                }

           if($delivery_item_id){
        redirect('/delivery/view/'.$delivery_id, 'refresh');
}     

    }*/
    function create_dc_from_invoice($invoice_id = 0) {
      
      $invoice = $this->Invoices_model->get_details(array("id" => $invoice_id))->row();
$client_details = $this->Clients_model->get_details(array("id" => $invoice->client_id))->row();
$client_state = $this->States_model->get_details(array("id" => $client_details->state))->row();
$user_details = $this->Users_model->get_details(array("id" => $invoice->dispatch_user_id))->row(); 
 if($invoice->member_type=="others"){
$dispatch_by= 0;
 }else{
    $dispatch_by= $invoice->dispatch_user_id;
 }
           if($invoice->invoice_delivery_address=="1"){
            $name=$invoice->delivery_address_company_name;
            $phone=$invoice->delivery_address_phone;
            $address=$invoice->delivery_address;
            $zip=$invoice->delivery_address_zip;
            $city=$invoice->delivery_address_city;
            $state=$invoice->delivery_address_state;
            $country=$invoice->delivery_address_country;
           }else{
            $name=$client_details->company_name;
            $phone=$client_details->phone;
            $address=$client_details->address;
            $zip=$client_details->zip;
            $city=$client_details->city;
            $state=$client_state->title;
            $country=$client_details->country;
           }     
                    $delivery_data = array(
            "client_id" => $dispatch_by,
            "invoice_client_id" => $invoice->client_id,
            "estimate_date" => $invoice->bill_date,
            "valid_until" =>  $invoice->bill_date,
            "import_from" => '-',
            "invoice_no" => 0,
            "proformainvoice_no" => 0,
            "invoice_for_dc" => $invoice_id,
            "invoice_date" => $invoice->bill_date,
            "note" => $invoice->note,
            "buyers_order_no" => $invoice->buyers_order_no,
            "buyers_order_date" => $invoice->buyers_order_date,
            "dispatch_date" => $invoice->delivery_note_date,
            "dc_type_id" => 1,
            "dispatched_through" => $invoice->dispatched_through,
            "invoice_delivery_address" => $invoice->invoice_delivery_address,
             "f_name" => $invoice->f_name,
            "l_name" => $invoice->l_name,
            "lc_no" => $invoice->lc_no,
            "lc_date" => $invoice->lc_date,
            "dispatch_docket" => $invoice->dispatch_docket,
            "dispatch_name" => $invoice->dispatch_name,
            "waybill_no" => $invoice->waybill_no,
            "delivery_address_company_name" => $name,
            "delivery_address_phone" => $phone,
            "delivery_address" => $address,
            "delivery_address_country" => $country,
            "delivery_address_state" => $state,
            "delivery_address_city" => $city,
            "delivery_address_zip" => $zip,
           "member_type" => $invoice->member_type,
           "status"=>'draft'
        );

                $delivery_id =$this->Delivery_model->save($delivery_data);
                if($delivery_id){

$invoice_items = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
 foreach ($invoice_items as $key) {
$delivery_item_data = array(
            "estimate_id" =>$delivery_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->quantity ,
            "is_tool"=>1,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate 
            );

        $delivery_item_id = $this->Delivery_items_model->save($delivery_item_data);
        
    }

                }

           if($delivery_item_id){
        redirect('/delivery/view/'.$delivery_id, 'refresh');
}     

    }
    function assoc_details(){
        
         $rate=$this->input->post("item_name");
        $group_list = "";
        if ($rate) {
            $groups = explode(",", $rate);
            foreach ($groups as $group) {
                if ($group) {
                     $options = array("id" => $group);
                    $list_group = $this->Part_no_generation_model->get_details($options)->row(); 
                    $group_list += $list_group->rate;
                }
            }
        }

        if ($group_list) {
            echo json_encode(array("success" => true, "assoc_rate" => $group_list));
        } else {
            echo json_encode(array("success" => false));
        }
    
    }
   /* function invoice_modal_form($dc_id=0) {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric",
            "project_id" => "numeric"
        ));

       

        $view_data['model_info'] = $model_info;

        //make the drodown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
         $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "title", array("online_payable" => 0, "deleted" => 0));
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));
        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $project_client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        $view_data['projects_suggestion'] = $suggestion;
$view_data['lut_dropdown'] = $this->_get_lut_dropdown_select2_data();
        $view_data['client_id'] = $client_id;
        $view_data['project_id'] = $project_id;
       $view_data['dc_id'] = $this->input->post('dc_id');;
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->result();


        $this->load->view('invoices/modal_form', $view_data);
    }

    function create_invoice_from_dc($invoice_id = 0) {
      
      $invoice = $this->Invoices_model->get_details(array("id" => $invoice_id))->row();
$client_details = $this->Clients_model->get_details(array("id" => $invoice->client_id))->row();
                
                    $delivery_data = array(
            "client_id" => 0,
            "estimate_date" => $invoice->bill_date,
            "valid_until" =>  $invoice->bill_date,
            "import_from" => '-',
            "invoice_no" => 0,
            "proformainvoice_no" => 0,
            "invoice_for_dc" => $invoice_id,
            "note" => $invoice->note,
            "f_name" => $invoice->company_name,
            "phone" => $client_details->phone,
            "address" => $client_details->address,
           "member_type" => 'others',
           "status"=>'draft'
        );

                $delivery_id =$this->Delivery_model->save($delivery_data);
                if($delivery_id){

$invoice_items = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
 foreach ($invoice_items as $key) {
$delivery_item_data = array(
            "estimate_id" =>$delivery_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->quantity ,
            "is_tool"=>1,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate 
            );

        $delivery_item_id = $this->Delivery_items_model->save($delivery_item_data);
        
    }

                }

           if($delivery_item_id){
        redirect('/delivery/view/'.$delivery_id, 'refresh');
}     

    }*/
}

/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */