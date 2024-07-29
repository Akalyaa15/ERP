<?php

class Cheque_status_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'cheque_status';
        parent::__construct($this->table);
    }
    function get_details($options = array()) {
        $cheque_status_table = $this->db->dbprefix('cheque_status');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $cheque_status_table.id=$id";
        }

        $sql = "SELECT $cheque_status_table.*
        FROM $cheque_status_table
        WHERE $cheque_status_table.deleted=0 $where
        ORDER BY $cheque_status_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_max_sort_value() {
        $cheque_status_table = $this->db->dbprefix('cheque_status');

        $sql = "SELECT MAX($cheque_status_table.sort) as sort
        FROM $cheque_status_table
        WHERE $cheque_status_table.deleted=0";
        $result = $this->db->query($sql);
        if ($result->num_rows()) {
            return $result->row()->sort;
        } else {
            return 0;
        }
    }

}
