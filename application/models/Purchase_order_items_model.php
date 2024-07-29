<?php

class Purchase_order_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_order_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimate_items_table = $this->db->dbprefix('purchase_order_items');
        $estimates_table = $this->db->dbprefix('purchase_orders');
        $clients_table = $this->db->dbprefix('vendors');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimate_items_table.id=$id";
        }
        $purchase_order_id = get_array_value($options, "purchase_order_id");
        if ($purchase_order_id) {
            $where .= " AND $estimate_items_table.purchase_order_id=$purchase_order_id";
        }

        $sql = "SELECT $estimate_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$estimates_table.vendor_id limit 1) AS currency_symbol
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id=$estimate_items_table.purchase_order_id
        WHERE $estimate_items_table.deleted=0 $where";
        return $this->db->query($sql);  
    }

    function is_po_product_exists($title,$purchase_order_id, $id = 0) {
        $result = $this->get_all_where(array("title" => $title ,"purchase_order_id" => $purchase_order_id, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }

}
