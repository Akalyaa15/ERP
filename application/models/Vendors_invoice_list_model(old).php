<?php

class Vendors_invoice_list_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vendors_invoice_list';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vendors_invoice_list_table = $this->db->dbprefix('vendors_invoice_list');
        $vendors_table = $this->db->dbprefix('vendors');
        $task_status_table = $this->db->dbprefix('vendors_invoice_status');
         $vendors_invoice_payment_list_table = $this->db->dbprefix("vendors_invoice_payments_list");

        $where= "";
        $id=get_array_value($options, "id");
        if($id){
            $where =" AND $vendors_invoice_list_table.id=$id";
        }
        $vendor_id = get_array_value($options, "vendor_id");
        if($vendor_id){
            $where .= " AND $vendors_invoice_list_table.vendor_id='$vendor_id'";
        }

        $status_id = get_array_value($options, "status_id");
              if($status_id){

            $where .= "AND $vendors_invoice_list_table.status_id='$status_id'";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($vendors_invoice_list_table.invoice_date BETWEEN '$start_date' AND '$end_date') ";
        } 
        
        $sql = "SELECT $vendors_invoice_list_table.*, $vendors_table.company_name AS vendor_name,
        $task_status_table.key_name AS status_key_name,$task_status_table.title AS status_title,  $task_status_table.color AS status_color,IFNULL(payments_table.paid_amount,0) AS paid_amount
        FROM $vendors_invoice_list_table
        LEFT JOIN $vendors_table ON $vendors_invoice_list_table.vendor_id = $vendors_table.id 
        LEFT JOIN $task_status_table ON $vendors_invoice_list_table.status_id = $task_status_table.id
        LEFT JOIN (SELECT task_id, SUM(title) AS paid_amount FROM 
        $vendors_invoice_payment_list_table WHERE deleted=0 GROUP BY task_id) AS payments_table ON payments_table.task_id = $vendors_invoice_list_table.id   
        WHERE $vendors_invoice_list_table.deleted=0 $where";
        return $this->db->query($sql);
    }


    function get_vendors_invoice_paid_amount_suggestion(
        $item_name = "") {
        $hsn_sac_code_table = $this->db->dbprefix('vendors_invoice_payments_list');
        

        $sql = "SELECT sum($hsn_sac_code_table.title) as paid
        FROM $hsn_sac_code_table
        WHERE $hsn_sac_code_table.deleted=0  AND $hsn_sac_code_table.task_id = '$item_name'
        
        ";
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }
    }

function is_vendors_invoice_exists($invoice_no, $id = 0) {
        $result = $this->get_all_where(array("invoice_no" => $invoice_no, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    } 



}
