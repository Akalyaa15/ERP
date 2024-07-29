<?php

class Vap_category_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vap_category';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vap_category_table = $this->db->dbprefix('vap_category');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $vap_category_table.id=$id";
        }

        $sql = "SELECT $vap_category_table.*
        FROM $vap_category_table
        WHERE $vap_category_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    
    


}
