<?php

class Payslip_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslip';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payslip_table = $this->db->dbprefix('payslip');
        $users_table = $this->db->dbprefix('users');
        $payslip_earnings_table = $this->db->dbprefix('payslip_earnings');
        $team_member_job_info_table = $this->db->dbprefix('team_member_job_info');
         $payslip_deductions_table = $this->db->dbprefix('payslip_deductions');
         $payslip_attendance_table = $this->db->dbprefix('payslip_attendance');
         $leave_applications_table = $this->db->dbprefix('leave_applications');
        $deductions_table = $this->db->dbprefix('deductions');
        $payslip_earningsadd_table = $this->db->dbprefix('payslip_earningsadd');

        $where = "";
        
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $payslip_table.id=$id";
        }

        

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $payslip_table.user_id=$user_id";
        }

       $payslip_earnings_calculation = "(
            IFNULL(items_table.earnings_value,0)+IFNULL(item_table.earningsadd_value,0))"; 

       
     /*  $payslip_deductions_calculation = "(

        IFNULL(item_table.deductions_value,0)
            )";


        $payslip_attendance_calculation = "(
        IFNULL(items_table.earnings_value,0)-
        (IFNULL(items_table.earnings_value,0)/DAYOFMONTH(LAST_DAY(now()-INTERVAL 1 MONTH)))*IFNULL(attendance_table.attendance_value,0)-IFNULL(item_table.deductions_value,0)
            )";

            */
     $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($payslip_table.payslip_date BETWEEN '$start_date' AND '$end_date') ";
        }  

       $month = date('m');
       $year = date('Y');
       $last_month = $month-1%12;

        $sql = "SELECT $payslip_table.*, 
                 CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name ,$users_table.image as user_id_avatar,$payslip_earnings_calculation AS earnings_value ,$payslip_table.rate AS dele,$payslip_table.total AS netsalary ,$payslip_table.over_time_amount AS over_time_amount
        FROM $payslip_table
        LEFT JOIN $users_table ON $users_table.id= $payslip_table.user_id
       
        LEFT JOIN (SELECT payslip_id, SUM(rate) AS earningsadd_value FROM $payslip_earningsadd_table WHERE deleted=0 GROUP BY payslip_id) AS item_table ON item_table.payslip_id = $payslip_table.id 
        LEFT JOIN (SELECT user_id ,salary AS earnings_value FROM $team_member_job_info_table WHERE deleted=0 GROUP BY user_id) AS items_table ON items_table.user_id = $payslip_table.user_id 
       /* LEFT JOIN (SELECT applicant_id , sum(total_days) AS attendance_value FROM $leave_applications_table WHERE deleted=0 AND ($leave_applications_table.status='approved') AND ($leave_applications_table.start_date  BETWEEN '$year-$last_month-01' AND '$year-$last_month-31') GROUP BY applicant_id) AS attendance_table ON attendance_table.applicant_id = $payslip_table.user_id */
        WHERE $payslip_table.deleted=0 $where";
        return $this->db->query($sql);
    }


      

    function get_deductions_total_summary($payslip_id = 0) {
        $payslip_deductions_table = $this->db->dbprefix('payslip_deductions');
        $payslip_earnings_table = $this->db->dbprefix('payslip_earnings');
        $payslip_attendance_table = $this->db->dbprefix('payslip_attendance');
        $payslip_earningsadd_table = $this->db->dbprefix('payslip_earningsadd');
        $payslip_table = $this->db->dbprefix('payslip');
        $users_table = $this->db->dbprefix('users');
        $earnings_table = $this->db->dbprefix('earnings');

        $team_member_job_info_table = $this->db->dbprefix('team_member_job_info');
        $leave_applications_table = $this->db->dbprefix('leave_applications');
        $item_sql = "SELECT SUM($payslip_deductions_table.rate) AS deductions_subtotal
        FROM $payslip_deductions_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_deductions_table.payslip_id    
        WHERE $payslip_deductions_table.deleted=0 AND $payslip_deductions_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        

       // $pd = get_array_value($options, "payslip_id");
                $leave_date_sqls = "SELECT $payslip_table.payslip_date
        FROM $payslip_table
           
        WHERE $payslip_table.id=$payslip_id ";
        $leave_date = $this->db->query($leave_date_sqls)->row();
        if ($id) {
            $where .= " AND $payslip_attendance_table.id=$id";
        }
        $date=$leave_date->payslip_date;
//$currentMonth =$date."last month";
        $currentMonth =$date."first day of previous month";
$last=Date('m', strtotime($currentMonth ));
        $start_date = "$leave_applications_table.start_date";
        $end_date = "$leave_applications_table.end_date";
        //$month = date('m');
        $year = Date('Y', strtotime($currentMonth ));
        $days = Date('t', strtotime($currentMonth ));
        //$last_month = $month-1%12;
      if ($start_date && $end_date) {
           $where .= " AND $leave_applications_table.status='approved'";
           $where .= " AND $leave_applications_table.deleted=0";
           $where .= " AND ($leave_applications_table.start_date  BETWEEN '$year-$last-01' AND '$year-$last-31')  "; 
        }
      $attendance_sql = "SELECT SUM($leave_applications_table.total_days)
                  AS attendance_subtotal
        FROM $payslip_attendance_table
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_attendance_table.payslip_id
        LEFT JOIN $leave_applications_table ON $leave_applications_table.applicant_id=$payslip_attendance_table.user_id
        WHERE $payslip_attendance_table.deleted=0 AND $payslip_attendance_table.payslip_id=$payslip_id AND $payslip_table.deleted=0 $where";
        $attendance = $this->db->query($attendance_sql)->row();
        
     $items_sql = "SELECT SUM($payslip_earningsadd_table.rate) AS earningsadd_subtotal
        FROM $payslip_earningsadd_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earningsadd_table.payslip_id    
        WHERE $payslip_earningsadd_table.deleted=0 AND $payslip_earningsadd_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $items = $this->db->query($items_sql)->row();
 
 
     $earnings_sql = "SELECT $payslip_earnings_table.*, 
                 $team_member_job_info_table.salary AS earnings_subtotal
        FROM $payslip_earnings_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earnings_table.payslip_id
        LEFT JOIN $team_member_job_info_table ON $team_member_job_info_table.user_id=$payslip_earnings_table.user_id 
        WHERE $payslip_earnings_table.deleted=0 AND $payslip_earnings_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $earnings = $this->db->query($earnings_sql)->row();

        $result = new stdClass();
        $result->deductions_subtotal = $item->deductions_subtotal;

        $result->earnings_subtotal = $earnings->earnings_subtotal;
        
  
        $result->earnings_total = $result->earnings_subtotal;

        $result->earningscurrentdate_total =  $result->earnings_total/$days;
        
          $result->attendance_subtotal = $attendance->attendance_subtotal-1;

          if($result->attendance_subtotal>0){

            $result->attendance_subtotal1 = $result->attendance_subtotal; 

          }
        
        $result->attendanceleaveamoumt_total = $result->attendance_subtotal1*$result->earningscurrentdate_total;
       // $result->earningsattendance_total =  $result->earnings_total - $result->attendanceleaveamoumt_total;
        

       $result->payslip_total = $result->earnings_subtotal;
       $result->earningsadd_subtotal = $items->earningsadd_subtotal;
       $result->earningsadd_total = $result->earningsadd_subtotal + $result->earnings_total ;
        return $result;
    }



