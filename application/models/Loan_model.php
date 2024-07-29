<?php 
class Loan_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan';
        parent::__construct($this->table);
    }

   
    function get_details($options = array()) {
        $loan_table = $this->db->dbprefix('loan');
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');
        $vendors_table = $this->db->dbprefix('vendors');
       // $taxes_table = $this->db->dbprefix('taxes');
$loan_payment_list_table =$this->db->dbprefix('loan_payments_list');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $loan_table.id=$id";
        }
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($loan_table.loan_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $category_id = get_array_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $loan_table.category_id=$category_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $loan_table.project_id=$project_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $loan_table.user_id=$user_id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $loan_table.company=$client_id";
        }
        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $loan_table.vendor_company=$vendor_id";
        }

        $now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


if ($status === "draft") {
            $where .= " AND  $loan_table.status='draft' AND IFNULL(payments_table.paid_amount,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $loan_table.status !='draft' AND IFNULL(payments_table.paid_amount,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.paid_amount,0)>0 AND IFNULL(payments_table.paid_amount,0)< $loan_table.total";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.paid_amount,0),2)>=$loan_table.total";
        } else if ($status === "overdue") {
            $where .= " AND $loan_table.status !='draft' AND $loan_table.due_date<'$now' AND TRUNCATE(IFNULL(payments_table.paid_amount,0),2)<$loan_table.total";
        }






        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("loan", $custom_fields, $loan_table);
        $select_custom_fields = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fields = get_array_value($custom_field_query_info, "join_string");


        $sql = "SELECT $loan_table.*, $expense_categories_table.title as category_title, 
                 CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name,
                 $projects_table.title AS project_title,$clients_table.company_name AS client_company,$vendors_table.company_name AS vendor_company,IFNULL(payments_table.paid_amount,0) AS paid_amount
                 
                 $select_custom_fields
        FROM $loan_table
        LEFT JOIN $expense_categories_table ON $expense_categories_table.id= $loan_table.category_id
        LEFT JOIN $clients_table ON $clients_table.id= $loan_table.company
        LEFT JOIN $vendors_table ON $vendors_table.id= $loan_table.vendor_company
        LEFT JOIN $projects_table ON $projects_table.id= $loan_table.project_id
        LEFT JOIN (SELECT loan_id, SUM(title) AS paid_amount FROM 
        $loan_payment_list_table WHERE deleted=0 GROUP BY loan_id) AS payments_table ON payments_table.loan_id = $loan_table.id  
        LEFT JOIN $users_table ON $users_table.id= $loan_table.user_id
        
            $join_custom_fields
        WHERE $loan_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    

    
    function get_yearly_loan_chart($year) {
        $loan_table = $this->db->dbprefix('loan');
       // $taxes_table = $this->db->dbprefix('taxes');

        $loan = "SELECT SUM($loan_table.total ) AS total, MONTH($loan_table.loan_date) AS month
        FROM $loan_table
        
        WHERE $loan_table.deleted=0 AND YEAR($loan_table.loan_date)= $year
        GROUP BY MONTH($loan_table.loan_date)";

        return $this->db->query($loan)->result();
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
    function get_voucher_id($item_name = "",$loan_voucher_no="") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        $voucher_table = $this->db->dbprefix('voucher');
        $voucher_types_table = $this->db->dbprefix('voucher_types');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        LEFT JOIN $voucher_table ON $voucher_table.id= $items_table.estimate_id
        LEFT JOIN $voucher_types_table ON 
        $voucher_types_table.id=$voucher_table.voucher_type_id
        WHERE $items_table.deleted=0  AND $items_table.user_id = '$item_name' AND  
        $items_table.estimate_id  NOT IN  $loan_voucher_no 
        AND ($voucher_types_table.title like '%loan%') AND $voucher_table.status = 'approved_by_accounts'

        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
    function get_client_voucher_id($item_name = "",$loan_voucher_no="") {

        $items_table = $this->db->dbprefix('voucher_expenses');
        $voucher_table = $this->db->dbprefix('voucher');
        $voucher_types_table = $this->db->dbprefix('voucher_types');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        LEFT JOIN $voucher_table ON $voucher_table.id= $items_table.estimate_id
        LEFT JOIN $voucher_types_table ON 
        $voucher_types_table.id=$voucher_table.voucher_type_id
        WHERE $items_table.deleted=0  AND $items_table.i_represent = '$item_name' AND  
        $items_table.estimate_id  NOT IN  $loan_voucher_no 
        AND ($voucher_types_table.title like '%loan%') AND $voucher_table.status = 'approved_by_accounts'

        ORDER BY id
        ";
        
       return $this->db->query($sql)->result();


    }
    function get_voucher_id_for_others($item_name = "",$loan_voucher_no="") {

        $items_table = $this->db->dbprefix('voucher_expenses');
         $voucher_table = $this->db->dbprefix('voucher');
         $voucher_types_table = $this->db->dbprefix('voucher_types');

        $sql = "SELECT $items_table.*
        FROM $items_table
        LEFT JOIN $voucher_table ON $voucher_table.id= $items_table.estimate_id
        LEFT JOIN $voucher_types_table ON 
        $voucher_types_table.id=$voucher_table.voucher_type_id
        WHERE $items_table.deleted=0  AND $items_table.phone = '$item_name' AND ($voucher_types_table.title like '%loan%') AND  
        $items_table.estimate_id  NOT IN  $loan_voucher_no AND $voucher_table.status = 'approved_by_accounts'
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
