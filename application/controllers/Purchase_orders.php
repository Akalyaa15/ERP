<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_orders extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("purchase_order");
    }

    /* load estimate list view */
    function index() {
        $this->check_module_availability("module_purchase_order");
        // $view_data['can_request_estimate'] = false;

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        if ($this->login_user->user_type === "staff") {
            $this->access_only_allowed_members();
            $this->template->rander("purchase_orders/index", $view_data);
        } else {
            // client view
            $view_data["vendor_info"] = $this->Vendors_model->get_one($this->login_user->vendor_id);
            $view_data['vendor_id'] = $this->login_user->vendor_id;
            $view_data['page_type'] = "full";

            // if (get_setting("module_estimate_request") == "1") {
            //     $view_data['can_request_estimate'] = true;
            // }

            $this->template->rander("vendors/purchase_orders/vendor_portal", $view_data);
        }
    }

    // load the yearly view of estimate list
    function yearly() {
        $this->load->view("purchase_orders/yearly_purchase_orders");
    }

    /* load new estimate modal */
    function modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "vendor_id" => "numeric"
        ));

        $vendor_id = $this->input->post('vendor_id');
        $view_data['model_info'] = $this->Purchase_orders_model->get_one($this->input->post('id'));

        // Define and initialize client_id
        $client_id = null;
        if (!empty($view_data['model_info']->client_id)) {
            $client_id = $view_data['model_info']->client_id;
        }

        // make the dropdown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['dispatched_through_dropdown'] = array("" => "-") + $this->Mode_of_dispatch_model->get_dropdown_list(array("title"), "id", array("status" => "active"));
        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "title", array("online_payable" => 0, "deleted" => 0));
        $view_data['vendors_dropdown'] = array("" => "-") + $this->Vendors_model->get_dropdown_list(array("company_name"));
        $view_data['lut_dropdown'] = $this->_get_lut_dropdown_select2_data();

        $view_data['vendor_id'] = $vendor_id;
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("estimates", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('purchase_orders/modal_form', $view_data);
    }

    private function _get_lut_dropdown_select2_data($show_header = false) {
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
            "purchase_order_vendor_id" => "required|numeric",
            "purchase_order_date" => "required",
            "valid_until" => "required"
        ));

        // Further implementation for saving the purchase order

        $vendor_id = $this->input->post('purchase_order_vendor_id');
        $id = $this->input->post('id');

        $purchase_order_data = array(
            "vendor_id" => $vendor_id,
            "purchase_order_date" => $this->input->post('purchase_order_date'),
            "valid_until" => $this->input->post('valid_until'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "estimate_delivery_address" => $this->input->post('estimate_delivery_address') ? 1 : 0,
            "delivery_address_company_name"=>$this->input->post('delivery_address_company_name'),
           
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
            //"terms_of_payment" => $this->input->post('terms_of_payment'),
            "terms_of_payment" => $this->input->post('purchase_order_payment_method_id'),
           "purchase_order_no" => $this->input->post('purchase_order_no'),
             "purchase_date" => $this->input->post('purchase_date'),
             "destination" => $this->input->post('destination'),
            "dispatch_document_no" => $this->input->post('dispatch_document_no'),
            "dispatched_through" => $this->input->post('dispatched_through'),
            "terms_of_delivery" => $this->input->post('terms_of_delivery'),
            "delivery_address" => $this->input->post('delivery_address'),
             "delivery_address_state" => $this->input->post('delivery_address_state'),
              "delivery_address_city" => $this->input->post('delivery_address_city'),
              "delivery_address_phone" => $this->input->post('delivery_address_phone'),
              "delivery_address_country" => $this->input->post('delivery_address_country'),
               "delivery_address_zip" => $this->input->post('delivery_address_zip'),
           "without_gst" => $this->input->post('without_gst')? 1 : 0,
            "note" => $this->input->post('purchase_order_note'),
            "lut_number" => $this->input->post('lut_number')

        );

//new  create new invoice no check already  exsits
        if($id){
    // check the invoice no already exits  update    
        $purchase_order_data["purchase_no"] = $this->input->post('purchase_no');
        if ($this->Purchase_orders_model->is_purchase_order_no_exists($purchase_order_data["purchase_no"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('po_no_already')));
                exit();
            }
}
// create new invoice no check already  exsits 
if (!$id) {
$get_last_purchase_order_id = $this->Purchase_orders_model->get_last_purchase_order_id_exists();
$purchase_order_no_last_id = ($get_last_purchase_order_id->id+1);
$purchase_order_prefix = get_purchase_order_id($purchase_order_no_last_id);
 
        if ($this->Purchase_orders_model->is_purchase_order_no_exists($purchase_order_prefix)) {
                echo json_encode(array("success" => false, 'message' => $purchase_order_prefix." ".lang('po_no_already')));
                exit();
            }
}

//end  create new invoice no check already  exsits

        $purchase_order_id = $this->Purchase_orders_model->save($purchase_order_data, $id);
        if ($purchase_order_id) {

            // Save the new invoice no 
           if (!$id) {
               $purchase_order_prefix = get_purchase_order_id($purchase_order_id);
               $purchase_order_prefix_data = array(
                   
                    "purchase_no" => $purchase_order_prefix
                );
                $purchase_order_prefix_id = $this->Purchase_orders_model->save($purchase_order_prefix_data, $purchase_order_id);
            }
// End  the new invoice no 

            save_custom_fields("purchase_order", $purchase_order_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($purchase_order_id), 'id' => $purchase_order_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //update estimate status
  /*  function update_purchase_order_status($purchase_order_id, $status) {
        if ($purchase_order_id && $status) {
            $purchase_order_info = $this->Purchase_orders_model->get_one($purchase_order_id);
            //$this->access_only_allowed_members_or_vendor_contact($purchase_order_info->vendor_id);


            if ($this->login_user->user_type == "vendor") {
                //updating by client
                //client can only update the status once and the value should be either accepted or declined
                if ($purchase_order_info->status == "sent" && ($status == "accepted" || $status == "declined")) {

                    $purchase_order_data = array("status" => $status);
                    $purchase_order_id = $this->Purchase_orders_model->save($purchase_order_data, $purchase_order_id);

                    //create notification
                    if ($status == "accepted") {
                        log_notification("purchase_order_accepted", array("purchase_order_id" => $purchase_order_id));
                    } else if ($status == "declined") {
                        log_notification("purchase_order_rejected", array("purchase_order_id" => $purchase_order_id));
                    }
                }
            } else {
                //updating by team members

                if ($status == "sent" || $status == "accepted" || $status == "declined") {
                    $purchase_order_data = array("status" => $status);
                    $purchase_order_id = $this->Purchase_orders_model->save($purchase_order_data, $purchase_order_id);

                    //create notification
                    if ($status == "sent") {
                        log_notification("purchase_order_sent", array("purchase_order_id" => $purchase_order_id));
                    }
                }
            }
        }
    } */

    /* delete or undo an estimate */

    function delete() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Purchase_orders_model->deletefreight($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Purchase_orders_model->deletefreight($id)) {
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

        $list_data = $this->Purchase_orders_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of estimate of a specific client, prepared for datatable  */

    function purchase_order_list_data_of_vendor($vendor_id) {
        $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("vendor_id" => $vendor_id, "status" => $this->input->post("status"), "custom_fields" => $custom_fields);

       //don't show draft invoices to client
        if ($this->login_user->user_type == "vendor") {
            $options["exclude_draft"] = true;
        }


        $list_data = $this->Purchase_orders_model->get_details($options)->result();
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
        $data = $this->Purchase_orders_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of estimate list table */

    private function _make_row($data, $custom_fields) {
       /* $purchase_order_url = "";
        if ($this->login_user->user_type == "staff") {
             $purchase_order_url = anchor(get_uri("purchase_orders/view/" . $data->id), get_purchase_order_id($data->id));
        } else {
            //for client client
            $purchase_order_url = anchor(get_uri("purchase_orders/preview/" . $data->id), get_purchase_order_id($data->id));
        }*/

        $purchase_order_no_value = $data->purchase_no ? $data->purchase_no: get_purchase_order_id($data->id);
        $purchase_order_no_url = "";
        if ($this->login_user->user_type == "staff") {
             $purchase_order_no_url = anchor(get_uri("purchase_orders/view/" . $data->id), $purchase_order_no_value);
        } else {
             $purchase_order_no_url = anchor(get_uri("purchase_orders/preview/" . $data->id), $purchase_order_no_value);
        }

        $due = 0;
        if ($data->purchase_order_value) {
            $due = ignor_minor_value($data->purchase_order_value - $data->payment_received);
        }
//vendors invoice status 
        $options = array("purchase_order_id" => $data->id);
$list_data = $this->Vendors_invoice_list_model->get_details($options)->result();
if($list_data && $data->modified=='0'){
//$purchase_order_status = "<span class='label $purchase_order_status_class large'>" . lang($status) . "</span>";  
$purchase_order_status_class = "label-danger"; 
$purchase_status ="<span class='label $purchase_order_status_class large'>" . lang('created_vendor_invoice') . "</span>";
}else{
   $purchase_status=$this->_get_purchase_order_status_label($data);
}

        $row_data = array(
            //$purchase_order_url,
            $data->id,
            $purchase_order_no_url,
            anchor(get_uri("vendors/view/" . $data->vendor_id), $data->company_name),
            $data->purchase_order_date,
            format_to_date($data->purchase_order_date, false),
            $data->valid_until,
            format_to_date($data->valid_until, false),
            to_currency($data->purchase_order_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol),
            
            $purchase_status
            //$this->_get_purchase_order_status_label($data),
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

      /*  $row_data[] = modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_estimate'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_estimate'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete"), "data-action" => "delete-confirmation")); */
    if($list_data && $data->modified=='0'){
$row_data[] = "-";
}else{
$row_data[] = modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_po'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete"), "data-action" => "delete-confirmation"));
}

        return $row_data;
    }

    private function _get_purchase_order_status_label($data, $return_html = true) {
        return get_purchase_order_status_label($data, $return_html);
    }

    //prepare estimate status label 
  /*  private function _get_purchase_order_status_label($purchase_order_info, $return_html = true) {
        $purchase_order_status_class = "label-default";

        //don't show sent status to client, change the status to 'new' from 'sent'

        if ($this->login_user->user_type == "vendor") {
            if ($purchase_order_info->status == "sent") {
                $purchase_order_info->status = "new";
            } else if ($purchase_order_info->status == "declined") {
                $purchase_order_info->status = "rejected";
            }
        }

        if ($purchase_order_info->status == "draft") {
            $purchase_order_status_class = "label-default";
        } else if ($purchase_order_info->status == "declined" || $purchase_order_info->status == "rejected") {
            $purchase_order_status_class = "label-danger";
        } else if ($purchase_order_info->status == "accepted") {
            $purchase_order_status_class = "label-success";
        } else if ($purchase_order_info->status == "sent") {
            $purchase_order_status_class = "label-primary";
        } else if ($purchase_order_info->status == "new") {
            $purchase_order_status_class = "label-warning";
        }

        $purchase_order_status = "<span class='label $purchase_order_status_class large'>" . lang($purchase_order_info->status) . "</span>";
        if ($return_html) {
            return $purchase_order_status;
        } else {
            return $purchase_order_info->status;
        }
    } */
    


    /* load estimate details view */

    function view($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        if ($purchase_order_id) {

            $view_data = get_purchase_order_making_data($purchase_order_id);

            if ($view_data) {
                $view_data['purchase_order_status_label'] = $this->_get_purchase_order_status_label($view_data["purchase_order_info"]); 
                $view_data['purchase_order_status'] = $this->_get_purchase_order_status_label($view_data["purchase_order_info"], false);

                $access_info = $this->get_access_info("invoice");
                $view_data["show_invoice_option"] = (get_setting("module_invoice") && $access_info->access_type == "all") ? true : false;
                
                $view_data["can_create_projects"] = $this->can_create_projects();

                $this->template->rander("purchase_orders/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* estimate total section */

    private function _get_purchase_order_total_view($purchase_order_id = 0) {
        $view_data["purchase_order_total_summary"] = $this->Purchase_orders_model->get_purchase_order_total_summary($purchase_order_id);
        return $this->load->view('purchase_orders/purchase_order_total_section', $view_data, true);
    }

    /* load item modal */

    function item_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $purchase_order_id = $this->input->post('purchase_order_id');

        $view_data['model_info'] = $this->Purchase_order_items_model->get_one($this->input->post('id'));
        if (!$purchase_order_id) {
            $purchase_order_id = $view_data['model_info']->purchase_order_id;
        }
        $optionss = array("id" => $purchase_order_id);
         $datas = $this->Purchase_orders_model->get_details($optionss)->row();
         $view_data['country'] = $datas->country;
         $view_data['buyer_type'] = $datas->buyer_type;
        $view_data["unit_type_dropdown"] = $this->_get_unit_type_dropdown_select2_data();
        $view_data['purchase_order_id'] = $purchase_order_id;

        $manufactures = $this->Manufacturer_model->get_all_where(array("deleted" => 0 , "status" => "active"), 0, 0, "title")->result();

        $make_dropdown = array(array("id" => "", "text" => "- " ));
        foreach ($manufactures as $manufacture) {
            $make_dropdown[] = array("id" => $manufacture->id, "text" => $manufacture->title);
        }
        $view_data['make_dropdown'] = json_encode($make_dropdown);
        // product category
        $product_categories_dropdowns = $this->Product_categories_model->get_all_where(array("deleted" => 0,"status"=>"active"))->result();

        $product_categories_dropdown = array(array("id"=>"", "text" => "-"));

        foreach ($product_categories_dropdowns as $product_categories) {
            $product_categories_dropdown[] = array("id" => $product_categories->id, "text" => $product_categories->title );

        }

       // $product_categories_dropdown[] = array("id"=> "+" ,"text"=> "+ " . lang("create_new_category"));

        
         $view_data['product_categories_dropdown'] =json_encode($product_categories_dropdown);

        $this->load->view('purchase_orders/item_modal_form', $view_data);
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
            "purchase_order_id" => "required|numeric"
        ));

        $purchase_order_id = $this->input->post('purchase_order_id');

        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('purchase_order_item_rate'));
        $quantity = unformat_currency($this->input->post('purchase_order_item_quantity'));
        $gst = unformat_currency($this->input->post('purchase_order_item_gst'));
         $discount_percentage = unformat_currency($this->input->post('discount_percentage'));
         $total =$rate * $quantity;
         $discount_amount = $total*$discount_percentage/100;
         $discount = $total-$discount_amount;
         $tax=$discount*$gst/100;
         $net_total = $discount+$tax;
         /*$total=$rate * $quantity;
         $tax=$total*$gst/100;
         $tax_total =$total+$tax;
         $net_total =$tax_total; */




$ss=$this->input->post('with_gst');

if($ss=="yes"){
        $purchase_order_item_data = array(
            "purchase_order_id" => $purchase_order_id,
            "title" => $this->input->post('purchase_order_item_title'),
            "description" => $this->input->post('purchase_order_item_description'),
             "category" => $this->input->post('purchase_order_item_category'),
            "make" => $this->input->post('purchase_order_item_make'),
            "hsn_code" => $this->input->post('purchase_order_item_hsn_code'),
            "gst" => $this->input->post('purchase_order_item_gst'),
            "hsn_description" => $this->input->post('purchase_order_item_hsn_code_description'),
            "quantity" => $quantity,
            "unit_type" => $this->input->post('purchase_order_unit_type'),
            "rate" => unformat_currency($this->input->post('purchase_order_item_rate')),
             "discount_percentage" => $this->input->post('discount_percentage'),
              "with_gst" => $this->input->post('with_gst'),
            "quantity_total"=>$total,
            "total" => $discount,
            "tax_amount" =>$tax,
            "net_total" =>$net_total,
        );
    } else {
        $purchase_order_item_data = array(
            "purchase_order_id" => $purchase_order_id,
            "title" => $this->input->post('purchase_order_item_title'),
            "description" => $this->input->post('purchase_order_item_description'),
             "category" => $this->input->post('purchase_order_item_category'),
            "make" => $this->input->post('purchase_order_item_make'),
            "hsn_code" => "-",
            "gst" => 0,
            "hsn_description" => "-",
            "quantity" => $quantity,
            "unit_type" => $this->input->post('purchase_order_unit_type'),
            "rate" => unformat_currency($this->input->post('purchase_order_item_rate')),
             "discount_percentage" => $this->input->post('discount_percentage'),
              "with_gst" => $this->input->post('with_gst'),
              "quantity_total"=>$total,
            "total" => $discount,
            "tax_amount" => 0,
            "net_total" => $discount,
        );

    }


//check duplicate product
 if (!$id) {
    // check the invoice product no     
        $purchase_order_item_data["title" ] =$this->input->post('purchase_order_item_title');
        //$invoice_item_data["title" ] =$this->input->post('invoice_item_title');
        
        if ($this->Purchase_order_items_model->is_po_product_exists($purchase_order_item_data["title" ],$purchase_order_id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }
        if ($id) {
    // check the  invoice product no     
       $purchase_order_item_data["title" ] =$this->input->post('purchase_order_item_title');
        $purchase_order_item_data["id"] =$this->input->post('id');
       if ($this->Purchase_order_items_model->is_po_product_exists($purchase_order_item_data["title" ],$purchase_order_id,$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_product_id')));
                exit();
            }

        }

//end check duplicate product
        $purchase_order_item_id = $this->Purchase_order_items_model->save($purchase_order_item_data, $id);
        if ($purchase_order_item_id) {


            //check if the add_new_item flag is on, if so, add the item to libary. 
           $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('purchase_order_item_title'),
                    "description" => $this->input->post('purchase_order_item_description'),
                    "unit_type" => $this->input->post('purchase_order_unit_type'),
                    "rate" => unformat_currency($this->input->post('purchase_order_item_rate')),
                    "category" => $this->input->post('purchase_order_item_category'),
                "make" => $this->input->post('purchase_order_item_make'),
                "hsn_code" => $this->input->post('purchase_order_item_hsn_code'),
                "gst" => $this->input->post('purchase_order_item_gst'),
                "hsn_description" => $this->input->post('purchase_order_item_hsn_code_description'),

                );
                $library_item_data["title"] = $this->input->post('purchase_order_item_title');
        if (!$this->Part_no_generation_model->is_part_no_generation_exists($library_item_data["title"])) {
                
               $product_generation_id_save = $this->Part_no_generation_model->save($library_item_data);
            }
                //$this->Part_no_generation_model->save($library_item_data);
            }

            $add_new_item_to_librarys = $this->input->post('add_new_item_to_librarys');
             if ($add_new_item_to_librarys) {
                $library_item_data = array(
                    
                    "hsn_code" => $this->input->post('purchase_order_item_hsn_code'),
                    "gst" => $this->input->post('purchase_order_item_gst'),
                    "hsn_description" => $this->input->post('purchase_order_item_hsn_code_description')
                    
                );
                 $library_item_data["hsn_code"] = $this->input->post('purchase_order_item_hsn_code');
        if (!$this->Hsn_sac_code_model->is_hsn_code_exists($library_item_data["hsn_code"])) {
                
               $this->Hsn_sac_code_model->save($library_item_data);
            }
               // $this->Hsn_sac_code_model->save($library_item_data);
            }



            $options = array("id" => $purchase_order_item_id);
            $item_info = $this->Purchase_order_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "purchase_order_id" => $item_info->purchase_order_id, "data" => $this->_make_item_row($item_info), "purchase_order_total_view" => $this->_get_purchase_order_total_view($item_info->purchase_order_id), 'id' => $purchase_order_item_id, 'message' => lang('record_saved')));
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
            if ($this->Purchase_order_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Purchase_order_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "purchase_order_id" => $item_info->purchase_order_id, "data" => $this->_make_item_row($item_info), "purchase_order_total_view" => $this->_get_purchase_order_total_view($item_info->purchase_order_id_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Purchase_order_items_model->delete($id)) {
                $item_info = $this->Purchase_order_items_model->get_one($id);
                echo json_encode(array("success" => true, "purchase_order_id" => $item_info->purchase_order_id, "purchase_order_total_view" => $this->_get_purchase_order_total_view($item_info->purchase_order_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimate items, prepared for datatable  */

    function item_list_data($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        $list_data = $this->Purchase_order_items_model->get_details(array("purchase_order_id" => $purchase_order_id))->result();
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
//vendors invoice add 
$optionss = array("id" => $data->purchase_order_id);
$modifed_data = $this->Purchase_orders_model->get_details($optionss)->row();
$options = array("purchase_order_id" => $data->purchase_order_id);
$list_data = $this->Vendors_invoice_list_model->get_details($options)->result();
if($list_data && $modifed_data->modified == '0'){
$edit = "-";
}else{
    $edit = modal_anchor(get_uri("purchase_orders/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete_item"), "data-action" => "delete-confirmation"));
} 
$make_name = $this->Manufacturer_model->get_one($data->make);
$category_name = $this->Product_categories_model->get_one($data->category);

       return array(
            $item,
            //$data->category,
            /*$data->make,*/
            $category_name->title?$category_name->title:"-",
            $make_name->title ? $make_name->title:"-",
            $data->hsn_code,
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
            to_currency($data->quantity_total, $data->currency_symbol),
            
            $data->gst."%",
            to_currency($data->tax_amount, $data->currency_symbol),
            $data->discount_percentage."%",
            to_currency($data->total, $data->currency_symbol),
            //to_currency($data->net_total),
            $edit
           /* modal_anchor(get_uri("purchase_orders/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_estimate'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete_item"), "data-action" => "delete-confirmation")) */
        );
    }

    /* prepare suggestion of estimate item */

     function get_estimate_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();
        $options = array("purchase_order_id" => $_REQUEST["s"] );
$list_data = $this->Purchase_order_items_model->get_details($options)->result();
if($list_data){
        $purchase_order_items = array();
foreach ($list_data as $code) {
            $purchase_order_items[] = $code->title;
        }
$aa=json_encode($purchase_order_items);
$vv=str_ireplace("[","(",$aa);
$d_item=str_ireplace("]",")",$vv);
       
}else{
    $d_item="('empty')";
}

    $items = $this->Part_no_generation_model->get_part_no_suggestion($key,$d_item);
        
        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product_id"));

        echo json_encode($suggestion);
    }

   /* function get_estimate_item_info_suggestion() {
        $item = $this->Part_no_generation_model->get_part_no_info_suggestion($this->input->post("item_name"));

        $itemss =  $this->Part_no_generation_model->get_item_suggestionss($this->input->post("s"));

if (empty($itemss->currency))
 {
    $itemss->currency = "INR";
 }             //print_r($itemss->currency) ;

$currency= get_setting("default_currency")."_".$itemss->currency;              
/*$currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
     $response_value   =   $cur_val->$currency; */
   /*  $connected = @fsockopen("www.google.com", 80);            
if ($connected){
        $currency_rate = file_get_contents("https://free.currconv.com/api/v7/convert?q=$currency&compact=ultra&apiKey=7bf2a122b1e76ac358b8");
       $cur_val = json_decode($currency_rate);
    $response_value   =   $cur_val->$currency;
    }else{
        $response_value   =   'failed';
    } 


        if ($item) {
            echo json_encode(array("success" => true, "item_infos" => $response_value,"item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    } */

    function get_estimate_item_info_suggestion() {
        $item = $this->Part_no_generation_model->get_part_no_info_suggestion($this->input->post("item_name"));

        $itemss =  $this->Part_no_generation_model->get_item_suggestionss($this->input->post("s"));
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
}else if ($itemss->country == $default_country){
    $response_value   =   'same_country';
}


        if ($item) {
            echo json_encode(array("success" => true, "item_infos" => $response_value,"item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
    function preview($purchase_order_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($purchase_order_id) {

            $purchase_order_data = get_purchase_order_making_data($purchase_order_id);
            $this->_check_purchase_order_access_permission($purchase_order_data);

            //get the label of the estimate
            $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
            $purchase_order_data['purchase_order_status_label'] = $this->_get_purchase_order_status_label($purchase_order_info); 

            $view_data['purchase_order_preview'] = prepare_purchase_order_pdf($purchase_order_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_purchase_order_net_banking_payment_methods();
            $this->load->library("paypal");
            $view_data['paypal_url'] = $this->paypal->get_paypal_url();

            $view_data['purchase_order_id'] = $purchase_order_id;

            $this->template->rander("purchase_orders/purchase_order_preview", $view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($purchase_order_id = 0) {
        if ($purchase_order_id) {
            $purchase_order_data = get_purchase_order_making_data($purchase_order_id);
            $this->_check_purchase_order_access_permission($purchase_order_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_purchase_order_pdf($purchase_order_data, "download");
        } else {
            show_404();
        }
    }

    function download_purchase_order_without_gst_pdf($purchase_order_id = 0) {
        if ($purchase_order_id) {
            $purchase_order_data = get_purchase_order_making_data($purchase_order_id);
            $this->_check_purchase_order_access_permission($purchase_order_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_purchase_order_without_gst_pdf($purchase_order_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_purchase_order_access_permission($purchase_order_data) {
        //check for valid estimate
        if (!$purchase_order_data) {
            show_404();
        }

        //check for security
        $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
        if ($this->login_user->user_type == "vendor") {

           /* $DB2 = $this->load->database('default', TRUE);
 $DB2->select ("vendor_id");
 $DB2->from('users');
  $DB2->where('deleted',0);
 $DB2->where('id',$this->login_user->id);
 $query2=$DB2->get();
 $query2->result();  
foreach ($query2->result() as $rows)
    {
    $c=$rows->vendor_id;
   
   
        } */
            if ($this->login_user->vendor_id != $purchase_order_info->vendor_id) {
                redirect("forbidden");
            }
        } else {
            $this->access_only_allowed_members();
        }
    }

  /*  function get_purchase_order_status_bar($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        $view_data["purchase_order_info"] = $this->Purchase_orders_model->get_details(array("id" => $purchase_order_id))->row();
        $view_data['purchase_order_status_label'] = $this->_get_purchase_order_status_label($view_data["purchase_order_info"]);
        $this->load->view('purchase_orders/purchase_order_status_bar', $view_data);
    } */

    function get_purchase_order_status_bar($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        $view_data["purchase_order_info"] = $this->Purchase_orders_model->get_details(array("id" => $purchase_order_id))->row();
        $view_data['purchase_order_status_label'] = $this->_get_purchase_order_status_label($view_data["purchase_order_info"]);
        $this->load->view('purchase_orders/purchase_order_status_bar', $view_data);
    }

    function set_purchase_order_status_to_not_paid($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        if ($purchase_order_id) {
            //change the draft status of the invoice
            $this->Purchase_orders_model->set_purchase_order_status_to_not_paid($purchase_order_id);
        }
        return "";
    }


    function set_purchase_order_status_to_modified($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        if ($purchase_order_id) {
            //change the draft status of the invoice
            $this->Purchase_orders_model->set_purchase_order_status_to_modified($purchase_order_id);
        }
        return "";
    }

    function set_purchase_order_status_to_not_modified($purchase_order_id = 0) {
        $this->access_only_allowed_members();

        if ($purchase_order_id) {
            //change the draft status of the invoice
            $this->Purchase_orders_model->set_purchase_order_status_to_not_modified($purchase_order_id);
        }
        return "";
    }


    function freight_modal_form() {
        $this->access_only_allowed_members();

      validate_submitted_data(array(
          "purchase_order_id" => "required|numeric"
        )); 

       $purchase_order_id = $this->input->post('purchase_order_id');

       $view_data['model_info'] = $this->Purchase_orders_model->get_one($purchase_order_id);
$optionss = array("id" => $purchase_order_id);
        $datas = $this->Purchase_orders_model->get_details($optionss)->row();
        $view_data['country'] = $datas->country;

    $this->load->view('purchase_orders/freight_modal_form', $view_data);
    }

    function save_freight() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "purchase_order_id" => "required|numeric",
           
            "freight_amount" => "numeric"
            
        ));

        $purchase_order_id = $this->input->post('purchase_order_id');

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

        $save_data = $this->Purchase_orders_model->save($data, $purchase_order_id);
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
            echo json_encode(array("success" => true, "purchase_order_total_view" => $this->_get_purchase_order_total_view($purchase_order_id), 'message' => lang('record_saved'), "purchase_order_id" => $purchase_order_id));
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

    function get_vendor_country_item_info_suggestion() {
        $item = $this->Vendors_model->get_vendor_country_info_suggestion($this->input->post("item_name"));
       // $itemss =  $this->Countries_model->get_item_suggestions_country_name($this->input->post("country_name"));
//print_r($itemss);
    
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }}

/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */