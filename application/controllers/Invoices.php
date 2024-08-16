<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("invoice");
    }

    /* load invoice list view */

    function index() {
        $this->check_module_availability("module_invoice");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        if ($this->login_user->user_type === "staff") {
            $this->access_only_allowed_members();

            $this->template->rander("invoices/index", $view_data);
        } else {
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            $this->template->rander("clients/invoices/index", $view_data);
        }
    }

    //load the yearly view of invoice list 
    function yearly() {
        $this->load->view("invoices/yearly_invoices");
    }

    //load the recurring view of invoice list 
    function recurring() {
        $this->load->view("invoices/recurring_invoices_list");
    }

    //load the custom view of invoice list 
    function custom() {
        $this->load->view("invoices/custom_invoices_list");
    }

    /* load new invoice modal */

    function modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric",
            "project_id" => "numeric"
        ));

        $client_id = $this->input->post('client_id');
        $project_id = $this->input->post('project_id');
        $model_info = $this->Invoices_model->get_one($this->input->post('id'));

        

        //check if estimate_id posted. if found estimate_id, so, we'll show the estimate info to copy the estimate 
        $estimate_id = $this->input->post('estimate_id');
        $view_data['estimate_id'] = $estimate_id;
        if ($estimate_id) {
            $estimate_info = $this->Estimates_model->get_one($estimate_id);
            $now = get_my_local_time("Y-m-d");
            $model_info->bill_date = $now;
            $model_info->due_date = $now;
            $model_info->client_id = $estimate_info->client_id;
            //$model_info->tax_id = $estimate_info->tax_id;
            //$model_info->tax_id2 = $estimate_info->tax_id2;
            $model_info->note = $estimate_info->note;
            $model_info->delivery_note_date = $estimate_info->delivery_note_date;
        $model_info->supplier_ref = $estimate_info->supplier_ref;
            $model_info->other_references = $estimate_info->other_references;
            $model_info->buyers_order_no = $estimate_info->buyers_order_no;
            $model_info->buyers_order_date = $estimate_info->buyers_order_date;
            $model_info->terms_of_payment = $estimate_info->terms_of_payment;
            $model_info->destination = $estimate_info->destination;
            $model_info->dispatch_document_no = $estimate_info->dispatch_document_no;
            $model_info->dispatched_through = $estimate_info->dispatched_through;
            $model_info->terms_of_payment = $estimate_info->terms_of_payment;
            $model_info->terms_of_delivery = $estimate_info->terms_of_delivery;
             $model_info->invoice_delivery_address = $estimate_info->invoice_delivery_address ? 1 : 0;
             $model_info->delivery_address = $estimate_info->delivery_address;
             $model_info->delivery_address_state = $estimate_info->delivery_address_state;
             $model_info->delivery_address_city = $estimate_info->delivery_address_city;
             $model_info->delivery_address_country = $estimate_info->delivery_address_country;
             $model_info->delivery_address_zip = $estimate_info->delivery_address_zip;
              $model_info->delivery_address_phone = $estimate_info->delivery_address_phone;
             $model_info->delivery_address_company_name = 
             $estimate_info->delivery_address_company_name;

//freight amount add       
            $model_info->amount =$estimate_info->amount;
            $model_info->freight_amount=$estimate_info->freight_amount;
            $model_info->hsn_code =$estimate_info->hsn_code;
             $model_info->gst=$estimate_info->gst;
            $model_info->hsn_desription =$estimate_info->hsn_desription;
           $model_info->with_gst =$estimate_info->with_gst;
           $model_info->with_inclusive_tax =$estimate_info->with_inclusive_tax;
           $model_info->freight_tax_amount =$estimate_info->freight_tax_amount;

             
        }

        //here has a project id. now set the client from the project
        if ($project_id) {
            $client_id = $this->Projects_model->get_one($project_id)->client_id;
            $model_info->client_id = $client_id;
        }


        $project_client_id = $client_id;
        if ($model_info->client_id) {
            $project_client_id = $model_info->client_id;
        }

        $view_data['model_info'] = $model_info;

        //make the drodown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
         $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "title", array("online_payable" => 0, "deleted" => 0));
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));
        $view_data['dispatched_through_dropdown'] = array("" => "-") + $this->Mode_of_dispatch_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $project_client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        $view_data['projects_suggestion'] = $suggestion;
     $view_data['lut_dropdown'] = $this->_get_lut_dropdown_select2_data();
        $view_data['client_id'] = $client_id;
        $view_data['project_id'] = $project_id;
       $view_data['dc_id'] = $this->input->post('client_id');;
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
//add dispatch dropdown

