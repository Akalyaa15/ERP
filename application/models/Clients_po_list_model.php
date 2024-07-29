<?php

class Clients_po_list_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'clients_po_list';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vendors_invoice_list_table = $this->db->dbprefix('clients_po_list');
        $vendors_table = $this->db->dbprefix('clients');
        $task_status_table = $this->db->dbprefix('vendors_invoice_status');
         $vendors_invoice_payment_list_table = $this->db->dbprefix("clients_po_payments_list");

        $where= "";
        $id=get_array_value($options, "id");
        if($id){
            $where =" AND $vendors_invoice_list_table.id=$id";
        }
        $purchase_order_id=get_array_value($options, "purchase_order_id");
        if($purchase_order_id){
            $where =" AND $vendors_invoice_list_table.purchase_order_id=$purchase_order_id";
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

$status = get_array_value($options, "status");
        if ($status === "draft") {
            $where .= " AND  $vendors_invoice_list_table.status='draft' AND IFNULL(payments_table.paid_amount,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $vendors_invoice_list_table.status !='draft' AND IFNULL(payments_table.paid_amount,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.paid_amount,0)>0 AND IFNULL(payments_table.paid_amount,0)< $vendors_invoice_list_table.total";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.paid_amount,0),2)>=$vendors_invoice_list_table.total";
        } /*else if ($status === "overdue") {
            $where .= " AND $loan_table.status !='draft' AND $loan_table.due_date<'$now' AND TRUNCATE(IFNULL(payments_table.paid_amount,0),2)<$loan_table.total";
        }*/
        
        $sql = "SELECT $vendors_invoice_list_table.*, $vendors_table.company_name AS vendor_name,$vendors_table.currency_symbol AS currency_symbol ,
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
        $hsn_sac_code_table = $this->db->dbprefix('clients_po_payments_list');
        

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

    function get_purchase_orderid($item_name = "",$loan_voucher_no="") {

       
         $purchase_orders_table = $this->db->dbprefix('estimates');

        $sql = "SELECT $purchase_orders_table.*
        FROM $purchase_orders_table
       
        WHERE $purchase_orders_table.deleted=0  AND $purchase_orders_table.client_id = '$item_name' AND  
        $purchase_orders_table.id  NOT IN  $loan_voucher_no 
       

        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }




}
