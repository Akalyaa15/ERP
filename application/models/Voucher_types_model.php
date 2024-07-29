<?php

class Voucher_types_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'voucher_types';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $voucher_types_table = $this->db->dbprefix('voucher_types');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $voucher_types_table.id=$id";
        }

        $sql = "SELECT $voucher_types_table.*
        FROM $voucher_types_table
        WHERE $voucher_types_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
