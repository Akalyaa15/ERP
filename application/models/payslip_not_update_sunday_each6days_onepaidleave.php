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
            /* orginal strat date */
     /*$start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($payslip_table.payslip_date BETWEEN '$start_date' AND '$end_date') ";
        }  */

        //payslip pervious month 
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
       $firstdayMonth_start_date = $start_date."first day of next month";
        $lastdayMonth_end_date = $start_date."last day of next month";
        $first_day_start_date= Date('Y-m-d', strtotime($firstdayMonth_start_date));
        $last_day_start_date= Date('Y-m-d', strtotime($lastdayMonth_end_date));
        


    if ($start_date && $end_date) {
        $where .= " AND ($payslip_table.payslip_date BETWEEN '$first_day_start_date' AND ' $last_day_start_date')";
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
         $events_table = $this->db->dbprefix('events');
        
        $where = "";
        //$pd = get_array_value($options, "payslip_id");
                $leave_date_sqls = "SELECT $payslip_table.payslip_date
        FROM $payslip_table
           
        WHERE $payslip_table.id=$payslip_id ";
        $leave_date = $this->db->query($leave_date_sqls)->row();
        
        $date=$leave_date->payslip_date;

       // get users attendance details per month 
        $firstdayMonth = $date."first day of previous month";
        $lastdayMonth = $date."last day of previous month";
        $first_day= Date('Y-m-d', strtotime($firstdayMonth ));
        $last_day= Date('Y-m-d', strtotime($lastdayMonth ));
        
        
        
        
//get attendance table convert seconds to hours format
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = $first_day;
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))>='$start_date'";
        }
        $end_date = $last_day;
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))<='$end_date'";
        }



//total number of sundays for this month ex.(4)
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
for ($i = 0; $i <= ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++)
{
    if(date('l',strtotime($fromdt) + ($i * 86400)) == 'Sunday')
    {
            $num_sundays++;
    }    
}


//No sunday date format for this month date format ex.('2020-02-12')
$sunday_startDate = new DateTime($fromdt);
$sunday_endDate = new DateTime($todt);

$sundays_date = array();

while ($sunday_startDate <= $sunday_endDate) {
    if ($sunday_startDate->format('w') == 0) {
        $sundays_date[] = $sunday_startDate->format('Y-m-d');
    }

    $sunday_startDate->modify('+1 day');
}

/*var_dump($sundays);*/
/*print_r($sundays);*/
$aa=json_encode($sundays_date);
$bb=str_ireplace("[","(",$aa);
$sunday_date=str_ireplace("]",")",$bb);
if($sunday_date=='()'){
  $sunday_date = '("0000-00-00")';
}
/*echo $sunday_date;*/



// get user leave for per month form the attendance table
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



// get official leave for per month using where condition form events table.
$official_leave_where="";
$currentMonth =$date."first day of previous month";
$leave_last_month=Date('m', strtotime($currentMonth ));
        $leave_start_date = " $events_table.start_date";
        $leave_end_date = " $events_table.end_date";
        //$month = date('m');
        $leave_year = Date('Y', strtotime($currentMonth ));
        $leave_days = Date('t', strtotime($currentMonth ));
        //$last_month = $month-1%12;
      if ($leave_start_date && $leave_end_date) {
           $official_leave_where .= " AND  $events_table.official_leave='1'";
            $official_leave_where .= " AND  $events_table.share_with='all'";
           $official_leave_where .= " AND  $events_table.deleted=0";
           $official_leave_where .= " AND ($events_table.start_date  BETWEEN '$leave_year-$leave_last_month-01' AND '$leave_year-$leave_last_month-31')  "; 
        } 



//get no of days official holidays from events startdate to enddate (betweeen no of date get format )  
  $events_date_sql = "SELECT $events_table.*, $events_table.start_date as official_start_date , $events_table.end_date as official_end_date FROM $events_table
   WHERE $events_table.deleted=0 $official_leave_where";

$event_date = $this->db->query($events_date_sql)->result();
$ff =array();
foreach ($event_date as $event_dat) {
  //$ff[] =  $event_dat->start_date;
 // $dd[] =  $event_dat->end_date;
  
   $start=$event_dat->start_date;  
   $end=$event_dat->end_date;
   $format =  'Y-m-d';
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $datess) {                  
       $ff[] = $datess->format($format);  
    } 

  }
$aaa=json_encode($ff);
$bbb=str_ireplace("[","(",$aaa);
$sunday_datess=str_ireplace("]",")",$bbb);
if($sunday_datess=='()'){
  $sunday_datess = '("0000-00-00")';
}
     



//overall total duration and days  user attendance     
    $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS total_duration ,COUNT(DISTINCT Date( ADDTIME($attendnace_table.in_time,'$offset'))) as start_date
          FROM $attendnace_table
          LEFT JOIN $payslip_table ON $payslip_table.user_id= $attendnace_table.user_id
          WHERE $attendnace_table.deleted=0 AND  $payslip_table.id = $payslip_id $where ";

        $total_duration = $this->db->query($sql)->row();


//overall total duration  of get the every day company working fetch user attendance 
$sqls = "SELECT SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS oneday_intime
          FROM $attendnace_table 
          LEFT JOIN $payslip_table ON $payslip_table.user_id= $attendnace_table.user_id
          WHERE $attendnace_table.deleted=0 AND  $payslip_table.id = $payslip_id $where GROUP BY DATE($attendnace_table.in_time) ";

        $total_durations = $this->db->query($sqls)->result();
  $oneday_user_contribute =0;
foreach ($total_durations as $total_durationss) {

/*$oneday_intime = $total_durationss->in_time; 
$oneday_outtime = $total_durationss->out_time;  

$oneday_user_contrib = strtotime($oneday_intime) - strtotime($oneday_outtime);*/
$oneday_intime = $total_durationss->oneday_intime;
$oneday_user_contrib = $oneday_intime;
$oneday_user_contribu =convert_seconds_to_time_format(abs(
    $oneday_user_contrib));  
$oneday_user_contribut = to_decimal_format(convert_time_string_to_decimal($oneday_user_contribu));
$company_working_hours_for_one_day=get_setting('company_working_hours_for_one_day');
if($oneday_user_contribut<=$company_working_hours_for_one_day){
  $oneday_user_contribute += $oneday_user_contribut;
}else if($oneday_user_contribut>$company_working_hours_for_one_day)
$oneday_user_contribute +=$company_working_hours_for_one_day;

}



//get duration and days excepted sunday and official holidays 
        $sunday_sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS total_duration_excepted_sundays ,COUNT(DISTINCT Date( ADDTIME($attendnace_table.in_time,'$offset'))) as total_days_excepted_sundays
          FROM $attendnace_table
          LEFT JOIN $payslip_table ON $payslip_table.user_id= $attendnace_table.user_id
          WHERE $attendnace_table.deleted=0 AND  $payslip_table.id = $payslip_id AND DATE(ADDTIME($attendnace_table.in_time,'$offset')) NOT IN $sunday_date AND 
          DATE(ADDTIME($attendnace_table.in_time,'$offset')) not in $sunday_datess $where ";

        $total_duration_excepted_sundays = $this->db->query($sunday_sql)->row();


//overall total duration  of get the every day company working fetch user attendance  excepted sunday and official holidays
   $sunday_sqls = "SELECT SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS holiday_oneday_intime
          FROM $attendnace_table 
          LEFT JOIN $payslip_table ON $payslip_table.user_id= $attendnace_table.user_id
          WHERE $attendnace_table.deleted=0 AND  $payslip_table.id = $payslip_id AND DATE(ADDTIME($attendnace_table.in_time,'$offset')) NOT IN $sunday_date AND 
          DATE(ADDTIME($attendnace_table.in_time,'$offset')) not in $sunday_datess $where GROUP BY DATE($attendnace_table.in_time)";

        $total_duration_excepted_sundays_company_hours = $this->db->query($sunday_sqls)->result();
  $sunday_oneday_user_contribute =0;
  foreach ($total_duration_excepted_sundays_company_hours as $total_duration_excepted_sundays_company_hour) {

/*$holiday_oneday_intime = $total_duration_excepted_sundays_company_hour->in_time; 
$holidays_oneday_outtime = $total_duration_excepted_sundays_company_hour->out_time;  */

/*$sunday_oneday_user_contrib = strtotime($holiday_oneday_intime) - strtotime($holidays_oneday_outtime);*/
$holiday_oneday_intime = $total_duration_excepted_sundays_company_hour->holiday_oneday_intime;
$sunday_oneday_user_contrib =$holiday_oneday_intime;
$sunday_oneday_user_contribu =convert_seconds_to_time_format(abs(
    $sunday_oneday_user_contrib));  
$sunday_oneday_user_contribut = to_decimal_format(convert_time_string_to_decimal($sunday_oneday_user_contribu));
$company_working_hours_for_one_day=get_setting('company_working_hours_for_one_day');
if($sunday_oneday_user_contribut<=$company_working_hours_for_one_day){
  $sunday_oneday_user_contribute += $sunday_oneday_user_contribut;
}else if($sunday_oneday_user_contribut>$company_working_hours_for_one_day)
$sunday_oneday_user_contribute +=$company_working_hours_for_one_day;

}

