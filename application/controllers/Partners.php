<?php
  if (!defined('BASEPATH'))
    exit('No direct script access allowed');
  class Partners extends MY_Controller {
        function __construct() {
        parent::__construct();

        //check permission to access this module
        $this->init_permission_checker("client");
        $this->load->library('excel');
        $this->load->model('Partners_model');
        }

    /* load clients list view */
    function index() {
        $this->access_only_allowed_members();
        $access_info = $this->get_access_info("invoice");
        $view_data["show_invoice_info"] = (get_setting("module_invoice") && $access_info->access_type == "all") ? true : false;
        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("clients", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data['groups_dropdown'] = json_encode($this->_get_groups_dropdown_select2_data(true));
        //$this->template->rander("partners/index", $view_data);
        $this->template->rander_scroll("partners/index", $view_data);
        }
        function modal_form() {
        $this->access_only_allowed_members();
        $id = $this->input->post('id');
        validate_submitted_data(array(
        "id" => "numeric"
         ));
        $view_data['label_column'] = "col-md-3";
        $view_data['field_column'] = "col-md-9";
        $view_data["view"] = $this->input->post('view'); // view='details' needed only when loading from the client's details view
        $view_data['model_info'] = $this->Partners_model->get_one($id);
        $view_data["currency_dropdown"] = $this->_get_currency_dropdown_select2_data();
        $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();
    
        // Prepare groups dropdown list
        $view_data['groups_dropdown'] = $this->_get_groups_dropdown_select2_data();
    
        // $view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();
        $country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
        $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $country_get_code->numberCode));
    
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }
    
        $view_data['state_dropdown'] = $state_categories_suggestion;
    
        // Ensure $client_id is defined before usage
        $client_id = $view_data['model_info']->client_id ?? null;
        if (!$client_id) {
            // Handle the missing client_id scenario appropriately
            // You can set a default value or handle the error as needed
            $client_id = 1; // Default value or handle error appropriately
        }
    
        // Get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("clients", $client_id, $this->login_user->is_admin, $this->login_user->user_type)->result();
    
        $this->load->view('partners/modal_form', $view_data);
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
        //$client_groups = $this->Partner_groups_model->get_all()->result();
        $client_groups = $this->Partner_groups_model->get_all_where(array("deleted" => 0, "status" => "active"))->result();
        $groups_dropdown = array();

        if ($show_header) {
            $groups_dropdown[] = array("id" => "", "text" => "- " . lang("partner_groups") . " -");
        }

        foreach ($client_groups as $group) {
            $groups_dropdown[] = array("id" => $group->id, "text" => $group->title);
        }
        return $groups_dropdown;
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
   
    public function save() {
        $id = $this->input->post('id');
        $client_id = $this->input->post('id');
        $this->access_only_allowed_members_or_client_contact($client_id);
    
        // Validate inputs
        validate_submitted_data(array(
            "id" => "numeric",
            "company_name" => "required"
        ));
    
        $company_name = $this->input->post('company_name');
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
            "state_mandatory" => $this->input->post('state_mandatory'),
        );
    
        if ($this->login_user->user_type === "staff") {
            $data["group_ids"] = $this->input->post('group_ids') ? $this->input->post('group_ids') : "";
        }
    
        if (!$id) {
            $data["created_date"] = get_current_utc_time();
        }
    
        if ($this->login_user->is_admin) {
            $data["currency_symbol"] = $this->input->post('currency_symbol') ? $this->input->post('currency_symbol') : "";
            $data["currency"] = $this->input->post('currency') ? $this->input->post('currency') : "";
            $data["disable_online_payment"] = $this->input->post('disable_online_payment') ? $this->input->post('disable_online_payment') : 0;
        }
    
        $data = clean_data($data);
    
        // Check for duplicate company name
        if (get_setting("disallow_duplicate_client_company_name") == "1" && $this->Partners_model->is_duplicate_company_name($data["company_name"], $client_id)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_your_company_name")));
            exit();
        }
    
        // Save data
        $save_id = $this->Partners_model->save($data, $id);
    
        // Update clients table
        $this->load->database(); // Use the default database instance
    
        if ($save_id) {
            $client_id = $this->db->insert_id(); // Get the last inserted id for the new client
    
            $this->db->where('partner_id', $client_id);
            $query = $this->db->get('clients');
            $exists = $query->num_rows();
    
            if ($exists == 0) {
                $this->db->insert('clients', array(
                    "partner_id" => $client_id,
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
                    "group_ids" => $this->input->post('group_ids'),
                    "state_mandatory" => $this->input->post('state_mandatory'),
                    "disable_online_payment" => $this->input->post('disable_online_payment') ? $this->input->post('disable_online_payment') : 0
                ));
            } else {
                $this->db->where('partner_id', $client_id);
                $this->db->update('clients', array(
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
                    "group_ids" => $this->input->post('group_ids'),
                    "state_mandatory" => $this->input->post('state_mandatory'),
                    "disable_online_payment" => $this->input->post('disable_online_payment') ? $this->input->post('disable_online_payment') : 0
                ));
            }
    
            // Update partner with client id
            $this->db->where('id', $client_id);
            $this->db->update('partners', array("client_id" => $client_id));
    
            save_custom_fields("partners", $save_id, $this->login_user->is_admin, $this->login_user->user_type);
    
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

        if ($this->Partners_model->delete_client_and_sub_items($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    /* list of clients, prepared for datatable  */
    public function list_data()
    {
        $this->access_only_allowed_members();
    
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("partners", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "custom_fields" => $custom_fields,
            "group_id" => $this->input->post("group_id")
        );
    
        // Update the method name here
        $list_data = $this->Partners_model->getDetails($options)->result();
    
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
    
        echo json_encode(array("data" => $result));
    }
    
    /* return a row of client list  table */
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("partners", $this->login_user->is_admin, $this->login_user->user_type);
        
        // Prepare options for the model method
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );
        
        // Fetch data from the model
        $data = $this->Partners_model->getDetails($options)->row(); // Correct method name
        
        // Check if data is found
        if ($data) {
            return $this->_make_row($data, $custom_fields);
        } else {
            return array(); // Return an empty array or handle the case where no data is found
        }
    }
     /* prepare a row of client list table */

    private function _make_row($data, $custom_fields) {


        $image_url = get_avatar($data->contact_avatar);
        $contact = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->primary_contact";
        $primary_contact = get_client_contact_profile_link($data->primary_contact_id, $contact);

        $group_list = "";
        if ($data->groups) {
            $groups = explode(",", $data->groups);
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
        if ($data->invoice_value) {
            $due = ignor_minor_value($data->invoice_value - $data->payment_received);
        }
$DB1 = $this->load->database('default', TRUE);
 $DB1->select ("id");
 $DB1->from('clients');
  $DB1->where('partner_id',$data->id);
 $query1=$DB1->get();
 $s=$query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->id;
   
   
        } 
       
        $row_data = array(
            $data->id,
            anchor(get_uri("clients/view/" . $data->id), $data->company_name), // Use $data->id instead of $b
            $data->primary_contact ? $primary_contact : "",
            $group_list,
            to_decimal_format($data->total_projects),
            to_currency($data->invoice_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol)
        );
        
        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }
        
        $row_data[] = modal_anchor(get_uri("partners/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_partner'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_client'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("partners/delete"), "data-action" => "delete-confirmation"));
        
        return $row_data;
    }        
    /* load client details view */

    function add_new_contact_modal_form() {
        $this->access_only_allowed_members();
$DB1 = $this->load->database('default', TRUE);
 $DB1->select ("id");
 $DB1->from('partners');
  $DB1->where('client_id',$this->input->post('client_id'));
 $query1=$DB1->get();
 $s=$query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->id;
}
        $view_data['model_info'] = $this->Users_model->get_one();
        $view_data['model_info']->partner_id = $b;
 $view_data['model_info']->client_id = $this->input->post('client_id');

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("contacts", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $this->load->view('partners/contacts/modal_form', $view_data);
    }
    function save_contact() {
        $contact_id = $this->input->post('contact_id');
        $client_id = $this->input->post('client_id');
 $partner_id = $this->input->post('partner_id');
        $this->access_only_allowed_members_or_contact_personally($contact_id);

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
           "phone" => $this->input->post('phone'),
           "alternative_phone" => $this->input->post('alternative_phone'),
            "skype" => $this->input->post('skype'),
            "job_title" => $this->input->post('job_title'),
            "gender" => $this->input->post('gender'),
            "note" => $this->input->post('note')
        );

        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "client_id" => "required|numeric"
        ));


        if (!$contact_id) {
            //inserting new contact. client_id is required

            validate_submitted_data(array(
                "email" => "required|valid_email",
            ));

            //we'll save following fields only when creating a new contact from this form
            $user_data["client_id"] = $client_id;
            $user_data["partner_id"] = $partner_id;

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
        $primary_contact = $this->Clients_model->get_primary_contact($client_id);
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
         private function _contact_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("contacts", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "user_type" => "client",
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

        $contact_link = anchor(get_uri("clients/contact_profile/" . $data->id), $full_name . $primary_contact);
        if ($this->login_user->user_type === "client") {
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

        $row_data[] = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_contact'), "class" => "delete", "data-id" => "$data->id", "data-action-url" => get_uri("clients/delete_contact"), "data-action" => "delete-confirmation"));

        return $row_data;
    }
    function invitation_modal() {


        validate_submitted_data(array(
            "partner_id" => "required|numeric"
        ));

        $partner_id = $this->input->post('partner_id');

        //$this->access_only_allowed_members_or_client_contact($client_id);

        $view_data["partner_info"] = $this->Partners_model->get_one($partner_id);
        $this->load->view('partners/contacts/invitation_modal', $view_data);
    }

    //send a team member invitation to an email address
    function send_invitation() {

        $partner_id = $this->input->post('partner_id');
        $email = trim($this->input->post('email'));

        validate_submitted_data(array(
            "partner_id" => "required|numeric",
            "email" => "required|valid_email|trim"
        ));

        $this->access_only_allowed_members_or_client_contact($client_id);

        $email_template = $this->Email_templates_model->get_final_template("partner_contact_invitation");

        $parser_data["INVITATION_SENT_BY"] = $this->login_user->first_name . " " . $this->login_user->last_name;
        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["SITE_URL"] = get_uri();
        $parser_data["LOGO_URL"] = get_logo_url();

        //make the invitation url with 24hrs validity
        $key = encode_id($this->encryption->encrypt('partner|' . $email . '|' . (time() + (24 * 60 * 60)) . '|' . $partner_id), "signup");
        $parser_data['INVITATION_URL'] = get_uri("signup/accept_invitation/" . $key);

        //send invitation email
        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        if (send_app_mail($email, $email_template->subject, $message)) {
            echo json_encode(array('success' => true, 'message' => lang("invitation_sent")));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }


//Import excel ,csv modal form  for vendors 
function partners_excel_form() {

        $this->load->view('partners/partners_excel_form');
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
        $list_datas = $this->Partners_model->get_import_detailss($options)->row();
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
            //"buyer_type" => 0,
            "group_ids" => 0,
            "deleted" =>0,
            "created_date" => get_my_local_time("Y-m-d")
                    );
                }
            }
            foreach($data as $sd){

         $optionss = array(
            "company_name" => $sd["company_name"],
            "address" => $sd["address"],
            "city" => $sd["city"],
            "state" => $sd["state"],
            "zip" => $sd["zip"],
            "country" => $sd["country"],
            "phone" => $sd["phone"],
            "website" => $sd["website"],
            "gst_number" => $sd["gst_number"],
            "gstin_number_first_two_digits" => $sd["gstin_number_first_two_digits"],
            "currency_symbol" => $sd["currency_symbol"],
            "currency" =>  $sd["currency"],
            );
                
$list_datass = $this->Clients_model->get_import_detailss($optionss)->row(); 
if(!$list_datass){         
            $this->db->insert('partners', $sd);
            $insertId = $this->db->insert_id();
            $this->db->insert('clients',array("partner_id"=>$insertId,"company_name" => $sd["company_name"],"address" => $sd["address"],
            "city" => $sd["city"],
            "state" => $sd["state"],
            "zip" => $sd["zip"],
            "country" => $sd["country"],
            "phone" => $sd["phone"],
            "website" => $sd["website"],
            "gst_number" => $sd["gst_number"],
            "gstin_number_first_two_digits" => $sd["gstin_number_first_two_digits"],
            "currency_symbol" => $sd["currency_symbol"],
            "currency" =>  $sd["currency"],
            //"buyer_type" => 0,
            "group_ids" => 0,
            "deleted" =>0,
            "created_date" => get_my_local_time("Y-m-d")));
         $clientsId = $this->db->insert_id();
         $DB01= $this->load->database('default', TRUE);
         $DB01 ->where('id',$insertId);
         $DB01->update('partners', array("client_id" => $clientsId));
        }
            

            /*$optionss = array(

            "company_name" => $sd["company_name"],
            "address" => $sd["address"],
            "city" => $sd["city"],
            "state" => $sd["state"],
            "zip" => $sd["zip"],
            "country" => $sd["country"],
            "phone" => $sd["phone"],
            "website" => $sd["website"],
            "gst_number" => $sd["gst_number"],
            "gstin_number_first_two_digits" => $sd["gstin_number_first_two_digits"],
            "currency_symbol" => $sd["currency_symbol"],
            "currency" =>  $sd["currency"],
            
            
                    );
                
$list_datass = $this->Clients_model->get_import_detailss($optionss)->row(); 
if(!$list_datass){  */
            

        /*}*/
    }
       }
            /*$this->Partners_model->insert($data);*/
            /*$this->db->insert_batch('partners', $data);*/
   /*$insertId = $this->db->insert_id();


   return  $insertId;*/
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
                
