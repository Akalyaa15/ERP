<?php

class Tools_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'tools';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $tools_table = $this->db->dbprefix('tools');
        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');
        $vendors_table = $this->db->dbprefix('vendors');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $tools_table.id=$id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $tools_table.user_id=$user_id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $tools_table.company=$client_id";
        }
        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $tools_table.vendor_company=$vendor_id";
        }

        $sql = "SELECT $tools_table.* ,CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name,
                 $clients_table.company_name AS client_company,$vendors_table.company_name AS vendor_company
        FROM $tools_table
        LEFT JOIN $clients_table ON $clients_table.id= $tools_table.company
        LEFT JOIN $vendors_table ON $vendors_table.id= $tools_table.vendor_company
        LEFT JOIN $users_table ON $users_table.id= $tools_table.user_id
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