//get the employee salary from team member job info table  
$earnings_sql = "SELECT $payslip_earnings_table.*, 
                 $team_member_job_info_table.salary AS earnings_subtotal
        FROM $payslip_earnings_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earnings_table.payslip_id
        LEFT JOIN $team_member_job_info_table ON 
        $team_member_job_info_table.user_id=$payslip_earnings_table.user_id 
        WHERE $payslip_earnings_table.deleted=0 AND $payslip_earnings_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $earnings = $this->db->query($earnings_sql)->row();


//get no of employee leave from attendance table  

    $attendance_sql = "SELECT SUM($leave_applications_table.total_days) 
                  AS attendance_subtotal ,SUM($leave_applications_table.total_hours) 
                  AS total_leave_hours
        FROM $payslip_attendance_table
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_attendance_table.payslip_id
        LEFT JOIN $leave_applications_table ON $leave_applications_table.applicant_id=$payslip_attendance_table.user_id
        WHERE $payslip_attendance_table.deleted=0 AND $payslip_attendance_table.payslip_id=$payslip_id AND $payslip_table.deleted=0 $leave_where";
        $attendance = $this->db->query($attendance_sql)->row(); 
 
//get the addtional earnings amount form the earningsadd table   
  $items_sql = "SELECT SUM($payslip_earningsadd_table.rate) AS earningsadd_subtotal
        FROM $payslip_earningsadd_table
        LEFT JOIN $payslip_table ON $payslip_table.id= $payslip_earningsadd_table.payslip_id    
        WHERE $payslip_earningsadd_table.deleted=0 AND $payslip_earningsadd_table.payslip_id=$payslip_id AND $payslip_table.deleted=0";
        $items = $this->db->query($items_sql)->row();


//get no of official holidays from events table 
      $events_sql = "SELECT SUM($events_table.total_days) 
                  AS official_leave  
                  
        FROM $events_table
        WHERE $events_table.deleted=0 $official_leave_where";
        
        $events = $this->db->query($events_sql)->row();

  //get payslip table details
        $payslip_sql = "SELECT $payslip_table.*
        FROM $payslip_table
        WHERE $payslip_table.deleted=0 AND $payslip_table.id=$payslip_id";
        $payslip = $this->db->query($payslip_sql)->row();      
        $result = new stdClass();
        

        /*$result->earnings_subtotal = $earnings->earnings_subtotal;
        
  
        $result->earnings_total = $result->earnings_subtotal;*/
        $company_working_hours_for_one_day=get_setting('company_working_hours_for_one_day');
        $max_no_casual_leave_per_month=get_setting('maximum_no_of_casual_leave_per_month');
        $payslip_ot_status=get_setting('payslip_ot_status');
        $payslip_ot_permission=get_setting('ot_permission');
        $payslip_ot_permission_specific=get_setting('ot_permission_specific');
        $result->total_duration = $total_duration->total_duration;
        $result->total_user_days = $total_duration->start_date;

//excepted sunday and official holidays
    $result->total_duration_excepted_sundays = $total_duration_excepted_sundays->total_duration_excepted_sundays;
    $result->total_days_excepted_sundays = $total_duration_excepted_sundays->total_days_excepted_sundays;
    $result->total_days_work_sundays =$result->total_user_days-$result->total_days_excepted_sundays;
 //  excepted sunday and official holidays end  


// user get overall duration every day get company hours one day fetch the user
$result->total_onedaycompany_duration_user=$oneday_user_contribute;

// user get overall duration excepted sunday and official holidays  every day get company hours one day fetch the user
$result->excepted_oneday_user_contribute=$sunday_oneday_user_contribute;

// user get overall duration only sunday and official holidays  every day get company hours one day fetch the user
$result->only_holidays_oneday_user_contribute=$result->total_onedaycompany_duration_user-
$result->excepted_oneday_user_contribute;


//official leave 
$result->official_leave = $events->official_leave;
$result->total_official_sunday_holidays = $num_sundays+$result->official_leave;
$result->employee_this_month_offical_holidays = $result->total_official_sunday_holidays-$result->total_days_work_sundays;
//$result->event_date = $sunday_datess;



