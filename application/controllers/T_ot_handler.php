<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T_ot_handler extends MY_Controller {

    protected $allowed_members;

    public function __construct() {
        parent::__construct();
        
        // Define $allowed_members here or retrieve it from somewhere
        $this->allowed_members = array(1, 2, 3); // Example array of allowed member IDs
    }

    private function _get_members_dropdown_list_for_filter() {
        //prepare the dropdown list of members
        //don't show none allowed members in dropdown

        $where = array("user_type" => "staff", "where_in" => array("id" => $this->allowed_members));

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdown[] = array("id" => $id, "text" => $name);
        }
        return $members_dropdown;
    }

    private function _get_rm_members_dropdown_list_for_filter() {
        //prepare the dropdown list of members
        //don't show none allowed members in dropdown

        $where = array("user_type" => "resource", "where_in" => array("id" => $this->allowed_members));

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdowns = array(array("id" => "", "text" => "- " . lang("outsource_member") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdowns[] = array("id" => $id, "text" => $name);
        }
        return $members_dropdowns;
    }

    public function index() {
        // $this->check_module_availability("module_attendance");

        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['team_members_dropdowns'] = json_encode($this->_get_rm_members_dropdown_list_for_filter());
        // $this->load->view("ot_handler/index", $view_data);
        $this->template->rander("project_ot_handler/index", $view_data);
    }
}

?>
