<?php

class Product_id_generation_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'product_id_generation';
        parent::__construct($this->table);
    }

     function get_details($options = array()) {
    $product_id_generation_table = $this->db->dbprefix('product_id_generation');
        $part_no_generation_table = $this->db->dbprefix('part_no_generation');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $product_id_generation_table.id=$id";
        }
        $product_id = get_array_value($options, "product_id");
        if ($product_id) {
            $where .= " AND $product_id_generation_table.title='$product_id'";
        }
        $sql = "SELECT $product_id_generation_table.*,$part_no_generation_table.rate AS part_no_value
        FROM $product_id_generation_table
        LEFT JOIN  $part_no_generation_table ON  $part_no_generation_table.id = $product_id_generation_table.associated_with_part_no
        WHERE $product_id_generation_table.deleted=0 $where";
        return $this->db->query($sql);
    }
function get_product_id_suggestion($keyword = "") {
        $product_id_generation_table = $this->db->dbprefix('product_id_generation');


        $inventory_table = $this->db->dbprefix('items');

        $sqls = "SELECT $inventory_table.title
        FROM $inventory_table
        WHERE $inventory_table.deleted=0  
        ";
        $inventory_result = $this->db->query($sqls)->result();

        if($inventory_result){
        $inventory_items = array();
foreach ($inventory_result as $inventory) {
            $inventory_items[] = $inventory->title;
        }
$aa=json_encode($inventory_items);
$vv=str_ireplace("[","(",$aa);
$inventory_item=str_ireplace("]",")",$vv);
       
}else{
    $inventory_item="('empty')";
}

        

        $sql = "SELECT $product_id_generation_table.title
        FROM $product_id_generation_table
        WHERE $product_id_generation_table.deleted=0  AND $product_id_generation_table.title LIKE '%$keyword%' AND 
           $product_id_generation_table.title  NOT IN  $inventory_item
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }

    function get_product_id_info_suggestion($item_name = "") {

        $product_id_generation_table = $this->db->dbprefix('product_id_generation');

        $sql = "SELECT $product_id_generation_table.*
        FROM $product_id_generation_table
        WHERE $product_id_generation_table.deleted=0  AND $product_id_generation_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

     function is_product_id_generation_exists($title, $id = 0) {
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }

     
}
