<?php

class Bank_name_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'bank_list';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $earnings_table = $this->db->dbprefix('bank_list');
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

    function get_account_number_suggestions($id) {
        $projects_table = $this->db->dbprefix('bank_list');
        $sql = "SELECT GROUP_CONCAT(account_number) as account_number_groups
        FROM $projects_table
        WHERE $projects_table.deleted=0 AND $projects_table.id = $id ";
        return $this->db->query($sql)->row()->account_number_groups;
    }

    function get_item_account_number_suggestions($keywords = "",$bank_id="") {
        
        $items_table = $this->db->dbprefix('bank_list');
       
        

        $sql = "SELECT $items_table.account_number
        FROM $items_table
        
        WHERE $items_table.deleted=0 AND $items_table.id = '$bank_id'   AND $items_table.account_number LIKE '%$keyword%'  
        LIMIT 500 
        ";
        return $this->db->query($sql)->row();
     }
 function is_bank_name_exists($bank_name) {
        $result = $this->get_all_where(array("title" => $bank_name, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
}
