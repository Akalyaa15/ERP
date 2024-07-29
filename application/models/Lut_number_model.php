<?php

class Lut_number_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'lut_number';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $lut_number_table = $this->db->dbprefix('lut_number');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $lut_number_table.id=$id";
        }

        $sql = "SELECT $lut_number_table.*
        FROM $lut_number_table
        WHERE $lut_number_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    
    


}
