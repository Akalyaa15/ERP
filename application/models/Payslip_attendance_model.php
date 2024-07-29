<?php

class Payslip_attendance_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslip_attendance';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payslip_attendance_table = $this->db->dbprefix('payslip_attendance');
        $payslip_table = $this->db->dbprefix('payslip');
        $users_table = $this->db->dbprefix('users');
        $leave_applications_table = $this->db->dbprefix('leave_applications');
        $leave_types_table = $this->db->dbprefix('leave_types');
        $where = "";
        $id = get_array_value($options, "id");

        $pd = get_array_value($options, "payslip_id");
                $item_sqls = "SELECT $payslip_table.payslip_date
        FROM $payslip_table
           
        WHERE $payslip_table.id=$pd ";
        $items = $this->db->query($item_sqls)->row();
        if ($id) {
            $where .= " AND $payslip_attendance_table.id=$id";
        }
        $date=$items->payslip_date;
//$currentMonth =$date."last month";
        $currentMonth =$date."first day of previous month";
$last=Date('m', strtotime($currentMonth ));
        $start_date = "$leave_applications_table.start_date";
        $end_date = "$leave_applications_table.end_date";
        //$month = date('m');
        $year = Date('Y', strtotime($currentMonth ));
        //$last_month = $month-1%12;
      if ($start_date && $end_date) {
           $where .= " AND $leave_applications_table.status='approved'";
           $where .= " AND $leave_applications_table.deleted=0";
           //$where .= " AND ($leave_applications_table.start_date  BETWEEN '$year-$last-01' AND '$year-$last-31')  "; 
           $where .= " AND ($leave_applications_table.start_date <='$year-$last-31' AND $leave_applications_table.end_date >= '$year-$last-01')  ";
        }
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $payslip_attendance_table.user_id=$user_id";
        }        
        $payslip_id = get_array_value($options, "payslip_id");
        if ($payslip_id) {
            $where .= " AND $payslip_attendance_table.payslip_id=$payslip_id";
        }

        $sql = "SELECT $payslip_attendance_table.*,$leave_applications_table.total_days
                  AS attendance_user_name ,$leave_applications_table.start_date AS leave_start_user_name ,$leave_applications_table.end_date AS leave_end_user_name ,$leave_types_table.title AS leave_type_id_name 
        FROM $payslip_attendance_table
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_attendance_table.payslip_id 
        LEFT JOIN $leave_applications_table ON $leave_applications_table.applicant_id=$payslip_attendance_table.user_id
        LEFT JOIN  $leave_types_table  ON  $leave_types_table.id=$leave_applications_table.leave_type_id
        WHERE $payslip_attendance_table.deleted=0 $where";
        return $this->db->query($sql);  
    }

//insert the payslip attendance  table auto payslip generate 
function insert($datas)
    {
        $this->db->insert_batch('payslip_attendance', $datas);
    }


  }