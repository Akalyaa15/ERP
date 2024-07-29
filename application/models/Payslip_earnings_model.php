<?php

class Payslip_earnings_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslip_earnings';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payslip_earnings_table = $this->db->dbprefix('payslip_earnings');
        $payslip_table = $this->db->dbprefix('payslip');
        $users_table = $this->db->dbprefix('users');
        $team_member_job_info_table = $this->db->dbprefix('team_member_job_info');
        $earnings_table = $this->db->dbprefix('earnings');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $payslip_earnings_table.id=$id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $payslip_earnings_table.user_id=$user_id";
        }


        $payslip_id = get_array_value($options, "payslip_id");
        if ($payslip_id) {
            $where .= " AND $payslip_earnings_table.payslip_id=$payslip_id";
        }

        $sql = "SELECT $payslip_earnings_table.*,CONCAT($users_table.first_name, ' ', $users_table.last_name) AS user_name,$team_member_job_info_table.salary AS linked_user_name ,$payslip_table.salary AS payslip_salary, $users_table.id AS payslip_user_id
        FROM $payslip_earnings_table
        LEFT JOIN $users_table ON $users_table.id= $payslip_earnings_table.user_id
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_earnings_table.payslip_id
        
        LEFT JOIN $team_member_job_info_table ON $team_member_job_info_table.user_id=$payslip_earnings_table.user_id
        WHERE $payslip_earnings_table.deleted=0 $where";
        return $this->db->query($sql);  
    }


//insert the payslip earnings  table auto payslip generate 
    function insert($datas)
    {
    $this->db->insert_batch('payslip_earnings', $datas);
    } 


  

}
