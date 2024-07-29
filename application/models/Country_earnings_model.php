<?php

class Country_earnings_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'country_earnings';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $earnings_table = $this->db->dbprefix('country_earnings');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $earnings_table.id=$id";
        }

        $country_id = get_array_value($options, "country_id");
        if ($country_id) {
            $where = " AND $earnings_table.country_id=$country_id";
        }
        

        $sql = "SELECT $earnings_table.*
        FROM $earnings_table
        WHERE $earnings_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_detailss($options = array()) {
        $earnings_table = $this->db->dbprefix('country_earnings');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $earnings_table.id !=$id";
        }

        $country_id = get_array_value($options, "country_id");
        if ($country_id) {
            $where = " AND $earnings_table.country_id=$country_id";
        }
        

        $sql = "SELECT $earnings_table.*
        FROM $earnings_table
        WHERE $earnings_table.deleted=0 AND $earnings_table.status ='active' AND $earnings_table.key_name !='basic_salary' AND $earnings_table.id !=$id  $where";
        return $this->db->query($sql);
    }

    function insert($datas)
    {
    $this->db->insert_batch('country_earnings', $datas);
    }

}
