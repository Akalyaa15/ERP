<?php

class Estimates_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'estimates';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimates_table = $this->db->dbprefix('estimates');
        $clients_table = $this->db->dbprefix('clients');
        $estimate_payments_table = $this->db->dbprefix('estimate_payments');
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $projects_table = $this->db->dbprefix('projects');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimates_table.id=$id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $estimates_table.client_id=$client_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($estimates_table.estimate_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $freight_amount = "(IFNULL($estimates_table.freight_amount,0))";
        $estimate_value_calculation = "round(
            IFNULL(items_table.estimate_value,0)+$freight_amount)
           ";

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $estimates_table.status='$status'";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $estimates_table.status!='draft' ";
        }

        $now = get_my_local_time("Y-m-d");
        $payment_status = get_array_value($options, "payment_status");

        if ($payment_status === "draft") {
            $where .= " AND $estimates_table.payment_status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($payment_status === "not_paid") {
            $where .= " AND $estimates_table.payment_status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($payment_status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$estimate_value_calculation";
        } else if ($payment_status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$estimate_value_calculation";
        } else if ($payment_status === "overdue") {
            $where .= " AND $estimates_table.payment_status !='draft' AND $estimates_table.valid_until<'$now' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)<$estimate_value_calculation";
        }

        // prepare custom field binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("estimates", $custom_fields, $estimates_table);
        $select_custom_fields = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fields = get_array_value($custom_field_query_info, "join_string");

        $sql = "SELECT $estimates_table.*, $clients_table.currency, $clients_table.currency_symbol, $clients_table.company_name, $clients_table.country, $clients_table.buyer_type, $projects_table.title as project_title,
           $estimate_value_calculation AS estimate_value , IFNULL(payments_table.payment_received,0) AS payment_received
           $select_custom_fields
        FROM $estimates_table
        LEFT JOIN $clients_table ON $clients_table.id= $estimates_table.client_id
        LEFT JOIN $projects_table ON $projects_table.id= $estimates_table.project_id
        LEFT JOIN (SELECT estimate_id, SUM(amount) AS payment_received FROM $estimate_payments_table WHERE deleted=0 GROUP BY estimate_id) AS payments_table ON payments_table.estimate_id = $estimates_table.id 
        LEFT JOIN (SELECT estimate_id, SUM(net_total) AS estimate_value FROM $estimate_items_table WHERE deleted=0 GROUP BY estimate_id) AS items_table ON items_table.estimate_id = $estimates_table.id 
        $join_custom_fields
        WHERE $estimates_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_estimate_total_summary($estimate_id = 0) {
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $estimate_payments_table = $this->db->dbprefix('estimate_payments');
        $estimates_table = $this->db->dbprefix('estimates');
        $clients_table = $this->db->dbprefix('clients');

        // Query to get installation total
        $installation_total_sql = "SELECT SUM($estimate_items_table.installation_total) AS installation_total
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $installation_total = $this->db->query($installation_total_sql)->row();

        // Query to get estimate quantity subtotal
        $item_quantity_total_sql = "SELECT SUM($estimate_items_table.quantity_total) AS estimate_quantity_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $item_quantity_total = $this->db->query($item_quantity_total_sql)->row();

        // Query to get estimate subtotal
        $item_sql = "SELECT SUM($estimate_items_table.total) AS estimate_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        // Query to get estimate tax subtotal
        $itemss_sql = "SELECT SUM($estimate_items_table.tax_amount) AS estimate_tax_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $itemss = $this->db->query($itemss_sql)->row();

        // Query to get estimate net subtotal
        $net_total_sql = "SELECT SUM($estimate_items_table.net_total) AS estimate_net_subtotal
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $net_total = $this->db->query($net_total_sql)->row();

        // Query to get estimate details
        $estimate_sql = "SELECT $estimates_table.*
        FROM $estimates_table
        WHERE $estimates_table.deleted=0 AND $estimates_table.id=$estimate_id";
        $estimate = $this->db->query($estimate_sql)->row();

        // Query to get client details
        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$estimate->client_id";
        $client = $this->db->query($client_sql)->row();

        // Query to get installation tax
        $installation_tax_sql = "SELECT SUM($estimate_items_table.installation_tax_amount) AS estimate_installation_tax
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $installation_tax = $this->db->query($installation_tax_sql)->row();

        // Query to get total payment received
        $payment_sql = "SELECT SUM($estimate_payments_table.amount) AS total_paid
        FROM $estimate_payments_table
        WHERE $estimate_payments_table.deleted=0 AND $estimate_payments_table.estimate_id=$estimate_id";
        $payment = $this->db->query($payment_sql)->row();

        $result = new stdClass();
        $result->estimate_quantity_subtotal = $item_quantity_total->estimate_quantity_subtotal;
        $result->installation_total = $installation_total->installation_total;
        $result->estimate_subtotal = $item->estimate_subtotal;
        $result->estimate_tax_subtotal = $itemss->estimate_tax_subtotal;
        $result->estimate_net_subtotal = $net_total->estimate_net_subtotal;
        $result->freight_amount = $estimate->freight_amount;
        $result->freight_rate_amount = $estimate->amount;
        $result->freight_tax_amount = $estimate->freight_tax_amount;
        $result->estimate_net_subtotal_default = $result->estimate_net_subtotal + $result->freight_amount;
        $result->igst_total = $result->estimate_tax_subtotal;
        $result->installation_tax = $installation_tax->estimate_installation_tax;

        // Cast gst to a float before division
        $freight_tax_rate = ((float)$estimate->gst / 100) + 1;
        $freight_tax_base = (float)$estimate->freight_amount / $freight_tax_rate;
        $freight_tax_amount = $freight_tax_base * ((float)$estimate->gst / 100);
        $result->freight_tax = $freight_tax_base + $freight_tax_amount;

        $result->estimate_net_total = $result->estimate_net_subtotal + $result->freight_amount;
        $result->estimate_total = round($result->estimate_net_total);

        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");

        $result->total_paid = $payment->total_paid;
        $result->balance_due = number_format(round($result->estimate_total), 2, ".", "") - number_format($payment->total_paid, 2, ".", "");
        return $result;
    }

    // invoice table invoice no check 
    function is_estimate_no_exists($estimate_no, $id = 0) {
        $result = $this->get_all_where(array("estimate_no" => $estimate_no, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

    function get_last_estimate_id_exists() {
        $estimates_table = $this->db->dbprefix('estimates');

        $sql = "SELECT $estimates_table.*
        FROM $estimates_table
        ORDER BY id DESC LIMIT 1";

        return $this->db->query($sql)->row();
    }

    // change the invoice status from draft to not_paid
    function set_estimate_payment_status_to_not_paid($estimate_id = 0) {
        $status_data = array("payment_status" => "not_paid");
        return $this->save($status_data, $estimate_id);
    }
}

