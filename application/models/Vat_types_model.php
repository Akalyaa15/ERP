<?php

class Vat_types_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vat_types';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vat_types_table = $this->db->dbprefix('vat_types');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $vat_types_table.id=$id";
        }

        $sql = "SELECT $vat_types_table.*
        FROM $vat_types_table
        WHERE $vat_types_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
