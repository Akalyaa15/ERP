<?php

class Hsn_sac_code_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'hsn_sac_code';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $hsn_sac_code_table = $this->db->dbprefix('hsn_sac_code');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $hsn_sac_code_table.id=$id";
        }

        $sql = "SELECT $hsn_sac_code_table.*
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_item_suggestion($keyword = "") {
        $hsn_sac_code_table = $this->db->dbprefix('hsn_sac_code');
        

        $sql = "SELECT $hsn_sac_code_table.hsn_code,$hsn_sac_code_table.hsn_description
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0  AND $hsn_sac_code_table.hsn_code LIKE '%$keyword%'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
    }

    function get_item_info_suggestion($item_name = "") {

        $hsn_sac_code_table = $this->db->dbprefix('hsn_sac_code');
        

        $sql = "SELECT $hsn_sac_code_table.*
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0  AND $hsn_sac_code_table.hsn_code LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }
    function get_item_freight_suggestion($item_name = "") {

        $hsn_sac_code_table = $this->db->dbprefix('hsn_sac_code');
        

        $sql = "SELECT $hsn_sac_code_table.*
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0  AND $hsn_sac_code_table.hsn_code LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

    function get_freight_suggestion($keyword = "") {
        $hsn_sac_code_table = $this->db->dbprefix('hsn_sac_code');
        

        $sql = "SELECT $hsn_sac_code_table.hsn_code,$hsn_sac_code_table.hsn_description
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0  AND $hsn_sac_code_table.hsn_code LIKE '%$keyword%'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
    }


    function is_hsn_code_exists($hsn_code, $id = 0) {
        $result = $this->get_all_where(array("hsn_code" => $hsn_code, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    } 


}
