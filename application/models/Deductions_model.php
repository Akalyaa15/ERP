<?php

class Deductions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'deductions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $deductions_table = $this->db->dbprefix('deductions');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $deductions_table.id=$id";
        }

        $sql = "SELECT $deductions_table.*
        FROM $deductions_table
        WHERE $deductions_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