function get_emp_monthly_payslip_info_suggestion($month = "",$user_id = "") {

        $payslip_table = $this->db->dbprefix('payslip');
        

        $sql = "SELECT $payslip_table.*
        FROM $payslip_table
        WHERE $payslip_table.deleted=0 
        AND MONTH($payslip_table.payslip_date) = '$month' AND $payslip_table.user_id = '$user_id' 
        ORDER BY id DESC";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->num_rows();
        }

    }  

    function get_payslip_user_id_suggestion($keyword = "",$month="") {
        $users_table = $this->db->dbprefix('users');
        
   $payslip_table = $this->db->dbprefix('payslip');
 $sqls = "SELECT $payslip_table.user_id
        FROM $payslip_table
        WHERE $payslip_table.deleted=0 AND MONTH($payslip_table.payslip_date) ='$month' 
        ";
        $inventory_result = $this->db->query($sqls)->result();

        if($inventory_result){
        $inventory_items = array();
foreach ($inventory_result as $inventory) {
            $inventory_items[] = $inventory->user_id;
        }
$aa=json_encode($inventory_items);
$vv=str_ireplace("[","(",$aa);
$inventory_item=str_ireplace("]",")",$vv);
       
}else{
    $inventory_item="('empty')";
}


        $sql = "SELECT $users_table.first_name,$users_table.last_name,
        $users_table.id
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.user_type = 'staff' AND $users_table.status = 'active' AND CONCAT($users_table.first_name,'',$users_table.last_name) LIKE '%$keyword%' and  $users_table.id  NOT IN  $inventory_item
        LIMIT 30 
        ";
        return $this->db->query($sql)->result();
    } 

//inser the payslip table auto generate 
function insert($data)
    {
        $this->db->insert_batch('payslip', $data);
    }


//payslip user per month total attendance duration 

