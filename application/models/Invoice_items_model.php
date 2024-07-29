<?php

class Invoice_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'invoice_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $invoices_table = $this->db->dbprefix('invoices');
        $taxes_table = $this->db->dbprefix('taxes');
        $clients_table = $this->db->dbprefix('clients');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_items_table.id=$id";
        }
        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
        }

        $sql = "SELECT $invoice_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol
        FROM $invoice_items_table
        LEFT JOIN $taxes_table ON $taxes_table.id= $invoice_items_table.tax
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
        WHERE $invoice_items_table.deleted=0 $where
        ORDER BY $invoice_items_table.sort ASC";
        return $this->db->query($sql);
    }
function get_item_suggestions($keyword = "",$d_item="",$category="") {
        $items_table = $this->db->dbprefix('items');
       
         $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%' and  $items_table.title  NOT IN  $d_item AND $items_table.category ='$category'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }
// invoice service type 
     function get_service_item_suggestions($keyword = "",$d_item="",$category="") {
        $items_table = $this->db->dbprefix('service_id_generation');
       
         $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%' and  $items_table.title  NOT IN  $d_item AND $items_table.category ='$category'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }



     function get_item_suggestionss($s = "") 
{
 $invoices_table = $this->db->dbprefix('invoices');        
 $clients_table = $this->db->dbprefix('clients');

        $sql = "SELECT $clients_table.currency,$clients_table.country
        FROM $clients_table
        LEFT JOIN $invoices_table ON $invoices_table.client_id=$clients_table.id
        WHERE $clients_table.deleted=0  AND $invoices_table.id='$s'
        LIMIT 1 
        ";
        return $this->db->query($sql)->row();
     }




     function get_item_suggestionsss($client_type = "") 
{
 $invoices_table = $this->db->dbprefix('invoices');        
 $clients_table = $this->db->dbprefix('clients');
 $buyer_types_table = $this->db->dbprefix('buyer_types');

        $sql = "SELECT $buyer_types_table.profit_margin,$buyer_types_table.buyer_type
        FROM $clients_table
        LEFT JOIN $invoices_table ON $invoices_table.client_id=$clients_table.id
        LEFT JOIN $buyer_types_table ON $buyer_types_table.id =
        $clients_table.buyer_type
        WHERE $clients_table.deleted=0  AND $invoices_table.id='$client_type'
        LIMIT 1 
        ";
        return $this->db->query($sql)->row();
     }

     
    function get_item_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('items');
        

        $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%'
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
     }

    function get_item_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('items');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

    // service item info
    function get_service_item_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('service_id_generation');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }


    function is_invoice_product_exists($title,$invoice_id, $id = 0) {
        $result = $this->get_all_where(array("title" => $title ,"invoice_id" => $invoice_id, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }

}
