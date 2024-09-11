<?php

class Voucher_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'voucher';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimates_table = $this->db->dbprefix('voucher');
        $clients_table = $this->db->dbprefix('users');
        $payment_method_table = $this->db->dbprefix('payment_methods');
        $voucher_types_table = $this->db->dbprefix('voucher_types');
        $estimate_items_table = $this->db->dbprefix('delivery_items');
        $expenses_table = $this->db->dbprefix('voucher_expenses');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimates_table.id=$id";
        }
        /*$client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $estimates_table.client_id=$client_id";
        }*/

        $created_user_id = get_array_value($options, "created_user_id");
        if ($created_user_id) {
            $where .= " AND $estimates_table.created_user_id=$created_user_id";
        }
        $created_by = get_array_value($options, "created_by");
        if ($created_by) {
            $where .= " AND $estimates_table.created_user_id!=$created_by";
        }        $line_manager = get_array_value($options, "line_manager");
        if ($line_manager) {
            $where .= " AND $estimates_table.line_manager=$line_manager";
        }
        $line_manager_admin = get_array_value($options, "line_manager_admin");
        if($line_manager_admin){
      $where .= " AND ($estimates_table.line_manager=$line_manager_admin OR $estimates_table.line_manager='admin' )AND $estimates_table.created_user_id!=$line_manager_admin";
        }
        $is_accountant = get_array_value($options, "is_accountant");
        if ($is_accountant) {
            $where .= " AND FIND_IN_SET($estimates_table.status, 'verified_by_manager,approved_by_accounts,rejected_by_accounts')";
        }      
          $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($estimates_table.estimate_date BETWEEN '$start_date' AND '$end_date') ";
        }



       $team_member = get_array_value($options, "team_member");
        if ($team_member) {
            $where .= " AND ($expenses_table.user_id=$team_member OR FIND_IN_SET('$team_member', $expenses_table.r_user_id))";
        }

         $line_manager_dropdown = get_array_value($options, "line_manager_dropdown");
        if ($line_manager_dropdown) {
            $where .= " AND ($estimates_table.line_manager=$line_manager_dropdown)";
        }
        


        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $estimates_table.status='$status'";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $estimates_table.status!='draft' ";
        }


        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("delivery", $custom_fields, $estimates_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");



        $sql = "SELECT $estimates_table.*, $payment_method_table.title as title,$voucher_types_table.title as type_title          
        FROM $estimates_table
       
        LEFT JOIN $payment_method_table ON $payment_method_table.id= $estimates_table.payment_method_id
         LEFT JOIN $voucher_types_table ON $voucher_types_table.id= $estimates_table.voucher_type_id
          LEFT JOIN $expenses_table ON $expenses_table.estimate_id= $estimates_table.id
        WHERE $estimates_table.deleted=0 $where";
        return $this->db->query($sql);
    }


    // invoice table invoice no check 
    function is_estimate_no_exists($voucher_no, $id = 0) {
        $result = $this->get_all_where(array("voucher_no" => $voucher_no, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    } 

    function get_last_estimate_id_exists() {
        $estimates_table = $this->db->dbprefix('voucher');

        $sql = "SELECT $estimates_table.*
        FROM $estimates_table
        ORDER BY id DESC LIMIT 1";

        return $this->db->query($sql)->row();
    }
    // end invoice no check 
}