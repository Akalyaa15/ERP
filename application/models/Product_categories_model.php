<?php

class Product_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'product_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expense_categories_table = $this->db->dbprefix('product_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $expense_categories_table.id=$id";
        }

        $sql = "SELECT $expense_categories_table.*
        FROM $expense_categories_table
        WHERE $expense_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_product_category_list_exists($title,  $id = 0) {
        
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

}
