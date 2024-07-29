<?php

class Vendor_groups_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vendor_groups';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vendor_groups_table = $this->db->dbprefix('vendor_groups');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $vendor_groups_table.id=$id";
        }

        $sql = "SELECT $vendor_groups_table.*
        FROM $vendor_groups_table
        WHERE $vendor_groups_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
