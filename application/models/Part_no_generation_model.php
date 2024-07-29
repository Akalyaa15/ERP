<?php

class Part_no_generation_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'part_no_generation';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $part_no_generation_table = $this->db->dbprefix('part_no_generation');
        $vendors_table = $this->db->dbprefix('vendors');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $part_no_generation_table.id=$id";
        }
        $part_no = get_array_value($options, "part_no");
        if ($part_no) {
            $where .= " AND $part_no_generation_table.title='$part_no'";
        }

        $group_id = get_array_value($options, "group_id");
        if ($group_id) {
            $where .= " AND FIND_IN_SET('$group_id', $part_no_generation_table.vendor_id)";
        }

        $sql = "SELECT $part_no_generation_table.*,
                       (SELECT GROUP_CONCAT($vendors_table.id) 
                        FROM $vendors_table 
                        WHERE FIND_IN_SET($vendors_table.id, $part_no_generation_table.vendor_id)) AS vendor_ids
                FROM $part_no_generation_table
                WHERE $part_no_generation_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_part_no_suggestion($keyword = "", $d_item = "") {
        $part_no_generation_table = $this->db->dbprefix('part_no_generation');

        $sql = "SELECT $part_no_generation_table.title
                FROM $part_no_generation_table
                WHERE $part_no_generation_table.deleted=0 
                      AND $part_no_generation_table.title LIKE '%$keyword%'
                      AND $part_no_generation_table.title NOT IN $d_item
                LIMIT 30";
        return $this->db->query($sql)->result();
    }

    function get_part_no_info_suggestion($item_name = "") {
        $part_no_generation_table = $this->db->dbprefix('part_no_generation');

        $sql = "SELECT $part_no_generation_table.*
                FROM $part_no_generation_table
                WHERE $part_no_generation_table.deleted=0 
                      AND $part_no_generation_table.title LIKE '%$item_name%'
                ORDER BY id DESC 
                LIMIT 1";
        
        $result = $this->db->query($sql);

        if ($result->num_rows()) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_item_suggestionss($s = "") {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');        
        $vendors_table = $this->db->dbprefix('vendors');

        $sql = "SELECT $vendors_table.currency, $vendors_table.country
                FROM $vendors_table
                LEFT JOIN $purchase_orders_table 
                ON $purchase_orders_table.vendor_id = $vendors_table.id
                WHERE $vendors_table.deleted=0 
                      AND $purchase_orders_table.id='$s'
                LIMIT 1";
        return $this->db->query($sql)->row();
    }

    function is_part_no_generation_exists($title, $id = 0) {
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }
}
?>