$list_datas = $this->Partners_model->get_import_detailss($options)->row();
  
if(!$list_datas){

$optionss = array(

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
                
$list_datass = $this->Clients_model->get_import_detailss($optionss)->row(); 
if(!$list_datass){         
            //$this->db->insert('partners', $sd);
    //insert member data into database
            $this->db->insert("partners", array(

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
            //"buyer_type" => 0,
            "group_ids" => 0,
            "deleted" =>0,
            "created_date" => get_my_local_time("Y-m-d")
                            /*"name"=>$line[0], 
                            "email"=>$line[1], 
                            "phone"=>$line[2], 
                            "created"=>$line[3], 
                            "status"=>$line[4])*/));
            $insertId = $this->db->insert_id();
            $this->db->insert('clients',array("partner_id"=>$insertId,"company_name" => $line[0],
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
            //"buyer_type" => 0,
            "group_ids" => 0,
            "deleted" =>0,
            "created_date" => get_my_local_time("Y-m-d")));
         $clientsId = $this->db->insert_id();
         $DB01= $this->load->database('default', TRUE);
         $DB01 ->where('id',$insertId);
         $DB01->update('partners', array("client_id" => $clientsId));
        }

                        
                    }
                  
                }
                
                //close opened csv file
                fclose($csvFile);

               
            }
    }
    }
}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */