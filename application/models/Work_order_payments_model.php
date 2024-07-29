<?php

class Work_order_payments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'work_order_payments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $work_order_payments_table = $this->db->dbprefix('work_order_payments');
        $work_orders_table = $this->db->dbprefix('work_orders');
        $payment_methods_table = $this->db->dbprefix('payment_methods');
        $vendors_table = $this->db->dbprefix('vendors');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $work_order_payments_table.id=$id";
        }

        

        $work_order_id = get_array_value($options, "work_order_id");
        if ($work_order_id) {
            $where .= " AND $work_order_payments_table.work_order_id=$work_order_id";
        }

        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $work_orders_table.vendor_id=$vendor_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $work_orders_table.project_id=$project_id";
        }

        $payment_method_id = get_array_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $work_order_payments_table.payment_method_id=$payment_method_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($work_order_payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $work_order_payments_table.*, $work_orders_table.vendor_id, (SELECT $vendors_table.currency_symbol FROM $vendors_table WHERE $vendors_table.id=$work_orders_table.vendor_id limit 1) AS currency_symbol, $payment_methods_table.title AS payment_method_title
        FROM $work_order_payments_table
        LEFT JOIN $work_orders_table ON $work_orders_table.id=$work_order_payments_table.work_order_id
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $work_order_payments_table.payment_method_id
        WHERE $work_order_payments_table.deleted=0 AND $work_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_yearly_payments_chart($year) {
        $payments_table = $this->db->dbprefix('work_order_payments');
        $work_orders_table = $this->db->dbprefix('work_orders');
         
        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $work_orders_table ON $work_orders_table.id=$payments_table.work_order_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $work_orders_table.deleted=0
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }

}