$team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "") + $members_dropdown;
        $rm_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"))->result();
        $rm_members_dropdown = array();

        foreach ($rm_members as $rm_member) {
            $rm_members_dropdown[$rm_member->id] = $rm_member->first_name . " " . $rm_member->last_name;
        }
 $view_data['rm_members_dropdown'] = array("" => "-") + $rm_members_dropdown;
        $view_data['members_dropdown'] = array("" => "-") + $members_dropdown;




        $this->load->view('invoices/modal_form', $view_data);
    }


    private function _get_lut_dropdown_select2_data($show_header = false) {
        //$luts = $this->Lut_number_model->get_all()->result();
        $luts = $this->Lut_number_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $lut_dropdown = array(array("id" => "", "text" => "-"));

        

        foreach ($luts as $code) {
            $lut_dropdown[] = array("id" => $code->lut_number, "text" => $code->lut_year);
        }
        return $lut_dropdown;
    } 

    /* prepare project dropdown based on this suggestion */

    function get_project_suggestion($client_id = 0) {
        $this->access_only_allowed_members();

        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        echo json_encode($suggestion);
    }

    /* add or edit an invoice */

    function save() {
        $this->access_only_allowed_members();
    
        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_client_id" => "required|numeric",
            "invoice_bill_date" => "required",
            "invoice_due_date" => "required"
        ));
    
        $client_id = $this->input->post('invoice_client_id');
        $id = $this->input->post('id');
    
        $recurring = $this->input->post('recurring') ? 1 : 0;
        $bill_date = $this->input->post('invoice_bill_date');
        $repeat_every = $this->input->post('repeat_every');
        $repeat_type = $this->input->post('repeat_type');
        $no_of_cycles = $this->input->post('no_of_cycles');
        $members_type = $this->input->post('member_type');
        
        // Initialize dispatch_user_id to a default value
        $dispatch_user_id = null;
        
        if ($members_type == 'tm') {
            $dispatch_user_id = $this->input->post('invoice_dispatch_team_member_id');
        } elseif ($members_type == 'om') {
            $dispatch_user_id = $this->input->post('invoice_dispatch_outsource_id');
        }
    
        $invoice_data = array(
            "client_id" => $client_id,
            "project_id" => $this->input->post('invoice_project_id') ? $this->input->post('invoice_project_id') : 0,
            "bill_date" => $bill_date,
            "due_date" => $this->input->post('invoice_due_date'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "recurring" => $recurring,
            "invoice_delivery_address" => $this->input->post('invoice_delivery_address') ? 1 : 0,
            "delivery_address_company_name" => $this->input->post('delivery_address_company_name'),
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
            "note" => $this->input->post('invoice_note'),
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
            "terms_of_payment" => $this->input->post('invoice_payment_method_id'),
            "buyers_order_no" => $this->input->post('buyers_order_no'),
            "buyers_order_date" => $this->input->post('buyers_order_date'),
            "destination" => $this->input->post('destination'),
            "dispatched_through" => $this->input->post('dispatched_through'),
            "terms_of_delivery" => $this->input->post('terms_of_delivery'),
            "delivery_address" => $this->input->post('delivery_address'),
            "delivery_address_state" => $this->input->post('delivery_address_state'),
            "delivery_address_city" => $this->input->post('delivery_address_city'),
            "delivery_address_country" => $this->input->post('delivery_address_country'),
            "delivery_address_zip" => $this->input->post('delivery_address_zip'),
            "delivery_address_phone" => $this->input->post('delivery_address_phone'),
            "lut_number" => $this->input->post('lut_number'),
            "lc_no" => $this->input->post('lc_no'),
            "lc_date" => $this->input->post('lc_date'),
            "dispatch_docket" => $this->input->post('dispatch_docket'),
            "dispatch_name" => $this->input->post('dispatch_name'),
            "waybill_no" => $this->input->post('waybill_no'),
            "dispatch_user_id" => $dispatch_user_id,
            "member_type" => $this->input->post('member_type'),
            "f_name" => $this->input->post('first_name'),
            "l_name" => $this->input->post('last_name'),
            "phone" => $this->input->post('phone'),
            "amount" => $this->input->post('amount'),
            "hsn_code" => $this->input->post('hsn_code') ? $this->input->post('hsn_code') : "",
            "hsn_description" => $this->input->post('hsn_description') ? $this->input->post('hsn_description') : "",
            "gst" => $this->input->post('gst') ? $this->input->post('gst') : "",
            "with_inclusive_tax" => $this->input->post('with_inclusive_tax'),
            "with_gst" => $this->input->post('with_gst'),
            "freight_tax_amount" => $this->input->post('freight_tax_amount'),
            "freight_amount" => $this->input->post('freight_amount') ? $this->input->post('freight_amount') : "",
            "warranty" => $this->input->post('warranty'),
            "warranty_type" => $this->input->post('warranty_type'),
            "warranty_expiry_date" => $this->input->post('warranty_expiry_date')
        );
    
        if ($recurring) {
            if ($id) {
                if ($this->input->post('next_recurring_date')) {
                    $invoice_data['next_recurring_date'] = $this->input->post('next_recurring_date');
                } else {
                    $invoice_info = $this->Invoices_model->get_one($id);
                    if ($invoice_info->recurring != $invoice_data['recurring'] || $invoice_info->repeat_every != $invoice_data['repeat_every'] || $invoice_info->repeat_type != $invoice_data['repeat_type'] || $invoice_info->bill_date != $invoice_data['bill_date']) {
                        $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
                    }
                }
            } else {
                $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
            }
    
            if (get_array_value($invoice_data, "next_recurring_date") && get_today_date() >= $invoice_data['next_recurring_date']) {
                echo json_encode(array("success" => false, 'message' => lang('past_recurring_date_error_message_title'), 'next_recurring_date_error' => lang('past_recurring_date_error_message'), "next_recurring_date_value" => $invoice_data['next_recurring_date']));
                return false;
            }
        }
    
        if ($id) {
            $invoice_data["invoice_no"] = $this->input->post('invoice_no');
            if ($this->Invoices_model->is_invoice_no_exists($invoice_data["invoice_no"], $id)) {
                echo json_encode(array("success" => false, 'message' => lang('vendors_invoice_already')));
                exit();
            }
        }
    
        if (!$id) {
            $get_last_invoice_id = $this->Invoices_model->get_last_invoice_id_exists();
            $invoice_no_last_id = ($get_last_invoice_id->id + 1);
            $invoice_prefix = get_invoice_id($invoice_no_last_id);
    
            if ($this->Invoices_model->is_invoice_no_exists($invoice_prefix)) {
                echo json_encode(array("success" => false, 'message' => $invoice_prefix . " " . lang('vendors_invoice_already')));
                exit();
            }
        }
    
        $invoice_id = $this->Invoices_model->save($invoice_data, $id);
        if ($invoice_id) {
    
            save_custom_fields("invoices", $invoice_id, $this->login_user->is_admin, $this->login_user->user_type);
    
            $copy_items_from_estimate = $this->input->post("copy_items_from_estimate");
            if ($copy_items_from_estimate) {
                $estimate_items = $this->Estimate_items_model->get_details(array("estimate_id" => $copy_items_from_estimate))->result();
    
                foreach ($estimate_items as $data) {
                    $invoice_item_data = array(
                        "invoice_id" => $invoice_id,
                        "title" => $data->title ? $data->title : "",
                        "description" => $data->description ? $data->description : "",
                        "quantity" => $data->quantity ? $data->quantity : 0,
                        "unit_type" => $data->unit_type ? $data->unit_type : "",
                        "rate" => $data->rate ? $data->rate : 0,
                        "total" => $data->total ? $data->total : 0,
                        "hsn_code" => $data->hsn_code ? $data->hsn_code : "",
                        "tax_id" => $data->tax_id ? $data->tax_id : 0,
                        "tax_id2" => $data->tax_id2 ? $data->tax_id2 : 0,
                        "with_gst" => $data->with_gst,
                        "gst" => $data->gst ? $data->gst : "",
                        "warranty" => $data->warranty,
                        "warranty_type" => $data->warranty_type,
                        "warranty_expiry_date" => $data->warranty_expiry_date
                    );
    
                    $this->Invoice_items_model->save($invoice_item_data);
                }
            }
    
            if ($id) {
                log_notification("invoice_updated", array("invoice_id" => $invoice_id));
            } else {
                log_notification("invoice_created", array("invoice_id" => $invoice_id));
            }
    
            echo json_encode(array("success" => true, 'message' => lang('record_saved'), "id" => $invoice_id, 'invoice_id' => get_invoice_id($invoice_id)));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    

    /* delete or undo an invoice */

    function delete() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Invoices_model->deletefreight($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Invoices_model->deletefreight($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoices, prepared for datatable  */

    function list_data() {
        $this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of invoice of a specific client, prepared for datatable  */

    function invoice_list_data_of_client($client_id) {
        $this->access_only_allowed_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "client_id" => $client_id,
            "status" => $this->input->post("status"),
            "custom_fields" => $custom_fields
        );

        //don't show draft invoices to client
        if ($this->login_user->user_type == "client") {
            $options["exclude_draft"] = true;
        }


        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice of a specific project, prepared for datatable  */

    function invoice_list_data_of_project($project_id) {
        $this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "project_id" => $project_id,
            "status" => $this->input->post("status"),
            "custom_fields" => $custom_fields
        );
        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* show sub invoices tab  */

    function sub_invoices($recurring_invoice_id) {
        $this->access_only_allowed_members();
        $view_data["recurring_invoice_id"] = $recurring_invoice_id;
        $this->load->view("invoices/sub_invoices", $view_data);
    }

    /* list of sub invoices of a recurring invoice, prepared for datatable  */

    function sub_invoices_list_data($recurring_invoice_id) {
        $this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "custom_fields" => $custom_fields,
            "recurring_invoice_id" => $recurring_invoice_id
        );

        $list_data = $this->Invoices_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of invoice list table */

    private function _make_row($data, $custom_fields) {
        // Initialize $warranty_expiry_date
        $warranty_expiry_date = "-"; // Default value if no date is available
    
        // Process invoice number URL
        $invoice_no_value = $data->invoice_no ? $data->invoice_no : get_invoice_id($data->id);
        $invoice_no_url = "";
        if ($this->login_user->user_type == "staff") {
            $invoice_no_url = anchor(get_uri("invoices/view/" . $data->id), $invoice_no_value);
        } else {
            $invoice_no_url = anchor(get_uri("invoices/preview/" . $data->id), $invoice_no_value);
        }
    
        // Calculate due value
        $due = 0;
        if ($data->invoice_value) {
            $due = ignor_minor_value($data->invoice_value - $data->payment_received);
        }
    
        // Process warranty expiry date
        $warranty = format_to_date($data->warranty_expiry_date, false);
        if (is_date_exists($warranty)) {
            if (get_my_local_time("Y-m-d") > $warranty) {
                $warranty_expiry_date = "<span class='text-danger mr5'>" . $warranty . "</span>";
            } else if (get_my_local_time("Y-m-d") == $warranty) {
                $warranty_expiry_date = "<span class='text-warning mr5'>" . $warranty . "</span>";
            } else {
                $warranty_expiry_date = $warranty;
            }
        }
    
        // Prepare row data
        $row_data = array(
            //$invoice_url,
            $data->id,
            $invoice_no_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $data->bill_date,
            format_to_date($data->bill_date, false),
            $data->due_date,
            format_to_date($data->due_date, false),
            $warranty_expiry_date,
            to_currency($data->invoice_value, $data->currency_symbol),
            to_currency($data->profit_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol),
            $this->_get_invoice_status_label($data)
        );
    
    
        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("invoices/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_invoice'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_invoice'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    //prepare invoice status label 
    private function _get_invoice_status_label($data, $return_html = true) {
        return get_invoice_status_label($data, $return_html);
    }

    // list of recurring invoices, prepared for datatable
    function recurring_list_data() {
        $this->access_only_allowed_members();


        $options = array(
            "recurring" => 1,
            "next_recurring_start_date" => $this->input->post("next_recurring_start_date"),
            "next_recurring_end_date" => $this->input->post("next_recurring_end_date")
        );

        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_recurring_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    /* prepare a row of recurring invoice list table */

    private function _make_recurring_row($data) {

        //$invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));
        $invoice_no_value = $data->invoice_no ? $data->invoice_no: get_invoice_id($data->id);
        $invoice_url = anchor(get_uri("invoices/view/" . $data->id),  $invoice_no_value);
        $cycles = $data->no_of_cycles_completed . "/" . $data->no_of_cycles;
        if (!$data->no_of_cycles) { //if not no of cycles, so it's infinity
            $cycles = $data->no_of_cycles_completed . "/&#8734;";
        }

        $status = "active";
        $invoice_status_class = "label-success";
        $cycle_class = "";

        //don't show next recurring if recurring is completed
        $next_recurring = format_to_date($data->next_recurring_date, false);

        //show red color if any recurring date is past
        if ($data->next_recurring_date < get_today_date()) {
            $next_recurring = "<span class='text-danger'>" . $next_recurring . "</span>";
        }
        $next_recurring_date = $data->next_recurring_date;
        if ($data->no_of_cycles_completed > 0 && $data->no_of_cycles_completed == $data->no_of_cycles) {
            $next_recurring = "-";
            $next_recurring_date = 0;
            $status = "stopped";
            $invoice_status_class = "label-danger";
            $cycle_class = "text-danger";
        }

        return array(
            $data->id,
            $invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $next_recurring_date,
            $next_recurring,
            $data->repeat_every . " " . lang("interval_" . $data->repeat_type),
            "<span class='$cycle_class'>" . $cycles . "</span>",
            "<span class='label $invoice_status_class large'>" . lang($status) . "</span>",
            to_currency($data->invoice_value, $data->currency_symbol),
            modal_anchor(get_uri("invoices/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_invoice'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_invoice'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete"), "data-action" => "delete"))
        );
    }

    /* load invoice details view */

    function view($invoice_id = 0) {
        $this->access_only_allowed_members();

        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            if ($view_data) {
                $view_data['invoice_status'] = $this->_get_invoice_status_label($view_data["invoice_info"], false);

                $this->template->rander("invoices/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* invoice total section */

    private function _get_invoice_total_view($invoice_id = 0) {
        $view_data["invoice_total_summary"] = $this->Invoices_model->get_invoice_total_summary($invoice_id);
        $view_data["invoice_id"] = $invoice_id;
        return $this->load->view('invoices/invoice_total_section', $view_data, true);
    }

    /* load item modal */

    function item_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $team_members = $this->Part_no_generation_model->get_all_where(array("deleted" => 0))->result();
        $part_no_dropdown = array();

        foreach ($team_members as $team_member) {
            $part_no_dropdown[] = array("id" => $team_member->id, "text" => $team_member->title);
        }

        $invoice_id = $this->input->post('invoice_id');
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['model_info'] = $this->Invoice_items_model->get_one($this->input->post('id'));
        if (!$invoice_id) {
            $invoice_id = $view_data['model_info']->invoice_id;
        }

        $optionss = array("id" => $invoice_id);
        $datas = $this->Invoices_model->get_details($optionss)->row();
        $view_data['country'] = $datas->country;
        $view_data['buyer_type'] = $datas->buyer_type;

        // Fetch Buyer Type Details
        $client_buyer_type_name = '';
        $client_buyer_type_profit = '';
        
        if ($datas->buyer_type) {
            $options = array(
                "id" => $datas->buyer_type,
            );
            $list_data = $this->Buyer_types_model->get_details($options)->row();

            if ($list_data) {
                $client_buyer_type_profit = $list_data->profit_margin;
                $client_buyer_type_name = $list_data->buyer_type;
            }
        }

        $view_data['client_buyer_type_name'] = $client_buyer_type_name;
        $view_data['client_buyer_type_profit'] = $client_buyer_type_profit;

        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $view_data['part_no_dropdown'] = json_encode($part_no_dropdown);
        $view_data['invoice_id'] = $invoice_id;
        $manufactures = $this->Manufacturer_model->get_all_where(array("deleted" => 0, "status" => "active"), 0, 0, "title")->result();

        $make_dropdown = array(array("id" => "", "text" => "- "));
        foreach ($manufactures as $manufacture) {
            $make_dropdown[] = array("id" => $manufacture->id, "text" => $manufacture->title);
        }
        $view_data['make_dropdown'] = json_encode($make_dropdown);

        $product_categories_dropdowns = $this->Product_categories_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $product_categories_dropdown = array(array("id" => "", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title);
        }

        $view_data['product_categories_dropdown'] = json_encode($product_categories_dropdown);

        // Service categories
        $service_categories_dropdowns = $this->Service_categories_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $service_categories_dropdown = array(array("id" => "", "text" => "-"));

        foreach ($service_categories_dropdowns as $service_categories) {
            $service_categories_dropdown[] = array("id" => $service_categories->id, "text" => $service_categories->title);
        }

        $view_data['service_categories_dropdown'] = json_encode($service_categories_dropdown);

        $job_id_info = $this->Job_id_generation_model->get_all_where(array("deleted" => 0))->result();
        $job_id_dropdown = array();

        foreach ($job_id_info as $job_id) {
            $job_id_dropdown[] = array("id" => $job_id->id, "text" => $job_id->title);
        }
        $view_data['job_id_dropdown'] = json_encode($job_id_dropdown);
        $this->load->view('invoices/item_modal_form', $view_data);
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

/* add or edit an invoice item */

    function save_item() {
        $this->access_only_allowed_members();
    
        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric"
        ));
    
        $invoice_type = (int)$this->input->post('invoice_type');
    
        if ($invoice_type == 0) {
            $invoice_id = (int)$this->input->post('invoice_id');
            $client_profit_margin = (float)$this->input->post('client_profit_margin');
    
            $id = (int)$this->input->post('id');
            $rate = (float)unformat_currency($this->input->post('invoice_item_rate'));
            $quantity = (float)unformat_currency($this->input->post('invoice_item_quantity'));
            $gst = (float)unformat_currency($this->input->post('invoice_item_gst'));
            $installation_gst_percentage = (float)unformat_currency($this->input->post('installation_gst'));
            $profit_percentage = (float)$this->input->post('profit_percentage');
    
            $profitrate = $rate * $quantity;
            $profit_supply_buyer_profit = $profit_percentage + $client_profit_margin;
            $profitadd = $profitrate / ($profit_supply_buyer_profit + 100);
            $profit = $profitadd * 100;
            $profitvalue = $profit * $profit_supply_buyer_profit / 100;
    
            $part_no = $this->input->post('associated_with_part_no');
            $group_list = 0.0; // Initialize as numeric
    
            if ($part_no) {
                $groups = explode(",", $part_no);
                foreach ($groups as $group) {
                    if ($group) {
                        $options = array("id" => $group);
                        $list_group = $this->Part_no_generation_model->get_details($options)->row();
                        if (isset($list_group->rate)) {
                            $group_list += (float)$list_group->rate; // Ensure $list_group->rate is treated as a float
                        }
                    }
                }
            }
    
            $sum = $group_list;
    
            $profits = $sum * $profit_percentage / 100;
            $profit_and_sum = $sum + $profits;
            $buyer_type_percentage = $profit_and_sum * $client_profit_margin / 100;
            $actual_values = $profit_and_sum + $buyer_type_percentage;
    
            $mrps = $actual_values * $gst / 100;
            $mrp_values = $mrps + $actual_values;
    
            $profitrates = $sum * $quantity;
            $profitvalues = $profitrates * $profit_percentage / 100;
    
            $discount_percentage = (float)unformat_currency($this->input->post('discount_percentage'));
    
            // Installation calculations
            $installation_new_rate = (float)$this->input->post('installation_new_rate');
            $installation_profit_percentage = (float)$this->input->post('installation_profit_percentage');
            $installation_profit_rate_percentage = $installation_new_rate * $installation_profit_percentage / 100;
            $installation_actual_rate = $installation_profit_rate_percentage + $installation_new_rate;
            $installation_actual_rate_total = $installation_actual_rate * $quantity;
    
            $installation_rate = (float)$this->input->post('installation_rate');
            $installation_total = $installation_rate * $quantity;
    
            $supply_total = isset($total) ? (float)$total : (float)$totals;
            $installtion_and_supply_subtotal = $supply_total + $installation_total;
    
            $totals = $actual_values * $quantity;
            $discount_amounts = $totals * $discount_percentage / 100;
            $discounts = $totals - $discount_amounts;
            $taxs = $discounts * $gst / 100;
    
            $supply_installation_totals = $discounts;
    
            $installation_taxs = $installation_actual_rate_total * $installation_gst_percentage / 100;
            $installation_net_totals = $installation_taxs + $installation_actual_rate_total;
    
            $supply_net_totals = $discounts + $taxs;
            $supply_net_total_installation_totals = $supply_net_totals + $installation_actual_rate_total;
            $installation_supply_net_totals = $supply_net_totals + $installation_net_totals;
    
            if ($rate) {
                $total = $rate * $quantity;
                $discount_amount = $total * $discount_percentage / 100;
                $discount = $total - $discount_amount;
    
                $tax = $discount * $gst / 100;
                $supply_installation_total = $discount;
    
                $supply_net_total = $discount + $tax;
                $installation_tax = $installation_total * $installation_gst_percentage / 100;
    
                $installation_net_total = $installation_tax + $installation_total;
                $supply_net_total_installation_total = $supply_net_total + $installation_total;
                $installation_supply_net_total = $supply_net_total + $installation_net_total;
    
                $totalss = $rate * $quantity;
                $discount_amountss = $totalss * $discount_percentage / 100;
                $discountss = $totalss - $discount_amountss;
    
                $supply_installation_totalss = $discountss;
                $supply_net_totalss = $discountss;
                $supply_net_total_installation_totalss = $supply_net_totalss + $installation_total;
                $supply_and_installation_net_totalss = $supply_net_totalss + $installation_net_total;
            }
       
       $totalsss= $actual_values* $quantity;
       $discount_amountsss = $totalsss*$discount_percentage/100;
       $discountsss = $totalsss-$discount_amountsss;
       //$supply_installation_totalsss=$discountsss+$installation_actual_rate_total;
       $supply_installation_totalsss=$discountsss;
       $supply_net_totalsss = $discountsss;
    $supply_net_total_installation_totalsss = $supply_net_totalsss+$installation_actual_rate_total;
       $supply_and_installation_net_totalsss = $supply_net_totalsss+$installation_net_totals;

//installation 
   $installation_rate = $this->input->post('installation_rate');
   $installation_total =  $installation_rate*$quantity;
   $supply_total =$total?$total:$totals;
   $installtion_and_supply_subtotal=$supply_total+$installation_total;




$ss=$this->input->post('with_gst');
$installation_applicable =$this->input->post('with_installation');
$installation_gst = $this->input->post('with_installation_gst');
if($ss=="yes"){

 if($ss=="yes"&& $installation_applicable=="no"&& $installation_gst=="no") {
        $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_item_title'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            "hsn_code" => $this->input->post('invoice_item_hsn_code'),
            "gst" => $this->input->post('invoice_item_gst'),
            "description" => $this->input->post('invoice_item_description'),
             "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('invoice_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_item_rate'))?unformat_currency($this->input->post('invoice_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amount ? $discount_amount:$discount_amounts,
             "with_gst" => $this->input->post('with_gst'),
            "quantity_total"=>$total?$total:$totalsss,
             
            "total" => $discount?$discount:$discounts,
            "tax_amount" =>$tax?$tax:$taxs,
            "net_total" =>  $supply_net_total?$supply_net_total: $supply_net_totals,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
 
//installatio add 

             "with_installation"=>$this->input->post('with_installation'),
             "with_installation_gst"=>$this->input->post('with_installation_gst'),
              "installation_gst"=>$this->input->post('installation_gst'),
              "installation_rate"=>0,
              "installation_hsn_code"=>"-",
               "installation_hsn_code_description"=>"-",
               "installation_total"=>0,
               "subtotal"=>$installtion_and_supply_subtotal,
               "installation_tax_amount"=> 0,
               "client_profit_margin"=>$client_profit_margin,



        );
 }if($ss=="yes" && $installation_applicable=="yes"&& $installation_gst=="no") {
  


  $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_item_title'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            "hsn_code" => $this->input->post('invoice_item_hsn_code'),
            "gst" => $this->input->post('invoice_item_gst'),
            "description" => $this->input->post('invoice_item_description'),
             "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('invoice_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_item_rate'))?unformat_currency($this->input->post('invoice_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amount ? $discount_amount:$discount_amounts,
             "with_gst" => $this->input->post('with_gst'),
            "quantity_total"=>$total?$total:$totalsss,
             
            "total" => $supply_installation_total?$supply_installation_total:$supply_installation_totals,
            "tax_amount" =>$tax?$tax:$taxs,
            "net_total" =>  $supply_net_total_installation_total? $supply_net_total_installation_total: $supply_net_total_installation_totals,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
 
//installatio add 

             "with_installation"=>$this->input->post('with_installation'),
             "with_installation_gst"=>$this->input->post('with_installation_gst'),
              "installation_gst"=>0,
              "installation_rate"=>$this->input->post('installation_rate')?$this->input->post('installation_rate'):$installation_actual_rate,
              "installation_hsn_code"=>"-",
               "installation_hsn_code_description"=>"-",
               "installation_total"=>$installation_total?$installation_total: $installation_actual_rate_total,
               "subtotal"=>$installtion_and_supply_subtotal,
               "installation_tax_amount"=> 0,
               "client_profit_margin"=>$client_profit_margin,


        );

 }if($ss=="yes"&& $installation_applicable=="yes"&& $installation_gst=="yes") {
    $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_item_title'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            "hsn_code" => $this->input->post('invoice_item_hsn_code'),
            "gst" => $this->input->post('invoice_item_gst'),
            "description" => $this->input->post('invoice_item_description'),
             "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('invoice_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_item_rate'))?unformat_currency($this->input->post('invoice_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amount ? $discount_amount:$discount_amounts,
             "with_gst" => $this->input->post('with_gst'),
            "quantity_total"=>$total?$total:$totalsss,
             
            "total" => $supply_installation_total?$supply_installation_total:$supply_installation_totals,
            "tax_amount" =>$tax?$tax:$taxs,
            "net_total" =>  $installation_supply_net_total? $installation_supply_net_total: $installation_supply_net_totals,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
 
//installatio add 

             "with_installation"=>$this->input->post('with_installation'),
             "with_installation_gst"=>$this->input->post('with_installation_gst'),
              "installation_gst"=>$this->input->post('installation_gst'),
              "installation_rate"=>$this->input->post('installation_rate')?$this->input->post('installation_rate'):$installation_actual_rate,
              "installation_hsn_code"=>$this->input->post('installation_hsn_code'),
               "installation_hsn_code_description"=>$this->input->post('installation_hsn_code_description'),
               "installation_total"=>$installation_total?$installation_total: $installation_actual_rate_total,
               "subtotal"=>$installtion_and_supply_subtotal,
               "installation_tax_amount"=> $installation_tax?$installation_tax:$installation_taxs,
               "client_profit_margin"=>$client_profit_margin,



        );

 }
}else if($ss=="no"){

   if($ss=="no"&& $installation_applicable=="no" && $installation_gst=="no") { 
    $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_item_title'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('invoice_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('invoice_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_item_rate'))?unformat_currency($this->input->post('invoice_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amountss ? $discount_amountss:$discount_amountsss,
             "with_gst" => $this->input->post('with_gst'),
             
            "total" => $discountss?$discountss:$discountsss,
            "tax_amount" =>0,
            "net_total" =>  $supply_net_totalss?$supply_net_totalss:$supply_net_totalsss,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
            "with_installation"=>$this->input->post('with_installation'),
             "with_installation_gst"=>$this->input->post('with_installation_gst'),
              "installation_gst"=>0,
              "installation_rate"=>0,
              "installation_hsn_code"=>"-",
               "installation_hsn_code_description"=>"-",
               "installation_total"=>0,
               "subtotal"=>$installtion_and_supply_subtotal,
               "installation_tax_amount"=> 0,
               "client_profit_margin"=>$client_profit_margin,
             
        );
}if($ss=="no"&& $installation_applicable=="yes" && $installation_gst=="no") { 
    $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_item_title'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('invoice_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('invoice_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_item_rate'))?unformat_currency($this->input->post('invoice_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amountss ? $discount_amountss:$discount_amountsss,
             "with_gst" => $this->input->post('with_gst'),
             
            "total" => $supply_installation_totalss?$supply_installation_totalss:$supply_installation_totalsss,
            "tax_amount" =>0,
            "net_total" =>  $supply_net_total_installation_totalss?$supply_net_total_installation_totalss:$supply_net_total_installation_totalsss,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
            "with_installation"=>$this->input->post('with_installation'),
             "with_installation_gst"=>$this->input->post('with_installation_gst'),
              "installation_gst"=>0,
              "installation_rate"=>$this->input->post('installation_rate')?$this->input->post('installation_rate'):$installation_actual_rate,
              "installation_hsn_code"=>"-",
               "installation_hsn_code_description"=>"-",
               "installation_total"=>$installation_total?$installation_total: $installation_actual_rate_total,
               "subtotal"=>$installtion_and_supply_subtotal,
               "installation_tax_amount"=> 0,
               "client_profit_margin"=>$client_profit_margin,
             
        );
}
if($ss=="no"&& $installation_applicable=="yes" && $installation_gst=="yes") { 
    $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_item_title'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('invoice_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('invoice_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_item_rate'))?unformat_currency($this->input->post('invoice_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amountss ? $discount_amountss:$discount_amountsss,
             "with_gst" => $this->input->post('with_gst'),
             
            "total" => $supply_installation_totalss?$supply_installation_totalss:$supply_installation_totalsss,
            "tax_amount" =>0,
            "net_total" =>  $supply_and_installation_net_totalss?$supply_and_installation_net_totalss:$supply_and_installation_net_totalsss,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
            "with_installation"=>$this->input->post('with_installation'),
             "with_installation_gst"=>$this->input->post('with_installation_gst'),
              "installation_gst"=>$this->input->post('installation_gst'),
              "installation_rate"=>$this->input->post('installation_rate')?$this->input->post('installation_rate'):$installation_actual_rate,
              "installation_hsn_code"=>$this->input->post('installation_hsn_code'),
               "installation_hsn_code_description"=>$this->input->post('installation_hsn_code_description'),
               "installation_total"=>$installation_total?$installation_total: $installation_actual_rate_total,
               "subtotal"=>$installtion_and_supply_subtotal,
               "installation_tax_amount"=> $installation_tax?$installation_tax:$installation_taxs,
               "client_profit_margin"=>$client_profit_margin,
             
        );
}
}
//supply and service 
$invoice_item_data["invoice_type"] = $this->input->post('invoice_type');

//check duplicate product
 if (!$id) {
    // check the invoice product no     
        $invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        //$invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        
        if ($this->Invoice_items_model->is_invoice_product_exists($invoice_item_data["title" ],$invoice_id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }
        if ($id) {
    // check the  invoice product no     
       $invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        $invoice_item_data["id"] =$this->input->post('id');
       if ($this->Invoice_items_model->is_invoice_product_exists($invoice_item_data["title" ],$invoice_id,$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }

//end check duplicate product

        $invoice_item_id = $this->Invoice_items_model->save($invoice_item_data, $id);
        if ($invoice_item_id) {

   
      $profit_percentage = $this->input->post('profit_percentage');
      $installation_profit_percentage = $this->input->post('installation_profit_percentage');
      $gst = unformat_currency($this->input->post('invoice_item_gst'));
      //$installation_rate = unformat_currency($this->input->post('installation_rate'));
      $installation_rate = unformat_currency($this->input->post('installation_new_rate'));
      $part_no = $this->input->post('associated_with_part_no');
        /*$sum = array_sum( explode( ',', $part_no ));
        $profits = $sum*$profit_percentage/100;
        $actual_values = $sum+$profits; 
        //$gst = $this->input->post('gst');
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;
    $installation_profit = $installation_rate*$installation_profit_percentage/100;
    $installation_actual_value =$installation_rate+$installation_profit;*/
                   //check if the add_new_item flag is on, if so, add the item to libary. 
            
            /*$rate = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $rate ) );*/

$add_new_item_to_libraryss = $this->input->post('add_new_item_to_library');
if ($add_new_item_to_libraryss) {
$library_product_id_data = array(
                    "title" => $this->input->post('invoice_item_title'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            //"total" => $this->input->post('associated_with_part_no'),
            "description" => $this->input->post('invoice_item_description'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make'),
            "last_activity_user"=>$this->login_user->id,
             "last_activity" => get_current_utc_time(),

                    
                );
          $library_product_id_data["title"] = $this->input->post('invoice_item_title');
        if (!$this->Product_id_generation_model->is_product_id_generation_exists($library_product_id_data["title"])) {
                
               $product_generation_id_save = $this->Product_id_generation_model->save($library_product_id_data);
            }
                //$this->Product_id_generation_model->save($library_product_id_data);
            }


            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('invoice_item_title'),
                    "category" => $this->input->post('invoice_item_category'),
                    "make" => $this->input->post('invoice_item_make'),
                    "hsn_code" => $this->input->post('invoice_item_hsn_code'),
                    "gst" => $this->input->post('invoice_item_gst'),
                    "description" => $this->input->post('invoice_item_description'),
            "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
                    "unit_type" => $this->input->post('invoice_unit_type'),
"rate" => $part_no,
"profit_percentage" => $this->input->post('profit_percentage'),
//"profit_value"=>$profits,

"associated_with_part_no" => $this->input->post('associated_with_part_no'),
            /*"actual_value" => $actual_values,
            "MRP" => $mrp_values,*/
            "installation_gst"=>$this->input->post('installation_gst'),
              "installation_rate"=>$this->input->post('installation_new_rate'),
              "installation_hsn_code"=>$this->input->post('installation_hsn_code'),
              "installation_hsn_description"=>$this->input->post('installation_hsn_code_description'),
              /*"installation_profit_value"=>$installation_profit,
               "installation_actual_value"=>$installation_actual_value,*/
               "installation_profit_percentage"=>$this->input->post('installation_profit_percentage'),
               "product_generation_id" => $product_generation_id_save,
               "last_activity_user"=>$this->login_user->id,
             "last_activity" => get_current_utc_time(),
               

                );
                // check the same inventory product     
         $library_item_data["title"] = $this->input->post('invoice_item_title');
        if (!$this->Items_model->is_inventory_product_exists($library_item_data["title"])) {
               
                $this->Items_model->save($library_item_data);
            }
                //$this->Items_model->save($library_item_data);

            }
        
$add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('invoice_item_hsn_code'),
                    "gst" => $this->input->post('invoice_item_gst'),
                     "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
                    
                );
                // check the same inventory product     
         $library_item_data["hsn_code"] = $this->input->post('invoice_item_hsn_code');
        if (!$this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                
               $this->Hsn_sac_code_model->save($library_item_data);
            }
               // $this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $invoice_item_id);
            $item_info = $this->Invoice_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

    }// supply invoice type  
    else if($invoice_type == 1) {

        $invoice_id = $this->input->post('invoice_id');
        $client_profit_margin = $this->input->post('client_profit_margin');


        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('invoice_service_item_rate'));

        $quantity = unformat_currency($this->input->post('invoice_service_item_quantity'));
        $gst = unformat_currency($this->input->post('invoice_service_item_gst'));
        $installation_gst_percentage = unformat_currency($this->input->post('installation_service_gst'));

        $profit_percentage = $this->input->post('profit_percentage');
//$profitrate= $rate*$quantity;
//$profit_supply_buyer_profit = $profit_percentage+$client_profit_margin;
//$profitadd = $profitrate/($profit_supply_buyer_profit+100);
//$profit = $profitadd*100;
//$profitvalue = $profit*$profit_supply_buyer_profit/100;
       
        
        $part_no = $this->input->post('associated_with_job_id');
        $group_list = "";
        if ($part_no) {
            $groups = explode(",", $part_no);
            foreach ($groups as $group) {
                if ($group) {
                     $options = array("id" => $group);
                    $list_group = $this->Job_id_generation_model->get_details($options)->row(); 
                    $group_list += $list_group->rate;
                }
            }
        }
        //$sum = array_sum( explode( ',', $part_no ));
        $sum = $group_list;


        $profits = $sum*$profit_percentage/100;
        $profit_and_sum = $sum+$profits;
        $buyer_type_percentage=$profit_and_sum*$client_profit_margin/100;
        $actual_values =$profit_and_sum+$buyer_type_percentage; 
        $gst = $this->input->post('invoice_service_item_gst');
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;

        $profitrates = $sum*$quantity;
        $profitvalues = $profitrates*$profit_percentage/100;




 $discount_percentage = unformat_currency($this->input->post('discount_percentage'));



 //installation 
   
  /* $installation_new_rate = $this->input->post('installation_new_rate');
   $installation_profit_percentage = $this->input->post('installation_profit_percentage');
   $installation_profit_rate_percentage = $installation_new_rate*$installation_profit_percentage/100;
    $installation_actual_rate=$installation_profit_rate_percentage+$installation_new_rate;
   $installation_actual_rate_total=$installation_actual_rate*$quantity;*/
   



  /* $installation_rate = $this->input->post('installation_rate');
   $installation_total =  $installation_rate*$quantity;*/
    $supply_total =$total?$total:$totals;
    //$installtion_and_supply_subtotal=$supply_total+$installation_total;

       

        $totals = $actual_values* $quantity;
        $discount_amounts = $totals*$discount_percentage/100;
        $discounts = $totals-$discount_amounts;
        $taxs =$discounts*$gst/100;
        //install
        //$supply_installation_totals=$discounts + $installation_actual_rate_total;
        $supply_installation_totals=$discounts;


$installation_taxs = $installation_actual_rate_total*$installation_gst_percentage/100;

$installation_net_totals =$installation_taxs+$installation_actual_rate_total;


$supply_net_totals = $discounts+$taxs;
$supply_net_total_installation_totals = $supply_net_totals+$installation_actual_rate_total;  
$installation_supply_net_totals =$supply_net_totals+$installation_net_totals;
        

if($rate) {
       $total=$rate * $quantity;
       $discount_amount = $total*$discount_percentage/100;
       $discount = $total-$discount_amount;

       $tax=$discount*$gst/100;
      // $supply_installation_total=$discount+$installation_total;
       $supply_installation_total=$discount;

       $supply_net_total = $discount+$tax;
       $installation_tax = $installation_total*$installation_gst_percentage/100;

      $installation_net_total =$installation_tax+$installation_total;
       $supply_net_total_installation_total = $supply_net_total+$installation_total;
       $installation_supply_net_total = $supply_net_total+$installation_net_total;


      

       $totalss=$rate * $quantity;
       $discount_amountss = $totalss*$discount_percentage/100;
       $discountss = $totalss-$discount_amountss;
       //$tax=$discount*$gst/100;
       //$supply_installation_totalss=$discountss+$installation_total;
       $supply_installation_totalss=$discountss;
       $supply_net_totalss = $discountss;
       $supply_net_total_installation_totalss = $supply_net_totalss+$installation_total;
       $supply_and_installation_net_totalss = $supply_net_totalss+$installation_net_total;

  }     

       
       $totalsss= $actual_values* $quantity;
       $discount_amountsss = $totalsss*$discount_percentage/100;
       $discountsss = $totalsss-$discount_amountsss;
       //$supply_installation_totalsss=$discountsss+$installation_actual_rate_total;
       $supply_installation_totalsss=$discountsss;
       $supply_net_totalsss = $discountsss;
       $supply_net_total_installation_totalsss = $supply_net_totalsss+$installation_actual_rate_total;
       $supply_and_installation_net_totalsss = $supply_net_totalsss+$installation_net_totals;

//installation 
   //$installation_rate = $this->input->post('installation_rate');
   //$installation_total =  $installation_rate*$quantity;
   $supply_total =$total?$total:$totals;
   $installtion_and_supply_subtotal=$supply_total+$installation_total;




$ss=$this->input->post('service_with_gst');
//$installation_applicable =$this->input->post('with_installation');
//$installation_gst = $this->input->post('with_installation_gst');
if($ss=="yes"){

 if($ss=="yes") {
        $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_service_item_title'),
            "category" => $this->input->post('invoice_service_item_category'),
            "make" => "",
            "hsn_code" => $this->input->post('invoice_service_item_hsn_code'),
            "gst" => $this->input->post('invoice_service_item_gst'),
            "description" => $this->input->post('invoice_service_item_description'),
             "hsn_description" => $this->input->post('invoice_service_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('invoice_service_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_service_item_rate'))?unformat_currency($this->input->post('invoice_service_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amount ? $discount_amount:$discount_amounts,
            "with_gst" => $this->input->post('service_with_gst'),
            "quantity_total"=>$total?$total:$totalsss,
             
            "total" => $discount?$discount:$discounts,
            "tax_amount" =>$tax?$tax:$taxs,
            "net_total" =>  $supply_net_total?$supply_net_total: $supply_net_totals,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_job_id'),
            "profit_value"=>0,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
 
//installatio add 

             "with_installation"=>"no",
             "with_installation_gst"=>"no",
             "installation_gst"=>0,
             "installation_rate"=>0,
             "installation_hsn_code"=>"-",
             "installation_hsn_code_description"=>"-",
             "installation_total"=>0,
             "subtotal"=>$installtion_and_supply_subtotal,
             "installation_tax_amount"=> 0,
             "client_profit_margin"=>$client_profit_margin,



        );
 }
}else if($ss=="no"){

   if($ss=="no") { 
    $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->input->post('invoice_service_item_title'),
            "category" => $this->input->post('invoice_service_item_category'),
            "make" => "",
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('invoice_service_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('invoice_service_unit_type'),
            "rate" => unformat_currency($this->input->post('invoice_service_item_rate'))?unformat_currency($this->input->post('invoice_service_item_rate')):$actual_values,
             "discount_percentage" => $this->input->post('discount_percentage'),
             "discount_amount"=> $discount_amountss ? $discount_amountss:$discount_amountsss,
             "with_gst" => $this->input->post('service_with_gst'),
             
            "total" => $discountss?$discountss:$discountsss,
            "tax_amount" =>0,
            "net_total" =>  $supply_net_totalss?$supply_net_totalss:$supply_net_totalsss,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_job_id'),
            "profit_value"=>$profitvalue?$profitvalue:$profitvalues,
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
            "with_installation"=>"no",
            "with_installation_gst"=>"no",
            "installation_gst"=>0,
            "installation_rate"=>0,
            "installation_hsn_code"=>"-",
            "installation_hsn_code_description"=>"-",
            "installation_total"=>0,
            "subtotal"=>$installtion_and_supply_subtotal,
            "installation_tax_amount"=> 0,
            "client_profit_margin"=>$client_profit_margin,
             
        );
}

}
//supply and service 
$invoice_item_data["invoice_type"] = $this->input->post('invoice_type');

//check duplicate product
 if (!$id) {
    // check the invoice product no     
        $invoice_item_data["title" ] =$this->input->post('invoice_service_item_title');
        //$invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        
        if ($this->Invoice_items_model->is_invoice_product_exists($invoice_item_data["title" ],$invoice_id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }
        if ($id) {
    // check the  invoice product no     
       $invoice_item_data["title" ] =$this->input->post('invoice_service_item_title');
        $invoice_item_data["id"] =$this->input->post('id');
       if ($this->Invoice_items_model->is_invoice_product_exists($invoice_item_data["title" ],$invoice_id,$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }

//end check duplicate product

        $invoice_item_id = $this->Invoice_items_model->save($invoice_item_data, $id);
        if ($invoice_item_id) {
      
      $gst = unformat_currency($this->input->post('invoice_service_item_gst'));
      $add_new_item_to_library = $this->input->post('add_new_service_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('invoice_service_item_title'),
                    "category" => $this->input->post('invoice_service_item_category'),
                    
                    "hsn_code" => $this->input->post('invoice_service_item_hsn_code'),
                    "gst" => $this->input->post('invoice_service_item_gst'),
                    "description" => $this->input->post('invoice_service_item_description'),
            "hsn_description" => $this->input->post('invoice_service_item_hsn_code_description'),
                    "unit_type" => $this->input->post('invoice_service_unit_type'),
            "rate" => 0,
            "associated_with_part_no" => $this->input->post('associated_with_job_id'),
            "last_activity_user"=>$this->login_user->id,
             "last_activity" => get_current_utc_time(),
            
            
               
               

                );
                // check the same service id  product     
         $library_item_data["title"] = $this->input->post('invoice_service_item_title');
        if (!$this->Service_id_generation_model->is_service_id_generation_exists($library_item_data["title"])) {
               
                $this->Service_id_generation_model->save($library_item_data);
            }
                //$this->Items_model->save($library_item_data);

            }
        
$add_new_item_to_librarys = $this->input->post('add_new_service_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('invoice_service_item_hsn_code'),
                    "gst" => $this->input->post('invoice_service_item_gst'),
                     "hsn_description" => $this->input->post('invoice_service_item_hsn_code_description'),
                    
                );
                // check the same inventory product     
         $library_item_data["hsn_code"] = $this->input->post('invoice_service_item_hsn_code');
        if (!$this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                
               $this->Hsn_sac_code_model->save($library_item_data);
            }
               // $this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $invoice_item_id);
            $item_info = $this->Invoice_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

    } // service type 


    } 


    /* delete or undo an invoice item */

    function delete_item() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Invoice_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Invoice_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Invoice_items_model->delete($id)) {
                $item_info = $this->Invoice_items_model->get_one($id);
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoice items, prepared for datatable  */

    function item_list_data($invoice_id = 0) {
        $this->access_only_allowed_members();

        $list_data = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice item list table */

    private function _make_item_row($data) {
        $item = "<div class='item-row strong mb5' data-id='$data->id'><i class='fa fa-bars pull-left move-icon'></i> $data->title</div>";
        if ($data->description) {
            $item .= "<span style='margin-left:25px'>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";

        $make_name = $this->Manufacturer_model->get_one($data->make);
        //$category_name = $this->Product_categories_model->get_one($data->category);

        if($data->invoice_type == 0){
         $category_name = $this->Product_categories_model->get_one($data->category); 
         }else if($data->invoice_type == 1){
         $category_name = $this->Service_categories_model->get_one($data->category);  

          }
         if($category_name->title){
            $category = $category_name->title ? $category_name->title:"-";
          } 

        return array(
            $data->sort,
            $item,
            //$data->category,
            /*$data->make,*/
            $category?$category:"-",
            $make_name->title?$make_name->title:"-",
            $data->hsn_code,

            
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
             to_currency($data->quantity_total, $data->currency_symbol),
            //to_currency($data->total, $data->currency_symbol),

            $data->gst."%",
            to_currency($data->tax_amount, $data->currency_symbol),
            $data->discount_percentage."%",
            to_currency($data->discount_amount, $data->currency_symbol),
            to_currency($data->installation_rate, $data->currency_symbol),
             to_currency($data->installation_total, $data->currency_symbol),
            to_currency($data->total, $data->currency_symbol),
            //to_currency($data->net_total, $data->currency_symbol),
            modal_anchor(get_uri("invoices/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_invoice'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete_item"), "data-action" => "delete-confirmation"))
        );
    }
    
    //update the sort value for the item
    function update_item_sort_values($id = 0) {

        $sort_values = $this->input->post("sort_values");
        if ($sort_values) {

            //extract the values from the comma separated string
            $sort_array = explode(",", $sort_values);


            //update the value in db
            foreach ($sort_array as $value) {
                $sort_item = explode("-", $value); //extract id and sort value

                $id = get_array_value($sort_item, 0);
                $sort = get_array_value($sort_item, 1);

                $data = array("sort" => $sort);
                $this->Invoice_items_model->save($data, $id);
            }
        }
    }

    /* prepare suggestion of invoice item */

     function get_invoice_item_suggestion() {
        $key = $_REQUEST["q"];
        $category = $_REQUEST["category"];
        $suggestion = array();
$options = array("invoice_id" => $_REQUEST["s"] );
$list_data = $this->Invoice_items_model->get_details($options)->result();
if($list_data){
        $invoice_items = array();
foreach ($list_data as $code) {
            $invoice_items[] = $code->title;
        }
$aa=json_encode($invoice_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}

        $items = $this->Invoice_items_model->get_item_suggestions($key,$d_item,$category);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product"));

        echo json_encode($suggestion);
    }

   // get invoice service info
function get_invoice_service_item_suggestion() {
        $key = $_REQUEST["q"];
        $category = $_REQUEST["category"];
        $suggestion = array();
$options = array("invoice_id" => $_REQUEST["s"] );
$list_data = $this->Invoice_items_model->get_details($options)->result();
if($list_data){
        $invoice_items = array();
foreach ($list_data as $code) {
            $invoice_items[] = $code->title;
        }
$aa=json_encode($invoice_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}

        $items = $this->Invoice_items_model->get_service_item_suggestions($key,$d_item,$category);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product"));

        echo json_encode($suggestion);
    }



/*
    function get_invoice_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));

    $itemss =  $this->Invoice_items_model->get_item_suggestionss($this->input->post("s"));

    $itemss_client =  $this->Invoice_items_model->get_item_suggestionsss($this->input->post("client_type"));
      // print_r($item) ;
 if (empty($itemss->currency))
 {
    $itemss->currency = "INR";
 }             //print_r($itemss->currency) ;

$currency= get_setting("default_currency")."_".$itemss->currency;              
/*$currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
     $response_value   =   $cur_val->$currency; */

/*
     $connected = @fsockopen("www.google.com", 80);            
if ($connected){
        $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
    $response_value   =   $cur_val->$currency;
    }else{
        $response_value   =   'failed';
    } 
        if ($item) {
            echo json_encode(array("success" => true,"item_infoss" => $itemss_client,"item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    } */

    function get_invoice_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));

    $itemss =  $this->Invoice_items_model->get_item_suggestionss($this->input->post("s"));

    $itemss_client =  $this->Invoice_items_model->get_item_suggestionsss($this->input->post("client_type"));
      // print_r($item) ;
    $default_curr =get_setting("default_currency");
    $default_country=get_setting("company_country");
 if (empty($itemss->currency))
 {
    $itemss->currency = $default_curr;
 }             //print_r($itemss->currency) ;

$currency= get_setting("default_currency")."_".$itemss->currency;
if($itemss->country !== $default_country){              
/*$currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
     $response_value   =   $cur_val->$currency; */
     $connected = @fsockopen("www.google.com", 80);            
if ($connected){
        $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
    $response_value   =   $cur_val->$currency;
    }else{
        $response_value   =   'failed';
    } 
}else if($itemss->country == $default_country){
              
$response_value   =  "same_country";
     
}
        if ($item) {
            echo json_encode(array("success" => true,"item_infoss" => $itemss_client,"item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    // invoice service item info suggestions
    function get_invoice_service_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_service_item_info_suggestion($this->input->post("item_name"));

    $itemss =  $this->Invoice_items_model->get_item_suggestionss($this->input->post("s"));

    $itemss_client =  $this->Invoice_items_model->get_item_suggestionsss($this->input->post("client_type"));
      // print_r($item) ;
    $default_curr =get_setting("default_currency");
    $default_country=get_setting("company_country");
 if (empty($itemss->currency))
 {
    $itemss->currency = $default_curr;
 }             //print_r($itemss->currency) ;

$currency= get_setting("default_currency")."_".$itemss->currency;
if($itemss->country !== $default_country){              
/*$currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
     $response_value   =   $cur_val->$currency; */
     $connected = @fsockopen("www.google.com", 80);            
if ($connected){
        $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
    $response_value   =   $cur_val->$currency;
    }else{
        $response_value   =   'failed';
    } 
}else if($itemss->country == $default_country){
              
$response_value   =  "same_country";
     
}
        if ($item) {
            echo json_encode(array("success" => true,"item_infoss" => $itemss_client,"item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }



    function get_invoice_freight_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Hsn_sac_code_model->get_freight_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->hsn_code, "text" => $item->hsn_code." (".$item->hsn_description.")");
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_hsn_code"));

        echo json_encode($suggestion);
    }

    function get_invoice_freight_info_suggestion() {
        $item = $this->Hsn_sac_code_model->get_item_freight_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
    function preview($invoice_id = 0, $show_close_preview = false) {




        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            $this->_check_invoice_access_permission($view_data);

            $view_data['invoice_preview'] = prepare_invoice_pdf($view_data, "html");
           
            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['invoice_id'] = $invoice_id;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

            $this->load->library("paypal");
            $view_data['paypal_url'] = $this->paypal->get_paypal_url();

            $this->template->rander("invoices/invoice_preview", $view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($invoice_id = 0) {

        if ($invoice_id) {
            $invoice_data = get_invoice_making_data($invoice_id);
            $this->_check_invoice_access_permission($invoice_data);

            prepare_invoice_pdf($invoice_data, "download");
        } else {
            show_404();
        }
    }

    function download_print_pdf($invoice_id = 0) {

        if ($invoice_id) {
            $invoice_data = get_invoice_making_data($invoice_id);
            $this->_check_invoice_access_permission($invoice_data);

            prepare_invoice_print_pdf($invoice_data, "download");
        } else {
            show_404();
        }
    }

    function download_print_invoice_without_gst_pdf($invoice_id = 0) {

        if ($invoice_id) {
            $invoice_data = get_invoice_making_data($invoice_id);
            $this->_check_invoice_access_permission($invoice_data);

        prepare_print_invoice_without_gst_pdf($invoice_data, "download");
        } else {
            show_404();
        }
    }



    function download_invoice_without_gst_pdf($invoice_id = 0) {


        if ($invoice_id) {
            $invoice_data = get_invoice_making_data($invoice_id);
           
            $this->_check_invoice_access_permission($invoice_data);
            
            prepare_invoice_without_gst_pdf($invoice_data, "download");
            
        } else {
            show_404();
        }
    }

    private function _check_invoice_access_permission($invoice_data) {
        //check for valid invoice
        if (!$invoice_data) {
            show_404();
        }

        //check for security
        $invoice_info = get_array_value($invoice_data, "invoice_info");
        if ($this->login_user->user_type == "client") {
            if ($this->login_user->client_id != $invoice_info->client_id) {
                redirect("forbidden");
            }
        } else {
            $this->access_only_allowed_members();
        }
    }
function send_invoice_modal_form($invoice_id) {
        $this->access_only_allowed_members();

        if ($invoice_id) {
            $options = array("id" => $invoice_id);
            $invoice_info = $this->Invoices_model->get_details($options)->row();
            $view_data['invoice_info'] = $invoice_info;
            $contacts_options = array("user_type" => "client", "client_id" => $invoice_info->client_id);
            $contacts = $this->Users_model->get_details($contacts_options)->result();
            $contact_first_name = "";
            $contact_last_name = "";
            $contacts_dropdown = array();
            foreach ($contacts as $contact) {
                if ($contact->is_primary_contact) {
                    $contact_first_name = $contact->first_name;
                    $contact_last_name = $contact->last_name;
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name . " (" . lang("primary_contact") . ")";
                }
            }

            foreach ($contacts as $contact) {
                if (!$contact->is_primary_contact) {
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name;
                }
            }

            $view_data['contacts_dropdown'] = $contacts_dropdown;

            $email_template = $this->Email_templates_model->get_final_template("send_invoice");

            $invoice_total_summary = $this->Invoices_model->get_invoice_total_summary($invoice_id);
            $parser_data["INVOICE_ID"] = $invoice_info->id;
            $parser_data["CONTACT_FIRST_NAME"] = $contact_first_name;
            $parser_data["CONTACT_LAST_NAME"] = $contact_last_name;
            $parser_data["BALANCE_DUE"] = to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol);
            $parser_data["DUE_DATE"] = $invoice_info->due_date;
            $parser_data["PROJECT_TITLE"] = $invoice_info->project_title;
            $parser_data["INVOICE_URL"] = get_uri("invoices/preview/" . $invoice_info->id);
            $parser_data['SIGNATURE'] = $email_template->signature;
            $parser_data["LOGO_URL"] = get_logo_url();

            $view_data['message'] = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
            $view_data['subject'] = $email_template->subject;

            $this->load->view('invoices/send_invoice_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function send_invoice() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('id');

        $contact_id = $this->input->post('contact_id');
        $cc = $this->input->post('invoice_cc');

        $custom_bcc = $this->input->post('invoice_bcc');
        $subject = $this->input->post('subject');
        $message = decode_ajax_post_data($this->input->post('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $invoice_data = get_invoice_making_data($invoice_id);
        $attachement_url = prepare_invoice_pdf($invoice_data, "send_email");

        $default_bcc = get_setting('send_bcc_to'); //get default settings
        $bcc_emails = "";

        if ($default_bcc && $custom_bcc) {
            $bcc_emails = $default_bcc . "," . $custom_bcc;
        } else if ($default_bcc) {
            $bcc_emails = $default_bcc;
        } else if ($custom_bcc) {
            $bcc_emails = $custom_bcc;
        }

        if (send_app_mail($contact->email, $subject, $message, array("attachments" => array(array("file_path" => $attachement_url)), "cc" => $cc, "bcc" => $bcc_emails))) {
            // change email status
            $status_data = array("status" => "not_paid", "last_email_sent_date" => get_my_local_time());
            if ($this->Invoices_model->save($status_data, $invoice_id)) {
                echo json_encode(array('success' => true, 'message' => lang("invoice_sent_message"), "invoice_id" => $invoice_id));
            }
            // delete the temp invoice
            if (file_exists($attachement_url)) {
                unlink($attachement_url);
            }
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function get_invoice_status_bar($invoice_id = 0) {
        $this->access_only_allowed_members();

        $view_data["invoice_info"] = $this->Invoices_model->get_details(array("id" => $invoice_id))->row();
        $view_data['invoice_status_label'] = $this->_get_invoice_status_label($view_data["invoice_info"]);
        $this->load->view('invoices/invoice_status_bar', $view_data);
    }

    function set_invoice_status_to_not_paid($invoice_id = 0) {
        $this->access_only_allowed_members();

        if ($invoice_id) {
            //change the draft status of the invoice
            $this->Invoices_model->set_invoice_status_to_not_paid($invoice_id);
        }
        return "";
    }

    /* load discount modal */

    function discount_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $view_data['model_info'] = $this->Invoices_model->get_one($invoice_id);

        $this->load->view('invoices/discount_modal_form', $view_data);
    }

    /* save discount */

    function save_discount() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "invoice_id" => "required|numeric",
            "discount_type" => "required",
            "discount_amount" => "numeric",
            "discount_amount_type" => "required"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $data = array(
            "discount_type" => $this->input->post('discount_type'),
            "discount_amount" => $this->input->post('discount_amount'),
            "discount_amount_type" => $this->input->post('discount_amount_type')
        );

        $data = clean_data($data);

        $save_data = $this->Invoices_model->save($data, $invoice_id);
        if ($save_data) {
            echo json_encode(array("success" => true, "invoice_total_view" => $this->_get_invoice_total_view($invoice_id), 'message' => lang('record_saved'), "invoice_id" => $invoice_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function freight_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $view_data['model_info'] = $this->Invoices_model->get_one($invoice_id);
        $optionss = array("id" => $invoice_id);
        $datas = $this->Invoices_model->get_details($optionss)->row();
        $view_data['country'] = $datas->country;


        $this->load->view('invoices/freight_modal_form', $view_data);
    }


    /* save discount */

    function save_freight() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "invoice_id" => "required|numeric",
           
            "amount" => "numeric"
            
        ));

        $invoice_id = $this->input->post('invoice_id');
$ss = $this->input->post('with_gst');
$with_inclusive= $this->input->post('with_inclusive_tax');
if($ss=="yes" && $with_inclusive=="yes"){
    $amount = unformat_currency($this->input->post('amount'));
  $gst = $this->input->post('gst');
  $tax = $amount/(100+$gst);
  $tax_orignal=$tax*100;
  $tax_value = $amount-$tax_orignal;
  //$tax_cgst_sgst = $tax_value/2;
        $data = array(
           
            "amount" => $tax_orignal,
            "hsn_code" => $this->input->post('hsn_code'),
             "hsn_description" => $this->input->post('hsn_description'),
            "gst" => $this->input->post('gst'),
            "with_inclusive_tax" => $this->input->post('with_inclusive_tax'),
            "with_gst" => $this->input->post('with_gst'),
            "freight_tax_amount" => $tax_value,
            "freight_amount" => $amount, 
            
        );
    }else if($ss=="yes" && $with_inclusive=="no"){
        $amount = unformat_currency($this->input->post('amount'));
  $gst = $this->input->post('gst')/100;
  $tax = $amount* $gst;
  
  $total =$amount+$tax;
  //$tax_cgst_sgst = $tax_value/2;
        $data = array(
           
            "amount" => $amount,
            "hsn_code" => $this->input->post('hsn_code'),
             "hsn_description" => $this->input->post('hsn_description'),
            "gst" => $this->input->post('gst'),
            "with_inclusive_tax" => $this->input->post('with_inclusive_tax'),
            "with_gst" => $this->input->post('with_gst'),
            "freight_tax_amount" => $tax,
            "freight_amount" => $total, 
            
        );
    }else {
        $amount = unformat_currency($this->input->post('amount'));
  //$gst = $this->input->post('gst')/100;
  //$tax = $amount* $gst;
  
  //$total =$amount+$tax;
  //$tax_cgst_sgst = $tax_value/2;
        $data = array(
           
            "amount" => $amount,
            "hsn_code" => "-",
             "hsn_description" =>"-" ,
            "gst" => 0,
            "with_inclusive_tax" => $this->input->post('with_inclusive_tax'),
            "with_gst" => $this->input->post('with_gst'),
            "freight_tax_amount" => 0,
            "freight_amount" => $amount, 
            
        );
    }

        $data = clean_data($data);

        $save_data = $this->Invoices_model->save($data, $invoice_id);
        if ($save_data) {

            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "hsn_code" => $this->input->post('hsn_code'),
                    "gst" => $this->input->post('gst'),
                    "hsn_description" => $this->input->post('hsn_description')
                    
                );
                $this->Hsn_sac_code_model->save($library_item_data);
            }
            echo json_encode(array("success" => true, "invoice_total_view" => $this->_get_invoice_total_view($invoice_id), 'message' => lang('record_saved'), "invoice_id" => $invoice_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* save discount */

  /*  function save_freight() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "invoice_id" => "required|numeric",
           
            "freight_amount" => "numeric"
            
        ));

        $invoice_id = $this->input->post('invoice_id');

        $data = array(
           
            "freight_amount" => $this->input->post('freight_amount'),
            "hsn_code" => $this->input->post('hsn_code'),
             "hsn_description" => $this->input->post('hsn_description'),
            "gst" => $this->input->post('gst'),
            
        );

        $data = clean_data($data);

        $save_data = $this->Invoices_model->save($data, $invoice_id);
        if ($save_data) {

            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "hsn_code" => $this->input->post('hsn_code'),
                    "gst" => $this->input->post('gst'),
                    "hsn_description" => $this->input->post('hsn_description')
                    
                );
                $this->Hsn_sac_code_model->save($library_item_data);
            }
            echo json_encode(array("success" => true, "invoice_total_view" => $this->_get_invoice_total_view($invoice_id), 'message' => lang('record_saved'), "invoice_id" => $invoice_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    } */

    public function get_client_country_item_info_suggestion() {
        $clients_model = new \App\Models\Clients_model(); // Instantiate Clients_model
        $item = $clients_model->get_client_country_info_suggestion($this->input->post("item_name"));
        
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

function invoice_modal_form($dc_id=0) {
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
       $view_data['dc_id'] = $dc_id;
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $view_data['dispatched_through_dropdown'] = array("" => "-") + $this->Mode_of_dispatch_model->get_dropdown_list(array("title"));

        $this->load->view('invoices/dc_to_invoice_modal_form', $view_data);
    }
    /*
    function save_inv_from_dc() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_client_id" => "required|numeric",
            "invoice_bill_date" => "required",
            "invoice_due_date" => "required"
        ));

        $client_id = $this->input->post('invoice_client_id');
        $id = $this->input->post('id');
 $dc_id = $this->input->post('dc_id');
        $recurring = $this->input->post('recurring') ? 1 : 0;
       // $invoice_delivery_address = $this->input->post('invoice_delivery_address') ? 1 : 0;
        $bill_date = $this->input->post('invoice_bill_date');
        $repeat_every = $this->input->post('repeat_every');
        $repeat_type = $this->input->post('repeat_type');
        $no_of_cycles = $this->input->post('no_of_cycles');


        $invoice_data = array(
            "client_id" => $client_id,
            "project_id" => $this->input->post('invoice_project_id') ? $this->input->post('invoice_project_id') : 0,
            "bill_date" => $bill_date,
            "due_date" => $this->input->post('invoice_due_date'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "recurring" => $recurring,
            "invoice_delivery_address" => $this->input->post('invoice_delivery_address') ? 1 : 0,
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
           
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
            "note" => $this->input->post('invoice_note'),
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
            //"terms_of_payment" => $this->input->post('terms_of_payment'),
            "terms_of_payment" => $this->input->post('invoice_payment_method_id'),
           "buyers_order_no" => $this->input->post('buyers_order_no'),
             "buyers_order_date" => $this->input->post('buyers_order_date'),
             "destination" => $this->input->post('destination'),
            "dispatch_document_no" => $this->input->post('dispatch_document_no'),
            "dispatched_through" => $this->input->post('dispatched_through'),
            "terms_of_delivery" => $this->input->post('terms_of_delivery'),
            "delivery_address" => $this->input->post('delivery_address'),
             "delivery_address_state" => $this->input->post('delivery_address_state'),
              "delivery_address_city" => $this->input->post('delivery_address_city'),
              "delivery_address_country" => $this->input->post('delivery_address_country'),
               "delivery_address_zip" => $this->input->post('delivery_address_zip'),
               "lut_number" => $this->input->post('lut_number')

        );



        $invoice_id = $this->Invoices_model->save($invoice_data, $id);
        if($invoice_id){
            $delivery_items = $this->Delivery_items_model->get_details_for_invoice(array("estimate_id" =>  $dc_id))->result();
 foreach ($delivery_items as $key) {
$delivery_item_data = array(
            "invoice_id" =>$invoice_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->sold ,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate ,
"hsn_code" => "-",
            "gst" => 0,
"discount_percentage" => 0,
             "with_gst" =>'no',
              "quantity_total"=>($key->rate*$key->sold),
            "total" => ($key->rate*$key->sold),
            "tax_amount" =>0,
            "net_total" =>  ($key->rate*$key->sold),
            "profit_percentage" => 5,
            "actual_value" => 0,
            "MRP" => 0 
            );

        $invoice_item_id = $this->Invoice_items_model->save($delivery_item_data);
        
    }

        }
        if($invoice_item_id){
             $delivery_data = array(
            "invoice_no" => $invoice_id
        );
       $delivery_id =$this->Delivery_model->save($delivery_data, $dc_id);
        echo json_encode(array("success" => true, "data" => $this->_row_data($invoice_id), 'id' => $invoice_id, 'message' => lang('record_saved')));
}     
    }*/
function save_inv_from_dc() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_client_id" => "required|numeric",
            "invoice_bill_date" => "required",
            "invoice_due_date" => "required"
        ));

        $client_id = $this->input->post('invoice_client_id');
        $id = $this->input->post('id');
 $dc_id = $this->input->post('dc_id');
        $recurring = $this->input->post('recurring') ? 1 : 0;
       // $invoice_delivery_address = $this->input->post('invoice_delivery_address') ? 1 : 0;
        $bill_date = $this->input->post('invoice_bill_date');
        $repeat_every = $this->input->post('repeat_every');
        $repeat_type = $this->input->post('repeat_type');
        $no_of_cycles = $this->input->post('no_of_cycles');


        $invoice_data = array(
            "client_id" => $client_id,
            "project_id" => $this->input->post('invoice_project_id') ? $this->input->post('invoice_project_id') : 0,
            "bill_date" => $bill_date,
            "due_date" => $this->input->post('invoice_due_date'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "recurring" => $recurring,
            "invoice_delivery_address" => $this->input->post('invoice_delivery_address') ? 1 : 0,
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
           
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
            "note" => $this->input->post('invoice_note'),
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
            //"terms_of_payment" => $this->input->post('terms_of_payment'),
            "terms_of_payment" => $this->input->post('invoice_payment_method_id'),
           "buyers_order_no" => $this->input->post('buyers_order_no'),
             "buyers_order_date" => $this->input->post('buyers_order_date'),
             "destination" => $this->input->post('destination'),
            "dispatch_document_no" => $this->input->post('dispatch_document_no'),
            "dispatched_through" => $this->input->post('dispatched_through'),
            "terms_of_delivery" => $this->input->post('terms_of_delivery'),
            "delivery_address" => $this->input->post('delivery_address'),
             "delivery_address_state" => $this->input->post('delivery_address_state'),
              "delivery_address_city" => $this->input->post('delivery_address_city'),
              "delivery_address_country" => $this->input->post('delivery_address_country'),
               "delivery_address_zip" => $this->input->post('delivery_address_zip'),
               "lut_number" => $this->input->post('lut_number'),
        "warranty" => '1',
            "warranty_type" => 'months'     

        );



        $invoice_id = $this->Invoices_model->save($invoice_data, $id);
        if($invoice_id){
            $delivery_items = $this->Delivery_items_model->get_details_for_invoice(array("estimate_id" =>  $dc_id))->result();
 foreach ($delivery_items as $key) {
$delivery_item_data = array(
            "invoice_id" =>$invoice_id ,
            "title" =>$key->title,
            "description" =>$key->description,
            "quantity" =>$key->sold ,
            "category" =>$key->category,
            "make" =>$key->make,
"unit_type" =>$key->unit_type ,
"rate" =>$key->rate ,
"hsn_code" => "-",
            "gst" => 0,
"discount_percentage" => 0,
             "with_gst" =>'no',
              "quantity_total"=>($key->rate*$key->sold),
            "total" => ($key->rate*$key->sold),
            "tax_amount" =>0,
            "net_total" =>  ($key->rate*$key->sold),
            "profit_percentage" => 5,
            "actual_value" => 0,
            "MRP" => 0 
            );

        $invoice_item_id = $this->Invoice_items_model->save($delivery_item_data);
        
    }

        }
        if($invoice_item_id){
             $delivery_data = array(
            "invoice_for_dc" => $invoice_id,
            "status"=>'invoice_created'
        );
       $delivery_id =$this->Delivery_model->save($delivery_data, $dc_id);
        echo json_encode(array("success" => true, "data" => $this->_row_data($invoice_id), 'id' => $invoice_id, 'message' => lang('record_saved')));
}     
    }

    // invioce service job id 
    function invoice_service_job_assoc_details(){
        
         $rate=$this->input->post("item_name");
        $group_list = "";
        if ($rate) {
            $groups = explode(",", $rate);
            foreach ($groups as $group) {
                if ($group) {
                     $options = array("id" => $group);
                    $list_group = $this->Job_id_generation_model->get_details($options)->row(); 
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





}

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */