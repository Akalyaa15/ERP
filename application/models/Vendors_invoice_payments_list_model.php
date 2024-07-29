<?php

class Vendors_invoice_payments_list_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vendors_invoice_payments_list';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $checklist_items_table = $this->db->dbprefix("vendors_invoice_payments_list");
        $payment_table = $this->db->dbprefix("payment_methods");

        $where = "";

        $task_id = get_array_value($options, "task_id");
        if ($task_id) {
            $where .= " AND $checklist_items_table.task_id=$task_id";
        }

        $sql = "SELECT $checklist_items_table.*, IF($checklist_items_table.sort!=0, $checklist_items_table.sort, $checklist_items_table.id) AS new_sort,$payment_table.title AS vendor_payment_name
        FROM $checklist_items_table
         LEFT JOIN $payment_table ON $payment_table.id=$checklist_items_table.payment_method_id 
        WHERE $checklist_items_table.deleted=0 $where
        ORDER BY new_sort ASC";
        return $this->db->query($sql);
    }

    function get_yearly_payments_chart($year) {
        $payments_table = $this->db->dbprefix('vendors_invoice_payments_list');
        $purchase_orders_table = $this->db->dbprefix('vendors_invoice_list');
         
        $payments = "SELECT SUM($payments_table.title) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$payments_table.task_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $purchase_orders_table.deleted=0
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }

   /* function get_all_checklist_of_project($vendor_id) {
        $checklist_items_table = $this->db->dbprefix('vendors_invoice_payments_list');
        $tasks_table = $this->db->dbprefix('vendors_invoice_lit');

        $sql = "SELECT $checklist_items_table.task_id, $checklist_items_table.title
        FROM $checklist_items_table
        LEFT JOIN $tasks_table ON $tasks_table.id = $checklist_items_table.task_id 
        WHERE $checklist_items_table.deleted=0 AND $tasks_table.project_id = $project_id";
        return $this->db->query($sql);
    } */

}