//monthly no of days and no of sundays company month hours etc..     
  $result->num_of_sundays= $num_sundays;
  $result->num_of_days=$num_of_days;
  $result->total_days= $result->num_of_days-($result->num_of_sundays+$result->official_leave);
  $result->total_month_days_company_hours = $result->num_of_days*$company_working_hours_for_one_day;
  $result->company_hours = $result->total_days*$company_working_hours_for_one_day;
  $result->earnings_subtotal = $earnings->earnings_subtotal;
  $result->earnings_total = $result->earnings_subtotal;





 //total contribute hours in employee duration for this month
  $s = convert_seconds_to_time_format(abs(
  $result->total_duration)); 
  $total_user_duration =to_decimal_format(convert_time_string_to_decimal($s));


//get monthly one hour salary get employee salary amount calculation
$result->users_monthly_salary_calculation = number_format($result->earnings_total/$result->total_month_days_company_hours, 4, '.', '');
  
//employee one day work salary and one hour salary
$result->employee_per_hour_salary = $result->users_monthly_salary_calculation;
$result->employee_per_one_day_salary = $result->users_monthly_salary_calculation*$company_working_hours_for_one_day;



//check the user clock date and official holidays calculation
$result->user_clockin_days_and_official_holidays  = $result->num_of_days-($result->total_user_days+$result->total_official_sunday_holidays);

/*//add the extra leave
$result->no_of_paid_leave = $payslip->no_of_paid_leave;
$result->employee_total_leave = $attendance->attendance_subtotal;*/

//user clock days month is zero or greater  lop of days(check1) 
if($result->user_clockin_days_and_official_holidays>=0){

//user leave and paid leave 
$result->no_of_paid_leave = $payslip->no_of_paid_leave;
$result->user_total_working_days_hours = $result->total_user_days*$company_working_hours_for_one_day;
$result->user_total_lop_hours_this_month = $result->user_total_working_days_hours -$result->total_onedaycompany_duration_user;

$result->user_lop_days_covert_to_days = $result->user_total_lop_hours_this_month/$company_working_hours_for_one_day;
$result->total_user_lop_days = $result->user_lop_days_covert_to_days+$result->user_clockin_days_and_official_holidays;
$result->employee_total_leave = $attendance->attendance_subtotal;
//employee leave leave days attendance leave below

if($result->employee_total_leave<=$result->total_user_lop_days){

//paid leave and official holidays calculation
if($result->employee_total_leave>$max_no_casual_leave_per_month)
   {

//check paid leave 0 value
if(is_null($result->no_of_paid_leave)) {
    $result->employee_paid_leave =($result->employee_total_leave -($result->employee_total_leave - $max_no_casual_leave_per_month));
}else if($result->no_of_paid_leave==0){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}else if($result->no_of_paid_leave){
   $result->employee_paid_leave = $result->no_of_paid_leave;
} 

    $result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->total_official_sunday_holidays;
    /*$result->employee_number_of_days_lop = $result->employee_total_leave - $result->employee_paid_leave;*/
    $result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;
    $result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation;   
}elseif ($result->employee_total_leave <=$max_no_casual_leave_per_month){

if(is_null($result->no_of_paid_leave)) {
$result->employee_paid_leave = $result->employee_total_leave;
}else if($result->no_of_paid_leave == 0){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}else if($result->no_of_paid_leave){
    $result->employee_paid_leave = $result->no_of_paid_leave;
}

$result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->total_official_sunday_holidays;
$result->employee_number_of_days_lop = "-";
$result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;

$result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation;

  }


}else if($result->employee_total_leave>$result->total_user_lop_days){

  $result->employee_paid_leave = 0;

  $result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->total_official_sunday_holidays;
    /*$result->employee_number_of_days_lop = $result->employee_total_leave - $result->employee_paid_leave;*/
   $result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;
   $result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation; 
 
      }


}


