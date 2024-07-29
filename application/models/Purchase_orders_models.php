<?php

class Purchase_orders_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_orders';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $vendors_table = $this->db->dbprefix('vendors');
       // $taxes_table = $this->db->dbprefix('taxes');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $projects_table = $this->db->dbprefix('projects');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_orders_table.id=$id";
        }
        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $purchase_orders_table.vendor_id=$vendor_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($purchase_orders_table.purchase_order_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $purchase_order_value_calculation_query = $this->_get_purchase_order_value_calculation_query($purchase_orders_table);
        

        $purchase_order_value_calculation = "TRUNCATE($purchase_order_value_calculation_query,2)";

       $now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        
        $status = get_array_value($options, "status");
        

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $purchase_orders_table.status!='draft' ";
        }

        if ($status === "draft") {
            $where .= " AND $purchase_orders_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $purchase_orders_table.status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$purchase_order_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$purchase_order_value_calculation";
        } else if ($status === "overdue") {
            $where .= " AND $purchase_orders_table.status !='draft' AND $purchase_orders_table.valid_until<'$now' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)<$purchase_order_value_calculation";
        }



        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("purchase_orders", $custom_fields, $purchase_orders_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");



        $sql = "SELECT $purchase_orders_table.*, $vendors_table.currency, $vendors_table.currency_symbol, $vendors_table.company_name,$vendors_table.country, $projects_table.title as project_title,
           $purchase_order_value_calculation AS purchase_order_value,IFNULL(payments_table.payment_received,0) AS payment_received  
           $select_custom_fieds
        FROM $purchase_orders_table
        LEFT JOIN $vendors_table ON $vendors_table.id= $purchase_orders_table.vendor_id
        LEFT JOIN $projects_table ON $projects_table.id= $purchase_orders_table.project_id
         LEFT JOIN (SELECT purchase_order_id, SUM(amount) AS payment_received FROM $purchase_order_payments_table WHERE deleted=0 GROUP BY purchase_order_id) AS payments_table ON payments_table.purchase_order_id = $purchase_orders_table.id 
        LEFT JOIN (SELECT purchase_order_id, SUM(net_total) AS purchase_order_value FROM $purchase_order_items_table WHERE deleted=0 GROUP BY purchase_order_id) AS items_table ON items_table.purchase_order_id = $purchase_orders_table.id 
        $join_custom_fieds
        WHERE $purchase_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }

   /* function get_estimate_total_summary($estimate_id = 0) {
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $estimates_table = $this->db->dbprefix('estimates');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($estimate_items_table.total) AS estimate_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $item = $this->db->query($item_sql)->row();


        $estimate_sql = "SELECT $estimates_table.*, tax_table.percentage AS tax_percentage, tax_table.title AS tax_name,
            tax_table2.percentage AS tax_percentage2, tax_table2.title AS tax_name2
        FROM $estimates_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $estimates_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $estimates_table.tax_id2
        WHERE $estimates_table.deleted=0 AND $estimates_table.id=$estimate_id";
        $estimate = $this->db->query($estimate_sql)->row();

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$estimate->client_id";
        $client = $this->db->query($client_sql)->row();


        $result = new stdClass();
        $result->estimate_subtotal = $item->estimate_subtotal;
        $result->tax_percentage = $estimate->tax_percentage;
        $result->tax_percentage2 = $estimate->tax_percentage2;
        $result->tax_name = $estimate->tax_name;
        $result->tax_name2 = $estimate->tax_name2;
        $result->tax = 0;
        $result->tax2 = 0;
        if ($estimate->tax_percentage) {
            $result->tax = $result->estimate_subtotal * ($estimate->tax_percentage / 100);
        }
        if ($estimate->tax_percentage2) {
            $result->tax2 = $result->estimate_subtotal * ($estimate->tax_percentage2 / 100);
        }
        $result->estimate_total = $item->estimate_subtotal + $result->tax + $result->tax2;

        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");
        return $result;
    }


*/
    function get_purchase_order_total_summary($purchase_order_id = 0) {
        $estimate_items_table = $this->db->dbprefix('purchase_order_items');
        $estimates_table = $this->db->dbprefix('purchase_orders');
        $clients_table = $this->db->dbprefix('vendors');
         $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
       // $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($estimate_items_table.total) AS estimate_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.purchase_order_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.purchase_order_id=$purchase_order_id AND $estimates_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

      $itemss_sql = "SELECT SUM($estimate_items_table.tax_amount) AS estimate_tax_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.purchase_order_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.purchase_order_id=$purchase_order_id AND $estimates_table.deleted=0";
         $itemss = $this->db->query($itemss_sql)->row();

        $net_total_sql = "SELECT SUM($estimate_items_table.net_total) AS estimate_net_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.purchase_order_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.purchase_order_id=$purchase_order_id AND $estimates_table.deleted=0";
        $net_total = $this->db->query($net_total_sql)->row();

        $estimate_sql = "SELECT $estimates_table.*
        FROM $estimates_table
       WHERE $estimates_table.deleted=0 AND $estimates_table.id=$purchase_order_id";
        $estimate = $this->db->query($estimate_sql)->row();

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$estimate->vendor_id";
        $client = $this->db->query($client_sql)->row();

        $payment_sql = "SELECT SUM($purchase_order_payments_table.amount) AS total_paid
        FROM $purchase_order_payments_table
        WHERE $purchase_order_payments_table.deleted=0 AND $purchase_order_payments_table.purchase_order_id=$purchase_order_id";
        $payment = $this->db->query($payment_sql)->row();


        $result = new stdClass();
         $result->estimate_subtotal = $item->estimate_subtotal;
        $result->estimate_tax_subtotal = $itemss->estimate_tax_subtotal;
        $result->estimate_net_subtotal = $net_total->estimate_net_subtotal;
       $result->freight_amount = $estimate->freight_amount;
      $result->estimate_net_subtotal_default = $net_total->estimate_net_subtotal+ $result->freight_amount;
      
       $result->igst_total = $result->estimate_tax_subtotal;
     $result->freight_tax1 =($estimate->gst/100)+1;
      $result->freight_tax2 = $estimate->freight_amount/$result->freight_tax1;
       $result->freight_tax3 = $result->freight_tax2*$estimate->gst/100;
       $result->freight_tax =  $result->freight_tax2+$result->freight_tax3;
      


     $result->estimate_net_total =$result->estimate_net_subtotal+$result->freight_amount;
     $result->total_paid = $payment->total_paid;

      //$result->estimate_total = round($result->estimate_net_total);

        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");

        $result->balance_due = number_format(round($result->estimate_net_total), 2, ".", "") - number_format($payment->total_paid, 2, ".", "") ;
        return $result;
    }

    private function _get_purchase_order_value_calculation_query($purchase_orders_table){
          
            $freight_amount = "(IFNULL($purchase_orders_table.freight_amount,0))";

            $purchase_order_value_calculation_query = "round(
                IFNULL(items_table.purchase_order_value,0)+$freight_amount
               )";
            
            return $purchase_order_value_calculation_query;
    }

    function set_purchase_order_status_to_not_paid($purchase_order_id = 0) {
        $status_data = array("status" => "not_paid");
        return $this->save($status_data, $purchase_order_id);
    }
}
