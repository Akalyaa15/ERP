<?php

class States_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'states';
        parent::__construct($this->table);
    } 

    function get_details($options = array()) {
        $states_table = $this->db->dbprefix('states');
        $country_table = $this->db->dbprefix('country');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $states_table.id = $id";
        }
        
        $sql = "SELECT $states_table.*, $country_table.countryName
                FROM $states_table
                LEFT JOIN $country_table ON $states_table.country_code = $country_table.numberCode
                WHERE $states_table.deleted = 0 $where";
        return $this->db->query($sql);
    }

    // Convert state name to ID for Excel/CSV files
    function get_state_id_excel($options = array()) {
        $state_table = $this->db->dbprefix('states');
        $where = "";
        
        $state = get_array_value($options, "title");
        if ($state) {
            $where = " AND $state_table.state_code = '$state'";
        }
        
        $sql = "SELECT $state_table.*
                FROM $state_table
                WHERE $state_table.deleted = 0 $where";
        return $this->db->query($sql);
    }

    /* Uncomment and correct these functions if needed
    function get_country_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('country');
        
        $sql = "SELECT $items_table.countryName
                FROM $items_table
                WHERE $items_table.deleted = 0 AND $items_table.countryName LIKE '%$keyword%'
                LIMIT 30";
        return $this->db->query($sql)->result();
    }

    function get_country_info_suggestion($item_name = "") {
        $items_table = $this->db->dbprefix('country');
        
        $sql = "SELECT $items_table.*
                FROM $items_table
                WHERE $items_table.deleted = 0 AND $items_table.countryName LIKE '%$item_name%'
                ORDER BY id DESC LIMIT 1";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }
    }
    */

    function is_state_exists($state_code) {
        $result = $this->get_all_where(array("state_code" => $state_code, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }

    function is_state_name_exists($state_name) {
        $result = $this->get_all_where(array("title" => $state_name, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
}
