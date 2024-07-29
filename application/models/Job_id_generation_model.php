<?php

class Job_id_generation_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'job_id_generation';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $job_id_generation_table = $this->db->dbprefix('job_id_generation');
        $vendors_table = $this->db->dbprefix('vendors');
        //$taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $job_id_generation_table.id = $id";
        }
        
        $job_id = get_array_value($options, "job_id");
        if ($job_id) {
            $where .= " AND $job_id_generation_table.title = '$job_id'";
        }
        
        $group_id = get_array_value($options, "group_id");
        if ($group_id) {
            $where .= " AND FIND_IN_SET('$group_id', $job_id_generation_table.vendor_id)";
        }
    
        // Use a different alias instead of 'groups' to avoid conflict with reserved keyword
        $sql = "SELECT $job_id_generation_table.*, 
                (SELECT GROUP_CONCAT($vendors_table.id) FROM $vendors_table WHERE FIND_IN_SET($vendors_table.id, $job_id_generation_table.vendor_id)) AS vendor_groups
                FROM $job_id_generation_table
                WHERE $job_id_generation_table.deleted = 0 $where";
        
        return $this->db->query($sql);
    }
    
    function get_job_id_suggestion($keyword = "",$d_item="") {
        $job_id_generation_table = $this->db->dbprefix('job_id_generation');
        

        $sql = "SELECT $job_id_generation_table.title
        FROM $job_id_generation_table
        WHERE $job_id_generation_table.deleted=0  AND $job_id_generation_table.title LIKE '%$keyword%'and  $job_id_generation_table.title  NOT IN  $d_item
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }

    function get_job_id_info_suggestion($item_name = "") {

        $job_id_generation_table = $this->db->dbprefix('job_id_generation');

       $sql = "SELECT $job_id_generation_table.*
        FROM $job_id_generation_table
        WHERE $job_id_generation_table.deleted=0  AND $job_id_generation_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

    function get_item_suggestionss($s = "") 
{
 $purchase_orders_table = $this->db->dbprefix('purchase_orders');        
 $vendors_table = $this->db->dbprefix('vendors');

        $sql = "SELECT $vendors_table.currency , $vendors_table.country
        FROM $vendors_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.vendor_id=$vendors_table.id
        WHERE $vendors_table.deleted=0  AND $purchase_orders_table.id='$s'
        LIMIT 1 
        ";
        return $this->db->query($sql)->row();
     }


     function is_job_id_generation_exists($title, $id = 0) {
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }

}
