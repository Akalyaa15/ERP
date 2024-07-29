<?php

class Delivery_model extends Crud_model {

    private $table = null; 

    function __construct() {
        $this->table = 'delivery';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimates_table = $this->db->dbprefix('delivery');
        $clients_table = $this->db->dbprefix('users');
        
        $estimate_items_table = $this->db->dbprefix('delivery_items');
 $dc_types_table = $this->db->dbprefix('dc_types');
  $mode_of_dispatch_table = $this->db->dbprefix('mode_of_dispatch');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimates_table.id=$id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $estimates_table.client_id=$client_id";
        }
$invoice_no = get_array_value($options, "invoice_for_dc");
        if ($invoice_no) {
            $where .= " AND $estimates_table.invoice_for_dc=$invoice_no";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($estimates_table.estimate_date BETWEEN '$start_date' AND '$end_date') ";
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



        $sql = "SELECT $estimates_table.*, $clients_table.first_name,$clients_table.last_name, $clients_table.id as project_title, $dc_types_table.title as dc_type_title, $mode_of_dispatch_table.title as mode_of_dispatch           
        FROM $estimates_table
        LEFT JOIN $clients_table ON $clients_table.id= $estimates_table.client_id
        LEFT JOIN $dc_types_table ON $dc_types_table.id= $estimates_table.dc_type_id
        LEFT JOIN $mode_of_dispatch_table ON $mode_of_dispatch_table.id= $estimates_table.dispatched_through
        WHERE $estimates_table.deleted=0 $where";
        return $this->db->query($sql);
    }
    function get_id($options = array()) {
        $invoice_items_table = $this->db->dbprefix('delivery');
        

        $sql = "SELECT $invoice_items_table.id FROM $invoice_items_table 
        ORDER BY $invoice_items_table.id DESC";
        return $this->db->query($sql);
    }

     // invoice table invoice no check 
    function is_estimate_no_exists($dc_no, $id = 0) {
        $result = $this->get_all_where(array("dc_no" => $dc_no, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    } 

    function get_last_estimate_id_exists() {
        $estimates_table = $this->db->dbprefix('delivery');

        $sql = "SELECT $estimates_table.*
        FROM $estimates_table
        ORDER BY id DESC LIMIT 1";

        return $this->db->query($sql)->row();
    }
    // end invoice no check 
}