//user clock days month is work greater no of month  no lop of days(check 2 value is negative) 
if($result->user_clockin_days_and_official_holidays<0){

//user leave and paid leave 
$result->no_of_paid_leave = $payslip->no_of_paid_leave;
$result->user_clockin_days_and_official_holidays_extra  = ($result->total_user_days+$result->total_official_sunday_holidays)-$result->num_of_days;
$result->user_working_remain_days =$result->total_official_sunday_holidays-$result->user_clockin_days_and_official_holidays_extra;

$result->user_total_working_days_hours = $result->total_user_days*$company_working_hours_for_one_day;

$result->user_total_lop_hours_this_month = $result->user_total_working_days_hours -$result->total_onedaycompany_duration_user;
$result->user_lop_days_covert_to_days = $result->user_total_lop_hours_this_month/$company_working_hours_for_one_day;
$result->total_user_lop_days = $result->user_lop_days_covert_to_days;
$result->employee_total_leave = $attendance->attendance_subtotal;


$result->employee_total_leave = $attendance->attendance_subtotal;
//employee leave leave days attendance leave below

if($result->employee_total_leave<=$result->total_user_lop_days){

//paid leave and official holidays calculation
if($result->employee_total_leave>$max_no_casual_leave_per_month)
   {

//check paid leave 0 value
if(is_null($result->no_of_paid_leave)) {
    $result->employee_paid_leave =($result->employee_total_leave -($result->employee_total_leave - $max_no_casual_leave_per_month));
}else if($result->no_of_paid_leave==0){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}else if($result->no_of_paid_leave){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}

    
    $result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->user_working_remain_days;
    /*$result->employee_number_of_days_lop = $result->employee_total_leave - $result->employee_paid_leave;*/
        $result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;
        $result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation;   
}elseif ($result->employee_total_leave <=$max_no_casual_leave_per_month){

if(is_null($result->no_of_paid_leave)) {
$result->employee_paid_leave = $result->employee_total_leave;
}else if($result->no_of_paid_leave == 0){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}else if($result->no_of_paid_leave){
    $result->employee_paid_leave = $result->no_of_paid_leave;
}

$result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->user_working_remain_days;
$result->employee_number_of_days_lop = "-";
$result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;

$result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation;

  }


}else if($result->employee_total_leave>$result->total_user_lop_days){

$result->employee_paid_leave = 0;

$result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->user_working_remain_days;
    /*$result->employee_number_of_days_lop = $result->employee_total_leave - $result->employee_paid_leave;*/
    $result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;
    $result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation; 
 
      }


}

// end check the user clock date and official holidays calculation 







//user leave and paid leave 
/*$result->no_of_paid_leave = $payslip->no_of_paid_leave;
$result->employee_total_leave = $attendance->attendance_subtotal;*/


/*//paid leave and official holidays calculation
if($result->employee_total_leave>$max_no_casual_leave_per_month)
   {

//check paid leave 0 value
if($result->employee_paid_leave == 0){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}else{
    $result->employee_paid_leave = ($result->no_of_paid_leave) ? ($result->no_of_paid_leave) :($result->employee_total_leave -($result->employee_total_leave - $max_no_casual_leave_per_month));
}

    
    $result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->employee_this_month_offical_holidays;
    $result->employee_number_of_days_lop = $result->employee_total_leave - $result->employee_paid_leave;
        $result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;
        $result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation;   
}elseif ($result->employee_total_leave <=$max_no_casual_leave_per_month){
if($result->employee_paid_leave == 0){
   $result->employee_paid_leave = $result->no_of_paid_leave;
}else{
    $result->employee_paid_leave = ($result->no_of_paid_leave) ? ($result->no_of_paid_leave) : $result->employee_total_leave;
}




$result->no_of_paidleave_ofiicial_sundays_holidays = $result->employee_paid_leave +$result->employee_this_month_offical_holidays;
$result->employee_number_of_days_lop = "-";
$result->employee_paid_leave_hours =  $result->no_of_paidleave_ofiicial_sundays_holidays *$company_working_hours_for_one_day;

$result->one_day_leave_amount = $result->employee_paid_leave_hours * $result->users_monthly_salary_calculation;
}*/


//no of lop days in saikriba calculation
/*$result->ex = ($result->num_of_days-($result->total_days_excepted_sundays +$result->total_official_sunday_holidays+$result->total_days_work_sundays)) -($result->employee_total_leave-$result->employee_paid_leave);
*/


