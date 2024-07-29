<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  class Estimates extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("estimate");
    }

    /* load estimate list view */

    function index() {
        $this->check_module_availability("module_estimate");
        $view_data['can_request_estimate'] = false;

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        if ($this->login_user->user_type === "staff") {
            $this->access_only_allowed_members();

            $this->template->rander("estimates/index", $view_data);
        } else {
            //client view
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";


            if (get_setting("module_estimate_request") == "1") {
                $view_data['can_request_estimate'] = true;
            }

            $this->template->rander("clients/estimates/client_portal", $view_data);
        }
    }

    //load the yearly view of estimate list
    function yearly() {
        $this->load->view("estimates/yearly_estimates");
    }

    /* load new estimate modal */

    function modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric"
        ));

        $client_id = $this->input->post('client_id');
        $view_data['model_info'] = $this->Estimates_model->get_one($this->input->post('id'));


        $project_client_id = $client_id;
        if ($view_data['model_info']->client_id) {
            $project_client_id = $view_data['model_info']->client_id;
        }

        //make the drodown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "title", array("online_payable" => 0, "deleted" => 0));
        $view_data['dispatched_through_dropdown'] = array("" => "-") + $this->Mode_of_dispatch_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));

        $view_data['lut_dropdown'] = $this->_get_lut_dropdown_select2_data();

        $view_data['client_id'] = $client_id;

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("estimates", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('estimates/modal_form', $view_data);
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


    /* add or edit an estimate */

    function save() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_client_id" => "required|numeric",
            "estimate_date" => "required",
            "valid_until" => "required"
        ));

        $client_id = $this->input->post('estimate_client_id');
        $id = $this->input->post('id');

        $estimate_data = array(
            "client_id" => $client_id,
            "estimate_date" => $this->input->post('estimate_date'),
            "valid_until" => $this->input->post('valid_until'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "estimate_delivery_address" => $this->input->post('estimate_delivery_address') ? 1 : 0,
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
           
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
            //"terms_of_payment" => $this->input->post('terms_of_payment'),
            "terms_of_payment" => $this->input->post('estimate_payment_method_id'),
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
               "delivery_address_phone" => $this->input->post('delivery_address_phone'),
           "without_gst" => $this->input->post('without_gst')? 1 : 0,
            "note" => $this->input->post('estimate_note'),
            "lut_number" => $this->input->post('lut_number')

        );

        if($id){
    // check the invoice no already exits  update    
        $estimate_data["estimate_no"] = $this->input->post('estimate_no');
        if ($this->Estimates_model->is_estimate_no_exists($estimate_data["estimate_no"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('po_invoice_already')));
                exit();
            }
}
// create new invoice no check already  exsits 
if (!$id) {
$get_last_estimate_id = $this->Estimates_model->get_last_estimate_id_exists();
$estimate_no_last_id = ($get_last_estimate_id->id+1);
$estimate_prefix = get_estimate_id($estimate_no_last_id);
 
        if ($this->Estimates_model->is_estimate_no_exists($estimate_prefix)) {
                echo json_encode(array("success" => false, 'message' => $estimate_prefix." ".lang('po_invoice_already')));
                exit();
            }
}

//end  create new invoice no check already  exsits
 

        $estimate_id = $this->Estimates_model->save($estimate_data, $id);
        if ($estimate_id) {

            // Save the new invoice no 
           if (!$id) {
               $estimate_prefix = get_estimate_id($estimate_id);
               $estimate_prefix_data = array(
                   
                    "estimate_no" => $estimate_prefix
                );
                $estimate_prefix_id = $this->Estimates_model->save($estimate_prefix_data, $estimate_id);
            }
// End  the new invoice no 

            save_custom_fields("estimates", $estimate_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($estimate_id), 'id' => $estimate_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //update estimate status
    function update_estimate_status($estimate_id, $status) {
        if ($estimate_id && $status) {
            $estmate_info = $this->Estimates_model->get_one($estimate_id);
            $this->access_only_allowed_members_or_client_contact($estmate_info->client_id);


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

                if ($status == "sent" || $status == "accepted" || $status == "declined") {
                    $estimate_data = array("status" => $status);
                    $estimate_id = $this->Estimates_model->save($estimate_data, $estimate_id);

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
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Estimates_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Estimates_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimates, prepared for datatable  */

    function list_data() {
        $this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "payment_status" => $this->input->post("payment_status"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Estimates_model->get_details($options)->result();
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
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Estimates_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of estimate list table */

    private function _make_row($data, $custom_fields) {
        /*$estimate_url = "";
        if ($this->login_user->user_type == "staff") {
            $estimate_url = anchor(get_uri("estimates/view/" . $data->id), get_estimate_id($data->id));
        } else {
            //for client client
            $estimate_url = anchor(get_uri("estimates/preview/" . $data->id), get_estimate_id($data->id));
        }*/

        $estimate_no_value = $data->estimate_no ? $data->estimate_no: get_estimate_id($data->id);
        $estimate_no_url = "";
        if ($this->login_user->user_type == "staff") {
             $estimate_no_url = anchor(get_uri("estimates/view/" . $data->id), $estimate_no_value);
        } else {
             $estimate_no_url = anchor(get_uri("estimates/preview/" . $data->id), $estimate_no_value);
        }


        // due
        $due = 0;
        if ($data->estimate_value) {
            $due = ignor_minor_value($data->estimate_value - $data->payment_received);
        }

        $row_data = array(
            //$estimate_url,
            $data->id,
            $estimate_no_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->estimate_date,
            format_to_date($data->estimate_date, false),
            to_currency($data->estimate_value, $data->currency_symbol),
          
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol),
            $this->_get_estimate_status_label($data),
            $this->_get_estimate_payment_status_label($data)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("estimates/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_estimate'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_estimate'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("estimates/delete"), "data-action" => "delete-confirmation"));

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
        } else if ($estimate_info->status == "declined" || $estimate_info->status == "rejected") {
            $estimate_status_class = "label-danger";
        } else if ($estimate_info->status == "accepted") {
            $estimate_status_class = "label-success";
        } else if ($estimate_info->status == "sent") {
            $estimate_status_class = "label-primary";
        } else if ($estimate_info->status == "new") {
            $estimate_status_class = "label-warning";
        }

        $estimate_status = "<span class='label $estimate_status_class large'>" . lang($estimate_info->status) . "</span>";
        if ($return_html) {
            return $estimate_status;
        } else {
            return $estimate_info->status;
        }
    }


    //prepare invoice status label 
   // private function _get_estimate_payment_status_label($data, $return_html = true) {
        //return get_estimate_payment_status_label($data, $return_html);
    //}

    private function _get_estimate_payment_status_label($estimate_info, $return_html = true) {
        $estimate_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $estimate_info->estimate_value = floor($estimate_info->estimate_value * 100) / 100;

        if ($estimate_info->payment_status != "draft" && $estimate_info->valid_until < $now && $estimate_info->payment_received < $estimate_info->estimate_value) {
            $estimate_status_class = "label-danger";
            $status = "overdue";
        } else if ($estimate_info->payment_status != "draft" && $estimate_info->payment_received <= 0) {
            $estimate_status_class = "label-warning";
            $status = "not_paid";
        } else if ($estimate_info->payment_received * 1 && $estimate_info->payment_received >= $estimate_info->estimate_value) {
            $estimate_status_class = "label-success";
            $status = "fully_paid";
        } else if ($estimate_info->payment_received > 0 && $estimate_info->payment_received < $estimate_info->estimate_value) {
            $estimate_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($estimate_info->payment_status === "draft") {
            $estimate_status_class = "label-default";
            $status = "draft";
        }

        $estimate_status = "<span class='label $estimate_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $estimate_status;
        } else {
            return $status;
        }
    }




    /* load estimate details view */

    function view($estimate_id = 0) {
        $this->access_only_allowed_members();

        if ($estimate_id) {

            $view_data = get_estimate_making_data($estimate_id);

            if ($view_data) {
                $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
                $view_data['estimate_status'] = $this->_get_estimate_status_label($view_data["estimate_info"], false);
                $view_data['estimate_payment_status'] = $this->_get_estimate_payment_status_label($view_data["estimate_info"], false);
                $view_data['estimate_payment_status_label'] = $this->_get_estimate_payment_status_label($view_data["estimate_info"]);

                $access_info = $this->get_access_info("invoice");
                $view_data["show_invoice_option"] = (get_setting("module_invoice") && $access_info->access_type == "all") ? true : false;
                
                $view_data["can_create_projects"] = $this->can_create_projects();

                $this->template->rander("estimates/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* estimate total section */

    private function _get_estimate_total_view($estimate_id = 0) {
        $view_data["estimate_total_summary"] = $this->Estimates_model->get_estimate_total_summary($estimate_id);
        return $this->load->view('estimates/estimate_total_section', $view_data, true);
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
    
        $estimate_id = $this->input->post('estimate_id');
    
        $view_data['model_info'] = $this->Estimate_items_model->get_one($this->input->post('id'));
        if (!$estimate_id) {
            $estimate_id = $view_data['model_info']->estimate_id;
        }
    
        $optionss = array("id" => $estimate_id);
        $datas = $this->Estimates_model->get_details($optionss)->row();
        $view_data['country'] = $datas->country;
        $view_data['buyer_type'] = $datas->buyer_type;
        $view_data['part_no_dropdown'] = json_encode($part_no_dropdown);
        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $view_data['estimate_id'] = $estimate_id;
    
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
    
        // service categories
        $service_categories_dropdowns = $this->Service_categories_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $service_categories_dropdown = array(array("id" => "", "text" => "-"));
    
        foreach ($service_categories_dropdowns as $service_categories) {
            $service_categories_dropdown[] = array("id" => $service_categories->id, "text" => $service_categories->title);
        }
    
        $view_data['service_categories_dropdown'] = json_encode($service_categories_dropdown);
    
        // job id dropdown
        $job_id_info = $this->Job_id_generation_model->get_all_where(array("deleted" => 0))->result();
        $job_id_dropdown = array();
    
        foreach ($job_id_info as $job_id) {
            $job_id_dropdown[] = array("id" => $job_id->id, "text" => $job_id->title);
        }
        $view_data['job_id_dropdown'] = json_encode($job_id_dropdown);
    
        $this->load->view('estimates/item_modal_form', $view_data);
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
            "estimate_id" => "required|numeric"
        ));
    
        $estimate_type = $this->input->post('estimate_type');
        if ($estimate_type == 0) { // supply type
            $estimate_id = $this->input->post('estimate_id');
            $client_profit_margin = $this->input->post('client_profit_margin');
    
            $id = $this->input->post('id');
            $rate = (float)unformat_currency($this->input->post('estimate_item_rate'));
            $quantity = (float)unformat_currency($this->input->post('estimate_item_quantity'));
            $gst = (float)unformat_currency($this->input->post('estimate_item_gst'));
            $installation_gst_percentage = (float)unformat_currency($this->input->post('installation_gst'));
    
            $profit_percentage = (float)$this->input->post('profit_percentage');
            $client_profit_margin = (float)$this->input->post('client_profit_margin');
            
            // Calculate profit and other values
            $profitrate = $rate * $quantity;
            $profit_supply_buyer_profit = $profit_percentage + $client_profit_margin;
            $profitadd = $profitrate / ($profit_supply_buyer_profit + 100);
            $profit = $profitadd * 100;
            $profitvalue = $profit * $profit_supply_buyer_profit / 100;
            
            $part_no = $this->input->post('associated_with_part_no');
            $group_list = 0; // Initialize as numeric value
    
            if ($part_no) {
                $groups = explode(",", $part_no);
                foreach ($groups as $group) {
                    if ($group) {
                        $options = array("id" => $group);
                        $list_group = $this->Part_no_generation_model->get_details($options)->row(); 
                        $group_list += (float)$list_group->rate; // Accumulate float values
                    }
                }
            }
    
        //$sum = array_sum( explode( ',', $part_no ));
        $sum = $group_list;


        $profits = $sum*$profit_percentage/100;
        $profit_and_sum = $sum+$profits;
        $buyer_type_percentage=$profit_and_sum*$client_profit_margin/100;
        $actual_values =$profit_and_sum+$buyer_type_percentage; 
        //$gst = $this->input->post('gst');
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;

        $profitrates = $sum*$quantity;
        $profitvalues = $profitrates*$profit_percentage/100;




 $discount_percentage = unformat_currency($this->input->post('discount_percentage'));



 //installation 
   
   $installation_new_rate = $this->input->post('installation_new_rate');
   $installation_profit_percentage = $this->input->post('installation_profit_percentage');
   $installation_profit_rate_percentage = $installation_new_rate*$installation_profit_percentage/100;
    $installation_actual_rate=$installation_profit_rate_percentage+$installation_new_rate;
   $installation_actual_rate_total=$installation_actual_rate*$quantity;
   



   $installation_rate = $this->input->post('installation_rate');
   $installation_total =  $installation_rate*$quantity;
    $supply_total =$total?$total:$totals;
    $installtion_and_supply_subtotal=$supply_total+$installation_total;

       

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
   $installation_rate = $this->input->post('installation_rate');
   $installation_total =  $installation_rate*$quantity;
   $supply_total =$total?$total:$totals;
   $installtion_and_supply_subtotal=$supply_total+$installation_total;




$ss=$this->input->post('with_gst');
$installation_applicable =$this->input->post('with_installation');
$installation_gst = $this->input->post('with_installation_gst');
if($ss=="yes"){

 if($ss=="yes"&& $installation_applicable=="no"&& $installation_gst=="no") {
        $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make'),
            "hsn_code" => $this->input->post('estimate_item_hsn_code'),
            "gst" => $this->input->post('estimate_item_gst'),
            "description" => $this->input->post('estimate_item_description'),
             "hsn_description" => $this->input->post('estimate_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('estimate_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_item_rate'))?unformat_currency($this->input->post('estimate_item_rate')):$actual_values,
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
  


  $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make'),
            "hsn_code" => $this->input->post('estimate_item_hsn_code'),
            "gst" => $this->input->post('estimate_item_gst'),
            "description" => $this->input->post('estimate_item_description'),
             "hsn_description" => $this->input->post('estimate_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('estimate_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_item_rate'))?unformat_currency($this->input->post('estimate_item_rate')):$actual_values,
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
    $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make'),
            "hsn_code" => $this->input->post('estimate_item_hsn_code'),
            "gst" => $this->input->post('estimate_item_gst'),
            "description" => $this->input->post('estimate_item_description'),
             "hsn_description" => $this->input->post('estimate_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('estimate_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_item_rate'))?unformat_currency($this->input->post('estimate_item_rate')):$actual_values,
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
    $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make'),
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('estimate_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('estimate_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_item_rate'))?unformat_currency($this->input->post('estimate_item_rate')):$actual_values,
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
    $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make'),
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('estimate_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('estimate_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_item_rate'))?unformat_currency($this->input->post('estimate_item_rate')):$actual_values,
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
    $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make'),
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('estimate_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('estimate_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_item_rate'))?unformat_currency($this->input->post('estimate_item_rate')):$actual_values,
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

//check duplicate product
 if (!$id) {
    // check the invoice product no     
        $estimate_item_data["title" ] =$this->input->post('estimate_item_title');
        //$invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        
        if ($this->Estimate_items_model->is_estimate_product_exists($estimate_item_data["title" ],$estimate_id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }
        if ($id) {
    // check the  invoice product no     
       $estimate_item_data["title" ] =$this->input->post('estimate_item_title');
        $estimate_item_data["id"] =$this->input->post('id');
       if ($this->Estimate_items_model->is_estimate_product_exists($estimate_item_data["title" ],$estimate_id,$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }

//end check duplicate product

       
        $estimate_item_id = $this->Estimate_items_model->save($estimate_item_data, $id);
        if ($estimate_item_id) {

   
      $profit_percentage = $this->input->post('profit_percentage');
      $installation_profit_percentage = $this->input->post('installation_profit_percentage');
      $gst = unformat_currency($this->input->post('estimate_item_gst'));
      
      $installation_rate = unformat_currency($this->input->post('installation_new_rate'));
      $part_no = $this->input->post('associated_with_part_no');
        /*$sum = array_sum( explode( ',', $part_no ));
        $profits = $sum*$profit_percentage/100;
        $actual_values = $sum+$profits; 
       
        $mrps = $actual_values*$gst/100;
        $mrp_values =$mrps+$actual_values;
    $installation_profit = $installation_rate*$installation_profit_percentage/100;
    $installation_actual_value =$installation_rate+$installation_profit;*/
                   //check if the add_new_item flag is on, if so, add the item to libary. 

        $rate = $this->input->post('associated_with_part_no');
        $sum = array_sum( explode( ',', $rate ) );

$add_new_item_to_libraryss = $this->input->post('add_new_item_to_library');
if ($add_new_item_to_libraryss) {
$library_product_id_data = array(
                    "title" => $this->input->post('estimate_item_title'),
            "associated_with_part_no" => $this->input->post('associated_with_part_no'),
            //"total" => $sum,
            "description" => $this->input->post('estimate_item_description'),
            "category" => $this->input->post('estimate_item_category'),
            "make" => $this->input->post('estimate_item_make')

                    
                );
      $library_product_id_data["title"] = $this->input->post('estimate_item_title');
        if (!$this->Product_id_generation_model->is_product_id_generation_exists($library_product_id_data["title"])) {
                
               $product_generation_id_save = $this->Product_id_generation_model->save($library_product_id_data);
            }
               // $this->Product_id_generation_model->save($library_product_id_data);
            }

// new invertory product
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('estimate_item_title'),
                    "category" => $this->input->post('estimate_item_category'),
                    "make" => $this->input->post('estimate_item_make'),
                    "hsn_code" => $this->input->post('estimate_item_hsn_code'),
                    "gst" => $this->input->post('estimate_item_gst'),
                    "description" => $this->input->post('estimate_item_description'),
            "hsn_description" => $this->input->post('estimate_item_hsn_code_description'),
                    "unit_type" => $this->input->post('estimate_unit_type'),
"rate" => $part_no,
"profit_percentage" => $this->input->post('profit_percentage'),
/*"profit_value"=>$profits,*/

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
               

                );
                 // check the same inventory product     
         $library_item_data["title"] = $this->input->post('estimate_item_title');
        if (!$this->Items_model->is_inventory_product_exists($library_item_data["title"])) {
               
                $this->Items_model->save($library_item_data);
            }
                //$this->Items_model->save($library_item_data);

            }
    // new hsn code    
$add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('estimate_item_hsn_code'),
                    "gst" => $this->input->post('estimate_item_gst'),
                     "hsn_description" => $this->input->post('estimate_item_hsn_code_description'),
                    
                );

         $library_item_data["hsn_code"] = $this->input->post('estimate_item_hsn_code');
        if (!$this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                
               $this->Hsn_sac_code_model->save($library_item_data);
            }
                //$this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $estimate_item_id);
            $item_info = $this->Estimate_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info), "estimate_total_view" => $this->_get_estimate_total_view($item_info->estimate_id), 'id' => $estimate_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

    } else if($estimate_type == 1){

        $estimate_id = $this->input->post('estimate_id');
        $client_profit_margin = $this->input->post('client_profit_margin');


        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('estimate_service_item_rate'));

        $quantity = unformat_currency($this->input->post('estimate_service_item_quantity'));
        $gst = unformat_currency($this->input->post('estimate_service_item_gst'));
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
        $gst = $this->input->post('estimate_service_item_gst');
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
        $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_service_item_title'),
            "category" => $this->input->post('estimate_service_item_category'),
            "make" => "",
            "hsn_code" => $this->input->post('estimate_service_item_hsn_code'),
            "gst" => $this->input->post('estimate_service_item_gst'),
            "description" => $this->input->post('estimate_service_item_description'),
             "hsn_description" => $this->input->post('estimate_service_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('estimate_service_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_service_item_rate'))?unformat_currency($this->input->post('estimate_service_item_rate')):$actual_values,
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
    $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_service_item_title'),
            "category" => $this->input->post('estimate_service_item_category'),
            "make" => "",
            
            "hsn_code" => "-",
            "gst" => 0,
            "description" => $this->input->post('estimate_service_item_description'),
             "hsn_description" => "-",
            "quantity" => $quantity,
            "quantity_total"=>$total?$total:$totalsss,
            "unit_type" => $this->input->post('estimate_service_unit_type'),
            "rate" => unformat_currency($this->input->post('estimate_service_item_rate'))?unformat_currency($this->input->post('estimate_service_item_rate')):$actual_values,
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
$estimate_item_data["estimate_type"] = $this->input->post('estimate_type');

//check duplicate product
 if (!$id) {
    // check the invoice product no     
        $estimate_item_data["title" ] =$this->input->post('estimate_service_item_title');
        //$invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        
        if ($this->Estimate_items_model->is_estimate_product_exists($estimate_item_data["title" ],$estimate_id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }
        if ($id) {
    // check the  invoice product no     
       $estimate_item_data["title" ] =$this->input->post('estimate_service_item_title');
        $estimate_item_data["id"] =$this->input->post('id');
       if ($this->Estimate_items_model->is_estimate_product_exists($estimate_item_data["title" ],$estimate_id,$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }

//end check duplicate product

        $estimate_item_id = $this->Estimate_items_model->save($estimate_item_data, $id);
        if ($estimate_item_id) {
      
      $gst = unformat_currency($this->input->post('estimate_service_item_gst'));
      $add_new_item_to_library = $this->input->post('add_new_service_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('estimate_service_item_title'),
                    "category" => $this->input->post('estimate_service_item_category'),
                    
                    "hsn_code" => $this->input->post('estimate_service_item_hsn_code'),
                    "gst" => $this->input->post('estimate_service_item_gst'),
                    "description" => $this->input->post('estimate_service_item_description'),
            "hsn_description" => $this->input->post('estimate_service_item_hsn_code_description'),
                    "unit_type" => $this->input->post('estimate_service_unit_type'),
            "rate" => 0,
            "associated_with_part_no" => $this->input->post('associated_with_job_id'),
            "last_activity_user"=>$this->login_user->id,
             "last_activity" => get_current_utc_time(),
            
            
               
               

                );
                // check the same service id  product     
         $library_item_data["title"] = $this->input->post('estimate_service_item_title');
        if (!$this->Service_id_generation_model->is_service_id_generation_exists($library_item_data["title"])) {
               
                $this->Service_id_generation_model->save($library_item_data);
            }
                //$this->Items_model->save($library_item_data);

            }
        
$add_new_item_to_librarys = $this->input->post('add_new_service_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('estimate_service_item_hsn_code'),
                    "gst" => $this->input->post('estimate_service_item_gst'),
                     "hsn_description" => $this->input->post('estimate_service_item_hsn_code_description'),
                    
                );
                // check the same inventory product     
         $library_item_data["hsn_code"] = $this->input->post('estimate_service_item_hsn_code');
        if (!$this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                
               $this->Hsn_sac_code_model->save($library_item_data);
            }
               // $this->Hsn_sac_code_model->save($library_item_data);
            }

            $options = array("id" => $estimate_item_id);
            $item_info = $this->Estimate_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info), "estimate_total_view" => $this->_get_estimate_total_view($item_info->estimate_id), 'id' => $estimate_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

    } // service type 


    } 


    /* delete or undo an estimate item */

    function delete_item() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Estimate_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Estimate_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info), "estimate_total_view" => $this->_get_estimate_total_view($item_info->estimate_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Estimate_items_model->delete($id)) {
                $item_info = $this->Estimate_items_model->get_one($id);
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "estimate_total_view" => $this->_get_estimate_total_view($item_info->estimate_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimate items, prepared for datatable  */

    function item_list_data($estimate_id = 0) {
        $this->access_only_allowed_members();

        $list_data = $this->Estimate_items_model->get_details(array("estimate_id" => $estimate_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of estimate item list table */

    private function _make_item_row($data) {
        $item = "<b>$data->title</b>";
        if ($data->description) {
            $item .= "<br /><span>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";
 $make_name = $this->Manufacturer_model->get_one($data->make);
 //$category_name = $this->Product_categories_model->get_one($data->category);
          if($data->estimate_type == 0){
           $category_name = $this->Product_categories_model->get_one($data->category); 
            }else if($data->estimate_type == 1){
             $category_name = $this->Service_categories_model->get_one($data->category);  

        }
        if($category_name->title){
                    $category = $category_name->title ? $category_name->title:"-";
                   } 
        return array(
            $item,
            //$data->category,
            /*$data->make,*/
            //$category_name->title?$category_name->title:"-",
            $category?$category:"-",
            $make_name->title?$make_name->title:"-",
            $data->hsn_code,
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
            //to_currency($data->total, $data->currency_symbol),
            to_currency($data->quantity_total, $data->currency_symbol),
            $data->gst,
            to_currency($data->tax_amount, $data->currency_symbol),
            $data->discount_percentage,
            to_currency($data->discount_amount, $data->currency_symbol),
            //to_currency($data->net_total),
            to_currency($data->installation_rate, $data->currency_symbol),
             to_currency($data->installation_total, $data->currency_symbol),
            to_currency($data->total, $data->currency_symbol),
            
            modal_anchor(get_uri("estimates/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_estimate'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("estimates/delete_item"), "data-action" => "delete-confirmation"))
        );
    }

    /* prepare suggestion of estimate item */

    function get_estimate_item_suggestion() {
        $key = $_REQUEST["q"];
         $category = $_REQUEST["category"];
        $suggestion = array();
        $options = array("estimate_id" => $_REQUEST["s"] );
$list_data = $this->Estimate_items_model->get_details($options)->result();
if($list_data){
        $estimate_items = array();
foreach ($list_data as $code) {
            $estimate_items[] = $code->title;
        }
$aa=json_encode($estimate_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}

        $items = $this->Invoice_items_model->get_item_suggestions($key,$d_item,$category );

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

       $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product"));

        echo json_encode($suggestion);
    }

    // get estimate service item
    function get_estimate_service_item_suggestion() {
        $key = $_REQUEST["q"];
         $category = $_REQUEST["category"];
        $suggestion = array();
        $options = array("estimate_id" => $_REQUEST["s"] );
$list_data = $this->Estimate_items_model->get_details($options)->result();
if($list_data){
        $estimate_items = array();
foreach ($list_data as $code) {
            $estimate_items[] = $code->title;
        }
$aa=json_encode($estimate_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}

        $items = $this->Estimate_items_model->get_service_item_suggestions($key,$d_item,$category );

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

       $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product"));

        echo json_encode($suggestion);
    }

/*    function get_estimate_item_info_suggestion() {
        $item = $this->Estimate_items_model->get_item_info_suggestion($this->input->post("item_name"));

    $itemss =  $this->Estimate_items_model->get_item_suggestionss($this->input->post("s"));

    $itemss_client =  $this->Estimate_items_model->get_item_suggestionsss($this->input->post("client_type"));
    //$cr =$itemss_client->currency;
      // print_r($item) ;
 if (empty($itemss->currency))
 {
    $itemss->currency = "INR";
 }             //print_r($itemss->currency) ;

$currency= get_setting("default_currency")."_".$itemss->currency;              
/*$currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
     $response_value   =   $cur_val->$currency; */
 /*    $connected = @fsockopen("www.google.com", 80);            
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
    function get_estimate_item_info_suggestion() {
        $item = $this->Estimate_items_model->get_item_info_suggestion($this->input->post("item_name"));
        $itemss = $this->Estimate_items_model->get_item_suggestionss($this->input->post("s"));
        $itemss_client = $this->Estimate_items_model->get_item_suggestionsss($this->input->post("client_type"));
    
        $default_curr = get_setting("default_currency");
        $default_country = get_setting("company_country");
    
        if (empty($itemss->currency)) {
            $itemss->currency = $default_curr;
        }
    
        $currency = get_setting("default_currency") . "_" . $itemss->currency;
        $response_value = "same_country"; // Default response
    
        if ($itemss->country !== $default_country) {
            $connected = @fsockopen("www.google.com", 80);            
            if ($connected) {
                $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
                fclose($connected); // Close the socket connection
    
                if ($currency_rate) {
                    $cur_val = json_decode($currency_rate);
                    if (isset($cur_val->$currency)) {
                        $response_value = $cur_val->$currency;
                    } else {
                        $response_value = 'failed';
                    }
                } else {
                    $response_value = 'failed';
                }
            } else {
                $response_value = 'failed';
            }
        }
    
        if ($item) {
            echo json_encode(array("success" => true, "item_infoss" => $itemss_client, "item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    
    // Service item info
    function get_estimate_service_item_info_suggestion() {
        $item = $this->Estimate_items_model->get_service_item_info_suggestion($this->input->post("item_name"));
        $itemss = $this->Estimate_items_model->get_item_suggestionss($this->input->post("s"));
        $itemss_client = $this->Estimate_items_model->get_item_suggestionsss($this->input->post("client_type"));
    
        $default_curr = get_setting("default_currency");
        $default_country = get_setting("company_country");
    
        if (empty($itemss->currency)) {
            $itemss->currency = $default_curr;
        }
    
        $currency = get_setting("default_currency") . "_" . $itemss->currency;
        $response_value = "same_country"; // Default response
    
        if ($itemss->country !== $default_country) {
            $connected = @fsockopen("www.google.com", 80);            
            if ($connected) {
                $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
                fclose($connected); // Close the socket connection
    
                if ($currency_rate) {
                    $cur_val = json_decode($currency_rate);
                    if (isset($cur_val->$currency)) {
                        $response_value = $cur_val->$currency;
                    } else {
                        $response_value = 'failed';
                    }
                } else {
                    $response_value = 'failed';
                }
            } else {
                $response_value = 'failed';
            }
        }
    
        if ($item) {
            echo json_encode(array("success" => true, "item_infoss" => $itemss_client, "item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    

    //view html is accessable to client only.
    function preview($estimate_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($estimate_id) {

            $estimate_data = get_estimate_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);

            //get the label of the estimate
            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $estimate_data['estimate_status_label'] = $this->_get_estimate_status_label($estimate_info);

            $view_data['estimate_preview'] = prepare_estimate_pdf($estimate_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['estimate_id'] = $estimate_id;

            $this->template->rander("estimates/estimate_preview", $view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($estimate_id = 0) {
        if ($estimate_id) {
            $estimate_data = get_estimate_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_estimate_pdf($estimate_data, "download");
        } else {
            show_404();
        }
    }


    function download_estimate_without_gst_pdf($estimate_id = 0) {
        if ($estimate_id) {
            $estimate_data = get_estimate_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_estimate_without_gst_pdf($estimate_data, "download");
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
            $this->access_only_allowed_members();
        }
    }

    function get_estimate_status_bar($estimate_id = 0) {
        $this->access_only_allowed_members();

        $view_data["estimate_info"] = $this->Estimates_model->get_details(array("id" => $estimate_id))->row();
        $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
         $view_data['estimate_payment_status_label'] = $this->_get_estimate_payment_status_label($view_data["estimate_info"]);
        $this->load->view('estimates/estimate_status_bar', $view_data);
    }

    function freight_modal_form() {
        $this->access_only_allowed_members();

      validate_submitted_data(array(
          "estimate_id" => "required|numeric"
        )); 

       $estimate_id = $this->input->post('estimate_id');

       $view_data['model_info'] = $this->Estimates_model->get_one($estimate_id);
       $optionss = array("id" => $estimate_id);
        $datas = $this->Estimates_model->get_details($optionss)->row();
        $view_data['country'] = $datas->country;

    $this->load->view('estimates/freight_modal_form', $view_data);
    }

    function save_freight() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "estimate_id" => "required|numeric",
           
            "amount" => "numeric"
            
        ));

        $estimate_id = $this->input->post('estimate_id');

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

        $save_data = $this->Estimates_model->save($data, $estimate_id);
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
            echo json_encode(array("success" => true, "estimate_total_view" => $this->_get_estimate_total_view($estimate_id), 'message' => lang('record_saved'), "estimate_id" => $estimate_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
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

    function get_client_country_item_info_suggestion() {
        $item = $this->Clients_model->get_proforma_invoice_client_country_info_suggestion($this->input->post("item_name"));
       // $itemss =  $this->Countries_model->get_item_suggestions_country_name($this->input->post("country_name"));
//print_r($itemss);
    
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }


    function estimate_service_job_assoc_details(){
        
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

/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */