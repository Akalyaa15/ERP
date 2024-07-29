<?php

class Payslip_payments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslip_payments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payslip_payments_table = $this->db->dbprefix('payslip_payments');
        $payslip_table = $this->db->dbprefix('payslip');
        $payment_methods_table = $this->db->dbprefix('payment_methods');
        $users_table = $this->db->dbprefix('users');


        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $payslip_payments_table.id=$id";
        }
        $voucher_no = get_array_value($options, "voucher_no");
        if ($voucher_no) {
            $where .= " AND $payslip_payments_table.voucher_no=$voucher_no";
        }
        $payslip_id = get_array_value($options, "payslip_id");
        if ($payslip_id) {
            $where .= " AND $payslip_payments_table.payslip_id=$payslip_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $payslip_table.user_id=$user_id";
        }

        /*$project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $invoices_table.project_id=$project_id";
        }*/

        $payment_method_id = get_array_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $payslip_payments_table.payment_method_id=$payment_method_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($payslip_payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $payslip_payments_table.*, $payslip_table.user_id, $payment_methods_table.title AS payment_method_title,CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name ,$users_table.image as user_id_avatar
        FROM $payslip_payments_table
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_payments_table.payslip_id
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $payslip_payments_table.payment_method_id
         LEFT JOIN $users_table ON $users_table.id=  $payslip_table.user_id
        WHERE $payslip_payments_table.deleted=0 AND $payslip_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_yearly_payments_chart($year) {
        $payments_table = $this->db->dbprefix('payslip_payments');
        $invoices_table = $this->db->dbprefix('payslip');
         
        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$payments_table.payslip_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $invoices_table.deleted=0
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }

}
