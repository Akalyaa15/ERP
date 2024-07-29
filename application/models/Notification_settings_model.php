<?php

class Notification_settings_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'notification_settings';
        parent::__construct($this->table);
    }

    function notify_to_terms() {
        return array(
            "team_members", "team", "project_members", "client_primary_contact", "client_all_contacts", "task_assignee", "task_collaborators", "comment_creator", "cusomer_feedback_creator", "leave_applicant", "ticket_creator", "ticket_assignee", "estimate_request_assignee", "recipient", "mentioned_members","employee_payslip_applicant","group_members","group_comment_creator","voucher_application_approved_by_manager","voucher_application_submitted","voucher_application_approved_by_accounts","voucher_application_rejected_by_manager","voucher_application_rejected_by_accounts","account_department","voucher_application_paid","voucher_application_resubmitted","line_manager","voucher_creater","leave_manager","leave_alternate"
        );
    }

    function get_details($options = array()) {
        $notification_settings_table = $this->db->dbprefix('notification_settings');
        $users_table = $this->db->dbprefix('users');
        $team_table = $this->db->dbprefix('team');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $notification_settings_table.id=$id";
        }
        
        $category = get_array_value($options, "category");
        if ($category) {
            $where .= " AND $notification_settings_table.category='$category'";
        }

        $sql = "SELECT $notification_settings_table.*, 
                (SELECT GROUP_CONCAT(' ',$users_table.first_name,' ',$users_table.last_name) FROM $users_table WHERE FIND_IN_SET($users_table.id, $notification_settings_table.notify_to_team_members)) as team_members_list,
                (SELECT GROUP_CONCAT(' ',$team_table.title) FROM $team_table WHERE FIND_IN_SET($team_table.id, $notification_settings_table.notify_to_team)) as team_list
        FROM $notification_settings_table
        WHERE $notification_settings_table.deleted=0 $where 
        ORDER BY $notification_settings_table.sort ASC";

        return $this->db->query($sql);
    }

}
