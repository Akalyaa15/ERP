<?php

class Buyer_types_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'buyer_types';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $buyer_types_table = $this->db->dbprefix('buyer_types');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $buyer_types_table.id=$id";
        }

        $sql = "SELECT $buyer_types_table.*
        FROM $buyer_types_table
        WHERE $buyer_types_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
