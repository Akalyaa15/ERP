<?php

class Student_desk_signup extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('email');
    }

    function index() {
        //by default only client can signup directly
        //if client login/signup is disabled then show 404 page
        if (get_setting("disable_student_desk_registration")) {
            show_404();
        }

        $view_data["type"] = "vendor";
        $view_data["signup_type"] = "new_vendor";
        $view_data["signup_message"] = lang("create_an_account_as_a_new_student_desk");
       $view_data['vap_category_dropdown'] = $this->Vap_category_model->get_dropdown_list(array("title"),"id",array("status"=>"active"));
        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;

        $this->load->view("student_desk_signup/index", $view_data);
    }

    //redirected from email
    function accept_invitation($signup_key = "") {
        $valid_key = $this->is_valid_key($signup_key);
        if ($valid_key) {
            $email = get_array_value($valid_key, "email");
            $type = get_array_value($valid_key, "type");
            if ($this->Users_model->is_email_exists($email)) {
                $view_data["heading"] = "Account exists!";
                $view_data["message"] = lang("account_already_exists_for_your_mail") . " " . anchor("signin", lang("signin"));
                $this->load->view("errors/html/error_general", $view_data);
                return false;
            }

            if ($type === "staff") {
                $view_data["signup_message"] = lang("create_an_account_as_a_team_member");
            } else if ($type === "vendor") {
                $view_data["signup_message"] = lang("create_an_account_as_a_vendor_contact");
            }

            $view_data["signup_type"] = "invitation";
            $view_data["type"] = $type;
            $view_data["signup_key"] = $signup_key;
            $this->load->view("vendor_signup/index", $view_data);
        } else {
            $view_data["heading"] = "406 Not Acceptable";
            $view_data["message"] = lang("invitation_expaired_message");
            $this->load->view("errors/html/error_general", $view_data);
        }
    }

    private function is_valid_key($signup_key = "") {
        $signup_key = decode_id($signup_key, "student_desk_signup");
        $signup_key = $this->encryption->decrypt($signup_key);
        $signup_key = explode('|', $signup_key);
        $type = get_array_value($signup_key, "0");
        $email = get_array_value($signup_key, "1");
        $expire_time = get_array_value($signup_key, "2");
        $vendor_id = get_array_value($signup_key, "3");
        if ($type && $email && valid_email($email) && $expire_time && $expire_time > time()) {
            return array("type" => $type, "email" => $email, "vendor_id" => $vendor_id);
        }
    }

    private function is_valid_recaptcha($recaptcha_post_data) {
        //load recaptcha lib
        require_once(APPPATH . "third_party/recaptcha/autoload.php");
        $recaptcha = new \ReCaptcha\ReCaptcha(get_setting("re_captcha_secret_key"));
        $resp = $recaptcha->verify($recaptcha_post_data, $_SERVER['REMOTE_ADDR']);

        if ($resp->isSuccess()) {
            return true;
        } else {

            $error = "";
            foreach ($resp->getErrorCodes() as $code) {
                $error = $code;
            }

            return $error;
        }
    }

    function create_account() {

        $signup_key = $this->input->post("signup_key");

         if ($this->Student_desk_model->is_student_desk_email_exists($this->input->post('email'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
            exit();
        }

        validate_submitted_data(array(
           
            "name" => "required"
            
           
        ));


        //check if there reCaptcha is enabled
        //if reCaptcha is enabled, check the validation
        if (get_setting("re_captcha_secret_key")) {

            $response = $this->is_valid_recaptcha($this->input->post("g-recaptcha-response"));

            if ($response !== true) {

                if ($response) {
                    echo json_encode(array('success' => false, 'message' => lang("re_captcha_error-" . $response)));
                } else {
                    echo json_encode(array('success' => false, 'message' => lang("re_captcha_expired")));
                }

                return false;
            }
        }

        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');

        if (get_setting("time_format") != "24_hours") {
            $start_time = convert_time_to_24hours_format($start_time);
            $end_time = convert_time_to_24hours_format($end_time);
        }else{
            $start_time = convert_time_to_24hours_format($start_time);
            $end_time = convert_time_to_24hours_format($end_time);
        }
        /*if (get_setting("time_format") == "24_hours") {
            $start_time = date("H:i", strtotime($start_time));
            $end_time = date("H:i", strtotime($end_time));
        }*/
//$student_desk_id= $this->input->post('id');
        $user_data = array(
           "name" => $this->input->post('name'),
            "college_name" => $this->input->post('college_name'),
            "department" => $this->input->post('department'),
            "date" =>$this->input->post('date'),
            "phone" => $this->input->post('phone'),
            "communication_address" => $this->input->post('communication_address'),
            "pincode" => $this->input->post('pincode'),
            "state" => $this->input->post('state'),
            "district" => $this->input->post('district'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "gender" => $this->input->post('gender'),
            "email" => $this->input->post('email'),
            "dob" => $this->input->post('dob'),
            "year" => $this->input->post('year'),
            "vap_category" => $this->input->post('vap_category'),
            "program_title" => $this->input->post('program_title'),
            "start_date" => $this->input->post('start_date'),
            "end_date" => $this->input->post('end_date'),
            "start_time" => $start_time,
            "end_time" =>  $end_time,
            "parent_name" => $this->input->post('parent_name'),
            "permanent_address" => $this->input->post('permanent_address'),
             "last_name" => $this->input->post('last_name'),
              "aadhar_no" => $this->input->post('aadhar_no'),
              "country" => $this->input->post('country'),

        );

        $user_data = clean_data($user_data);
        $user_id = $this->Student_desk_model->save($user_data,
            $student_desk_id);

        // don't clean password since there might be special characters 
       // $user_data["password"] = md5($this->input->post("password"));


         if ($user_id) {
            echo json_encode(array("success" => true, 'message' => lang('account_created'). " " . anchor("signin", lang("signin"))."<br> ".anchor(get_uri("student_desk_signup/download_pdf/" . $user_id), "<i class='fa fa-download'></i>" . lang('download_pdf'), array("class" => "btn btn-default", "title" => lang('download_pdf'),)),'id' => $user_id));

        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    function download_pdf($student_desk_id = 0) {
        if ($student_desk_id) {
            $student_desk_data = get_student_making_data($student_desk_id);
          //$this->_check_payslip_access_permission($payslip_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_student_desk_pdf($student_desk_data, "download");
        } else {
            show_404();
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

}
