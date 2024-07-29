<?php

class Delivery_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'delivery_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $delivery_items_table = $this->db->dbprefix('delivery_items');
        $delivery_table = $this->db->dbprefix('delivery');
        
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $delivery_items_table.id=$id";
        }
        $estimate_id = get_array_value($options, "estimate_id");
        if ($estimate_id) {
            $where .= " AND $delivery_items_table.estimate_id=$estimate_id";
        }

        $sql = "SELECT $delivery_items_table.* 
        FROM $delivery_items_table
        LEFT JOIN $delivery_table ON $delivery_table.id=$delivery_items_table.estimate_id
        WHERE $delivery_items_table.deleted=0 $where";
        return $this->db->query($sql);  
    }
function get_sold_details($options = array()) {
        $delivery_items_table = $this->db->dbprefix('delivery_items');
        $delivery_table = $this->db->dbprefix('delivery');
        
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $delivery_items_table.id=$id";
        }
        $estimate_id = get_array_value($options, "estimate_id");
        if ($estimate_id) {
            $where .= " AND $delivery_items_table.estimate_id=$estimate_id";
        }

        $sql = "SELECT $delivery_items_table.sold 
        FROM $delivery_items_table
        LEFT JOIN $delivery_table ON $delivery_table.id=$delivery_items_table.estimate_id
        WHERE $delivery_items_table.deleted=0 
        AND $delivery_items_table.sold!=0 $where";
        return $this->db->query($sql);  
    }
    function get_ret_sold_details($options = array()) {
        $delivery_items_table = $this->db->dbprefix('delivery_items');
        $delivery_table = $this->db->dbprefix('delivery');
        
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $delivery_items_table.id=$id";
        }
        $estimate_id = get_array_value($options, "estimate_id");
        if ($estimate_id) {
            $where .= " AND $delivery_items_table.estimate_id=$estimate_id";
        }

        $sql = "SELECT $delivery_items_table.sold,$delivery_items_table.ret_sold 
        FROM $delivery_items_table
        WHERE $delivery_items_table.deleted=0 
        AND ($delivery_items_table.sold>0 OR $delivery_items_table.ret_sold>0) $where";
        return $this->db->query($sql);  
    }
    function get_details_for_invoice($options = array()) {
        $delivery_items_table = $this->db->dbprefix('delivery_items');
        $delivery_table = $this->db->dbprefix('delivery');
        
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $delivery_items_table.id=$id";
        }
        $estimate_id = get_array_value($options, "estimate_id");
        if ($estimate_id) {
            $where .= " AND $delivery_items_table.estimate_id=$estimate_id";
        }

        $sql = "SELECT $delivery_items_table.*
        FROM $delivery_items_table
        LEFT JOIN $delivery_table ON $delivery_table.id=$delivery_items_table.estimate_id
        WHERE $delivery_items_table.deleted=0 
        AND $delivery_items_table.sold!=0 $where";
        return $this->db->query($sql);  
    }
}
