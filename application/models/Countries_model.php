<?php

class Countries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'country';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $countries_table = $this->db->dbprefix('country');
         $vat_types_table = $this->db->dbprefix('vat_types');
        
        $where= "";
        $id=get_array_value($options, "id");
        if($id){
            $where =" AND $countries_table.id=$id";
        }

        $numberCode=get_array_value($options, "numberCode");
        if($numberCode){
            $where =" AND $countries_table.numberCode=$numberCode";
        }
        
        $sql = "SELECT $countries_table.*,$vat_types_table.title as vat_name
        FROM $countries_table
         LEFT JOIN $vat_types_table ON $vat_types_table.id= $countries_table.vat_type  
        WHERE $countries_table.deleted=0 $where";
        return $this->db->query($sql);
    }


function get_item_suggestions_country_name($keyword = "",$keywords = "") {
        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('states');
        

        $sql = "SELECT $states_table.title,$states_table.id
        FROM $items_table
        LEFT JOIN $states_table ON $states_table.country_code= $items_table.numberCode  
        /*WHERE $items_table.deleted=0  AND $items_table.countryName = '$keywords' AND $states_table.title like '%$keyword%'*/
        WHERE $items_table.deleted=0  AND $items_table.id = '$keywords' AND $states_table.title like '%$keyword%' AND 
        $states_table.deleted=0
        LIMIT 500 
        ";
        return $this->db->query($sql)->result();
     }
    function get_country_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('states');
        

        $sql = "SELECT $items_table.countryName,$items_table.id
        FROM $items_table
        /*LEFT JOIN $states_table ON $states_table.country_code= $items_table.numberCode  */
        WHERE $items_table.deleted=0  AND $items_table.countryName LIKE '%$keyword%'
        LIMIT 500 
        ";
        return $this->db->query($sql)->result();
     }

     function get_country_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('states');

        $sql = "SELECT $items_table.*
        FROM $items_table
        /*LEFT JOIN $states_table ON $states_table.country_code= $items_table.numberCode */
        /*WHERE $items_table.deleted=0  AND $items_table.countryName LIKE '%$item_name%'*/
        WHERE $items_table.deleted=0  AND $items_table.id = '$item_name'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

     function get_country_code_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('states');
        $vat_types_table = $this->db->dbprefix('vat_types');

        $sql = "SELECT $items_table.*,$vat_types_table.title as vat_name
        FROM $items_table
        /*LEFT JOIN $states_table ON $states_table.country_code= $items_table.numberCode */
        /*WHERE $items_table.deleted=0  AND $items_table.countryName LIKE '%$item_name%'*/
                LEFT JOIN $vat_types_table ON $vat_types_table.id= $items_table.vat_type  
WHERE $items_table.deleted=0  AND $items_table.id = '$item_name'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

    //excel,csv  file country name convert to id 
    function get_country_id_excel($options = array()) {
        $country_table = $this->db->dbprefix('country');
        $where = "";
        
        /*$country = get_array_value($options, "countryName");
        if ($country) {
            $where = " AND  $country_table.countryName ='$country'";
        }*/
        $country = get_array_value($options, "countryName");
        if ($country) {
            $where = " AND  $country_table.numberCode ='$country'";
        }
        
        $sql = "SELECT  $country_table.*
        FROM  $country_table
        WHERE  $country_table.deleted=0 $where";
        return $this->db->query($sql);
    }
function is_country_iso_exists($iso) {
        $result = $this->get_all_where(array("iso" => $iso, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
function is_country_exists($numberCode) {
        $result = $this->get_all_where(array("numberCode" => $numberCode, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }    
function is_country_name_exists($countryName) {
        $result = $this->get_all_where(array("countryName" => $countryName, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }


     function get_country_annual_leave_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('country');
       // $states_table = $this->db->dbprefix('states');

        $sql = "SELECT $items_table.*
        FROM $items_table
        /*LEFT JOIN $states_table ON $states_table.country_code= $items_table.numberCode */
        /*WHERE $items_table.deleted=0  AND $items_table.countryName LIKE '%$item_name%'*/
        WHERE $items_table.deleted=0  AND $items_table.numberCode = '$item_name'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }


}
