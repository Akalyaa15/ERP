<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_desk extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
       $this->init_permission_checker("student_desk");
        //$this->access_only_allowed_members();
    }

    function index() {
        $this->check_module_availability("module_student_desk");
        if ($this->login_user->is_admin == "1")
        { 

            //$this->template->rander("student_desk/index");
            $this->template->rander_scroll("student_desk/index");
        }
        else if ($this->login_user->user_type == "staff")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, 
        $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander_scroll("student_desk/index");;
        }else {


        $this->template->rander_scroll("student_desk/index");
    } 
        
    }

    //load the student_desk list yearly view
    function yearly() {
        $this->load->view("student_desk/yearly_student_desk");
    }

    //load custom student_desk list
    function custom() {
        $this->load->view("student_desk/custom_student_desk");
    }

    //get clients dropdown
  /*  private function _vap_category_dropdown() {
        $vap_category_dropdown = array(array("id" => "", "text" => "- " . lang("client") . " -"));
        $vap_category = $this->Vap_category_model->get_dropdown_list(array("title"));
        foreach ($vap_category as $key => $value) {
            $vap_category_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $vap_category_dropdown;
    } */
 
    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Student_desk_model->get_one($this->input->post('id'));
        //$view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();
        $country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
         $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $country_get_code->numberCode));
        
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }

        $view_data['state_dropdown'] = $state_categories_suggestion;
        $view_data['vap_category_dropdown'] = $this->Vap_category_model->get_dropdown_list(array("title"),"id" , array("status"=>"active"));
        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $this->load->view('student_desk/modal_form', $view_data);
    }

  private function _get_state_dropdown_select2_data($show_header = false) {
        $states = $this->States_model->get_all()->result();
        $state_dropdown = array();

        

        foreach ($states as $code) {
            $state_dropdown[] = array("id" => $code->id, "text" => $code->title);
        }
        return $state_dropdown;
    }  

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required"
            //"lut_number" => "required"
        ));
        //convert to 24hrs time format
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');

        if (get_setting("time_format") != "24_hours") {
            $start_time = convert_time_to_24hours_format($start_time);
            $end_time = convert_time_to_24hours_format($end_time);
        }
        $student_desk_id = $this->input->post('id');
        $id = $this->input->post('id');
        $data = array(
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
            "end_time" => $end_time,
            "parent_name" => $this->input->post('parent_name'),
            "permanent_address" => $this->input->post('permanent_address'),
             "last_name" => $this->input->post('last_name'),
              "aadhar_no" => $this->input->post('aadhar_no'),
              "country" => $this->input->post('country'),
              "same_address" => $this->input->post('same_address'),
              "state_mandatory"=>$this->input->post('state_mandatory'),




            //"timing" => $this->input->post('timing'),
            //"study_course" => $this->input->post('study_course')









            //"title" =>$this->input->post('title')
        );
