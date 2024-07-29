<?php

class Tools_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'tools';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $tools_table = $this->db->dbprefix('tools');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $tools_table.id=$id";
        }

        $sql = "SELECT $tools_table.*
        FROM $tools_table
        WHERE $tools_table.deleted=0 $where";
        return $this->db->query($sql);
    }
function get_item_suggestion($keyword = "") {
        $tools_table = $this->db->dbprefix('tools');
        

        $sql = "SELECT $tools_table.title
        FROM $tools_table
        WHERE $tools_table.deleted=0  AND $tools_table.title LIKE '%$keyword%'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }
function get_item_suggestions($keyword = "",$d_item="") {
        $tools_table = $this->db->dbprefix('tools');
        

        $sql = "SELECT $tools_table.title
        FROM $tools_table
        WHERE $tools_table.deleted=0  AND $tools_table.title LIKE '%$keyword%' and  $tools_table.title  NOT IN  $d_item
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }
    function get_item_info_suggestion($item_name = "") {

        $tools_table = $this->db->dbprefix('tools');

        $sql = "SELECT $tools_table.*
        FROM $tools_table
        WHERE $tools_table.deleted=0  AND $tools_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }
}
