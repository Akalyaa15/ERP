<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_orders extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("purchase_order");
    }

    // Load purchase order list view
    function index()
    {
        $this->check_module_availability("module_purchase_order");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table(
            "estimates", 
            $this->login_user->is_admin, 
            $this->login_user->user_type
        );

        if ($this->login_user->user_type === "staff") {
            $this->access_only_allowed_members();
            $this->template->rander("purchase_orders/index", $view_data);
        } else {
            // Client view
            $view_data["vendor_info"] = $this->Vendors_model->get_one($this->login_user->vendor_id);
            $view_data['vendor_id'] = $this->login_user->vendor_id;
            $view_data['page_type'] = "full";
            $this->template->rander("vendors/purchase_orders/vendor_portal", $view_data);
        }
    }

    // Load yearly view of purchase orders
    function yearly()
    {
        $this->load->view("purchase_orders/yearly_purchase_orders");
    }

    // Load new purchase order modal form
    // Load new purchase order modal form
// Load new purchase order modal form
// Load new purchase order modal form
// Load new purchase order modal form
function modal_form()
{
    $this->access_only_allowed_members();

    validate_submitted_data(array(
        "id" => "numeric",
        "vendor_id" => "numeric"
    ));

    $vendor_id = $this->input->post('vendor_id');
    $model_info = $this->Purchase_orders_model->get_one($this->input->post('id'));
    $project_client_id = $vendor_id;

    if ($model_info && property_exists($model_info, 'client_id') && $model_info->client_id) {
        $project_client_id = $model_info->client_id;
    }

    $view_data['model_info'] = $model_info;
    $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
    $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "title", array("online_payable" => 0, "deleted" => 0));
    $view_data['vendors_dropdown'] = array("" => "-") + $this->Vendors_model->get_dropdown_list(array("company_name"));
    $view_data['lut_dropdown'] = $this->_get_lut_dropdown_select2_data();
    
    $view_data['vendor_id'] = $vendor_id;
    $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details(
        "estimates", 
        $model_info->id, 
        $this->login_user->is_admin, 
        $this->login_user->user_type
    )->result();

    $this->load->view('purchase_orders/modal_form', $view_data);
}
 // Get LUT dropdown data for Select2
    private function _get_lut_dropdown_select2_data($show_header = false)
    {
        $luts = $this->Lut_number_model->get_all()->result();
        $lut_dropdown = array(array("id" => "", "text" => "-"));

        foreach ($luts as $code) {
            $lut_dropdown[] = array("id" => $code->lut_number, "text" => $code->lut_year);
        }
        return $lut_dropdown;
    }

    // Add or edit a purchase order
    function save()
    {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "purchase_order_vendor_id" => "required|numeric",
            "purchase_order_date" => "required",
            "valid_until" => "required"
        ));

        $vendor_id = $this->input->post('purchase_order_vendor_id');
        $id = $this->input->post('id');

        $purchase_order_data = array(
            "vendor_id" => $vendor_id,
            "purchase_order_date" => $this->input->post('purchase_order_date'),
            "valid_until" => $this->input->post('valid_until'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "estimate_delivery_address" => $this->input->post('estimate_delivery_address') ? 1 : 0,
            "delivery_address_company_name" => $this->input->post('delivery_address_company_name'),
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_ref" => $this->input->post('supplier_ref'),
            "other_references" => $this->input->post('other_references'),
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
            "delivery_address_country" => $this->input->post('delivery_address_country'),
            "delivery_address_zip" => $this->input->post('delivery_address_zip'),
            "without_gst" => $this->input->post('without_gst') ? 1 : 0,
            "note" => $this->input->post('purchase_order_note'),
            "lut_number" => $this->input->post('lut_number')
        );
        $purchase_order_id = $this->Purchase_orders_model->save($purchase_order_data, $id);
        if ($purchase_order_id) {
            save_custom_fields("purchase_order", $purchase_order_id, $this->login_user->is_admin, $this->login_user->user_type);
            echo json_encode(array("success" => true, "data" => $this->_row_data($purchase_order_id), 'id' => $purchase_order_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    // Delete or undo a purchase order
    function delete()
    {
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

    // List of purchase orders for datatable
    function list_data()
    {
        $this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table(
            "estimates", 
            $this->login_user->is_admin, 
            $this->login_user->user_type
        );

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

    // List of purchase orders for a specific vendor, prepared for datatable
    function purchase_order_list_data_of_vendor($vendor_id)
    {
        $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table(
            "estimates", 
            $this->login_user->is_admin, 
            $this->login_user->user_type
        );

        $options = array(
            "vendor_id" => $vendor_id,
            "status" => $this->input->post("status"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Purchase_orders_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    // Prepare a row of purchase order data for datatable
    private function _row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table(
            "estimates", 
            $this->login_user->is_admin, 
            $this->login_user->user_type
        );

        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $data = $this->Purchase_orders_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    // Prepare purchase order data row for datatable
    private function _make_row($data, $custom_fields)
    {
        $purchase_order_url = anchor(
            get_uri("purchase_orders/view/" . $data->id),
            get_purchase_order_id($data->id),
            array("class" => "edit", "title" => lang('purchase_order_details'))
        );

        $vendor = anchor(
            get_uri("vendors/view/" . $data->vendor_id),
            $data->vendor_name,
            array("class" => "edit", "title" => lang('vendor'))
        );

        $row_data = array(
            $purchase_order_url,
            $vendor,
            format_to_date($data->purchase_order_date, false),
            format_to_date($data->valid_until, false),
            to_currency($data->amount, $data->currency_symbol)
        );

        foreach ($custom_fields as $field) {
            $field_name = "cfv_" . $field->id;
            $row_data[] = view("custom_fields/output_" . $field->field_type, array("value" => $data->$field_name));
        }

        $row_data[] = modal_anchor(
            get_uri("purchase_orders/modal_form"),
            "<i data-feather='edit' class='icon-16'></i>",
            array("class" => "edit", "title" => lang('edit_purchase_order'), "data-post-id" => $data->id)
        )
            . js_anchor(
                "<i data-feather='x' class='icon-16'></i>",
                array('title' => lang('delete_purchase_order'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete"), "data-action" => "delete-confirmation")
            );

        return $row_data;
    }


    private function _make_item_row($data)
    {
        $item = "<b>$data->title</b>";
        if ($data->description) {
            $item .= "<br /><span>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";

        return array(
            $item,
            $data->category,
            $data->make,
            $data->hsn_code,
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
            to_currency($data->total, $data->currency_symbol),
            $data->gst . "%",
            to_currency($data->tax_amount, $data->currency_symbol),
            $data->discount_percentage . "%",
            to_currency($data->net_total),

            modal_anchor(get_uri("purchase_orders/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_estimate'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete_item"), "data-action" => "delete"))
        );
    }

    /* prepare suggestion of estimate item */

    function get_estimate_item_suggestion()
    {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Part_no_generation_model->get_part_no_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product_id"));

        echo json_encode($suggestion);
    }

    function get_estimate_item_info_suggestion()
    {
        $item = $this->Part_no_generation_model->get_part_no_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
    function preview($purchase_order_id = 0, $show_close_preview = false)
    {

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

    function download_pdf($purchase_order_id = 0)
    {
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

    function download_purchase_order_without_gst_pdf($purchase_order_id = 0)
    {
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

    private function _check_purchase_order_access_permission($purchase_order_data)
    {
        //check for valid estimate
        if (!$purchase_order_data) {
            show_404();
        }

        //check for security
        $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
        if ($this->login_user->user_type == "vendor") {


            if ($this->login_user->vendor_id != $purchase_order_info->vendor_id) {
                redirect("forbidden");
            } else {
                $this->access_only_allowed_members();
            }
        }
    }



    function get_purchase_order_status_bar($purchase_order_id = 0)
    {
        $this->access_only_allowed_members();

        $view_data["purchase_order_info"] = $this->Purchase_orders_model->get_details(array("id" => $purchase_order_id))->row();
        $view_data['purchase_order_status_label'] = $this->_get_purchase_order_status_label($view_data["purchase_order_info"]);
        $this->load->view('purchase_orders/purchase_order_status_bar', $view_data);
    }

    function set_purchase_order_status_to_not_paid($purchase_order_id = 0)
    {
        $this->access_only_allowed_members();

        if ($purchase_order_id) {
            //change the draft status of the invoice
            $this->Purchase_orders_model->set_purchase_order_status_to_not_paid($purchase_order_id);
        }
        return "";
    }


    function freight_modal_form()
    {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "purchase_order_id" => "required|numeric"
        ));

        $purchase_order_id = $this->input->post('purchase_order_id');

        $view_data['model_info'] = $this->Purchase_orders_model->get_one($purchase_order_id);

        $this->load->view('purchase_orders/freight_modal_form', $view_data);
    }

    function save_freight()
    {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "purchase_order_id" => "required|numeric",

            "freight_amount" => "numeric"

        ));

        $purchase_order_id = $this->input->post('purchase_order_id');

        $data = array(

            "freight_amount" => $this->input->post('freight_amount'),
            "hsn_code" => $this->input->post('hsn_code'),
            "hsn_description" => $this->input->post('hsn_description'),
            "gst" => $this->input->post('gst'),

        );

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


    function get_invoice_freight_suggestion()
    {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Hsn_sac_code_model->get_freight_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->hsn_code, "text" => $item->hsn_code);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_hsn_code"));

        echo json_encode($suggestion);
    }

    function get_invoice_freight_info_suggestion()
    {
        $item = $this->Hsn_sac_code_model->get_item_freight_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    function get_vendor_country_item_info_suggestion()
    {
        $item = $this->Vendors_model->get_vendor_country_info_suggestion($this->input->post("item_name"));
        // $itemss =  $this->Countries_model->get_item_suggestions_country_name($this->input->post("country_name"));
        //print_r($itemss);

        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    
    
    }
}



/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */