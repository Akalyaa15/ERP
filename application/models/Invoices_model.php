<?php

class Invoices_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'invoices';
        parent::__construct($this->table);
    }
    function get_details($options = array()) {
        $invoices_table = $this->db->dbprefix('invoices');
        $clients_table = $this->db->dbprefix('clients');
        $projects_table = $this->db->dbprefix('projects');
       $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoice_items_table = $this->db->dbprefix('invoice_items');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoices_table.id=$id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $invoices_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $invoices_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            //$where .= " AND ($invoices_table.due_date BETWEEN '$start_date' AND '$end_date') ";
            $where .= " AND ($invoices_table.bill_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $next_recurring_start_date = get_array_value($options, "next_recurring_start_date");
        $next_recurring_end_date = get_array_value($options, "next_recurring_end_date");
        if ($next_recurring_start_date && $next_recurring_start_date) {
            $where .= " AND ($invoices_table.next_recurring_date BETWEEN '$next_recurring_start_date' AND '$next_recurring_end_date') ";
        } else if ($next_recurring_start_date) {
            $where .= " AND $invoices_table.next_recurring_date >= '$next_recurring_start_date' ";
        } else if ($next_recurring_end_date) {
            $where .= " AND $invoices_table.next_recurring_date <= '$next_recurring_end_date' ";
        }

        $recurring_invoice_id = get_array_value($options, "recurring_invoice_id");
        if ($recurring_invoice_id) {
            $where .= " AND $invoices_table.recurring_invoice_id=$recurring_invoice_id";
        }

        $now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");
        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);
        $invoice_value_calculation = "TRUNCATE($invoice_value_calculation_query,2)";

        $profit_value_calculation = "(
            IFNULL(profit_table.profit_value,0))"; 

        if ($status === "draft") {
            $where .= " AND $invoices_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $invoices_table.status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$invoice_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$invoice_value_calculation";
        } else if ($status === "overdue") {
            $where .= " AND $invoices_table.status !='draft' AND $invoices_table.due_date<'$now' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)<$invoice_value_calculation";
        }


        $recurring = get_array_value($options, "recurring");
        if ($recurring) {
            $where .= " AND $invoices_table.recurring=1";
        }


        $exclude_due_reminder_date = get_array_value($options, "exclude_due_reminder_date");
        if ($exclude_due_reminder_date) {
            $where .= " AND ($invoices_table.due_reminder_date !='$exclude_due_reminder_date' OR $invoices_table.due_reminder_date = '' IS NULL ) ";
        }

        $exclude_recurring_reminder_date = get_array_value($options, "exclude_recurring_reminder_date");
        if ($exclude_recurring_reminder_date) {
            $where .= " AND ($invoices_table.recurring_reminder_date !='$exclude_recurring_reminder_date') ";
        }


        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("invoices", $custom_fields, $invoices_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");




        $sql = "SELECT $invoices_table.*, $clients_table.currency, $clients_table.currency_symbol,$clients_table.country,$clients_table.buyer_type, $clients_table.company_name, $projects_table.title AS project_title,
           $invoice_value_calculation_query AS invoice_value, IFNULL(payments_table.payment_received,0) AS payment_received ,$profit_value_calculation AS profit_value 
           $select_custom_fieds
        FROM $invoices_table
        LEFT JOIN $clients_table ON $clients_table.id= $invoices_table.client_id
        LEFT JOIN $projects_table ON $projects_table.id= $invoices_table.project_id
        LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
        LEFT JOIN (SELECT invoice_id, SUM(net_total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 
        LEFT JOIN (SELECT invoice_id, SUM(profit_value) AS profit_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS profit_table ON profit_table.invoice_id = $invoices_table.id
        $join_custom_fieds
        WHERE $invoices_table.deleted=0 $where";
        return $this->db->query($sql);
    }
 function get_invoice_total_summary($invoice_id = 0) {
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoices_table = $this->db->dbprefix('invoices');
        $clients_table = $this->db->dbprefix('clients');
        $installation_total_sql = "SELECT SUM($invoice_items_table.installation_total) AS installation_total
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $installation_total = $this->db->query($installation_total_sql)->row();
        

        $item_quantity_total_sql = "SELECT SUM($invoice_items_table.quantity_total) AS invoice_quantity_subtotal
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $item_quantity_total = $this->db->query($item_quantity_total_sql)->row();
        $item_sql = "SELECT SUM($invoice_items_table.total) AS invoice_subtotal
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $items_sql = "SELECT SUM($invoice_items_table.tax_amount) AS invoice_tax_subtotal
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $items = $this->db->query($items_sql)->row();

        $itemss_sql = "SELECT SUM($invoice_items_table.gst) AS invoice_tax_percentage_subtotal
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $itemss = $this->db->query($itemss_sql)->row();

        $net_total_sql = "SELECT SUM($invoice_items_table.net_total) AS invoice_net_subtotal
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $net_total = $this->db->query($net_total_sql)->row();

        $payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.invoice_id=$invoice_id";
        $payment = $this->db->query($payment_sql)->row();

       $invoice_sql = "SELECT $invoices_table.*
        FROM $invoices_table
       WHERE $invoices_table.deleted=0 AND $invoices_table.id=$invoice_id";
        $invoice = $this->db->query($invoice_sql)->row(); 

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoice->client_id";
        $client = $this->db->query($client_sql)->row();

        $installation_tax_sql = "SELECT SUM($invoice_items_table.installation_tax_amount) AS invoice_installation_tax
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.invoice_id    
        WHERE $invoice_items_table.deleted = 0 AND $invoice_items_table.invoice_id = $invoice_id AND $invoices_table.deleted = 0";
    $installation_tax = $this->db->query($installation_tax_sql)->row();
    
    $installation_tax_sql = "SELECT SUM($invoice_items_table.installation_tax_amount) AS invoice_installation_tax
    FROM $invoice_items_table
    LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.invoice_id    
    WHERE $invoice_items_table.deleted = 0 AND $invoice_items_table.invoice_id = $invoice_id AND $invoices_table.deleted = 0";
$installation_tax = $this->db->query($installation_tax_sql)->row();

$result = new stdClass();
$result->invoice_quantity_subtotal = isset($item_quantity_total->invoice_quantity_subtotal) ? (float) $item_quantity_total->invoice_quantity_subtotal : 0;
$result->installation_total = isset($installation_total->installation_total) ? (float) $installation_total->installation_total : 0;
$result->invoice_subtotal = isset($item->invoice_subtotal) ? (float) $item->invoice_subtotal : 0;
$result->invoice_tax_subtotal = isset($items->invoice_tax_subtotal) ? (float) $items->invoice_tax_subtotal : 0;
$result->invoice_net_subtotal = isset($net_total->invoice_net_subtotal) ? (float) $net_total->invoice_net_subtotal : 0;
$result->freight_amount = isset($invoice->freight_amount) ? (float) $invoice->freight_amount : 0;
$result->freight_rate_amount = isset($invoice->amount) ? (float) $invoice->amount : 0;
$result->freight_tax_amount = isset($invoice->freight_tax_amount) ? (float) $invoice->freight_tax_amount : 0;
$result->invoice_net_subtotal_default = $result->invoice_net_subtotal + $result->freight_amount;
$result->igst_total = $result->invoice_tax_subtotal;
$result->installation_tax = isset($installation_tax->invoice_installation_tax) ? (float) $installation_tax->invoice_installation_tax : 0;

// Ensure $invoice->gst is set and is a number before performing division
$gst = isset($invoice->gst) ? (float) $invoice->gst : 0;
$result->freight_tax1 = ($gst / 100) + 1;

if (is_numeric($result->freight_tax1) && $result->freight_tax1 != 0) {
    $result->freight_tax2 = $result->freight_amount / $result->freight_tax1;
} else {
    $result->freight_tax2 = 0;
}

$result->freight_tax3 = $result->freight_tax2 * ($gst / 100);
$result->freight_tax = $result->freight_tax2 + $result->freight_tax3;
$result->invoice_net_total = $result->invoice_net_subtotal + $result->freight_amount;
$result->total_paid = isset($payment->total_paid) ? (float) $payment->total_paid : 0;

$result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
$result->currency = $client->currency ? $client->currency : get_setting("default_currency");

// Ensure invoice_net_total and total_paid are numeric before performing subtraction
$invoice_net_total_numeric = is_numeric($result->invoice_net_total) ? $result->invoice_net_total : 0;
$total_paid_numeric = is_numeric($result->total_paid) ? $result->total_paid : 0;

// Calculate balance due as a numeric value
$balance_due = round($invoice_net_total_numeric, 2) - round($total_paid_numeric, 2);

// Format the final balance due as a string
$result->balance_due = number_format($balance_due, 2, ".", "");

return $result;



    }

    function invoice_statistics($options = array()) {
        $invoices_table = $this->db->dbprefix('invoices');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        
        $info = new stdClass();
        $year = get_my_local_time("Y");

        $where = "";
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $payments = "SELECT SUM($invoice_payments_table.amount) AS total, MONTH($invoice_payments_table.payment_date) AS month
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND YEAR($invoice_payments_table.payment_date)=$year AND $invoices_table.deleted=0 $where
            GROUP BY MONTH($invoice_payments_table.payment_date)";
        $info->payments = $this->db->query($payments)->result();

        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $invoices = "SELECT SUM(total) AS total, MONTH(due_date) AS month FROM (SELECT $invoice_value_calculation_query AS total ,$invoices_table.due_date
            FROM $invoices_table
            LEFT JOIN (SELECT invoice_id, SUM(net_total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid' $where AND YEAR($invoices_table.due_date)=$year) as details_table
            GROUP BY  MONTH(due_date)";

        $info->payments = $this->db->query($payments)->result();
        $info->invoices = $this->db->query($invoices)->result();
        return $info;
    }

    function get_invoices_total_and_paymnts() {
        $invoices_table = $this->db->dbprefix('invoices');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoice_items_table = $this->db->dbprefix('invoice_items');
       
        $info = new stdClass();


        $payments = "SELECT SUM($invoice_payments_table.amount) AS total
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND $invoices_table.deleted=0";
        $info->payments = $this->db->query($payments)->result();
        
        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $invoices = "SELECT SUM(total) AS total FROM (SELECT $invoice_value_calculation_query AS total
            FROM $invoices_table
            
            LEFT JOIN (SELECT invoice_id, SUM(net_total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid') as details_table";

        $info->payments_total = $this->db->query($payments)->row()->total;
        $info->invoices_total = $this->db->query($invoices)->row()->total;
        $info->due = $info->invoices_total - $info->payments_total;
        return $info;
    }
    private function _get_invoice_value_calculation_query($invoices_table){
          
            $freight_amount = "(IFNULL($invoices_table.freight_amount,0))";

            $invoice_value_calculation_query = "round(
                IFNULL(items_table.invoice_value,0)+$freight_amount
               )";
            
            return $invoice_value_calculation_query;
    }
    //change the invoice status from draft to not_paid
    function set_invoice_status_to_not_paid($invoice_id = 0) {
        $status_data = array("status" => "not_paid");
        return $this->save($status_data, $invoice_id);
    }

    //get the recurring invoices which are ready to renew as on a given date
    function get_renewable_invoices($date) {
        $invoices_table = $this->db->dbprefix('invoices');

        $sql = "SELECT * FROM $invoices_table
                        WHERE $invoices_table.deleted=0 AND $invoices_table.recurring=1
                        AND $invoices_table.next_recurring_date IS NOT NULL AND $invoices_table.next_recurring_date<='$date'
                        AND ($invoices_table.no_of_cycles < 1 OR ($invoices_table.no_of_cycles_completed < $invoices_table.no_of_cycles ))";

        return $this->db->query($sql);
    }

    // invoice table invoice no check 
    function is_invoice_no_exists($invoice_no, $id = 0) {
        $result = $this->get_all_where(array("invoice_no" => $invoice_no, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    } 
   function get_last_invoice_id_exists() {
        $invoices_table = $this->db->dbprefix('invoices');

        $sql = "SELECT $invoices_table.*
        FROM $invoices_table
        ORDER BY id DESC LIMIT 1";

        return $this->db->query($sql)->row();
    }
    // end invoice no check 

}
