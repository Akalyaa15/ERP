<?php

class Dc_types_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'dc_types';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $dc_types_table = $this->db->dbprefix('dc_types');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $dc_types_table.id=$id";
        }

        $sql = "SELECT $dc_types_table.*
        FROM $dc_types_table
        WHERE $dc_types_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