if ($id) {
    // check the vendor invoice no     
        $data["email"] =$this->input->post('email');
        $data["id"] =$this->input->post('id');
       if ($this->Student_desk_model->is_student_desk_email_exists($data["email"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
                exit();
            }

        }
        if (!$id) {
    // check the vendor invoice no     
        $data["email"] =$this->input->post('email');
        $data["id"] =$this->input->post('id');
       if ($this->Student_desk_model->is_student_desk_email_exists($data["email"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
                exit();
            }

        }

        $save_id = $this->Student_desk_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Student_desk_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Student_desk_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {

$options = array(
            
            
            "start_date" => $this->input->post('start_date'),
            "end_date" => $this->input->post('end_date')
        );

        $list_data = $this->Student_desk_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Student_desk_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {

$time_format_24_hours = get_setting("time_format") == "24_hours" ? true : false;

$start_time="";
$start_time ="";
$start_times = is_date_exists($data->start_time) ? $data->start_time : "";
$end_times = is_date_exists($data->end_time) ? $data->end_time : "";
            if ($time_format_24_hours) {
                $start_time = $start_times ? date("H:i", strtotime($start_times)) : "";
                $end_time = $end_times ? date("H:i", strtotime($end_times)) : "";
            } else {
                $start_time = $start_times ? convert_time_to_12hours_format(date("H:i:s", strtotime($start_times))) : "";
                 $end_time = $end_times ? convert_time_to_12hours_format(date("H:i:s", strtotime($end_times))) : "";
            }

$to = "<span style='text-align:center;'>To</span>";
$duration_of_course = $data->start_date. '</br>'." ".$to.'</br>'." ".$data->end_date;
$timing = $start_time. '</br>'." ".$to." ".'</br>'.$end_time;
        return array(
anchor(get_uri("student_desk/view/" . $data->id), $data->name." ".$data->last_name),
//$data->name,
            $data->date,
            nl2br($data->college_name),
            $data->department,
            $data->vap_category_title,
            $data->program_title,
            $duration_of_course,
            $timing,

            $data->phone,
            $data->email,
            modal_anchor(get_uri("student_desk/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_student_desk'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_student_desk'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("student_desk/delete"), "data-action" => "delete-confirmation"))
        );
    }

    /* load client details view */

    function view($student_desk_id = 0, $tab = "") {
        //$this->access_only_allowed_members();

        if ($student_desk_id) {
            $options = array("id" => $student_desk_id);
            $student_desk_info = $this->Student_desk_model->get_details($options)->row();
            if ($student_desk_info) {

                
      $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
                $view_data['student_desk_info'] = $student_desk_info;

                

                $view_data["tab"] = $tab;

                //even it's hidden, admin can view all information of client
               
                $this->template->rander("student_desk/view", $view_data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

/* load contact's company info tab view */

    function student_desk_info_tab($student_desk_id = 0) {
        if ($student_desk_id) {
            //$this->access_only_allowed_members_or_client_contact($client_id);

            $view_data['model_info'] = $this->Student_desk_model->get_one($student_desk_id);
            
 $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
$view_data['vap_category_dropdown'] = $this->Vap_category_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
//$view_data['state_dropdown'] = $this->_get_state_dropdown_select2_data();
$country_get_code = $this->Countries_model->get_one($view_data['model_info']->country);
         $state_categories = $this->States_model->get_dropdown_list(array("title"), "id", array("country_code" => $country_get_code->numberCode));
        
        $state_categories_suggestion = array(array("id" => "", "text" => "-"));
        foreach ($state_categories as $key => $value) {
            $state_categories_suggestion[] = array("id" => $key, "text" => $value);
        }

        $view_data['state_dropdown'] = $state_categories_suggestion;
            $view_data['label_column'] = "col-md-2";
            $view_data['field_column'] = "col-md-10";
            $this->load->view('student_desk/student_desk_info_tab', $view_data);
        }
    }


    //save general information of a team member
    function save_student_desk_info($student_desk_info_id) {
        //$this->update_only_allowed_members($user_id);

        validate_submitted_data(array(
            "name" => "required"
            
        ));

        $user_data = array(
            "name" => $this->input->post('name'),
            "college_name" => $this->input->post('college_name'),
            "department" => $this->input->post('department'),
            "date"=>$this->input->post('date'),
            "phone" => $this->input->post('phone')

        );

        $user_data = clean_data($user_data);

        $user_info_updated = $this->Student_desk_model->save($user_data, $student_desk_info_id);

       // save_custom_fields("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type);

        if ($user_info_updated) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
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


    //view html is accessable to client only.
    function preview($student_desk_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($student_desk_id) {

             $student_desk_data = get_student_making_data(
                $student_desk_id);
            //$this->_check_estimate_access_permission($estimate_data);

            //get the label of the estimate
            //$estimate_info = get_array_value($estimate_data, "estimate_info");
           // $estimate_data['estimate_status_label'] = $this->_get_estimate_status_label($estimate_info);

            $view_data['student_desk_preview'] = prepare_student_desk_pdf($student_desk_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['student_desk_id'] = $student_desk_id;

            $this->template->rander("student_desk/student_desk_preview", $view_data);
        } else {
            show_404();
        }
    }



}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */