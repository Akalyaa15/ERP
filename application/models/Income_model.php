<?php 
class Income_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'income';
        parent::__construct($this->table);
    }

   
    function get_details($options = array()) {
        $income_table = $this->db->dbprefix('income');
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');
        $vendors_table = $this->db->dbprefix('vendors');
       // $taxes_table = $this->db->dbprefix('taxes');


        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $income_table.id=$id";
        }
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($income_table.income_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $category_id = get_array_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $income_table.category_id=$category_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $income_table.project_id=$project_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $income_table.user_id=$user_id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $income_table.company=$client_id";
        }
        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $income_table.vendor_company=$vendor_id";
        }

        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("income", $custom_fields, $income_table);
        $select_custom_fields = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fields = get_array_value($custom_field_query_info, "join_string");


        $sql = "SELECT $income_table.*, $expense_categories_table.title as category_title, 
                 CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name,
                 $projects_table.title AS project_title,$clients_table.company_name AS client_company,$vendors_table.company_name AS vendor_company
                 
                 $select_custom_fields
        FROM $income_table
        LEFT JOIN $expense_categories_table ON $expense_categories_table.id= $income_table.category_id
        LEFT JOIN $clients_table ON $clients_table.id= $income_table.company
        LEFT JOIN $vendors_table ON $vendors_table.id= $income_table.vendor_company
        LEFT JOIN $projects_table ON $projects_table.id= $income_table.project_id
        LEFT JOIN $users_table ON $users_table.id= $income_table.user_id
        
            $join_custom_fields
        WHERE $income_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_income_expenses_info() {
        $expenses_table = $this->db->dbprefix('income');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $payments_table = $this->db->dbprefix('purchase_order_payments');
        $work_order_payments_table = $this->db->dbprefix('work_order_payments');
       //$taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();

        $sql1 = "SELECT SUM($invoice_payments_table.amount) as total_income
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0";
        $income = $this->db->query($sql1)->row();



        /*$sql2 = "SELECT SUM($expenses_table.amount + IFNULL(tax_table.percentage,0)/100*IFNULL($expenses_table.amount,0) + IFNULL(tax_table2.percentage,0)/100*IFNULL($expenses_table.amount,0)) AS total_expenses
        FROM $expenses_table
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
        WHERE $expenses_table.deleted=0";
        $expenses = $this->db->query($sql2)->row(); */
        $sql2 = "SELECT SUM($expenses_table.total) AS total_expenses
        FROM $expenses_table
        
        WHERE $expenses_table.deleted=0";
        $expenses = $this->db->query($sql2)->row();
        
        $sql3 = "SELECT SUM($payments_table.amount) AS total_purchase_payments
        FROM $payments_table
        
        WHERE $payments_table.deleted=0";
        $purchase_order_payments = $this->db->query($sql3)->row();

        $sql4 = "SELECT SUM($work_order_payments_table.amount) AS total_work_order_payments
        FROM $work_order_payments_table
        
        WHERE $work_order_payments_table.deleted=0";
        $work_order_payments = $this->db->query($sql4)->row();

        $info->income = $income->total_income;
        $info->expnesess = $expenses->total_expenses;
        $info->purchase_order_payments = $purchase_order_payments->total_purchase_payments;
        $info->work_order_payments = $work_order_payments->total_work_order_payments;
        $info->expneses = $info->expnesess+$info->purchase_order_payments+$info->work_order_payments;
        return $info;
    }

    
    function get_yearly_income_chart($year) {
        $income_table = $this->db->dbprefix('income');
       // $taxes_table = $this->db->dbprefix('taxes');

        $income = "SELECT SUM($income_table.total ) AS total, MONTH($income_table.income_date) AS month
        FROM $income_table
        
        WHERE $income_table.deleted=0 AND YEAR($income_table.income_date)= $year
        GROUP BY MONTH($income_table.income_date)";

        return $this->db->query($income)->result();
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
    function get_voucher_id($item_name = "",$income_voucher_no="") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        $voucher_table = $this->db->dbprefix('voucher');
        $voucher_types_table = $this->db->dbprefix('voucher_types');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        LEFT JOIN $voucher_table ON $voucher_table.id= $items_table.estimate_id
        LEFT JOIN $voucher_types_table ON 
        $voucher_types_table.id=$voucher_table.voucher_type_id
        WHERE $items_table.deleted=0  AND $items_table.user_id = '$item_name' AND  
        $items_table.estimate_id  NOT IN  $income_voucher_no 
        AND ($voucher_types_table.title like '%income%'||$voucher_types_table.title like '%advance%') AND $voucher_table.status = 'approved_by_accounts'

        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
    function get_client_voucher_id($item_name = "",$income_voucher_no="") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        $voucher_table = $this->db->dbprefix('voucher');
        $voucher_types_table = $this->db->dbprefix('voucher_types');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        LEFT JOIN $voucher_table ON $voucher_table.id= $items_table.estimate_id
        LEFT JOIN $voucher_types_table ON 
        $voucher_types_table.id=$voucher_table.voucher_type_id
        WHERE $items_table.deleted=0  AND $items_table.i_represent = '$item_name' AND  
        $items_table.estimate_id  NOT IN  $income_voucher_no 
        AND ($voucher_types_table.title like '%income%'||$voucher_types_table.title like '%advance%') AND $voucher_table.status = 'approved_by_accounts'

        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
    function get_voucher_id_for_others($item_name = "",$income_voucher_no="") {

        $items_table = $this->db->dbprefix('voucher_expenses');
         $voucher_table = $this->db->dbprefix('voucher');
         $voucher_types_table = $this->db->dbprefix('voucher_types');

        $sql = "SELECT $items_table.*
        FROM $items_table
        LEFT JOIN $voucher_table ON $voucher_table.id= $items_table.estimate_id
        LEFT JOIN $voucher_types_table ON 
        $voucher_types_table.id=$voucher_table.voucher_type_id
        WHERE $items_table.deleted=0  AND $items_table.phone = '$item_name' AND ($voucher_types_table.title like '%income%'||$voucher_types_table.title like '%advance%') AND  
        $items_table.estimate_id  NOT IN  $income_voucher_no AND $voucher_table.status = 'approved_by_accounts'
        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }



    function get_client_contacts($item_name = "") {

        $items_table = $this->db->dbprefix('users');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.client_id = '$item_name'
        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
    function get_vendor_contacts($item_name = "") {

        $items_table = $this->db->dbprefix('users');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.vendor_id = '$item_name'
        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }




}
