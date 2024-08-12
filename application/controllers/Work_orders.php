<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Work_orders extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("work_order");
    }

    /* load estimate list view */

 function index() {
        $this->check_module_availability("module_work_order");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        if ($this->login_user->user_type === "staff") {
            $this->access_only_allowed_members();
            $this->template->rander("work_orders/index", $view_data);
        } else {
            //client view
            $view_data["vendor_info"] = $this->Vendors_model->get_one($this->login_user->vendor_id);
            $view_data['vendor_id'] = $this->login_user->vendor_id;
            $view_data['page_type'] = "full";
            $this->template->rander("vendors/work_orders/vendor_portal", $view_data);
        }
    }

    //load the yearly view of estimate list
    function yearly() {
        $this->load->view("work_orders/yearly_work_orders");
    }

    /* load new estimate modal */
    function modal_form() {
        $this->access_only_allowed_members();
    
        validate_submitted_data(array(
            "id" => "numeric",
            "vendor_id" => "numeric"
        ));
    
        $vendor_id = $this->input->post('vendor_id');
        $view_data['model_info'] = $this->Work_orders_model->get_one($this->input->post('id'));
    
        // Initialize $client_id
        $client_id = null;
    
        // Check if model_info exists and has a client_id
        $project_client_id = $client_id;
        if (isset($view_data['model_info']) && property_exists($view_data['model_info'], 'client_id') && $view_data['model_info']->client_id) {
            $project_client_id = $view_data['model_info']->client_id;
        } else {
            // Handle the case where model_info or client_id is not set
            log_message('error', 'model_info is not set or client_id is not available');
        }
    
        // Make the dropdown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "title", array("online_payable" => 0, "deleted" => 0));
        $view_data['dispatched_through_dropdown'] = array("" => "-") + $this->Mode_of_dispatch_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
        $view_data['vendors_dropdown'] = array("" => "-") + $this->Vendors_model->get_dropdown_list(array("company_name"));
    
        // Example of assigning $members_dropdown
        $view_data['members_dropdown'] = array(
            '1' => 'Member 1',
            '2' => 'Member 2',
            '3' => 'Member 3'
        );
    
        $view_data['vendor_id'] = $vendor_id;
        $view_data['lut_dropdown'] = $this->_get_lut_dropdown_select2_data();
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("estimates", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
    
        $this->load->view('work_orders/modal_form', $view_data);
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
            "work_order_vendor_id" => "required|numeric",
            "work_order_date" => "required",
            "valid_until" => "required"
        ));

        $vendor_id = $this->input->post('work_order_vendor_id');
        $id = $this->input->post('id');

        $work_order_data = array(
            "vendor_id" => $vendor_id,
            "work_order_date" => $this->input->post('work_order_date'),
            "valid_until" => $this->input->post('valid_until'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "estimate_delivery_address" => $this->input->post('estimate_delivery_address') ? 1 : 0,
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
           
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
            //"terms_of_payment" => $this->input->post('terms_of_payment'),
            "terms_of_payment" => $this->input->post('work_order_payment_method_id'),
           "work_order_no" => $this->input->post('work_order_no'),
             "work_date" => $this->input->post('work_date'),
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
            "note" => $this->input->post('work_order_note'),
            "lut_number" => $this->input->post('lut_number')

        );

        //new  create new invoice no check already  exsits
        if($id){
    // check the invoice no already exits  update    
        $work_order_data["work_no"] = $this->input->post('work_no');
        if ($this->Work_orders_model->is_work_order_no_exists($work_order_data["work_no"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('wo_no_already')));
                exit();
            }
}
// create new invoice no check already  exsits 
if (!$id) {
$get_last_work_order_id = $this->Work_orders_model->get_last_work_order_id_exists();
$work_order_no_last_id = ($get_last_work_order_id->id+1);
$work_order_prefix = get_work_order_id($work_order_no_last_id);
 
        if ($this->Work_orders_model->is_work_order_no_exists($work_order_prefix)) {
                echo json_encode(array("success" => false, 'message' => $work_order_prefix." ".lang('po_no_already')));
                exit();
            }
}

//end  create new invoice no check already  exsits

        $work_order_id = $this->Work_orders_model->save($work_order_data, $id);
        if ($work_order_id) {

            // Save the new invoice no 
           if (!$id) {
               $work_order_prefix = get_work_order_id($work_order_id);
               $work_order_prefix_data = array(
                   
                    "work_no" => $work_order_prefix
                );
                $work_order_prefix_id = $this->Work_orders_model->save($work_order_prefix_data, $work_order_id);
            }
// End  the new invoice no 

            save_custom_fields("work_order", $work_order_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($work_order_id), 'id' => $work_order_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //update estimate status
  /*  function update_work_order_status($work_order_id, $status) {
        if ($work_order_id && $status) {
            $work_order_info = $this->Work_orders_model->get_one($work_order_id);
            //$this->access_only_allowed_members_or_vendor_contact($purchase_order_info->vendor_id);


            if ($this->login_user->user_type == "vendor") {
                //updating by client
                //client can only update the status once and the value should be either accepted or declined
                if ($work_order_info->status == "sent" && ($status == "accepted" || $status == "declined")) {

                    $work_order_data = array("status" => $status);
                    $work_order_id = $this->Work_orders_model->save($work_order_data, $work_order_id);

                    //create notification
                    if ($status == "accepted") {
                        log_notification("work_order_accepted", array("work_order_id" => $work_order_id));
                    } else if ($status == "declined") {
                        log_notification("work_order_rejected", array("work_order_id" => $work_order_id));
                    }
                }
            } else {
                //updating by team members

                if ($status == "sent" || $status == "accepted" || $status == "declined") {
                    $work_order_data = array("status" => $status);
                    $work_order_id = $this->Work_orders_model->save($work_order_data, $work_order_id);

                    //create notification
                    if ($status == "sent") {
                        log_notification("work_order_sent", array("work_order_id" => $work_order_id));
                    }
                }
            }
        }
    }
*/
    /* delete or undo an estimate */

    function delete() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Work_orders_model->deletefreight($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Work_orders_model->deletefreight($id)) {
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
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Work_orders_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of estimate of a specific client, prepared for datatable  */

    function work_order_list_data_of_vendor($vendor_id) {
        $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("vendor_id" => $vendor_id, "status" => $this->input->post("status"), "custom_fields" => $custom_fields);
//don't show draft invoices to client
        if ($this->login_user->user_type == "vendor") {
            $options["exclude_draft"] = true;
        }

        $list_data = $this->Work_orders_model->get_details($options)->result();
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
        $data = $this->Work_orders_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of estimate list table */

    private function _make_row($data, $custom_fields) {
       /* $work_order_url = "";
        if ($this->login_user->user_type == "staff") {
             $work_order_url = anchor(get_uri("work_orders/view/" . $data->id), get_work_order_id($data->id));
        } else {
            //for client client
            $work_order_url = anchor(get_uri("work_orders/preview/" . $data->id), get_work_order_id($data->id));
        }*/

        $work_order_no_value = $data->work_no ? $data->work_no: get_work_order_id($data->id);
        $work_order_no_url = "";
        if ($this->login_user->user_type == "staff") {
             $work_order_no_url = anchor(get_uri("work_orders/view/" . $data->id), $work_order_no_value);
        } else {
             $work_order_no_url = anchor(get_uri("work_orders/preview/" . $data->id), $work_order_no_value);
        }

        $due = 0;
        if ($data->work_order_value) {
            $due = ignor_minor_value($data->work_order_value - $data->payment_received);
        }

        $row_data = array(
            //$work_order_url,
            $data->id,
            $work_order_no_url,
            anchor(get_uri("vendors/view/" . $data->vendor_id), $data->company_name),
            $data->work_order_date,
            format_to_date($data->work_order_date, false),
            $data->valid_until,
            format_to_date($data->valid_until, false),
            to_currency($data->work_order_value, $data->currency_symbol),
             to_currency($data->payment_received, $data->currency_symbol),
             to_currency($due, $data->currency_symbol),
            $this->_get_work_order_status_label($data),
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("work_orders/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_estimate'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("work_orders/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    //prepare estimate status label 
  /*  private function _get_work_order_status_label($work_order_info, $return_html = true) {
        $work_order_status_class = "label-default";

        //don't show sent status to client, change the status to 'new' from 'sent'

        if ($this->login_user->user_type == "vendor") {
            if ($work_order_info->status == "sent") {
                $work_order_info->status = "new";
            } else if ($work_order_info->status == "declined") {
                $work_order_info->status = "rejected";
            }
        }

        if ($work_order_info->status == "draft") {
            $work_order_status_class = "label-default";
        } else if ($work_order_info->status == "declined" || $work_order_info->status == "rejected") {
            $work_order_status_class = "label-danger";
        } else if ($work_order_info->status == "accepted") {
            $work_order_status_class = "label-success";
        } else if ($work_order_info->status == "sent") {
            $work_order_status_class = "label-primary";
        } else if ($work_order_info->status == "new") {
            $work_order_status_class = "label-warning";
        }

        $work_order_status = "<span class='label $work_order_status_class large'>" . lang($work_order_info->status) . "</span>";
        if ($return_html) {
            return $work_order_status;
        } else {
            return $work_order_info->status;
        }
    } */
 private function _get_work_order_status_label($data, $return_html = true) {
        return get_work_order_status_label($data, $return_html);
    }

    /* load estimate details view */

    function view($work_order_id = 0) {
        $this->access_only_allowed_members();

        if ($work_order_id) {

            $view_data = get_work_order_making_data($work_order_id);

            if ($view_data) {
                $view_data['work_order_status_label'] = $this->_get_work_order_status_label($view_data["work_order_info"]);
                $view_data['work_order_status'] = $this->_get_work_order_status_label($view_data["work_order_info"], false); 

                $access_info = $this->get_access_info("invoice");
                $view_data["show_invoice_option"] = (get_setting("module_invoice") && $access_info->access_type == "all") ? true : false;
                
                $view_data["can_create_projects"] = $this->can_create_projects();

                $this->template->rander("work_orders/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* estimate total section */

    private function _get_work_order_total_view($work_order_id = 0) {
        $view_data["work_order_total_summary"] = $this->Work_orders_model->get_work_order_total_summary($work_order_id);
        return $this->load->view('work_orders/work_order_total_section', $view_data, true);
    }

    /* load item modal */

        function item_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

         $work_order_id = $this->input->post('work_order_id');

         $view_data['model_info'] = $this->Work_order_items_model->get_one($this->input->post('id'));
         if (!$work_order_id) {
            $work_order_id = $view_data['model_info']->work_order_id;
         }
         $optionss = array("id" => $work_order_id);
         $datas = $this->Work_orders_model->get_details($optionss)->row();
         $view_data['country'] = $datas->country;
         $view_data['buyer_type'] = $datas->buyer_type;
         $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $view_data['work_order_id'] = $work_order_id;
        $this->load->view('work_orders/item_modal_form', $view_data);
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
        $this->access_only_allowed_members();
    
        validate_submitted_data(array(
            "id" => "numeric",
            "work_order_id" => "required|numeric"
        ));
    
        $work_order_id = $this->input->post('work_order_id');
    
        $id = $this->input->post('id');
        $rate = floatval(unformat_currency($this->input->post('work_order_item_rate')));
        $quantity = floatval(unformat_currency($this->input->post('work_order_item_quantity')));
        $gst = floatval(unformat_currency($this->input->post('work_order_item_gst')));
        $discount_percentage = floatval(unformat_currency($this->input->post('discount_percentage')));
        
        $total = $rate * $quantity;
        $discount_amount = $total * $discount_percentage / 100;
        $discount = $total - $discount_amount;
        $tax = $discount * $gst / 100; 
        $net_total = $tax + $discount;
    
        $ss = $this->input->post('with_gst');
    
        if($ss == "yes"){
            $work_order_item_data = array(
                "work_order_id" => $work_order_id,
                "title" => $this->input->post('work_order_item_title'),
                "description" => $this->input->post('work_order_item_description'),
                "category" => $this->input->post('work_order_item_category'),
                "hsn_code" => $this->input->post('work_order_item_hsn_code'),
                "gst" => $this->input->post('work_order_item_gst'),
                "hsn_description" => $this->input->post('work_order_item_hsn_code_description'),
                "quantity" => $quantity,
                "unit_type" => $this->input->post('work_order_unit_type'),
                "rate" => $rate,
                "with_gst" => $this->input->post('with_gst'),
                "discount_percentage" => $this->input->post('discount_percentage'),
                "total" => $discount,
                "tax_amount" => $tax,
                "net_total" => $net_total,
                "quantity_total" => $total,
            );
        } else {
            $work_order_item_data = array(
                "work_order_id" => $work_order_id,
                "title" => $this->input->post('work_order_item_title'),
                "description" => $this->input->post('work_order_item_description'),
                "category" => $this->input->post('work_order_item_category'),
                "hsn_code" => "-",
                "gst" => 0,
                "hsn_description" => "-",
                "quantity" => $quantity,
                "unit_type" => $this->input->post('work_order_unit_type'),
                "rate" => $rate,
                "with_gst" => $this->input->post('with_gst'),
                "discount_percentage" => $this->input->post('discount_percentage'),
                "total" => $discount,
                "tax_amount" => 0,
                "net_total" => $discount,
                "quantity_total" => $total,
            );
        }
    
        //check duplicate product
        if (!$id) {
            // check the invoice product no     
            $work_order_item_data["title"] = $this->input->post('work_order_item_title');
            
            if ($this->Work_order_items_model->is_wo_product_exists($work_order_item_data["title"], $work_order_id)) {
                echo json_encode(array("success" => false, 'message' => lang('job_id_already')));
                exit();
            }
        }
        if ($id) {
            // check the invoice product no     
            $work_order_item_data["title"] = $this->input->post('work_order_item_title');
            $work_order_item_data["id"] = $this->input->post('id');
            if ($this->Work_order_items_model->is_wo_product_exists($work_order_item_data["title"], $work_order_id, $id)) {
                echo json_encode(array("success" => false, 'message' => lang('job_id_already')));
                exit();
            }
        }
    
        //end check duplicate product
    
        $work_order_item_id = $this->Work_order_items_model->save($work_order_item_data, $id);
        if ($work_order_item_id) {
            //check if the add_new_item flag is on, if so, add the item to library. 
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('work_order_item_title'),
                    "description" => $this->input->post('work_order_item_description'),
                    "unit_type" => $this->input->post('work_order_unit_type'),
                    "rate" => $rate,
                    "category" => $this->input->post('work_order_item_category'),
                    "hsn_code" => $this->input->post('work_order_item_hsn_code'),
                    "gst" => $this->input->post('work_order_item_gst'),
                    "hsn_description" => $this->input->post('work_order_item_hsn_code_description'),
                );
                $library_item_data["title"] = $this->input->post('work_order_item_title');
                if (!$this->Outsource_jobs_model->is_outsource_job_exists($library_item_data["title"])) {
                    $this->Outsource_jobs_model->save($library_item_data);
                }
            }
    
            $add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
            if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    "hsn_code" => $this->input->post('work_order_item_hsn_code'),
                    "gst" => $this->input->post('work_order_item_gst'),
                    "hsn_description" => $this->input->post('work_order_item_hsn_code_description')
                );
                $library_item_data["hsn_code"] = $this->input->post('work_order_item_title');
                if (!$this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                    $this->Hsn_sac_code_model->save($library_item_data);
                }
            }
    
            $options = array("id" => $work_order_item_id);
            $item_info = $this->Work_order_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "work_order_id" => $item_info->work_order_id, "data" => $this->_make_item_row($item_info), "work_order_total_view" => $this->_get_work_order_total_view($item_info->work_order_id), 'id' => $work_order_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
    

    /* delete or undo an estimate item */

    function delete_item() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Work_order_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Work_order_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "work_order_id" => $item_info->work_order_id, "data" => $this->_make_item_row($item_info), "work_order_total_view" => $this->_get_work_order_total_view($item_info->work_order_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Work_order_items_model->delete($id)) {
                $item_info = $this->Work_order_items_model->get_one($id);
                echo json_encode(array("success" => true, "work_order_id" => $item_info->work_order_id, "work_order_total_view" => $this->_get_work_order_total_view($item_info->work_order_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimate items, prepared for datatable  */

    function item_list_data($work_order_id = 0) {
        $this->access_only_allowed_members();

        $list_data = $this->Work_order_items_model->get_details(array("work_order_id" => $work_order_id))->result();
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

        return array(
            $item,
            $data->category,
           // $data->make,
            $data->hsn_code,
            $data->gst."%",
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
           to_currency($data->quantity_total, $data->currency_symbol),
            //to_currency($data->total, $data->currency_symbol),
            to_currency($data->tax_amount, $data->currency_symbol),
            $data->discount_percentage."%",
            //to_currency($data->net_total),
            to_currency($data->total, $data->currency_symbol),
            
            modal_anchor(get_uri("work_orders/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("work_orders/delete_item"), "data-action" => "delete-confirmation"))
        );
    }

    /* prepare suggestion of estimate item */

     function get_estimate_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();
        $options = array("work_order_id" => $_REQUEST["s"] );
        $list_data = $this->Work_order_items_model->get_details($options)->result();
        if($list_data){
        $work_order_items = array();
        foreach ($list_data as $code) {
            $work_order_items[] = $code->title;
        }
$aa=json_encode($work_order_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}

        $items = $this->Work_order_items_model->get_item_suggestion($key,$d_item);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_outsource_job"));

        echo json_encode($suggestion);
    }

/*    function get_estimate_item_info_suggestion() {
        $item = $this->Work_order_items_model->get_item_info_suggestion($this->input->post("item_name"));

        $itemss =  $this->Work_order_items_model->get_item_suggestionss($this->input->post("s"));

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
            echo json_encode(array("success" => true,"item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    } */

    function get_estimate_item_info_suggestion() {
        $item = $this->Work_order_items_model->get_item_info_suggestion($this->input->post("item_name"));

        $itemss =  $this->Work_order_items_model->get_item_suggestionss($this->input->post("s"));
   
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
            echo json_encode(array("success" => true,"item_infos" => $response_value, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
     function preview($work_order_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($work_order_id) {

            $work_order_data = get_work_order_making_data($work_order_id);
            $this->_check_work_order_access_permission($work_order_data);

            //get the label of the estimate
            $work_order_info = get_array_value($work_order_data, "work_order_info");
            $work_order_data['work_order_status_label'] = $this->_get_work_order_status_label($work_order_info);

            $view_data['work_order_preview'] = prepare_work_order_pdf($work_order_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_work_order_net_banking_payment_methods();


            $view_data['work_order_id'] = $work_order_id;

            $this->template->rander("work_orders/work_order_preview", $view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($work_order_id = 0) {
        if ($work_order_id) {
            $work_order_data = get_work_order_making_data($work_order_id);
            $this->_check_work_order_access_permission($work_order_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_work_order_pdf($work_order_data, "download");
        } else {
            show_404();
        }
    }


function download_work_order_without_gst_pdf($work_order_id = 0) {
        if ($work_order_id) {
            $work_order_data = get_work_order_making_data($work_order_id);
            $this->_check_work_order_access_permission($work_order_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_work_order_without_gst_pdf($work_order_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_work_order_access_permission($work_order_data) {
        //check for valid estimate
        if (!$work_order_data) {
            show_404();
        }

        //check for security
        $work_order_info = get_array_value($work_order_data, "work_order_info");
        if ($this->login_user->user_type == "vendor") {

           
            if ($this->login_user->vendor_id != $work_order_info->vendor_id) {
                redirect("forbidden");
            }
        } else {
            $this->access_only_allowed_members();
        }
    }

    function get_work_order_status_bar($work_order_id = 0) {
        $this->access_only_allowed_members();

        $view_data["work_order_info"] = $this->Work_orders_model->get_details(array("id" => $work_order_id))->row();
        $view_data['work_order_status_label'] = $this->_get_work_order_status_label($view_data["work_order_info"]);
        $this->load->view('work_orders/work_order_status_bar', $view_data);
    }
     function set_work_order_status_to_not_paid($work_order_id = 0) {
        $this->access_only_allowed_members();

        if ($work_order_id) {
            //change the draft status of the invoice
            $this->Work_orders_model->set_work_order_status_to_not_paid($work_order_id);
        }
        return "";
    }


    function freight_modal_form() {
        $this->access_only_allowed_members();

      validate_submitted_data(array(
          "work_order_id" => "required|numeric"
        )); 

       $work_order_id = $this->input->post('work_order_id');

       $view_data['model_info'] = $this->Work_orders_model->get_one($work_order_id);
       $optionss = array("id" => $work_order_id);
        $datas = $this->Work_orders_model->get_details($optionss)->row();
        $view_data['country'] = $datas->country;

    $this->load->view('work_orders/freight_modal_form', $view_data);
    }

    function save_freight() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "work_order_id" => "required|numeric",
           
            "freight_amount" => "numeric"
            
        ));

        $work_order_id = $this->input->post('work_order_id');

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

        $save_data = $this->Work_orders_model->save($data, $work_order_id);
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
            echo json_encode(array("success" => true, "work_order_total_view" => $this->_get_work_order_total_view($work_order_id), 'message' => lang('record_saved'), "work_order_id" => $work_order_id));
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

     function get_vendor_country_info_suggestion($item_name) {
        $this->db->select('*');
        $this->db->from('vendors');
        $this->db->like('country_name', $item_name);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}


/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */