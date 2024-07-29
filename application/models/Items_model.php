<?php

class Items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $items_table = $this->db->dbprefix('items');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $items_table.id=$id";
        }

        /*$product_id = get_array_value($options, "product_id");
        if ($product_id) {
            $where .= " AND $items_table.title='$product_id'";
        }*/
        $product_generation_id = get_array_value($options, "product_generation_id");
        if ($product_generation_id) {
            $where .= " AND $items_table.product_generation_id='$product_generation_id'";
        }
 $quantity = get_array_value($options, "quantity");
        if ($quantity=="0") {
            $where .= " AND $items_table.stock='$quantity'";
        }else if($quantity=="10") {
            $where .= " AND $items_table.stock>0 and $items_table.stock<=10";
        }else if($quantity=="30") {
            $where .= " AND $items_table.stock>10 and $items_table.stock<=30";
        }else if($quantity=="50") {
            $where .= " AND $items_table.stock>30 and $items_table.stock<=50";
        }else if($quantity=="51") {
            $where .= " AND $items_table.stock>='$quantity'";
        }else if($quantity=="101") {
            $where .= " AND $items_table.stock>='$quantity'";
        }
        $sql = "SELECT $items_table.*
        FROM $items_table
        
        WHERE $items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_inventory_product_exists($title, $id = 0) {
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }

}
