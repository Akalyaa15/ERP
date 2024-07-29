<?php

class Cheque_handler_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'cheque_handler';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $tools_table = $this->db->dbprefix('cheque_handler');
        $cheque_status_table = $this->db->dbprefix('cheque_status');
        $bank_name_table = $this->db->dbprefix('bank_list');
        $cheque_categories_table = $this->db->dbprefix('cheque_categories');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $tools_table.id=$id";
        }
$status_id = get_array_value($options, "status_id");
        if ($status_id) {
            $where .= "AND $tools_table.status_id=$status_id";
        }
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($tools_table.issue_date BETWEEN '$start_date' AND '$end_date') ";
        }
        //contact members 
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $tools_table.member_id=$user_id AND ($tools_table.member_type='tm' OR $tools_table.member_type='om')";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $tools_table.member_id=$client_id AND $tools_table.member_type='clients'";
        }
        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $tools_table.member_id=$vendor_id AND $tools_table.member_type='vendors'";
        }
        $other_id = get_array_value($options, "other_id");
        if ($other_id) {
            $where .= " AND $tools_table.id=$other_id AND $tools_table.member_type='others'";
        }
        //end contact members
        $sql = "SELECT $tools_table.*,$cheque_status_table.key_name AS status_key_name,$cheque_status_table.title AS status_title,  $cheque_status_table.color AS status_color,$bank_name_table.title AS bank_name,$cheque_categories_table.title AS cheque_category
        FROM $tools_table
        LEFT JOIN $cheque_status_table ON $tools_table.status_id = $cheque_status_table.id 
        LEFT JOIN $bank_name_table ON $tools_table.bank_name = $bank_name_table.id 
        LEFT JOIN $cheque_categories_table ON $tools_table.cheque_category_id = $cheque_categories_table.id  
        WHERE $tools_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