function get_payslip_user_per_month_total_duration($payslip_id = 0) {
        $payslip_deductions_table = $this->db->dbprefix('payslip_deductions');
        $payslip_earnings_table = $this->db->dbprefix('payslip_earnings');
        $payslip_attendance_table = $this->db->dbprefix('payslip_attendance');
        $payslip_earningsadd_table = $this->db->dbprefix('payslip_earningsadd');
        $payslip_table = $this->db->dbprefix('payslip');
        $users_table = $this->db->dbprefix('users');
        $earnings_table = $this->db->dbprefix('earnings');

        $attendnace_table = $this->db->dbprefix('attendance');
        $team_member_job_info_table = $this->db->dbprefix('team_member_job_info');
         $leave_applications_table = $this->db->dbprefix('leave_applications');
        
        $where = "";
        //$pd = get_array_value($options, "payslip_id");
                $leave_date_sqls = "SELECT $payslip_table.payslip_date
        FROM $payslip_table
           
        WHERE $payslip_table.id=$payslip_id ";
        $leave_date = $this->db->query($leave_date_sqls)->row();
        
        $date=$leave_date->payslip_date;

       // get user uer attendance details per month 
        $firstdayMonth = $date."first day of previous month";
        $lastdayMonth = $date."last day of previous month";
        $first_day= Date('Y-m-d', strtotime($firstdayMonth ));
        $last_day= Date('Y-m-d', strtotime($lastdayMonth ));
        
        
        
        

        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = $first_day;
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))>='$start_date'";
        }
        $end_date = $last_day;
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))<='$end_date'";
        }


        //number of sundays 
$lastmonth = $date."first day of previous month";
//$Date=Date('Y-m-d', strtotime($lastmonth));
$last=Date('m', strtotime($lastmonth));
$year=Date('Y', strtotime($lastmonth));
$num_of_days=Date('t', strtotime($lastmonth));
$months = $last;  
$years=$year;                                      
$monthName = date("F", mktime(0, 0, 0, $months));
$fromdt=date('Y-m-01 ',strtotime("First Day Of  $monthName $years")) ;
$todt=date('Y-m-d ',strtotime("Last Day of $monthName $years"));

$num_sundays='';                
for ($i = 0; $i < ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++)
{
    if(date('l',strtotime($fromdt) + ($i * 86400)) == 'Sunday')
    {
            $num_sundays++;
    }    
}

// get user leave for per month
$leave_where="";
$currentMonth =$date."first day of previous month";
$leave_last_month=Date('m', strtotime($currentMonth ));
        $leave_start_date = "$leave_applications_table.start_date";
        $leave_end_date = "$leave_applications_table.end_date";
        //$month = date('m');
        $leave_year = Date('Y', strtotime($currentMonth ));
        $leave_days = Date('t', strtotime($currentMonth ));
        //$last_month = $month-1%12;
      if ($leave_start_date && $leave_end_date) {
           $leave_where .= " AND $leave_applications_table.status='approved'";
           $leave_where .= " AND $leave_applications_table.deleted=0";
           $leave_where .= " AND ($leave_applications_table.start_date  BETWEEN '$leave_year-$leave_last_month-01' AND '$leave_year-$leave_last_month-31')  "; 
        }

   
