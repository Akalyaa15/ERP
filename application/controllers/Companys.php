<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companys extends MY_Controller {

    function __construct() {
        parent::__construct();

        //check permission to access this module
        //$this->init_permission_checker("client");
        //$this->init_permission_checker("master_data");
        $this->init_permission_checker("company");
        $this->access_only_allowed_members();
        $this->load->library('excel');
        $this->load->model('Companys_model');
    }

    /* load clients list view */

    function index() {
        $this->access_only_allowed_members();
        //$this->check_module_availability("module_master_data");

        $this->check_module_availability("module_company");

       // $access_info = $this->get_access_info("invoice");
        //$view_data["show_invoice_info"] = (get_setting("module_invoice") && $access_info->access_type == "all") ? true : false;
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("clients", $this->login_user->is_admin, $this->login_user->user_type);
         if ($this->login_user->is_admin == "1")
        {
            $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));

        $this->template->rander("companys/index", $view_data);
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));

        $this->template->rander("companys/index", $view_data);
        }else {


        $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));

        $this->template->rander("companys/index", $view_data);
    }

        /*$view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));

        $this->template->rander("Companys/index", $view_data);*/
    }

    /* load client add/edit modal */

    function modal_form() {
        $this->access_only_allowed_members();

        $company_id = $this->input->post('id');
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['label_column'] = "col-md-3";
        $view_data['field_column'] = "col-md-9";

        $view_data["view"] = $this->input->post('view'); //view='details' needed only when loding from the client's details view
        $view_data['model_info'] = $this->Companys_model->get_one($company_id);
        $view_data["currency_dropdown"] = $this->_get_currency_dropdown_select2_data();
        $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();


        //prepare groups dropdown list
        $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();

       // $view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();

         $country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
         $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $view_data['model_info']->country));
        
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }

        $view_data['state_dropdown'] = $state_categories_suggestion;

        $view_data['buyer_types_dropdown'] = $this->_get_buyer_types_dropdown_select2_data();

        $company_setup_country = $this->Countries_model->get_all()->result();
        $company_setup_country_dropdown = array();

        

        foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
        
        $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);


        //get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("companys", $company_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('companys/modal_form', $view_data);
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

    //$client_groups = $this->Client_groups_model->get_all()->result();
    $client_groups = $this->Company_groups_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $groups_dropdown = array();


        if ($show_header) {
            $groups_dropdown[] = array("id" => "", "text" => "- " . lang("company_groups") . " -");
        }

        foreach ($client_groups as $group) {
            $groups_dropdown[] = array("id" => $group->id, "text" => $group->title);
        }
        return $groups_dropdown;
    }


    private function _get_buyer_types_dropdown_select2_data($show_header = false) {
        //$buyer_types = $this->Buyer_types_model->get_all()->result();
        $buyer_types = $this->Buyer_types_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $buyer_types_dropdown = array();

        

        foreach ($buyer_types as $buyer_type) {
            $buyer_types_dropdown[] = array("id" => $buyer_type->id, "text" => $buyer_type->buyer_type);
        }
        return $buyer_types_dropdown;
    }

    private function _get_currency_dropdown_select2_data() {
        $currency = array(array("id" => "", "text" => "-"));
        foreach (get_international_currency_code_dropdown() as $value) {
            $currency[] = array("id" => $value, "text" => $value);
        }
        return $currency;
    }

    private function _get_gst_code_dropdown_select2_data($show_header = false) {
        $gst_code = $this->Gst_state_code_model->get_all()->result();
        $gst_code_dropdown = array();

        

        foreach ($gst_code as $code) {
            $gst_code_dropdown[] = array("id" => $code->gstin_number_first_two_digits, "text" => $code->title);
        }
        return $gst_code_dropdown;
    }

    /* insert or update a client */

    public function save()
    {
        $company_id = $this->input->post('id');
        
        validate_submitted_data(array(
            "id" => "numeric",
            "company_name" => "required"
        ));
    
        $company_name = $this->input->post('company_name');
        $company_logo = $this->input->post('site_logo');
        $target_path = getcwd() . "/" . get_general_file_path("company", $company_id);
        $value = move_temp_file("companys-logo.png", $target_path, "", $company_logo);
    
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
            "enable_company_logo" => $this->input->post('enable_company_logo'),
            "state_mandatory" => $this->input->post('state_mandatory'),
        );
    
        // Handle company logo
        $company_info_logo = $this->Companys_model->get_one($company_id);
        $company_logo_file = $company_info_logo->company_logo;
        if ($company_logo && !$company_logo_file) {
            $data["company_logo"] = $value;
        } elseif ($company_logo && $company_logo_file) {
            delete_file_from_directory(get_general_file_path("company", $company_id) . $company_logo_file);
            $data["company_logo"] = $value;
        }
    
        if ($this->login_user->user_type === "staff") {
            $data["group_ids"] = $this->input->post('group_ids') ? $this->input->post('group_ids') : "";
        }
    
        if (!$company_id) {
            $data["created_date"] = get_current_utc_time();
        }
    
        if ($this->login_user->is_admin) {
            $data["currency_symbol"] = $this->input->post('currency_symbol') ? $this->input->post('currency_symbol') : "";
            $data["currency"] = $this->input->post('currency') ? $this->input->post('currency') : "";
            $data["disable_online_payment"] = $this->input->post('disable_online_payment') ? $this->input->post('disable_online_payment') : 0;
        }
    
        $data = clean_data($data);
    
        // Check for duplicate company name
        if ($this->Companys_model->is_duplicate_company_name($data["company_name"], $company_id)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_your_company_name")));
            exit();
        }
    
        $save_id = $this->Companys_model->save($data, $company_id);
    
        // Save the new invoice number
        if (!$company_id) {
            $crid_string = "CR";
            $cr_id = str_pad($save_id, 3, '0', STR_PAD_LEFT);
            $cr_id = $crid_string . $cr_id;
            $data = array("cr_id" => $cr_id);
            $this->Companys_model->save($data, $save_id);
        }
    
        if ($save_id) {
            save_custom_fields("companys", $save_id, $this->login_user->is_admin, $this->login_user->user_type);
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
        $company_data = $this->Companys_model->get_one($id);
        $cr_id =$company_data->cr_id;
        if ($this->Companys_model->delete_company_and_sub_items($id,$cr_id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    /* list of clients, prepared for datatable  */

    function list_data() {

        $this->access_only_allowed_members();
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("companys", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "custom_fields" => $custom_fields,
            "group_id" => $this->input->post("group_id")
        );
        $list_data = $this->Companys_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of client list  table */
    private function _row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("companys", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );
    
        // Debugging: Check if the model is loaded and methods available
        if (isset($this->Companys_model)) {
            if (method_exists($this->Companys_model, 'get_details')) {
                $data = $this->Companys_model->get_details($options);
                $data = !empty($data) ? $data[0] : null;
                return $this->_make_row($data, $custom_fields);
            } else {
                log_message('error', 'Method get_details does not exist in Companys_model.');
                throw new \Exception('Method get_details does not exist in Companys_model.');
            }
        } else {
            log_message('error', 'Companys_model is not loaded.');
            throw new \Exception('Companys_model is not loaded.');
        }
    }
    
     
    /* prepare a row of client list table */

    private function _make_row($data, $custom_fields) {


        $image_url = get_avatar($data->contact_avatar);
        $contact = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->primary_contact";
        $primary_contact = get_company_contact_profile_link($data->primary_contact_id, $contact);

        $group_list = "";
        if ($data->groups) {
            $groups = explode(",", $data->groups);
            foreach ($groups as $group) {
                if ($group) {
                    $group_list .= "<li>" . $group . "</li>";
                }
            }
        }

        if ($group_list) {
            $group_list = "<ul class='pl15'>" . $group_list . "</ul>";
        }


        $due = 0;
        if ($data->invoice_value) {
            $due = ignor_minor_value($data->invoice_value - $data->payment_received);
        }

        $row_data = array($data->id,
            $data->cr_id,
            anchor(get_uri("companys/view/" . $data->cr_id), $data->company_name),
            $data->primary_contact ? $primary_contact : "",
            $group_list,
           /* to_decimal_format($data->total_projects),
            to_currency($data->invoice_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol)*/
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = modal_anchor(get_uri("companys/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_company'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_company'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("companys/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load client details view */

    function view($company_id = 0, $tab = "") {
        $this->access_only_allowed_members();

        if ($company_id) {
            $options = array("cr_id" => $company_id);
            $company_info = $this->Companys_model->get_details($options)->row();
            if ($company_info) {

                $access_info = $this->get_access_info("invoice");
                $view_data["show_invoice_info"] = (get_setting("module_invoice") && $access_info->access_type == "all") ? true : false;

                $access_info = $this->get_access_info("estimate");
                $view_data["show_estimate_info"] = (get_setting("module_estimate") && $access_info->access_type == "all") ? true : false;

                $access_info = $this->get_access_info("estimate_request");
                $view_data["show_estimate_request_info"] = (get_setting("module_estimate_request") && $access_info->access_type == "all") ? true : false;

                $access_info = $this->get_access_info("ticket");
                $view_data["show_ticket_info"] = (get_setting("module_ticket") && $access_info->access_type == "all") ? true : false;

                $view_data["show_note_info"] = (get_setting("module_note")) ? true : false;
                $view_data["show_event_info"] = (get_setting("module_event")) ? true : false;

                $view_data['company_info'] = $company_info;

                $view_data["is_starred"] = strpos($company_info->starred_by, ":" . $this->login_user->id . ":") ? true : false;

                $view_data["tab"] = $tab;

                //even it's hidden, admin can view all information of client
                $view_data['hidden_menu'] = array("");

                $this->template->rander("companys/view", $view_data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    /* add-remove start mark from client */

    function add_remove_star($company_id, $type = "add") {
        if ($company_id) {
            $view_data["company_id"] = $company_id;

            if ($type === "add") {
                $this->Companys_model->add_remove_star($company_id, $this->login_user->id, $type = "add");
                $this->load->view('companys/star/starred', $view_data);
            } else {
                $this->Companys_model->add_remove_star($company_id, $this->login_user->id, $type = "remove");
                $this->load->view('companys/star/not_starred', $view_data);
            }
        }
    }

    function show_my_starred_companys() {
        $view_data["companys"] = $this->Companys_model->get_starred_companys($this->login_user->id)->result();
        $this->load->view('companys/star/companys_list', $view_data);
    }

    /* load projects tab  */

    function projects($company_id) {
        $this->access_only_allowed_members();

        $view_data['can_create_projects'] = $this->can_create_projects();
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['company_id'] = $company_id;
        $this->load->view("companys/projects/index", $view_data);
    }

    /* load payments tab  */

    function payments($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data["company_info"] = $this->Companys_model->get_one($company_id);
            $view_data['company_id'] = $company_id;
            $this->load->view("companys/payments/index", $view_data);
        }
    }

    /* load tickets tab  */

    function tickets($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {

            $view_data['company_id'] = $company_id;
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

            $view_data['show_project_reference'] = get_setting('project_reference_in_tickets');

            $this->load->view("companys/tickets/index", $view_data);
        }
    }

    /* load invoices tab  */

    function invoices($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data["company_info"] = $this->Companys_model->get_one($company_id);
            $view_data['company_id'] = $company_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("companys/invoices/index", $view_data);
        }
    }

    /* load estimates tab  */

    function estimates($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data["company_info"] = $this->Companys_model->get_one($company_id);
            $view_data['company_id'] = $company_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("companys/estimates/estimates", $view_data);
        }
    }

    /* load estimate requests tab  */

    function estimate_requests($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data['company_id'] = $company_id;
            $this->load->view("companys/estimates/estimate_requests", $view_data);
        }
    }

    /* load notes tab  */

    function notes($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data['company_id'] = $company_id;
            $this->load->view("companys/notes/index", $view_data);
        }
    }

    /* load events tab  */

    function events($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data['company_id'] = $company_id;
            $this->load->view("events/index", $view_data);
        }
    }

    /* load files tab */

    function files($company_id) {

        $this->access_only_allowed_members();

        $options = array("company_id" => $company_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();
        $view_data['company_id'] = $company_id;
        $this->load->view("companys/files/index", $view_data);
    }

    /* file upload modal */

    function file_modal_form() {
        $view_data['model_info'] = $this->General_files_model->get_one($this->input->post('id'));
        $company_id = $this->input->post('company_id') ? $this->input->post('company_id') : $view_data['model_info']->company_id;

        $this->access_only_allowed_members();

        $view_data['company_id'] = $company_id;
        $this->load->view('companys/files/modal_form', $view_data);
    }

    /* save file data and move temp file to parmanent file directory */

    function save_file() {


        validate_submitted_data(array(
            "id" => "numeric",
            "company_id" => "required"
        ));

        $company_id = $this->input->post('company_id');
        $this->access_only_allowed_members();


        $files = $this->input->post("files");
        $success = false;
        $now = get_current_utc_time();

        $target_path = getcwd() . "/" . get_general_file_path("company", $company_id);

        //process the fiiles which has been uploaded by dropzone
        if ($files && get_array_value($files, 0)) {
            foreach ($files as $file) {
                $file_name = $this->input->post('file_name_' . $file);
                $new_file_name = move_temp_file($file_name, $target_path);
                if ($new_file_name) {
                    $data = array(
                        "company_id" => $company_id,
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

    function files_list_data($company_id = 0) {
        $this->access_only_allowed_members();

        $options = array("company_id" => $company_id);
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
                js_anchor(remove_file_prefix($data->file_name), array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => get_uri("companys/view_file/" . $data->id)));

        if ($data->description) {
            $description .= "<br /><span>" . $data->description . "</span></div>";
        } else {
            $description .= "</div>";
        }

        $options = anchor(get_uri("companys/download_file/" . $data->id), "<i class='fa fa fa-cloud-download'></i>", array("title" => lang("download")));

        $options .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_file'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("companys/delete_file"), "data-action" => "delete-confirmation"));


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

            if (!$file_info->company_id) {
                redirect("forbidden");
            }

            $view_data['can_comment_on_files'] = false;

            $view_data["file_url"] = get_file_uri(get_general_file_path("company", $file_info->company_id) . $file_info->file_name);
            ;
            $view_data["is_image_file"] = is_image_file($file_info->file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_info->file_name);

            $view_data["file_info"] = $file_info;
            $view_data['file_id'] = $file_id;
            $this->load->view("companys/files/view", $view_data);
        } else {
            show_404();
        }
    }

    /* download a file */

    function download_file($id) {

        $file_info = $this->General_files_model->get_one($id);

        if (!$file_info->company_id) {
            redirect("forbidden");
        }
        //serilize the path
        $file_data = serialize(array(array("file_name" => $file_info->file_name)));

        download_app_files(get_general_file_path("company", $file_info->company_id), $file_data);
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

        if (!$info->company_id) {
            redirect("forbidden");
        }

        if ($this->General_files_model->delete($id)) {

            delete_file_from_directory(get_general_file_path("company", $info->company_id) . $info->file_name);

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function contact_profile($contact_id = 0, $tab = "") {
        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $view_data['user_info'] = $this->Users_model->get_one($contact_id);
        //$view_data['company_info'] = $this->Companys_model->get_one($view_data['user_info']->company_id);
        $view_data["company_info"] = $this->Companys_model->get_details(array("cr_id" => $view_data['user_info']->company_id))->row();
        $view_data['tab'] = $tab;
        if ($view_data['user_info']->user_type === "company") {

            $view_data['show_cotact_info'] = true;
            $view_data['show_social_links'] = true;
            $view_data['social_link'] = $this->Social_links_model->get_one($contact_id);
            $this->template->rander("companys/contacts/view", $view_data);
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

        $this->load->view("companys/contacts/my_preferences", $view_data);
    }

    function save_my_preferences() {
        //setting preferences
        $settings = array("notification_sound_volume");

        if (!get_setting("disable_language_selector_for_clients")) {
            array_push($settings, "personal_language");
        }

        foreach ($settings as $setting) {
            $value = $this->input->post($setting);
            if ($value || $value === "0") {

                $value = clean_data($value);

                $this->Settings_model->save_setting("user_" . $this->login_user->id . "_" . $setting, $value, "user");
            }
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

    function contacts($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data['company_id'] = $company_id;
           // $view_data["company_info"] = $this->Companys_model->get_one($company_id);
            $view_data["company_info"] = $this->Companys_model->get_details(array("cr_id" => $company_id))->row();
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("contacts", $this->login_user->is_admin, $this->login_user->user_type);

            $this->load->view("companys/contacts/index", $view_data);
        }
    }

    /* contact add modal */

    function add_new_contact_modal_form() {
        $this->access_only_allowed_members();

        $view_data['model_info'] = $this->Users_model->get_one(0);
        $view_data['model_info']->company_id = $this->input->post('company_id');

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("contacts", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $this->load->view('companys/contacts/modal_form', $view_data);
    }

    /* load contact's general info tab view */

    function contact_general_info_tab($contact_id = 0) {
        if ($contact_id) {
            $this->access_only_allowed_members_or_contact_personally($contact_id);

            $view_data['model_info'] = $this->Users_model->get_one($contact_id);
            $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("contacts", $contact_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('companys/contacts/contact_general_info_tab', $view_data);
        }
    }

    /* load contact's company info tab view */

    function company_info_tab($company_id = 0) {
        if ($company_id) {
            //$this->access_only_allowed_members_or_client_contact($company_id);

           // $view_data['model_info'] = $this->Companys_model->get_one($company_id);
            $view_data["model_info"] = $this->Companys_model->get_details(array("cr_id" => $company_id))->row();
            $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();
            $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();
            //$view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();
            $country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
            $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $view_data['model_info']->country));
        
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }

        $view_data['state_dropdown'] = $state_categories_suggestion;
        $view_data['buyer_types_dropdown'] = $this->_get_buyer_types_dropdown_select2_data();
        $company_setup_country = $this->Countries_model->get_all()->result();
        $company_setup_country_dropdown = array();

        

        foreach ($company_setup_country as $country) {
            $company_setup_country_dropdown[] = array("id" => $country->numberCode, "text" => $country->countryName);
        }
        
        $view_data['company_setup_country_dropdown'] = json_encode($company_setup_country_dropdown);

           // $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("companys", $company_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('companys/contacts/company_info_tab', $view_data);
        }
    }

    function bank_info_tab($company_id = 0) {
        if ($company_id) {
           // $this->access_only_allowed_members_or_client_contact($company_id);

            //$view_data['model_info'] = $this->Companys_model->get_one($company_id);
            $view_data["model_info"] = $this->Companys_model->get_details(array("cr_id" => $company_id))->row();
            $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();
            $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();

            //$view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("companys", $company_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('companys/bank_info/bank_info', $view_data);
        }
    }

    function save_bank_info($company_id) {
        //$this->update_only_allowed_members($user_id);
    // $this->access_only_allowed_members_or_client_contact($company_id);
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

        $user_info_updated = $this->Companys_model->save($user_data, $company_id);

        

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
            $view_data['user_type'] = "company";
            $view_data['model_info'] = $this->Social_links_model->get_one($contact_id);
            $this->load->view('users/social_links', $view_data);
        }
    }

    function contact_kyc_info_tab($contact_id = 0) {
        if ($contact_id) {
            $this->access_only_allowed_members_or_contact_personally($contact_id);

            $view_data['user_id'] = $contact_id;
            $view_data['user_type'] = "company";
            $view_data['model_info'] = $this->Kyc_info_model->get_one($contact_id);
            $this->load->view('users/kyc_info', $view_data);
        }
    }

    /* insert/upadate a contact */

    function save_contact() {
        $contact_id = $this->input->post('contact_id');
        $company_id = $this->input->post('company_id');

        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "phone" => $this->input->post('phone'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "skype" => $this->input->post('skype'),
            "job_title" => $this->input->post('job_title'),
            "gender" => $this->input->post('gender'),
            "note" => $this->input->post('note'),
            "user_type" => "company",
        );

        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "company_id" => "required"
        ));


        if (!$contact_id) {
            //inserting new contact. client_id is required

            validate_submitted_data(array(
                "email" => "required|valid_email",
            ));

            //we'll save following fields only when creating a new contact from this form
            $user_data["company_id"] = $company_id;
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
        $primary_contact = $this->Companys_model->get_primary_contact($company_id);
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

    function contacts_list_data($company_id = 0) {

       // $this->access_only_allowed_members_or_client_contact($company_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("contacts", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("user_type" => "company", "company_id" => $company_id, "custom_fields" => $custom_fields);
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
            "user_type" => "company",
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

        $contact_link = anchor(get_uri("companys/contact_profile/" . $data->id), $full_name . $primary_contact);
        if ($this->login_user->user_type === "company") {
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

        $row_data[] = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_contact'), "class" => "delete", "data-id" => "$data->id", "data-action-url" => get_uri("companys/delete_contact"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* open invitation modal */

    function invitation_modal() {


        validate_submitted_data(array(
            "company_id" => "required"
        ));

        $company_id = $this->input->post('company_id');

        $this->access_only_allowed_members_or_client_contact($company_id);

        //$view_data["company_info"] = $this->Companys_model->get_one($company_id);
         $view_data["company_info"] = $this->Companys_model->get_details(array("cr_id" => $company_id))->row();
        $this->load->view('companys/contacts/invitation_modal', $view_data);
    }

    //send a team member invitation to an email address
    function send_invitation() {

        $company_id = $this->input->post('company_id');
        $email = trim($this->input->post('email'));

        validate_submitted_data(array(
            "company_id" => "required",
            "email" => "required|valid_email|trim"
        ));

       // $this->access_only_allowed_members_or_client_contact($company_id);

        $email_template = $this->Email_templates_model->get_final_template("company_contact_invitation");

        $parser_data["INVITATION_SENT_BY"] = $this->login_user->first_name . " " . $this->login_user->last_name;
        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["SITE_URL"] = get_uri();
        $parser_data["LOGO_URL"] = get_logo_url();

        //make the invitation url with 24hrs validity
        $key = encode_id($this->encryption->encrypt('company|' . $email . '|' . (time() + (24 * 60 * 60)) . '|' . $company_id), "signup");
        $parser_data['INVITATION_URL'] = get_uri("signup/accept_invitation/" . $key);

        //send invitation email
        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        if (send_app_mail($email, $email_template->subject, $message)) {
            echo json_encode(array('success' => true, 'message' => lang("invitation_sent")));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    /* only visible to client  */

    function users() {
        if ($this->login_user->user_type === "company") {
            $view_data['company_id'] = $this->login_user->company_id;
            $this->template->rander("companys/contacts/users", $view_data);
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
        $item = $this->Companys_model->get_country_info_suggestion($this->input->post("item_name"));
        
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
        $itemss =  $this->Companys_model->get_item_suggestions_country_name($key,$ss);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->title);
       }
        echo json_encode($suggestions);
    }

function get_state_suggestionss() {
    $key = $_REQUEST["q"];
    $ss=$_REQUEST["ss"];
        $itemss =  $this->Countries_model->get_country_suggestionss($key,$ss);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->title);
       }
        echo json_encode($suggestions);
    }


   /* function get_gst_state_suggestion() {

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
function companys_excel_form() {

        $this->load->view('companys/companys_excel_form');
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
                
$list_datas = $this->Companys_model->get_import_detailss($options)->row();
  
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
            $this->Companys_model->insert($data);
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
                
$list_datas = $this->Companys_model->get_import_detailss($options)->row();
  
if(!$list_datas){   
                        //insert member data into database
                        $this->db->insert("companys", array(

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

//clients po list
function companys_po_list($company_id) {
        $this->access_only_allowed_members();

        if ($company_id) {
            $view_data["company_info"] = $this->Companys_model->get_one($company_id);
            $view_data['company_id'] = $company_id;
            $this->load->view("companys/company_po_list", $view_data);
        }
    }





















}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */