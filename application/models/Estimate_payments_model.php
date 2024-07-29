<?php

class Estimate_payments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'estimate_payments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimate_payments_table = $this->db->dbprefix('estimate_payments');
        $estimates_table = $this->db->dbprefix('estimates');
        $payment_methods_table = $this->db->dbprefix('payment_methods');
        $clients_table = $this->db->dbprefix('clients');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimate_payments_table.id=$id";
        }

        $estimate_id = get_array_value($options, "estimate_id");
        if ($estimate_id) {
            $where .= " AND $estimate_payments_table.estimate_id=$estimate_id";
        }

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $estimates_table.client_id=$client_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $estimates_table.project_id=$project_id";
        }

        $payment_method_id = get_array_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $estimate_payments_table.payment_method_id=$payment_method_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($estimate_payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $estimate_payments_table.*, $estimates_table.client_id, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$estimates_table.client_id limit 1) AS currency_symbol, $payment_methods_table.title AS payment_method_title
        FROM $estimate_payments_table
        LEFT JOIN $estimates_table ON $estimates_table.id=$estimate_payments_table.estimate_id
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $estimate_payments_table.payment_method_id
        WHERE $estimate_payments_table.deleted=0 AND $estimates_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_yearly_payments_chart($year) {
        $payments_table = $this->db->dbprefix('estimate_payments');
        $estimates_table = $this->db->dbprefix('estimates');
         
        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $estimates_table ON $estimates_table.id=$payments_table.estimate_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $estimates_table.deleted=0
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }

}
