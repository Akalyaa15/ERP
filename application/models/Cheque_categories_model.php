<?php

class Cheque_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'cheque_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $cheque_categories_table = $this->db->dbprefix('cheque_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $cheque_categories_table.id=$id";
        }

        $sql = "SELECT $cheque_categories_table.*
        FROM $cheque_categories_table
        WHERE $cheque_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
