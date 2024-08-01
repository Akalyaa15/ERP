<?php

class Countries_model extends Crud_model {

    private $table = 'country';

    function __construct() {
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $countries_table = $this->db->dbprefix('country');
        $vat_types_table = $this->db->dbprefix('vat_types');
        
        $where = " AND $countries_table.deleted=0";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $countries_table.id = ?";
            $params[] = $id;
        }

        $numberCode = get_array_value($options, "numberCode");
        if ($numberCode) {
            $where .= " AND $countries_table.numberCode = ?";
            $params[] = $numberCode;
        }

        $sql = "SELECT $countries_table.*, $vat_types_table.title as vat_name
                FROM $countries_table
                LEFT JOIN $vat_types_table ON $vat_types_table.id = $countries_table.vat_type  
                WHERE 1=1 $where";
                
        return $this->db->query($sql, $params);
    }

    function get_item_suggestions_country_name($keyword = "", $keywords = "") {
        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('states');

        $sql = "SELECT $states_table.title, $states_table.id
                FROM $items_table
                LEFT JOIN $states_table ON $states_table.country_code = $items_table.numberCode  
                WHERE $items_table.deleted = 0 AND $items_table.id = ? AND $states_table.title LIKE ? AND $states_table.deleted = 0
                LIMIT 500";
                
        return $this->db->query($sql, [$keywords, "%$keyword%"])->result();
    }

    function get_country_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('country');

        $sql = "SELECT $items_table.countryName, $items_table.id
                FROM $items_table
                WHERE $items_table.deleted = 0 AND $items_table.countryName LIKE ?
                LIMIT 500";
                
        return $this->db->query($sql, ["%$keyword%"])->result();
    }

    function get_country_info_suggestion($item_name = "") {
        $items_table = $this->db->dbprefix('country');

        $sql = "SELECT $items_table.*
                FROM $items_table
                WHERE $items_table.deleted = 0 AND $items_table.id = ?
                ORDER BY id DESC LIMIT 1";
                
        $result = $this->db->query($sql, [$item_name]);

        if ($result->num_rows()) {
            return $result->row();
        }
    }

    function get_country_code_suggestion($item_name = "") {
        $items_table = $this->db->dbprefix('country');
        $vat_types_table = $this->db->dbprefix('vat_types');

        $sql = "SELECT $items_table.*, $vat_types_table.title as vat_name
                FROM $items_table
                LEFT JOIN $vat_types_table ON $vat_types_table.id = $items_table.vat_type  
                WHERE $items_table.deleted = 0 AND $items_table.id = ?
                ORDER BY id DESC LIMIT 1";
                
        $result = $this->db->query($sql, [$item_name]);

        if ($result->num_rows()) {
            return $result->row();
        }
    }

    function get_country_id_excel($options = array()) {
        $country_table = $this->db->dbprefix('country');
        $where = " AND $country_table.deleted = 0";
        
        $country = get_array_value($options, "countryName");
        if ($country) {
            $where .= " AND $country_table.numberCode = ?";
            $params[] = $country;
        }

        $sql = "SELECT $country_table.*
                FROM $country_table
                WHERE 1=1 $where";
                
        return $this->db->query($sql, $params);
    }

    function is_country_iso_exists($iso) {
        $result = $this->get_all_where(array("iso" => $iso, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        }
        return false;
    }

    function is_country_exists($numberCode) {
        $result = $this->get_all_where(array("numberCode" => $numberCode, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        }
        return false;
    }    

    function is_country_name_exists($countryName) {
        $result = $this->get_all_where(array("countryName" => $countryName, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        }
        return false;
    }

    function get_country_annual_leave_info_suggestion($item_name = "") {
        $items_table = $this->db->dbprefix('country');

        $sql = "SELECT $items_table.*
                FROM $items_table
                WHERE $items_table.deleted = 0 AND $items_table.numberCode = ?
                ORDER BY id DESC LIMIT 1";
                
        $result = $this->db->query($sql, [$item_name]);

        if ($result->num_rows()) {
            return $result->row();
        }
    }
}