/*$earnings_sql = "SELECT $payslip_earnings_table.*, 
                 $team_member_job_info_table.salary AS earnings_subtotal
        FROM $payslip_earnings_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earnings_table.payslip_id
        LEFT JOIN $team_member_job_info_table ON $team_member_job_info_table.user_id=$payslip_earnings_table.user_id 
        WHERE $payslip_earnings_table.deleted=0 AND $payslip_earnings_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $earnings = $this->db->query($earnings_sql)->row(); */


        
           /* $extra_select = ", start_date ";
            $extra_inner_select = "MAX(DATE(ADDTIME($attendnace_table.in_time,'$offset'))) AS start_date ";
            $extra_group_by = ", DATE(ADDTIME($attendnace_table.in_time,'$offset')) ";
            $sort_by = "ORDER BY user_id, start_date ASC"; //order by must be with user_id  */
        
        $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS total_duration ,COUNT(DISTINCT Date( ADDTIME($attendnace_table.in_time,'$offset'))) as start_date
          FROM $attendnace_table
          LEFT JOIN $payslip_table ON $payslip_table.user_id= $attendnace_table.user_id
          WHERE $attendnace_table.deleted=0 AND  $payslip_table.id = $payslip_id $where";

        $total_duration = $this->db->query($sql)->row();
 
  $earnings_sql = "SELECT $payslip_earnings_table.*, 
                 $team_member_job_info_table.salary AS earnings_subtotal
        FROM $payslip_earnings_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earnings_table.payslip_id
        LEFT JOIN $team_member_job_info_table ON 
        $team_member_job_info_table.user_id=$payslip_earnings_table.user_id 
        WHERE $payslip_earnings_table.deleted=0 AND $payslip_earnings_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $earnings = $this->db->query($earnings_sql)->row();


       $attendance_sql = "SELECT SUM($leave_applications_table.total_days) 
                  AS attendance_subtotal ,SUM($leave_applications_table.total_hours) 
                  AS total_leave_hours
        FROM $payslip_attendance_table
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_attendance_table.payslip_id
        LEFT JOIN $leave_applications_table ON $leave_applications_table.applicant_id=$payslip_attendance_table.user_id
        WHERE $payslip_attendance_table.deleted=0 AND $payslip_attendance_table.payslip_id=$payslip_id AND $payslip_table.deleted=0 $leave_where";
        $attendance = $this->db->query($attendance_sql)->row(); 
 
 $items_sql = "SELECT SUM($payslip_earningsadd_table.rate) AS earningsadd_subtotal
        FROM $payslip_earningsadd_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earningsadd_table.payslip_id    
        WHERE $payslip_earningsadd_table.deleted=0 AND $payslip_earningsadd_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $items = $this->db->query($items_sql)->row();
  
      
        $result = new stdClass();
        

        /*$result->earnings_subtotal = $earnings->earnings_subtotal;
        
  
        $result->earnings_total = $result->earnings_subtotal;*/
        $company_working_hours_for_one_day=get_setting('company_working_hours_for_one_day');
        $result->total_duration = $total_duration->total_duration;
        $result->total_user_days = $total_duration->start_date;
        $result->num_of_sundays= $num_sundays;
        $result->num_of_days=$num_of_days;
        $result->total_days= $result->num_of_days-$result->num_of_sundays;
        $result->company_hours = $result->total_days*$company_working_hours_for_one_day;
        $result->earnings_subtotal = $earnings->earnings_subtotal;
        $result->earnings_total = $result->earnings_subtotal;

        $s = convert_seconds_to_time_format(abs(
        $result->total_duration)); 
        $total_user_duration =to_decimal_format(convert_time_string_to_decimal($s));
      /*  if($total_user_duration<$result->company_hours && $result->total_days>$result->total_user_days ){
          $result->earnings_total = $result->earnings_subtotal;
        } */
       

       //monthly salary get userd duration
$result->users_monthly_salary_calculation = $result->earnings_total/$result->company_hours;
  $result->monthly_salarys = $result->users_monthly_salary_calculation*$total_user_duration;
// over time 
$result->over_time="";
$result->over_time_amount="";
$result->monthly_salary="";
if($total_user_duration>=$result->company_hours){
  $result->monthly_salary = $result->earnings_total;
  //over time extra hours calculation
  $result->over_time = $total_user_duration - $result->company_hours;
  $result->over_time_amount = $result->over_time *$result->users_monthly_salary_calculation;
}else if($total_user_duration<=$result->company_hours){
  $result->monthly_salary = $result->monthly_salarys;
  $result->over_time=0;
  $result->over_time_amount=0;
}

//deductions amount calculation
$result->deductions_amount="";
if($total_user_duration>=$result->company_hours){
  $result->deductions_amount=0;
}else if($total_user_duration<=$result->company_hours){

 $result->deductions_amount=$result->earnings_total-$result->monthly_salarys; 

}

//num of leave calculate
$result->total_leave_hours = $attendance->total_leave_hours;
$result->attendance_subtotal = $attendance->attendance_subtotal;
$result->one_day_leave_amount = 0;
$result->total_leave_monthly_salary=0;
//allow the less than company hours to add the one day amount
if($total_user_duration<=$result->company_hours){
if($result->attendance_subtotal<1 && $result->attendance_subtotal!=0){
  $result->leave_calculation = $result->users_monthly_salary_calculation*$result->total_leave_hours;
$result->one_day_leave_amount = $result->leave_calculation;
$result->total_leave_monthly_salary=$result->one_day_leave_amount+$result->monthly_salary;

}else if($result->attendance_subtotal>=1){

$result->leave_calculation = $result->users_monthly_salary_calculation*$company_working_hours_for_one_day;

$result->one_day_leave_amount = $result->leave_calculation;
$result->total_leave_monthly_salary = $result->one_day_leave_amount+$result->monthly_salary;
}else if($result->attendance_subtotal==0){
$result->one_day_leave_amount = 0;
$result->total_leave_monthly_salary = 0;
 }
}

//earnings add ot amount
  $result->earningsadd_subtotal = $items->earningsadd_subtotal;
       $result->earningsadd_total = $result->earningsadd_subtotal + $result->over_time_amount;
        
        
        return $result;
    }

     




   }
