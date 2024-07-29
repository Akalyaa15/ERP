<?php

class Voucher_expenses_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'voucher_expenses';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expenses_table = $this->db->dbprefix('voucher_expenses');
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $taxes_table = $this->db->dbprefix('taxes');
        $clients_table = $this->db->dbprefix('clients');
        $vendors_table = $this->db->dbprefix('vendors');


        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $expenses_table.id=$id";
        }
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($expenses_table.expense_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $category_id = get_array_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $expenses_table.category_id=$category_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $expenses_table.project_id=$project_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $expenses_table.user_id=$user_id";
        }
        $estimate_id = get_array_value($options, "estimate_id");
if ($estimate_id) {
            $where .= " AND $expenses_table.estimate_id=$estimate_id";
        }
        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("expenses", $custom_fields, $expenses_table);
        $select_custom_fields = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fields = get_array_value($custom_field_query_info, "join_string");


        $sql = "SELECT $expenses_table.*,CONCAT(receiver.first_name, ' ', receiver.last_name)AS receiver_name,CONCAT(i_rep.first_name, ' ', i_rep.last_name)AS i_rep,CONCAT(r_rep.first_name, ' ', r_rep.last_name)AS r_rep,client.company_name AS client_name,client.address AS client_address,CONCAT(client.city, '- ', client.zip)AS client_pincode,receiver_client.company_name AS receiver_client_name,receiver_client.address AS receiver_client_address,CONCAT(receiver_client.city, '- ', receiver_client.zip)AS receiver_client_pincode,vendor.company_name AS vendor_name,vendor.address AS vendor_address,CONCAT(vendor.city, '- ', vendor.zip)AS vendor_pincode,receiver_vendor.company_name AS receiver_vendor_name,receiver_vendor.address AS receiver_vendor_address,CONCAT(receiver_vendor.city, '- ', receiver_vendor.zip)AS receiver_vendor_pincode, $expense_categories_table.title as category_title, 
                 CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name,CONCAT($users_table.employee_id) AS employee_id,CONCAT($users_table.job_title) AS job_title,CONCAT(receiver.first_name, ' ', receiver.last_name) AS r_linked_user_name,CONCAT(receiver.employee_id) AS r_employee_id,CONCAT(receiver.job_title) AS r_job_title,
                 $projects_table.title AS project_title
        FROM $expenses_table
        LEFT JOIN $expense_categories_table ON $expense_categories_table.id= $expenses_table.category_id
        LEFT JOIN $projects_table ON $projects_table.id= $expenses_table.project_id
        LEFT JOIN $users_table ON $users_table.id= $expenses_table.user_id
        LEFT JOIN $users_table as receiver  ON receiver.id= $expenses_table.r_user_id
        LEFT JOIN $users_table as i_rep  ON i_rep.id= $expenses_table.i_represent
         LEFT JOIN $users_table as r_rep  ON r_rep.id= $expenses_table.r_represent
        LEFT JOIN $clients_table as client  ON client.id= $expenses_table.user_id
        LEFT JOIN $clients_table as receiver_client  ON receiver_client.id= $expenses_table.r_user_id
        LEFT JOIN $vendors_table as vendor  ON vendor.id= $expenses_table.user_id
        LEFT JOIN $vendors_table as receiver_vendor  ON receiver_vendor.id= $expenses_table.r_user_id
        WHERE $expenses_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_income_expenses_info() {
        $expenses_table = $this->db->dbprefix('expenses');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();

        $sql1 = "SELECT SUM($invoice_payments_table.amount) as total_income
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0";
        $income = $this->db->query($sql1)->row();

        $sql2 = "SELECT SUM($expenses_table.amount + IFNULL(tax_table.percentage,0)/100*IFNULL($expenses_table.amount,0) + IFNULL(tax_table2.percentage,0)/100*IFNULL($expenses_table.amount,0)) AS total_expenses
        FROM $expenses_table
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
        WHERE $expenses_table.deleted=0";
        $expenses = $this->db->query($sql2)->row();

        $info->income = $income->total_income;
        $info->expneses = $expenses->total_expenses;
        return $info;
    }

    function get_yearly_expenses_chart($year) {
        $expenses_table = $this->db->dbprefix('expenses');
        $taxes_table = $this->db->dbprefix('taxes');

        $expenses = "SELECT SUM($expenses_table.amount + IFNULL(tax_table.percentage,0)/100*IFNULL($expenses_table.amount,0) + IFNULL(tax_table2.percentage,0)/100*IFNULL($expenses_table.amount,0)) AS total, MONTH($expenses_table.expense_date) AS month
        FROM $expenses_table
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
        WHERE $expenses_table.deleted=0 AND YEAR($expenses_table.expense_date)= $year
        GROUP BY MONTH($expenses_table.expense_date)";

        return $this->db->query($expenses)->result();
    }
function get_voucher_expense_details($item_name = "") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.estimate_id = '$item_name'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }
    function get_voucher_id($item_name = "") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.user_id = '$item_name'
        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
    function get_voucher_id_for_others($item_name = "") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.phone = '$item_name'
        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
}
