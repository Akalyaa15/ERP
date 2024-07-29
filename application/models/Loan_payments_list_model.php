<?php

class Loan_payments_list_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan_payments_list';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $checklist_items_table = $this->db->dbprefix("loan_payments_list");
        $payment_table = $this->db->dbprefix("payment_methods");
        $where = "";

        $loan_id = get_array_value($options, "loan_id");
        if ($loan_id) {
            $where .= " AND $checklist_items_table.loan_id=$loan_id";
        }

        $sql = "SELECT $checklist_items_table.*, IF($checklist_items_table.sort!=0, $checklist_items_table.sort, $checklist_items_table.id) AS new_sort ,$payment_table.title AS loan_payment_name
        FROM $checklist_items_table
        LEFT JOIN $payment_table ON $payment_table.id=$checklist_items_table.payment_method_id
        WHERE $checklist_items_table.deleted=0 $where
        ORDER BY new_sort ASC";
        return $this->db->query($sql);
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

    function get_vendors_invoice_paid_amount_suggestion(
        $item_name = "") {
        $hsn_sac_code_table = $this->db->dbprefix('loan_payments_list');
        

        $sql = "SELECT sum($hsn_sac_code_table.title) as paid
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0  AND $hsn_sac_code_table.loan_id = '$item_name'
        
        ";
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }
    }

}
