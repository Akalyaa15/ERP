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
             $model_info->delivery_address_company_name = 
             $estimate_info->delivery_address_company_name;
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
        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $project_client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        $view_data['projects_suggestion'] = $suggestion;

        $view_data['client_id'] = $client_id;
        $view_data['project_id'] = $project_id;

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->result();


        $this->load->view('invoices/modal_form', $view_data);
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
               "delivery_address_zip" => $this->input->post('delivery_address_zip')

        );



        if ($recurring) {
            //set next recurring date for recurring invoices

            if ($id) {
                //update
                if ($this->input->post('next_recurring_date')) { //submitted any recurring date? set it.
                    $invoice_data['next_recurring_date'] = $this->input->post('next_recurring_date');
                } else {
                    //re-calculate the next recurring date, if any recurring fields has changed.
                    $invoice_info = $this->Invoices_model->get_one($id);
                    if ($invoice_info->recurring != $invoice_data['recurring'] || $invoice_info->repeat_every != $invoice_data['repeat_every'] || $invoice_info->repeat_type != $invoice_data['repeat_type'] || $invoice_info->bill_date != $invoice_data['bill_date']) {
                        $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
                    }
                }
            } else {
                //insert new
                $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
            }


            //recurring date must have to set a future date
            if (get_array_value($invoice_data, "next_recurring_date") && get_today_date() >= $invoice_data['next_recurring_date']) {
                echo json_encode(array("success" => false, 'message' => lang('past_recurring_date_error_message_title'), 'next_recurring_date_error' => lang('past_recurring_date_error_message'), "next_recurring_date_value" => $invoice_data['next_recurring_date']));
                return false;
            }
        }




        $invoice_id = $this->Invoices_model->save($invoice_data, $id);
        if ($invoice_id) {

            save_custom_fields("invoices", $invoice_id, $this->login_user->is_admin, $this->login_user->user_type);

            //submitted copy_items_from_estimate? copy all items from estimate
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
                        "category" => $data->category ? $data->category : 0,
                        "make" => $data->make ? $data->make : 0,
                        "hsn_code" => $data->hsn_code ? $data->hsn_code : 0,
                        "gst" => $data->gst ? $data->gst : 0,

                        "total" => $data->total ? $data->total : 0,
                        "hsn_description" => $data->hsn_description ? $data->hsn_description : 0,
                        "tax_amount" =>$data->tax_amount ? $data->tax_amount : 0,
                        "net_total"=> $data->net_total ? $data->net_total : 0,
                        "discount_percentage"=> $data->discount_percentage ? $data->discount_percentage : 0,
                    );
                    $this->Invoice_items_model->save($invoice_item_data);
                }
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($invoice_id), 'id' => $invoice_id, 'message' => lang('record_saved')));
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

    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Invoices_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of invoice list table */

    private function _make_row($data, $custom_fields) {
        $invoice_url = "";
        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->id), get_invoice_id($data->id));
        }

        $due = 0;
        if ($data->invoice_value) {
            $due = ignor_minor_value($data->invoice_value - $data->payment_received);
        }

        $row_data = array($invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $data->bill_date,
            format_to_date($data->bill_date, false),
            $data->due_date,
            format_to_date($data->due_date, false),
            to_currency($data->invoice_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol),
            $this->_get_invoice_status_label($data)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("invoices/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_invoice'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_invoice'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete"), "data-action" => "delete"));

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

        $invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));

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

 /*   function item_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['model_info'] = $this->Invoice_items_model->get_one($this->input->post('id'));
        if (!$invoice_id) {
            $invoice_id = $view_data['model_info']->invoice_id;
        }
        $view_data['invoice_id'] = $invoice_id;
        $this->load->view('invoices/item_modal_form', $view_data);
    }

    /* add or edit an invoice item */

 /*   function save_item() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('invoice_item_rate'));
        $quantity = unformat_currency($this->input->post('invoice_item_quantity'));
        $gst = unformat_currency($this->input->post('invoice_item_gst'));
        $discount_percentage = unformat_currency($this->input->post('discount_percentage'));
       $total=$rate * $quantity;
       $discount_amount = $total*$discount_percentage/100;
       $discount = $total-$discount_amount;
       $tax=$discount*$gst/100;

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
            "rate" => unformat_currency($this->input->post('invoice_item_rate')),
             "discount_percentage" => $this->input->post('discount_percentage'),
            "total" => $discount,
            "tax_amount" =>$tax,
            "net_total" => $discount+$tax,
            
        );

        $invoice_item_id = $this->Invoice_items_model->save($invoice_item_data, $id);
        if ($invoice_item_id) {

            //check if the add_new_item flag is on, if so, add the item to libary. 
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
                    "rate" => unformat_currency($this->input->post('invoice_item_rate'))
                );
                $this->Items_model->save($library_item_data);
            }
$add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('invoice_item_hsn_code'),
                    "gst" => $this->input->post('invoice_item_gst'),
                     "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
                    
                );
                $this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $invoice_item_id);
            $item_info = $this->Invoice_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    */

    /* delete or undo an invoice item */

    /* load item modal */

    function item_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $team_members = $this->Part_no_generation_model->get_all_where(array("deleted" => 0))->result();
        $part_no_dropdown = array();

        foreach ($team_members as $team_member) {
            $part_no_dropdown[] = array("id" => $team_member->rate, "text" => $team_member->title );
        }

        $invoice_id = $this->input->post('invoice_id');
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['model_info'] = $this->Invoice_items_model->get_one($this->input->post('id'));
        if (!$invoice_id) {
            $invoice_id = $view_data['model_info']->invoice_id;
        }
        $view_data['part_no_dropdown'] = json_encode($part_no_dropdown);
        $view_data['invoice_id'] = $invoice_id;
        $this->load->view('invoices/item_modal_form', $view_data);
    }

    /* add or edit an invoice item */

    function save_item() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('invoice_item_rate'));
        $quantity = unformat_currency($this->input->post('invoice_item_quantity'));
        $gst = unformat_currency($this->input->post('invoice_item_gst'));

