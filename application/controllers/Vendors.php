<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendors extends MY_Controller {

    function __construct() {
        parent::__construct();

        //check permission to access this module
        $this->init_permission_checker("vendor");
         $this->load->model('vendors_model');
        $this->load->library('excel');
    }

    /* load clients list view */

    function index() {
        $this->access_only_allowed_members();

        $access_info = $this->get_access_info("purchase_order");
        $view_data["show_invoice_info"] = (get_setting("module_purchase_order") && $access_info->access_type == "all") ? true : false;
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("clients", $this->login_user->is_admin, $this->login_user->user_type);
 
        $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));
        //get team members dropdown
    

        //$this->template->rander("vendors/index", $view_data);
        $this->template->rander_scroll("vendors/index", $view_data);
        
    }



    /* load client add/edit modal */

    function modal_form() {
        $this->access_only_allowed_members();

        $vendor_id = $this->input->post('id');
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['label_column'] = "col-md-3";
        $view_data['field_column'] = "col-md-9";

        $view_data["view"] = $this->input->post('view'); //view='details' needed only when loding from the client's details view
        $view_data['model_info'] = $this->Vendors_model->get_one($vendor_id);
        $view_data["currency_dropdown"] = $this->_get_currency_dropdown_select2_data();
        $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();
        //$view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();

        $country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
         $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $country_get_code->numberCode));
        
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }

        $view_data['state_dropdown'] = $state_categories_suggestion;

        //prepare groups dropdown list
        $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();

         $view_data['buyer_types_dropdown'] = $this->_get_buyer_types_dropdown_select2_data();

        //get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("clients", $vendor_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('vendors/modal_form', $view_data);
    }


     private function _get_buyer_types_dropdown_select2_data($show_header = false) {
        /*$buyer_types = $this->Buyer_types_model->get_all()->result();*/
       $buyer_types = $this->Buyer_types_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $buyer_types_dropdown = array();

        

        foreach ($buyer_types as $buyer_type) {
            $buyer_types_dropdown[] = array("id" => $buyer_type->id, "text" => $buyer_type->buyer_type);
        }
        return $buyer_types_dropdown;
    }

   private function _get_state_dropdown_select2_data($show_header = false) {
        $states = $this->States_model->get_all()->result();
        $state_dropdown = array();

        

        foreach ($states as $code) {
            $state_dropdown[] = array("id" => $code->id, "text" => $code->title);
        }
        return $state_dropdown;
    }


    private function _get_groups_dropdown_select2_data($show_header = false) {
        /*$vendor_groups = $this->Vendor_groups_model->get_all()->result();*/
        $vendor_groups = $this->Vendor_groups_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();

        $groups_dropdown = array();

        if ($show_header) {
            $groups_dropdown[] = array("id" => "", "text" => "- " . lang("vendor_groups") . " -");
        }

        foreach ($vendor_groups as $group) {
            $groups_dropdown[] = array("id" => $group->id, "text" => $group->title);
        }
        return $groups_dropdown;
    }

     private function _get_gst_code_dropdown_select2_data($show_header = false) {
        $gst_code = $this->Gst_state_code_model->get_all()->result();
        $gst_code_dropdown = array();

        

        foreach ($gst_code as $code) {
            $gst_code_dropdown[] = array("id" => $code->gstin_number_first_two_digits, "text" => $code->title);
        }
        return $gst_code_dropdown;
    }

    private function _get_currency_dropdown_select2_data() {
        $currency = array(array("id" => "", "text" => "-"));
        foreach (get_international_currency_code_dropdown() as $value) {
            $currency[] = array("id" => $value, "text" => $value);
        }
        return $currency;
    }

    /* insert or update a client */

    public function save() {
        $vendor_id = $this->input->post('id');

        $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        validate_submitted_data(array(
            "id" => "numeric",
            "company_name" => "required"
        ));

        $company_name = $this->input->post('company_name');
        $vendor_logo = $this->input->post('site_logo');
        $target_path = getcwd() . "/" . get_general_file_path("vendor", $vendor_id);
        $value = move_temp_file("vendors-logo.png", $target_path, "", $vendor_logo);

        $data = array(
            "company_name" => $company_name,
            "address" => $this->input->post('address'),
            "city" => $this->input->post('city'),
            "state" => $this->input->post('state'),
            "zip" => $this->input->post('zip'),
            "country" => $this->input->post('country'),
            "phone" => $this->input->post('phone'),
            "website" => $this->input->post('website'),
            "gst_number" => $this->input->post('gst_number'),
            "gstin_number_first_two_digits" => $this->input->post('gstin_number_first_two_digits'),
            "currency_symbol" => $this->input->post('currency_symbol'),
            "currency" => $this->input->post('currency'),
            "buyer_type" => $this->input->post('buyer_type'),
            "enable_vendor_logo" => $this->input->post('enable_vendor_logo'),
            "state_mandatory" => $this->input->post('state_mandatory'),
        );
        
        $vendor_info_logo = $this->Vendors_model->get_one($vendor_id);
        $vendor_logo_file = $vendor_info_logo->vendor_logo; 

        if ($vendor_logo && !$vendor_logo_file) {
            $data["vendor_logo"] = $value;
        } else if ($vendor_logo && $vendor_logo_file) {
            delete_file_from_directory(get_general_file_path("vendor", $vendor_id) . $vendor_logo_file);
            $data["vendor_logo"] = $value;
        }

        if ($this->login_user->user_type === "staff") {
            $data["group_ids"] = $this->input->post('group_ids') ? $this->input->post('group_ids') : "";
        }

        if (!$vendor_id) {
            $data["created_date"] = get_current_utc_time();
        }

        if ($this->login_user->is_admin) {
            $data["currency_symbol"] = $this->input->post('currency_symbol') ? $this->input->post('currency_symbol') : "";
            $data["currency"] = $this->input->post('currency') ? $this->input->post('currency') : "";
            $data["disable_online_payment"] = $this->input->post('disable_online_payment') ? $this->input->post('disable_online_payment') : 0;
        }

        $data = clean_data($data);
        
        // Check for duplicate company name
        if ($this->Vendors_model->is_duplicate_company_name($data["company_name"], $vendor_id)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_your_company_name")));
            exit();
        }

        $save_id = $this->Vendors_model->save($data, $vendor_id);
        if ($save_id) {
            save_custom_fields("clients", $save_id, $this->login_user->is_admin, $this->login_user->user_type);
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'view' => $this->input->post('view'), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo a client */

    function delete() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Vendors_model->delete_vendor_and_sub_items($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    /* list of clients, prepared for datatable  */

    function list_data() {
        $this->access_only_allowed_members();
        
        // Get custom fields for vendors
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("vendors", $this->login_user->is_admin, $this->login_user->user_type);
        
        // Prepare options for retrieving vendor details
        $options = array(
            "custom_fields" => $custom_fields,
            "group_id" => $this->input->post("group_id")
        );
        
        // Retrieve vendor details
        $list_data = $this->Vendors_model->get_details($options)->result();        
        
            // $list_data = $query->result();
        $result = array();
        
        // Iterate through the retrieved vendor data and prepare rows
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        
        // Output the result as JSON
        echo json_encode(array("data" => $result));
    }
    /* return a row of client list  table */

    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("clients", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );
        $data = $this->Vendors_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of client list table */

    private function _make_row($data, $custom_fields) {


        $image_url = get_avatar($data->contact_avatar);
        $contact = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->primary_contact";
        $primary_contact = get_vendor_contact_profile_link($data->primary_contact_id, $contact);
        

        $group_list = "";
        if ($data->groupss) {
            $groups = explode(",", $data->groupss);
            foreach ($groups as $group) {
                if ($group) {
                    $group_list .= "<li>" . $group.'&nbsp&nbsp&nbsp' . "</li>";
                }
            }
        }

        if ($group_list) {
            $group_list = "<ul class='pl15'>" . $group_list . "</ul>";
        }


        $due = 0;
        if ($data->purchase_order_value) {
            $due = ignor_minor_value($data->purchase_order_value - $data->payment_received);
        }

        $row_data = array($data->id,
            anchor(get_uri("vendors/view/" . $data->id), $data->company_name),
            $data->primary_contact ? $primary_contact : "",
            $group_list,
            to_currency($data->purchase_order_value, $data->currency_symbol),
            /*to_decimal_format($data->total_projects),
            to_currency($data->invoice_value, $data->currency_symbol), */
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol) 
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("vendors/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vendor'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_vendor'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vendors/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load client details view */

    function view($vendor_id = 0, $tab = "") {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $options = array("id" => $vendor_id);
            $vendor_info = $this->Vendors_model->get_details($options)->row();
            if ($vendor_info) {

                $access_info = $this->get_access_info("purchase_order");
                $view_data["show_invoice_info"] = (get_setting("module_purchase_order") && $access_info->access_type == "all") ? true : false;

                $access_info = $this->get_access_info("work_order");
                $view_data["show_estimate_info"] = (get_setting("module_work_order") && $access_info->access_type == "all") ? true : false;

                $access_info = $this->get_access_info("estimate_request");
                $view_data["show_estimate_request_info"] = (get_setting("module_estimate_request") && $access_info->access_type == "all") ? true : false;

                $access_info = $this->get_access_info("ticket");
                $view_data["show_ticket_info"] = (get_setting("module_ticket") && $access_info->access_type == "all") ? true : false;

                $view_data["show_note_info"] = (get_setting("module_note")) ? true : false;
                $view_data["show_event_info"] = (get_setting("module_event")) ? true : false;

                $view_data['vendor_info'] = $vendor_info;

                $view_data["is_starred"] = strpos($vendor_info->starred_by, ":" . $this->login_user->id . ":") ? true : false;

                $view_data["tab"] = $tab;

                //even it's hidden, admin can view all information of client
                $view_data['hidden_menu'] = array("");

                $this->template->rander("vendors/view", $view_data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    /* add-remove start mark from client */

    function add_remove_star($vendor_id, $type = "add") {
        if ($vendor_id) {
            $view_data["vendor_id"] = $vendor_id;

            if ($type === "add") {
                $this->Vendors_model->add_remove_star($vendor_id, $this->login_user->id, $type = "add");
                $this->load->view('vendors/star/starred', $view_data);
            } else {
                $this->Vendors_model->add_remove_star($vendor_id, $this->login_user->id, $type = "remove");
                $this->load->view('vendors/star/not_starred', $view_data);
            }
        }
    }

    function show_my_starred_vendors() {
        $view_data["vendors"] = $this->Vendors_model->get_starred_vendors($this->login_user->id)->result();
        $this->load->view('vendors/star/vendors_list', $view_data);
    }

    /* load projects tab  */

    function projects($client_id) {
        $this->access_only_allowed_members();

        $view_data['can_create_projects'] = $this->can_create_projects();
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['client_id'] = $client_id;
        $this->load->view("clients/projects/index", $view_data);
    }

    /* load payments tab  */

    function payments($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data["vendor_info"] = $this->Vendors_model->get_one($vendor_id);
            $view_data['vendor_id'] = $vendor_id;
            $this->load->view("vendors/payments/index", $view_data);
        }
    }



    function vendors_invoice_list($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data["vendor_info"] = $this->Vendors_model->get_one($vendor_id);
            $view_data['vendor_id'] = $vendor_id;
            $this->load->view("vendors/vendors_invoice_list", $view_data);
        }
    }


    /* load tickets tab  */

    function tickets($client_id) {
        $this->access_only_allowed_members();

        if ($client_id) {

            $view_data['client_id'] = $client_id;
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

            $view_data['show_project_reference'] = get_setting('project_reference_in_tickets');

            $this->load->view("clients/tickets/index", $view_data);
        }
    }

    function wo_payments($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data["vendor_info"] = $this->Vendors_model->get_one($vendor_id);
            $view_data['vendor_id'] = $vendor_id;
            $this->load->view("vendors/wo_payments/index", $view_data);
        }
    }


    /* load invoices tab  */

    function invoices($client_id) {
        $this->access_only_allowed_members();

        if ($client_id) {
            $view_data["client_info"] = $this->Clients_model->get_one($client_id);
            $view_data['client_id'] = $client_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("clients/invoices/index", $view_data);
        }
    }

    /* load estimates tab  */

    function purchase_orders($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data["vendor_info"] = $this->Vendors_model->get_one($vendor_id);
            $view_data['vendor_id'] = $vendor_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("vendors/purchase_orders/purchase_orders", $view_data);
        }
    }

    function work_orders($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data["vendor_info"] = $this->Vendors_model->get_one($vendor_id);
            $view_data['vendor_id'] = $vendor_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("vendors/work_orders/work_orders", $view_data);
        }
    }

    /* load estimate requests tab  */

    function estimate_requests($client_id) {
        $this->access_only_allowed_members();

        if ($client_id) {
            $view_data['client_id'] = $client_id;
            $this->load->view("clients/estimates/estimate_requests", $view_data);
        }
    }

    /* load notes tab  */

    function notes($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data['vendor_id'] = $vendor_id;
            $this->load->view("vendors/notes/index", $view_data);
        }
    }

    /* load events tab  */

    function events($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data['vendor_id'] = $vendor_id;
            $this->load->view("events/indexs", $view_data);
        }
    }

    /* load files tab */

    function files($vendor_id) {

        $this->access_only_allowed_members();

        $options = array("vendor_id" => $vendor_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();
        $view_data['vendor_id'] = $vendor_id;
        $this->load->view("vendors/files/index", $view_data);
    }

    /* file upload modal */

    function file_modal_form() {
        $view_data['model_info'] = $this->General_files_model->get_one($this->input->post('id'));
        $vendor_id = $this->input->post('vendor_id') ? $this->input->post('vendor_id') : $view_data['model_info']->vendor_id;

        $this->access_only_allowed_members();

        $view_data['vendor_id'] = $vendor_id;
        $this->load->view('vendors/files/modal_form', $view_data);
    }

    /* save file data and move temp file to parmanent file directory */

    function save_file() {


        validate_submitted_data(array(
            "id" => "numeric",
            "vendor_id" => "required|numeric"
        ));

        $vendor_id = $this->input->post('vendor_id');
        $this->access_only_allowed_members();


        $files = $this->input->post("files");
        $success = false;
        $now = get_current_utc_time();

        $target_path = getcwd() . "/" . get_general_file_path("vendor", $vendor_id);

        //process the fiiles which has been uploaded by dropzone
        if ($files && get_array_value($files, 0)) {
            foreach ($files as $file) {
                $file_name = $this->input->post('file_name_' . $file);
                $new_file_name = move_temp_file($file_name, $target_path);
                if ($new_file_name) {
                    $data = array(
                        "vendor_id" => $vendor_id,
                        "file_name" => $new_file_name,
                        "description" => $this->input->post('description_' . $file),
                        "file_size" => $this->input->post('file_size_' . $file),
                        "created_at" => $now,
                        "uploaded_by" => $this->login_user->id
                    );
                    $success = $this->General_files_model->save($data);
                } else {
                    $success = false;
                }
            }
        }


        if ($success) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* list of files, prepared for datatable  */

    function files_list_data($vendor_id = 0) {
        $this->access_only_allowed_members();

        $options = array("vendor_id" => $vendor_id);
        $list_data = $this->General_files_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_file_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_file_row($data) {
        $file_icon = get_file_icon(strtolower(pathinfo($data->file_name, PATHINFO_EXTENSION)));

        $image_url = get_avatar($data->uploaded_by_user_image);
        $uploaded_by = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->uploaded_by_user_name";

        $uploaded_by = get_team_member_profile_link($data->uploaded_by, $uploaded_by);

        $description = "<div class='pull-left'>" .
                js_anchor(remove_file_prefix($data->file_name), array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => get_uri("vendors/view_file/" . $data->id)));

        if ($data->description) {
            $description .= "<br /><span>" . $data->description . "</span></div>";
        } else {
            $description .= "</div>";
        }

        $options = anchor(get_uri("vendors/download_file/" . $data->id), "<i class='fa fa fa-cloud-download'></i>", array("title" => lang("download")));

        $options .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_file'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vendors/delete_file"), "data-action" => "delete-confirmation"));


        return array($data->id,
            "<div class='fa fa-$file_icon font-22 mr10 pull-left'></div>" . $description,
            convert_file_size($data->file_size),
            $uploaded_by,
            format_to_datetime($data->created_at),
            $options
        );
    }

    function view_file($file_id = 0) {
        $file_info = $this->General_files_model->get_details(array("id" => $file_id))->row();

        if ($file_info) {
            $this->access_only_allowed_members();

            if (!$file_info->vendor_id) {
                redirect("forbidden");
            }

            $view_data['can_comment_on_files'] = false;

            $view_data["file_url"] = get_file_uri(get_general_file_path("vendor", $file_info->vendor_id) . $file_info->file_name);
            ;
            $view_data["is_image_file"] = is_image_file($file_info->file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_info->file_name);

            $view_data["file_info"] = $file_info;
            $view_data['file_id'] = $file_id;
            $this->load->view("vendors/files/view", $view_data);
        } else {
            show_404();
        }
    }

    /* download a file */

    function download_file($id) {

        $file_info = $this->General_files_model->get_one($id);

        if (!$file_info->vendor_id) {
            redirect("forbidden");
        }
        //serilize the path
        $file_data = serialize(array(array("file_name" => $file_info->file_name)));

        download_app_files(get_general_file_path("vendor", $file_info->vendor_id), $file_data);
    }

    /* upload a post file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for client */

    function validate_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    /* delete a file */

    function delete_file() {

        $id = $this->input->post('id');
        $info = $this->General_files_model->get_one($id);

        if (!$info->vendor_id) {
            redirect("forbidden");
        }

        if ($this->General_files_model->delete($id)) {

            delete_file_from_directory(get_general_file_path("vendor", $info->vendor_id) . $info->file_name);

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function contact_profile($contact_id = 0, $tab = "") {
        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $view_data['user_info'] = $this->Users_model->get_one($contact_id);
        $view_data['vendor_info'] = $this->Vendors_model->get_one($view_data['user_info']->vendor_id);
        $view_data['tab'] = $tab;
        if ($view_data['user_info']->user_type === "vendor") {
            $view_data['show_company_info'] = true;
            $view_data['show_cotact_info'] = true;
            $view_data['show_social_links'] = true;
            $view_data['social_link'] = $this->Social_links_model->get_one($contact_id);
            $this->template->rander("vendors/contacts/view", $view_data);
        } else {
            show_404();
        }
    }

    //show account settings of a user
    function account_settings($contact_id) {
        $this->access_only_allowed_members_or_contact_personally($contact_id);
        $view_data['user_info'] = $this->Users_model->get_one($contact_id);
        $this->load->view("users/account_settings", $view_data);
    }

    //show my preference settings of a team member
    function my_preferences() {
        $view_data["user_info"] = $this->Users_model->get_one($this->login_user->id);

        $view_data['language_dropdown'] = array();

        if (!get_setting("disable_language_selector_for_clients")) {
              $view_data['language_dropdown'] = get_language_list();
        }

        $view_data["hidden_topbar_menus_dropdown"] = $this->get_hidden_topbar_menus_dropdown();

        $this->load->view("vendors/contacts/my_preferences", $view_data);
    }

    function save_my_preferences() {
        //setting preferences
        $settings = array("notification_sound_volume", "disable_keyboard_shortcuts");

        if (!get_setting("disable_language_selector_for_clients")) {
            array_push($settings, "personal_language");
        }

        if (!get_setting("disable_topbar_menu_customization_vendors")) {
            array_push($settings, "hidden_topbar_menus");
        }

        foreach ($settings as $setting) {
            $value = $this->input->post($setting);
            if (is_null($value)) {
                $value = "";
            }

                $this->Settings_model->save_setting("user_" . $this->login_user->id . "_" . $setting, $value, "user");
            
        }

        //there was 2 settings in users table.
        //so, update the users table also


        $user_data = array(
            "enable_web_notification" => $this->input->post("enable_web_notification"),
            "enable_email_notification" => $this->input->post("enable_email_notification"),
        );

        $user_data = clean_data($user_data);

        $this->Users_model->save($user_data, $this->login_user->id);

        echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
    }

    function save_personal_language($language) {
        if (!get_setting("disable_language_selector_for_clients") && ($language || $language === "0")) {

            $language = clean_data($language);

            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_personal_language", strtolower($language), "user");
        }
    }

    /* load contacts tab  */

    function contacts($vendor_id) {
        $this->access_only_allowed_members();

        if ($vendor_id) {
            $view_data['vendor_id'] = $vendor_id;
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("contacts", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("vendors/contacts/index", $view_data);
        }
    }

    /* contact add modal */

    function add_new_contact_modal_form() {
        $this->access_only_allowed_members();

        $view_data['model_info'] = $this->Users_model->get_one(0);
        $view_data['model_info']->vendor_id = $this->input->post('vendor_id');

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("contacts", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $this->load->view('vendors/contacts/modal_form', $view_data);
    }

    /* load contact's general info tab view */

    function contact_general_info_tab($contact_id = 0) {
        if ($contact_id) {
            $this->access_only_allowed_members_or_contact_personally($contact_id);

            $view_data['model_info'] = $this->Users_model->get_one($contact_id);
            $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("contacts", $contact_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('vendors/contacts/contact_general_info_tab', $view_data);
        }
    }

    /* load contact's company info tab view */

    function company_info_tab($vendor_id = 0) {
        if ($vendor_id) {
            $this->access_only_allowed_members_or_vendor_contact($vendor_id);

            $view_data['model_info'] = $this->Vendors_model->get_one($vendor_id);
            $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();
            $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();
           // $view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();
            $country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
         $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $country_get_code->numberCode));
        
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }

        $view_data['state_dropdown'] = $state_categories_suggestion;

            $view_data['buyer_types_dropdown'] = $this->_get_buyer_types_dropdown_select2_data();

            $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("clients", $client_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('vendors/contacts/company_info_tab', $view_data);
        }
    }

    function bank_info_tab($vendor_id = 0) {
        if ($vendor_id) {
            $this->access_only_allowed_members_or_vendor_contact($vendor_id);

            $view_data['model_info'] = $this->Vendors_model->get_one($vendor_id);
            $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();
            $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();

            $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("clients", $client_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('vendors/bank_info/bank_info', $view_data);
        }
    }

    function save_bank_info($vendor_id) {
        
     $this->access_only_allowed_members_or_vendor_contact($vendor_id);
       // validate_submitted_data(array(
           // "first_name" => "required",
           // "last_name" => "required"
       // ));

        $user_data = array(
            "cin" => $this->input->post('cin'),
            "tan" => $this->input->post('tan'),
            "uam" => $this->input->post('uam'),
            "panno" => $this->input->post('panno'),
            "iec" => $this->input->post('iec'),
            "name" => $this->input->post('name'),
            "accountnumber" => $this->input->post('accountnumber'),
            "swift_code"=> $this->input->post('swift_code'),
            "bankname" => $this->input->post('bankname'),
            "branch" => $this->input->post('branch'),
            "ifsc" => $this->input->post('ifsc'),
            "micr" => $this->input->post('micr')

        );

        $user_data = clean_data($user_data);

        $user_info_updated = $this->Vendors_model->save($user_data, $vendor_id);

        

        if ($user_info_updated) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* load contact's social links tab view */

    function contact_social_links_tab($contact_id = 0) {
        if ($contact_id) {
            $this->access_only_allowed_members_or_contact_personally($contact_id);

            $view_data['user_id'] = $contact_id;
            $view_data['user_type'] = "vendor";
            $view_data['model_info'] = $this->Social_links_model->get_one($contact_id);
            $this->load->view('users/social_links', $view_data);
        }
    }

    function contact_kyc_info_tab($contact_id = 0) {
        if ($contact_id) {
            $this->access_only_allowed_members_or_contact_personally($contact_id);

            $view_data['user_id'] = $contact_id;
            $view_data['user_type'] = "vendor";
            $view_data['model_info'] = $this->Kyc_info_model->get_one($contact_id);
            $this->load->view('users/kyc_info', $view_data);
        }
    }


    /* insert/upadate a contact */

    function save_contact() {
        $contact_id = $this->input->post('contact_id');
        $vendor_id = $this->input->post('vendor_id');

        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "phone" => $this->input->post('phone'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "skype" => $this->input->post('skype'),
            "job_title" => $this->input->post('job_title'),
            "gender" => $this->input->post('gender'),
            "user_type"=>"vendor",
            "note" => $this->input->post('note')
        );

        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "vendor_id" => "required|numeric"
        ));


        if (!$contact_id) {
            //inserting new contact. client_id is required

            validate_submitted_data(array(
                "email" => "required|valid_email",
            ));

            //we'll save following fields only when creating a new contact from this form
            $user_data["vendor_id"] = $vendor_id;
            $user_data["email"] = trim($this->input->post('email'));
            $user_data["password"] = md5($this->input->post('login_password'));
            $user_data["created_at"] = get_current_utc_time();

            //validate duplicate email address
            if ($this->Users_model->is_email_exists($user_data["email"])) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
                exit();
            }
        }

        //by default, the first contact of a client is the primary contact
        //check existing primary contact. if not found then set the first contact = primary contact
        $primary_contact = $this->Vendors_model->get_primary_contact($vendor_id);
        if (!$primary_contact) {
            $user_data['is_primary_contact'] = 1;
        }

        //only admin can change existing primary contact
        $is_primary_contact = $this->input->post('is_primary_contact');
        if ($is_primary_contact && $this->login_user->is_admin) {
            $user_data['is_primary_contact'] = 1;
        }

        $user_data = clean_data($user_data);

        $save_id = $this->Users_model->save($user_data, $contact_id);
        if ($save_id) {

            save_custom_fields("contacts", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            //has changed the existing primary contact? updete previous primary contact and set is_primary_contact=0
            if ($is_primary_contact) {
                $user_data = array("is_primary_contact" => 0);
                $this->Users_model->save($user_data, $primary_contact);
            }

            //send login details to user only for first time. when creating  a new contact
            if (!$contact_id && $this->input->post('email_login_details')) {
                $email_template = $this->Email_templates_model->get_final_template("login_info");

                $parser_data["SIGNATURE"] = $email_template->signature;
                $parser_data["USER_FIRST_NAME"] = $user_data["first_name"];
                $parser_data["USER_LAST_NAME"] = $user_data["last_name"];
                $parser_data["USER_LOGIN_EMAIL"] = $user_data["email"];
                $parser_data["USER_LOGIN_PASSWORD"] = $this->input->post('login_password');
                $parser_data["DASHBOARD_URL"] = base_url();
                $parser_data["LOGO_URL"] = get_logo_url();

                $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
                send_app_mail($this->input->post('email'), $email_template->subject, $message);
            }

            echo json_encode(array("success" => true, "data" => $this->_contact_row_data($save_id), 'id' => $contact_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


   
    //save social links of a contact
    function save_contact_social_links($contact_id = 0) {
        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $id = 0;

        //find out, the user has existing social link row or not? if found update the row otherwise add new row.
        $has_social_links = $this->Social_links_model->get_one($contact_id);
        if (isset($has_social_links->id)) {
            $id = $has_social_links->id;
        }

        $social_link_data = array(
            "facebook" => $this->input->post('facebook'),
            "twitter" => $this->input->post('twitter'),
            "linkedin" => $this->input->post('linkedin'),
            "googleplus" => $this->input->post('googleplus'),
            "digg" => $this->input->post('digg'),
            "youtube" => $this->input->post('youtube'),
            "pinterest" => $this->input->post('pinterest'),
            "instagram" => $this->input->post('instagram'),
            "github" => $this->input->post('github'),
            "tumblr" => $this->input->post('tumblr'),
            "vine" => $this->input->post('vine'),
            "user_id" => $contact_id,
            "id" => $id ? $id : $contact_id
        );

        $social_link_data = clean_data($social_link_data);

        $this->Social_links_model->save($social_link_data, $id);
        echo json_encode(array("success" => true, 'message' => lang('record_updated')));
    }

    function save_kyc_info($contact_id = 0) {
        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $id = 0;

        //find out, the user has existing social link row or not? if found update the row otherwise add new row.
        $has_social_links = $this->Kyc_info_model->get_one($contact_id);
        if (isset($has_social_links->id)) {
            $id = $has_social_links->id;
        }

        $social_link_data = array(
            "aadhar_no" => $this->input->post('aadhar_no'),
            "passportno" => $this->input->post('passportno'),
            "drivinglicenseno" => $this->input->post('drivinglicenseno'),
            "panno" => $this->input->post('panno'),
            "voterid" => $this->input->post('voterid'),
            "name" => $this->input->post('name'),
            "accountnumber" => $this->input->post('accountnumber'),
            "bankname" => $this->input->post('bankname'),
            "branch" => $this->input->post('branch'),
            "ifsc" => $this->input->post('ifsc'),
            "micr" => $this->input->post('micr'),
            "epf_no" => $this->input->post('epf_no'),
            "uan_no" => $this->input->post('uan_no'),
            "swift_code" => $this->input->post('swift_code'),
            "iban_code" => $this->input->post('iban_code'),
            "user_id" => $contact_id,
            "id" => $id ? $id : $contact_id
        );

        $social_link_data = clean_data($social_link_data);

        $this->Kyc_info_model->save($social_link_data, $id);
        echo json_encode(array("success" => true, 'message' => lang('record_updated')));
    }

    //save social links of a team member
  /*  function save_kyc_info($contact_id = 0) {
        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $id = 0;

        
        $has_kyc_info = $this->Kyc_info_model->get_one($user_id);
        if (isset($has_kyc_info->id)) {
            $id = $has_kyc_info->id;
        }

        $kyc_info_data = array(
            "aadhar_no" => $this->input->post('aadhar_no'),
            "passportno" => $this->input->post('passportno'),
            "drivinglicenseno" => $this->input->post('drivinglicenseno'),
            "panno" => $this->input->post('panno'),
            "voterid" => $this->input->post('voterid'),
            "name" => $this->input->post('name'),
            "accountnumber" => $this->input->post('accountnumber'),
            "bankname" => $this->input->post('bankname'),
            "branch" => $this->input->post('branch'),
            "ifsc" => $this->input->post('ifsc'),
            "micr" => $this->input->post('micr'),
            "user_id" => $contact_id,
            "id" => $id ? $id : $contact_id
        );

        $kyc_info_data = clean_data($kyc_info_data);

        $this->Kyc_info_model->save($kyc_info_data, $id);
        echo json_encode(array("success" => true, 'message' => lang('record_updated')));
    } */

    //save account settings of a client contact (user)
    function save_account_settings($user_id) {
        $this->access_only_allowed_members_or_contact_personally($user_id);

        validate_submitted_data(array(
            "email" => "required|valid_email"
        ));

        if ($this->Users_model->is_email_exists($this->input->post('email'), $user_id)) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
            exit();
        }

        $account_data = array(
            "email" => $this->input->post('email')
        );

        //don't reset password if user doesn't entered any password
        if ($this->input->post('password')) {
            $account_data['password'] = md5($this->input->post('password'));
        }

        //only admin can disable other users login permission
        if ($this->login_user->is_admin) {
            $account_data['disable_login'] = $this->input->post('disable_login');
        }


        if ($this->Users_model->save($account_data, $user_id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save profile image of a contact
    function save_profile_image($user_id = 0) {
        $this->access_only_allowed_members_or_contact_personally($user_id);

        //process the the file which has uploaded by dropzone
        $profile_image = str_replace("~", ":", $this->input->post("profile_image"));

        if ($profile_image) {
            $profile_image = move_temp_file("avatar.png", get_setting("profile_image_path"), "", $profile_image);
            $image_data = array("image" => $profile_image);
            $this->Users_model->save($image_data, $user_id);
            echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
        }

        //process the the file which has uploaded using manual file submit
        if ($_FILES) {
            $profile_image_file = get_array_value($_FILES, "profile_image_file");
            $image_file_name = get_array_value($profile_image_file, "tmp_name");
            if ($image_file_name) {
                $profile_image = move_temp_file("avatar.png", get_setting("profile_image_path"), "", $image_file_name);
                $image_data = array("image" => $profile_image);
                $this->Users_model->save($image_data, $user_id);
                echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
            }
        }
    }

    /* delete or undo a contact */

    function delete_contact() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $this->access_only_allowed_members();

        $id = $this->input->post('id');

        if ($this->input->post('undo')) {
            if ($this->Users_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_contact_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Users_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of contacts, prepared for datatable  */

    function contacts_list_data($vendor_id = 0) {

       $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("contacts", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("user_type" => "vendor", "vendor_id" => $vendor_id, "custom_fields" => $custom_fields);
        $list_data = $this->Users_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_contact_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of contact list table */

    private function _contact_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("contacts", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "user_type" => "vendor",
            "custom_fields" => $custom_fields
        );
        $data = $this->Users_model->get_details($options)->row();
        return $this->_make_contact_row($data, $custom_fields);
    }

    /* prepare a row of contact list table */

    private function _make_contact_row($data, $custom_fields) {
        $image_url = get_avatar($data->image);
        $user_avatar = "<span class='avatar avatar-xs'><img src='$image_url' alt='...'></span>";
        $full_name = $data->first_name . " " . $data->last_name . " ";
        $primary_contact = "";
        if ($data->is_primary_contact == "1") {
            $primary_contact = "<span class='label-info label'>" . lang('primary_contact') . "</span>";
        }

        $contact_link = anchor(get_uri("vendors/contact_profile/" . $data->id), $full_name . $primary_contact);
        if ($this->login_user->user_type === "vendor") {
            $contact_link = $full_name; //don't show clickable link to client
        }


        $row_data = array(
            $user_avatar,
            $contact_link,
            $data->job_title,
            $data->email,
            $data->phone ? $data->phone : "-",
            $data->alternative_phone ? $data->alternative_phone : "-",
            $data->skype ? $data->skype : "-"
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_contact'), "class" => "delete", "data-id" => "$data->id", "data-action-url" => get_uri("vendors/delete_contact"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* open invitation modal */

    function invitation_modal() {


        validate_submitted_data(array(
            "vendor_id" => "required|numeric"
        ));

        $vendor_id = $this->input->post('vendor_id');

       $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        $view_data["vendor_info"] = $this->Vendors_model->get_one($vendor_id);
        $this->load->view('vendors/contacts/invitation_modal', $view_data);
    }

    //send a team member invitation to an email address
    function send_invitation() {

        $vendor_id = $this->input->post('vendor_id');
        $email = trim($this->input->post('email'));

        validate_submitted_data(array(
            "vendor_id" => "required|numeric",
            "email" => "required|valid_email|trim"
        ));

       $this->access_only_allowed_members_or_vendor_contact($vendor_id);

        $email_template = $this->Email_templates_model->get_final_template("vendor_contact_invitation");

        $parser_data["INVITATION_SENT_BY"] = $this->login_user->first_name . " " . $this->login_user->last_name;
        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["SITE_URL"] = get_uri();
        $parser_data["LOGO_URL"] = get_logo_url();

        //make the invitation url with 24hrs validity
        $key = encode_id($this->encryption->encrypt('vendor|' . $email . '|' . (time() + (24 * 60 * 60)) . '|' . $vendor_id), "vendor_signup");
        $parser_data['INVITATION_URL'] = get_uri("vendor_signup/accept_invitation/" . $key);

        //send invitation email
        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        if (send_app_mail($email, $email_template->subject, $message)) {
            echo json_encode(array('success' => true, 'message' => lang("invitation_sent")));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    /* only visible to client  */

  /*  function users() {
        if ($this->login_user->user_type === "vendor") {
            $DB1 = $this->load->database('default', TRUE);
 $DB1->select ("vendor_id");
 $DB1->from('users');
  $DB1->where('deleted',0);
 $DB1->where('id',$this->login_user->id);
 $query1=$DB1->get();
 $query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->vendor_id;
   
   
        }
            $view_data['vendor_id'] = $b;
            $this->template->rander("vendors/contacts/users", $view_data);
        }
    } */
    function users() {
        if ($this->login_user->user_type === "vendor") {
            $view_data['vendor_id'] = $this->login_user->vendor_id;
            $this->template->rander("vendors/contacts/users", $view_data);
        }
    }

   function get_country_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Countries_model->get_country_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->id, "text" => $item->countryName);
        }

        //$suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_product"));

        echo json_encode($suggestion);
    }

    function get_country_item_info_suggestion() {
        $item = $this->Countries_model->get_country_info_suggestion($this->input->post("item_name"));
        
//print_r($itemss);
    
        if ($item) {
            echo json_encode(array("success" => true,"item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

 function get_country_code_suggestion() {
        $item = $this->Countries_model->get_country_code_suggestion($this->input->post("item_name"));
        
//print_r($itemss);
    
        if ($item) {
            echo json_encode(array("success" => true,"item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }
    
function get_state_suggestion() {
$key = $_REQUEST["q"];
    $ss=$_REQUEST["ss"];
    
         $itemss =  $this->Countries_model->get_item_suggestions_country_name($key,$ss);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->title);
       }
        echo json_encode($suggestions);
    }

  /*  function get_gst_state_suggestion() {

        $gst_number =  $this->input->post("gst");
        $gstin_number_first_two_digits =substr($gst_number,0,2);
        $itemss =  $this->Gst_state_code_model->get_item_suggestions_gst_state($gstin_number_first_two_digits);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->gstin_number_first_two_digits, "text" => $items->title);
       }
        echo json_encode($suggestions);
    } */

//Import excel ,csv modal form  for vendors 
function vendors_excel_form() {

        $this->load->view('vendors/vendors_excel_form');
    }


   //import excel file for vendors 
    function import()
    {
        if(isset($_FILES["file"]["name"]))
        {
            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet)
            {
               $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row<=($highestRow); $row++)
                {
                    $company_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $address = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $city = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $state = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $country = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $zip = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $phone = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $website = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $gst_number = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $gstin_number_first_two_digits = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $currency = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $currency_symbol = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                    //get contry name convert to country id
                   $options_excel_country = array(

                      "countryName" => $country,
            );
                   $country_id_list = $this->Countries_model->get_country_id_excel($options_excel_country)->row();

                  $country_id = $country_id_list->id;

            //state name convert to state id 
                  $options_excel_state = array(

                      "title" => $state,
            );
                   $state_id_list = $this->States_model->get_state_id_excel($options_excel_state)->row();

                  $state_id = $state_id_list->id;


$options = array(

            "company_name" => $company_name,
            "address" => $address,
            "city" => $city,
            "state" => $state_id,
            "zip" => $zip,
            "country" => $country_id,
            "phone" => $phone,
            "website" => $website,
            "gst_number" => $gst_number,
            "gstin_number_first_two_digits" => $gstin_number_first_two_digits,
            "currency_symbol" => $currency_symbol,
            "currency" =>  $currency,
            
            
                    );
                
$list_datas = $this->Vendors_model->get_import_detailss($options)->row();
  
if(!$list_datas){   
                    
            $data[] = array(
                       
            "company_name" => $company_name,
            "address" => $address,
            "city" => $city,
            "state" => $state_id,
            "zip" => $zip,
            "country" => $country_id,
            "phone" => $phone,
            "website" => $website,
            "gst_number" => $gst_number,
            "gstin_number_first_two_digits" => $gstin_number_first_two_digits,
            "currency_symbol" => $currency_symbol,
            "currency" =>  $currency,
            "buyer_type" => 0,
            "group_ids" => 0,
            "deleted" =>0,
            "created_date" => get_my_local_time("Y-m-d")
                    );
                }
            }
       }
            $this->Vendors_model->insert($data);
            echo 'Data Imported successfully';
        }   
    }


    //import csv file 
    function upload_file_csv(){
        $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                
                //open uploaded csv file with read only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                
                // skip first line
                // if your csv file have no heading, just comment the next line
                fgetcsv($csvFile);
                
                //parse data from csv file line by line
                while(($line = fgetcsv($csvFile)) !== FALSE){

//get contry name convert to country id
                   $options_excel_country = array(

                      "countryName" => $line[4],
            );
                   $country_id_list = $this->Countries_model->get_country_id_excel($options_excel_country)->row();

                  $country_id = $country_id_list->id;

            //state name convert to state id 
                  $options_excel_state = array(

                      "title" => $line[3],
            );
                   $state_id_list = $this->States_model->get_state_id_excel($options_excel_state)->row();

                  $state_id = $state_id_list->id;                    
                    
                    


                    $options = array(

            "company_name" => $line[0],
            "address" => $line[1],
            "city" => $line[2],
            "state" => $state_id,
            "country" => $country_id,
            "zip" => $line[5],
            "phone" => $line[6],
            "website" => $line[7],
            "gst_number" => $line[8],
            "gstin_number_first_two_digits" => $line[9],
            "currency" =>  $line[10],
            "currency_symbol" => $line[11],
            
            
            
                    );
                
$list_datas = $this->Vendors_model->get_import_detailss($options)->row();
  
if(!$list_datas){   
                        //insert member data into database
                        $this->db->insert("vendors", array(

            "company_name" => $line[0],
            "address" => $line[1],
            "city" => $line[2],
            "state" => $state_id,
            "country" => $country_id,
            "zip" => $line[5],
            "phone" => $line[6],
            "website" => $line[7],
            "gst_number" => $line[8],
            "gstin_number_first_two_digits" => $line[9],
             "currency" =>  $line[10],
            "currency_symbol" => $line[11],
            "buyer_type" => 0,
            "group_ids" => 0,
            "deleted" =>0,
            "created_date" => get_my_local_time("Y-m-d")
                            /*"name"=>$line[0], 
                            "email"=>$line[1], 
                            "phone"=>$line[2], 
                            "created"=>$line[3], 
                            "status"=>$line[4])*/));
                    }
                  
                }
                
                //close opened csv file
                fclose($csvFile);

               
            }
    }
}

/* show keyboard shortcut modal form */

    function keyboard_shortcut_modal_form() {
        $this->load->view('team_members/keyboard_shortcut_modal_form');
    }





}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */