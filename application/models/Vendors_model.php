<?php
class Vendors_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vendors';
        parent::__construct($this->table);
    }
    function get_details($options = array()) {
        $vendors_table = $this->db->dbprefix('vendors');
        $users_table = $this->db->dbprefix('users');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $work_orders_table = $this->db->dbprefix('work_orders');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $work_order_payments_table = $this->db->dbprefix('work_order_payments');
        $work_order_items_table = $this->db->dbprefix('work_order_items');
        $vendor_groups_table = $this->db->dbprefix('vendor_groups');

        $where = "WHERE $vendors_table.deleted=0";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $vendors_table.id=$id";
        }

        $group_id = get_array_value($options, "group_id");
        if ($group_id) {
            $where .= " AND FIND_IN_SET('$group_id', $vendors_table.group_ids)";
        }

        // Prepare custom field binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("vendors", $custom_fields, $vendors_table);
        $select_custom_fields = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fields = get_array_value($custom_field_query_info, "join_string");

        $this->db->query('SET SQL_BIG_SELECTS=1');

        $sql = "SELECT $vendors_table.*, 
                       CONCAT($users_table.first_name, ' ', $users_table.last_name) AS primary_contact, 
                       $users_table.id AS primary_contact_id, 
                       $users_table.image AS contact_avatar, 
                       COALESCE(purchase_orders_count_table.total_purchase_orders, 0) AS total_purchase_orders, 
                       COALESCE(purchase_order_details.purchase_order_value, 0) AS purchase_order_value, 
                       COALESCE(purchase_order_details.payment_received, 0) AS payment_received, 
                       COALESCE(purchase_order_details.total_work_orders, 0) AS total_work_orders, 
                       COALESCE(work_order_details.work_order_value, 0) AS work_order_value, 
                       COALESCE(work_order_details.work_order_payment_received, 0) AS work_order_payment_received, 
                       $select_custom_fields
                       (SELECT GROUP_CONCAT($vendor_groups_table.title) 
                        FROM $vendor_groups_table 
                        WHERE FIND_IN_SET($vendor_groups_table.id, $vendors_table.group_ids)) AS groupss
                FROM $vendors_table
                LEFT JOIN $users_table ON $users_table.vendor_id = $vendors_table.id 
                                       AND $users_table.deleted = 0 
                                       AND $users_table.is_primary_contact = 1
                LEFT JOIN (
                    SELECT vendor_id, COUNT(id) AS total_purchase_orders 
                    FROM $purchase_orders_table 
                    WHERE deleted = 0 
                    GROUP BY vendor_id
                ) AS purchase_orders_count_table ON purchase_orders_count_table.vendor_id = $vendors_table.id
                LEFT JOIN (
                    SELECT vendor_id, 
                            SUM(vendor_id) as total_work_orders,
                           SUM(payments_table.payment_received) AS payment_received, 
                           SUM(items_table.purchase_order_value) AS purchase_order_value 
                    FROM $purchase_orders_table 
                    LEFT JOIN (
                        SELECT purchase_order_id, SUM(amount) AS payment_received 
                        FROM $purchase_order_payments_table 
                        WHERE deleted = 0 
                        GROUP BY purchase_order_id
                    ) AS payments_table ON payments_table.purchase_order_id = $purchase_orders_table.id 
                                        AND $purchase_orders_table.deleted = 0 
                                        AND $purchase_orders_table.status = 'not_paid'
                    LEFT JOIN (
                        SELECT purchase_order_id, SUM(net_total) AS purchase_order_value 
                        FROM $purchase_order_items_table 
                        WHERE deleted = 0 
                        GROUP BY purchase_order_id
                    ) AS items_table ON items_table.purchase_order_id = $purchase_orders_table.id 
                                     AND $purchase_orders_table.deleted = 0 
                                     AND $purchase_orders_table.status = 'not_paid'
                    GROUP BY vendor_id
                ) AS purchase_order_details ON purchase_order_details.vendor_id = $vendors_table.id
                LEFT JOIN (
                    SELECT vendor_id, 
                           SUM(work_order_payments_table.work_order_payment_received) AS work_order_payment_received, 
                           SUM(works_table.work_order_value) AS work_order_value 
                    FROM $work_orders_table 
                    LEFT JOIN (
                        SELECT work_order_id, SUM(amount) AS work_order_payment_received 
                        FROM $work_order_payments_table 
                        WHERE deleted = 0 
                        GROUP BY work_order_id
                    ) AS work_order_payments_table ON work_order_payments_table.work_order_id = $work_orders_table.id 
                                                  AND $work_orders_table.deleted = 0 
                                                  AND $work_orders_table.status = 'not_paid'
                    LEFT JOIN (
                        SELECT work_order_id, SUM(net_total) AS work_order_value 
                        FROM $work_order_items_table 
                        WHERE deleted = 0 
                        GROUP BY work_order_id
                    ) AS works_table ON works_table.work_order_id = $work_orders_table.id 
                                     AND $work_orders_table.status = 'not_paid'
                    GROUP BY vendor_id
                ) AS work_order_details ON work_order_details.vendor_id = $vendors_table.id
                $join_custom_fields
                $where";

        $query = $this->db->query($sql);
        return $query ? $query : null;
    }

    // Method to check if the company name is a duplicate
    public function is_duplicate_company_name($company_name, $vendor_id = 0) {
        $this->db->where('company_name', $company_name);
        if ($vendor_id) {
            $this->db->where('id !=', $vendor_id);
        }
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }
}
?>