$profit_percentage = $this->input->post('profit_percentage');
       //$profit = $rate*$profit_percentage/100;
        //$actual_value = $rate+$profit; 
        //$gst = $this->input->post('gst');
        //$mrp = $actual_value*$gst/100;
        //$mrp_value =$mrp+$actual_value;
        $part_no = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $part_no ));
        $profits = $sum*$profit_percentage/100;
        $actual_values = $sum+$profits; 
        //$gst = $this->input->post('gst');
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;



 $discount_percentage = unformat_currency($this->input->post('discount_percentage'));
       $totals= $actual_values* $quantity;
       $discount_amounts = $totals*$discount_percentage/100;
       $discounts = $totals-$discount_amounts;
       $taxs=$discounts*$gst/100;
        $net_totals = $discounts+$taxs;
        


       $total=$rate * $quantity;
       $discount_amount = $total*$discount_percentage/100;
       $discount = $total-$discount_amount;
       $tax=$discount*$gst/100;
       $net_total = $discount+$tax;





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
            "total" => $discount?$discount:$discounts,
            "tax_amount" =>$tax?$tax:$taxs,
            "net_total" =>  $net_total? $net_total: $net_totals,
            "profit_percentage" => $this->input->post('profit_percentage'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "actual_value" => $actual_values,
            "MRP" => $mrp_values,
            
        );

        $invoice_item_id = $this->Invoice_items_model->save($invoice_item_data, $id);
        if ($invoice_item_id) {

   
      $profit_percentage = $this->input->post('profit_percentage');
      $gst = unformat_currency($this->input->post('invoice_item_gst'));
      $part_no = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $part_no ));
        $profits = $sum*$profit_percentage/100;
        $actual_values = $sum+$profits; 
        //$gst = $this->input->post('gst');
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;           //check if the add_new_item flag is on, if so, add the item to libary. 
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
"rate" => unformat_currency($this->input->post('item_rate'))?unformat_currency($this->input->post('item_rate')):$sum,
"profit_percentage" => $this->input->post('profit_percentage'),

"associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "actual_value" => $actual_values,
            "MRP" => $mrp_values
                );
                $this->Items_model->save($library_item_data);

            }
            $rate = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $rate ) );

$add_new_item_to_libraryss = $this->input->post('add_new_item_to_library');
if ($add_new_item_to_libraryss) {
$library_product_id_data = array(
                    "title" => $this->input->post('invoice_item_title'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            "total" => $sum,
            "description" => $this->input->post('invoice_item_description'),
            "category" => $this->input->post('invoice_item_category'),
            "make" => $this->input->post('invoice_item_make')

                    
                );
                $this->Product_id_generation_model->save($library_product_id_data);
            }
        
$add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('invoice_item_hsn_code'),
                    "gst" => $this->input->post('invoice_item_gst'),
                     "hsn_description" => $this->input->post('invoice_item_hsn_code_description'),
                    
                );
                $this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $invoice_item_id);
            $item_info = $this->Invoice_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


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

        return array(
            $data->sort,
            $item,
            $data->category,
            $data->make,
            $data->hsn_code,

            
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
            to_currency($data->total, $data->currency_symbol),

            $data->gst."%",
            to_currency($data->tax_amount, $data->currency_symbol),
            $data->discount_percentage."%",
            to_currency($data->net_total),
            modal_anchor(get_uri("invoices/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_invoice'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete_item"), "data-action" => "delete"))
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
        $suggestion = array();

        $items = $this->Invoice_items_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product"));

        echo json_encode($suggestion);
    }

    function get_invoice_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    function get_invoice_freight_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Hsn_sac_code_model->get_freight_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->hsn_code, "text" => $item->hsn_code);
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

        $this->load->view('invoices/freight_modal_form', $view_data);
    }

    /* save discount */

    function save_freight() {
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
    }}

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */