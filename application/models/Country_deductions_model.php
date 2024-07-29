<?php

class Country_deductions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'country_deductions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $deductions_table = $this->db->dbprefix('country_deductions');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $deductions_table.id=$id";
        }
        $country_id = get_array_value($options, "country_id");
        if ($country_id) {
            $where = " AND $deductions_table.country_id=$country_id";
        }

        $sql = "SELECT $deductions_table.*
        FROM $deductions_table
        WHERE $deductions_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
