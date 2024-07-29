<?php

class Payment_status_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payment_status';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payment_status_table = $this->db->dbprefix('payment_status');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $payment_status_table.id=$id";
        }

        $sql = "SELECT $payment_status_table.*
        FROM $payment_status_table
        WHERE $payment_status_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