//monthly salary calculation user contributed hours in less than total days of monthly hours 
$result->over_time="-";
$result->over_time_amount=0;
$result->monthly_working_salary=0; 
$result->monthly_total_earnings=0;
/*$user_official_holidays_hours_for_this_months = $result->total_official_sunday_holidays *$company_working_hours_for_one_day;*/
/*$result->total_userduration_plus_offical_hours_hours =$user_official_holidays_hours_for_this_month+$result->total_onedaycompany_duration_user;*/
if($result->total_onedaycompany_duration_user<=$result->total_month_days_company_hours){
 $result->monthly_salary = $result->total_onedaycompany_duration_user * $result->users_monthly_salary_calculation;
 $monthly_lop_hours_diff_month_minus_user_hour = $result->total_month_days_company_hours-$result->total_onedaycompany_duration_user;

$monthly_lop_hours = $monthly_lop_hours_diff_month_minus_user_hour-$result->employee_paid_leave_hours;
//monthly lop hour  to lop days 
$result->monthly_lop_days = number_format($monthly_lop_hours/$company_working_hours_for_one_day, 3, '.', ''); 

$monthly_lop_amount = number_format($monthly_lop_hours*$result->users_monthly_salary_calculation, 2, '.', '');

$result->monthly_working_salary = $result->monthly_salary;

$result->monthly_total_earnings = $result->monthly_working_salary+$result->one_day_leave_amount;
$result->deductions_amount = $monthly_lop_amount; 
$result->over_time=0;
$result->over_time_amount=0;

}


//monthly salary calculation  user contributed hours greater than total days of  monthly hours (user working over time )
/*if($result->total_userduration_plus_offical_hours_hours>$result->total_month_days_company_hours){

$user_official_holidays_hours_for_this_months = $result->total_official_sunday_holidays *$company_working_hours_for_one_day;
$result->remain_official_hours =$result->total_month_days_company_hours-$user_official_holidays_hours_for_this_months;
     $result->monthly_salary =  $result->remain_official_hours* $result->users_monthly_salary_calculation;
 

$monthly_lop_hours = 0;
//monthly lop hour  to lop days 
$result->monthly_lop_days = number_format($monthly_lop_hours/$company_working_hours_for_one_day, 3, '.', ''); 

$monthly_lop_amount = number_format($monthly_lop_hours*$result->users_monthly_salary_calculation, 2, '.', '');

$result->monthly_working_salary = $result->monthly_salary;

$result->monthly_total_earnings = $result->monthly_working_salary+$result->one_day_leave_amount;
$result->deductions_amount = $monthly_lop_amount; 
$result->over_time=0;
$result->over_time_amount=0;


}*/


//over time add in enable user add the over amount for monthly salary
/*if($payslip_ot_status=="active"){
  $result->over_time = $total_user_duration - $result->total_onedaycompany_duration_user;
  $result->over_time_amount = $result->over_time *$result->users_monthly_salary_calculation;
}*/

// OT Permission  specfic team group list 
$ot_str = $payslip_ot_permission_specific;
$ot_s = explode(",",$ot_str);
/*print_r($s);*/
$ot_arri =array();
foreach($ot_s as $ot_sus){
if (strpos($ot_sus, 'team') !== false) {
    $ot_arri[] = $ot_sus;
}
}
$ot_string_convert = implode(",",$ot_arri);
$ot_replace_string = str_replace("team:","",$ot_string_convert );
$ot_team_array_convert = explode(",",$ot_replace_string);
$ot_teamgroup_list = "";
foreach ($ot_team_array_convert as $ot_team_group) {
                if ($ot_team_group) {
                     $options = array("id" => $ot_team_group);
                    $list_team_group = $this->Team_model->get_details($options)->row(); 
                    $ot_teamgroup_list .= $list_team_group->members.",";
                }
            }

$ot_team_group_list_array = explode(",",
    $ot_teamgroup_list);

//OT Team members list 
  $ot_permission_user_array=explode(",",
    $payslip_ot_permission_specific);
//OT spefic permission teammembers and team show to team members payslip
if($payslip_ot_permission =="all"){
  $result->over_time = $total_user_duration - $result->total_onedaycompany_duration_user;
  $result->over_time_amount = $result->over_time *$result->users_monthly_salary_calculation;
}else if($payslip_ot_permission =="specific" && (in_array('member:'.$payslip->user_id,$ot_permission_user_array)||in_array($payslip->user_id,$ot_team_group_list_array))){
    $result->over_time = $total_user_duration - $result->total_onedaycompany_duration_user;
    $result->over_time_amount =$result->over_time *$result->users_monthly_salary_calculation;
}

//This Show only admin view page OT amount Show 
$result->over_time_admin = $total_user_duration - $result->total_onedaycompany_duration_user;
$result->over_time_amount_admin =$result->over_time_admin *$result->users_monthly_salary_calculation;
$result->earningsadd_total_admin = $result->earningsadd_subtotal + $result->over_time_amount_admin;


//add the addtional earnings amount  and ot amount 
$result->earningsadd_subtotal = $items->earningsadd_subtotal;
$result->earningsadd_total = $result->earningsadd_subtotal + $result->over_time_amount;

return $result;
    
}

     




   }
