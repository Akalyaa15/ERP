<?php

class Work_order_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'work_order_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimate_items_table = $this->db->dbprefix('work_order_items');
        $estimates_table = $this->db->dbprefix('work_orders');
        $clients_table = $this->db->dbprefix('vendors');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimate_items_table.id=$id";
        }
        $work_order_id = get_array_value($options, "work_order_id");
        if ($work_order_id) {
            $where .= " AND $estimate_items_table.work_order_id=$work_order_id";
        }

        $sql = "SELECT $estimate_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$estimates_table.vendor_id limit 1) AS currency_symbol
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id=$estimate_items_table.work_order_id
        WHERE $estimate_items_table.deleted=0 $where";
        return $this->db->query($sql);  
    }

    function get_item_suggestion($keyword = "",$d_item="") {
        $items_table = $this->db->dbprefix('outsource_jobs');
        

        $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%'and  $items_table.title  NOT IN  $d_item
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }

    /* function get_item_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('outsource_jobs');
        

        $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     } */



    function get_item_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('outsource_jobs');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }
    }

    function get_item_suggestionss($s = "") 
{
 $work_orders_table = $this->db->dbprefix('work_orders');        
 $vendors_table = $this->db->dbprefix('vendors');

        $sql = "SELECT $vendors_table.currency , $vendors_table.country
        FROM $vendors_table
        LEFT JOIN $work_orders_table ON $work_orders_table.vendor_id=$vendors_table.id
        WHERE $vendors_table.deleted=0  AND $work_orders_table.id='$s'
        LIMIT 1 
        ";
        return $this->db->query($sql)->row();
     }

     function is_wo_product_exists($title,$work_order_id, $id = 0) {
        $result = $this->get_all_where(array("title" => $title ,"work_order_id" => $work_order_id, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }


}
