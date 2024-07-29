<?php

class Mode_of_dispatch_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'mode_of_dispatch';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $mode_of_dispatch_table = $this->db->dbprefix('mode_of_dispatch');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $mode_of_dispatch_table.id=$id";
        }

        $sql = "SELECT $mode_of_dispatch_table.*
        FROM $mode_of_dispatch_table
        WHERE $mode_of_dispatch_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
