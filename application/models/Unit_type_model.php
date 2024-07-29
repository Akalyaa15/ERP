<?php

class Unit_type_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'unit_type';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $unit_type_table = $this->db->dbprefix('unit_type');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $unit_type_table.id=$id";
        }

        $sql = "SELECT $unit_type_table.*
        FROM $unit_type_table
        WHERE $unit_type_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    
    


}
