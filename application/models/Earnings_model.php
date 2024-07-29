<?php

class Earnings_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'earnings';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $earnings_table = $this->db->dbprefix('earnings');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $earnings_table.id=$id";
        }

        $sql = "SELECT $earnings_table.*
        FROM $earnings_table
        WHERE $earnings_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_detailss($options = array()) {
        $earnings_table = $this->db->dbprefix('earnings');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $earnings_table.id !=$id";
        }

        $sql = "SELECT $earnings_table.*
        FROM $earnings_table
        WHERE $earnings_table.deleted=0 AND status ='active' AND key_name !='basic_salary'  $where";
        return $this->db->query($sql);
    }

}
