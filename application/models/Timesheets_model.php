<?php

class Timesheets_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'project_time';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $timesheet_table = $this->db->dbprefix('project_time');
        $tasks_table = $this->db->dbprefix('tasks');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $timesheet_table.id=$id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $timesheet_table.project_id=$project_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $timesheet_table.user_id=$user_id";
        }

        $status = get_array_value($options, "status");
        if ($status === "none_open") {
            $where .= " AND $timesheet_table.status !='open'";
        } else if ($status) {
            $where .= " AND $timesheet_table.status='$status'";
        }

        $task_id = get_array_value($options, "task_id");
        if ($task_id) {
            $where .= " AND $timesheet_table.task_id=$task_id";
        }

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $timesheet_table.project_id IN(SELECT $projects_table.id FROM $projects_table WHERE $projects_table.client_id=$client_id)";
        }

       // $offset = convert_seconds_to_time_format(get_timezone_offset());
         $offset = convert_seconds_to_time_format();

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($timesheet_table.start_time,'$offset'))>='$start_date'";
        }

        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($timesheet_table.end_time,'$offset'))<='$end_date'";
        }


        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND $timesheet_table.user_id IN($allowed_members)";
        }


        $sql = "SELECT $timesheet_table.*,  CONCAT($users_table.first_name, ' ',$users_table.last_name) AS logged_by_user, $users_table.image as logged_by_avatar,
            $tasks_table.title AS task_title, $projects_table.title AS project_title
        FROM $timesheet_table
        LEFT JOIN $users_table ON $users_table.id= $timesheet_table.user_id
        LEFT JOIN $tasks_table ON $tasks_table.id= $timesheet_table.task_id
        LEFT JOIN $projects_table ON $projects_table.id= $timesheet_table.project_id
        WHERE $timesheet_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_summary_details($options = array()) {
        $timesheet_table = $this->db->dbprefix('project_time');
        $tasks_table = $this->db->dbprefix('tasks');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $timesheet_table.id=$id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $timesheet_table.project_id=$project_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $timesheet_table.user_id=$user_id";
        }

        $status = get_array_value($options, "status");
        if ($status === "none_open") {
            $where .= " AND $timesheet_table.status !='open'";
        } else if ($status) {
            $where .= " AND $timesheet_table.status='$status'";
        }

        $task_id = get_array_value($options, "task_id");
        if ($task_id) {
            $where .= " AND $timesheet_table.task_id=$task_id";
        }

        //$offset = convert_seconds_to_time_format(get_timezone_offset());
         $offset = convert_seconds_to_time_format();
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($timesheet_table.start_time,'$offset'))>='$start_date'";
        }

        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($timesheet_table.end_time,'$offset'))<='$end_date'";
        }

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $timesheet_table.project_id IN(SELECT $projects_table.id FROM $projects_table WHERE $projects_table.client_id=$client_id)";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND $timesheet_table.user_id IN($allowed_members)";
        }


        //group by
        $group_by_option = "$timesheet_table.user_id, $timesheet_table.task_id, $timesheet_table.project_id";
        $group_by = get_array_value($options, "group_by");

        if ($group_by === "member") {
            $group_by_option = "$timesheet_table.user_id";
        } else if ($group_by === "task") {
            $group_by_option = "$timesheet_table.task_id";
        } else if ($group_by === "project") {
            $group_by_option = "$timesheet_table.project_id";
        }



        $sql = "SELECT new_summary_table.user_id, new_summary_table.total_duration, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS logged_by_user, $users_table.image as logged_by_avatar,
                       $tasks_table.id AS task_id,  $tasks_table.title AS task_title,  $projects_table.id AS project_id,  $projects_table.title AS project_title
                FROM (SELECT MAX($timesheet_table.project_id) AS project_id, MAX($timesheet_table.user_id) AS user_id, MAX($timesheet_table.task_id) AS task_id, SUM(TIMESTAMPDIFF(SECOND, $timesheet_table.start_time, $timesheet_table.end_time)) AS total_duration
                        FROM $timesheet_table
                        WHERE $timesheet_table.deleted=0 $where 
                        GROUP BY $group_by_option) AS new_summary_table
                LEFT JOIN $users_table ON $users_table.id= new_summary_table.user_id
                LEFT JOIN $tasks_table ON $tasks_table.id= new_summary_table.task_id
                LEFT JOIN $projects_table ON $projects_table.id= new_summary_table.project_id            
                ";
        return $this->db->query($sql);
    }

    function get_timer_info($project_id, $user_id) {
        return parent::get_all_where(array("project_id" => $project_id, "user_id" => $user_id, "status" => "open", "deleted" => 0));
    }

    function process_timer($data) {
        $status = get_array_value($data, "status"); //user wants to set this status
        $project_id = get_array_value($data, "project_id");
        $user_id = get_array_value($data, "user_id");
        $note = get_array_value($data, "note");
        $task_id = get_array_value($data, "task_id");

        //check if timer record already exists
        $where = array("project_id" => $project_id, "user_id" => $user_id, "status" => "open", "deleted" => 0);
        $timer_info = parent::get_one_where($where);

        //$now = get_current_utc_time();
        $usertime_model_info =$this->Users_model->get_one($user_id);
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone($usertime_model_info->user_timezone));
        $now = $d->format("Y-m-d H:i:s");

        if ($status === "start" && !$timer_info->id) {
            //no record found, create a new record 
            $timer_data = array(
                "project_id" => $project_id,
                "user_id" => $user_id,
                "status" => "open",
                "start_time" => $now
            );
            return parent::save($timer_data);
        } else if ($status === "stop" && $timer_info->id) {
            //timer is running
            //calculate the total time and stop the timer
            $timer_data = array(
                "end_time" => $now,
                "status" => "logged",
                "note" => $note,
                "task_id" => $task_id,
            );
            return parent::save($timer_data, $timer_info->id);
        }
    }

    function get_open_timers($user_id = 0) {
        $timesheet_table = $this->db->dbprefix('project_time');
        $projects_table = $this->db->dbprefix('projects');

        $sql = "SELECT $timesheet_table.*, $projects_table.title AS project_title
        FROM $timesheet_table
        LEFT JOIN $projects_table ON $projects_table.id= $timesheet_table.project_id
        WHERE $timesheet_table.deleted=0 AND $timesheet_table.user_id=$user_id AND $timesheet_table.status='open'";
        return $this->db->query($sql);
    }

    function get_timesheet_statistics($options = array()) {
        $timesheet_table = $this->db->dbprefix('project_time');

        $where = "";
        //$offset = convert_seconds_to_time_format(get_timezone_offset());
        $offset = convert_seconds_to_time_format();
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($timesheet_table.start_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($timesheet_table.start_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $timesheet_table.user_id=$user_id";
        }

        //ignor sql mode here 
        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT DATE_FORMAT($timesheet_table.start_time,'%d') AS day, SUM(TIME_TO_SEC(TIMEDIFF($timesheet_table.end_time,$timesheet_table.start_time))) total_sec
                FROM $timesheet_table 
                WHERE $timesheet_table.deleted=0 AND $timesheet_table.status='logged' $where
                GROUP BY DATE($timesheet_table.start_time)";
        return $this->db->query($sql);
    }

    function get_co_details($options = array()) {
        $attendnace_table = $this->db->dbprefix('project_time');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $where .= " AND $attendnace_table.status = 'open'";

        $sql = "SELECT $attendnace_table.*,  CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar, $users_table.id as user_id, $users_table.job_title as user_job_title, $users_table.user_type as user_user_type,$users_table.user_timezone as user_timezone,
        $users_table.email as user_emails ,$users_table.phone as user_phone
        FROM $attendnace_table
        LEFT JOIN $users_table ON $users_table.id = $attendnace_table.user_id
        WHERE $attendnace_table.deleted=0 $where
        ORDER BY $attendnace_table.start_time ASC";
        return $this->db->query($sql);
    }

    function update_clock_out($id){
        $note= "Your did't Stop Timer";
        $now = date("Y-m-d");

        $data = array(
               'note' => $note,
               'end_time' => $now,
               'start_time' => $now,
               'status'=> 'logged'
               //'date' => $date
            );
        
        $this->db->where('id',$id);
        $this->db->update('project_time',$data);
        }



        // project OT handler
 function get_project_ot_summary_details($options = array()) {
        $attendnace_table = $this->db->dbprefix('project_time');
        $users_table = $this->db->dbprefix('users');
        //$userss_table = $this->db->dbprefix('attendance_to_do');

        $where = "";
        //$offset = convert_seconds_to_time_format(get_timezone_offset());
         $offset = convert_seconds_to_time_format();

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.start_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.end_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $attendnace_table.user_id=$user_id";
        }

        /*$access_type = get_array_value($options, "access_type");

        if ($access_type !== "all") {

            $allowed_members = get_array_value($options, "allowed_members");
            if (is_array($allowed_members) && count($allowed_members)) {
                $allowed_members = join(",", $allowed_members);
            } else {
                $allowed_members = '0';
            }
            $login_user_id = get_array_value($options, "login_user_id");
            if ($login_user_id) {
                $allowed_members .= "," . $login_user_id;
            }
            $where .= " AND $attendnace_table.user_id IN($allowed_members)";
        }
*/


        //we'll show the details deport in summary_detials view         
        $extra_inner_select = "";
        $extra_group_by = "";
        $extra_select = "";
        $sort_by = "";
        if (get_array_value($options, "summary_details")) {
            $extra_select = ", start_date ";
            $extra_inner_select = ", MAX(DATE(ADDTIME($attendnace_table.start_time,'$offset'))) AS start_date ";
            $extra_group_by = ", DATE(ADDTIME($attendnace_table.end_time,'$offset')) ";
            $sort_by = "ORDER BY user_id, start_date ASC"; //order by must be with user_id 
        }


        $sql = "SELECT user_id, total_duration, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar $extra_select
                 FROM (SELECT $attendnace_table.user_id, SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.start_time, $attendnace_table.end_time)) AS total_duration  $extra_inner_select
                    FROM $attendnace_table
                    WHERE $attendnace_table.deleted=0 $where 

                    GROUP BY $attendnace_table.user_id $extra_group_by) AS new_summary_table 
                LEFT JOIN $users_table ON $users_table.id = new_summary_table.user_id
                

                $sort_by    
               ";

        return $this->db->query($sql);
    }


function project_ot_handler_get_details($options = array()) {
        $todo_table = $this->db->dbprefix('project_time');

        $where = "";
       $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $todo_table.id=$id";
        } 

       
     $start_date=get_array_value($options, "start_date");
   if ($start_date) {
            $where .= " AND DATE($todo_table.start_time) = '$start_date' ";
        }      

     $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $todo_table.user_id=$user_id";
        }  

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $todo_table.project_id=$project_id";
        }
        
        $sql = "SELECT $todo_table.* 
        FROM $todo_table
        WHERE $todo_table.deleted=0   $where";
        return $this->db->query($sql);
    }

   // end projects timesheets ot hander

     //inside project timesheets ot 
      // project OT handler
 function get_timesheets_project_ot_summary_details($options = array()) {
        $attendnace_table = $this->db->dbprefix('project_time');
        $users_table = $this->db->dbprefix('users');
        //$userss_table = $this->db->dbprefix('attendance_to_do');

        $where = "";
        //$offset = convert_seconds_to_time_format(get_timezone_offset());
         $offset = convert_seconds_to_time_format();

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.start_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.end_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $attendnace_table.user_id=$user_id";
        }

         $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $attendnace_table.project_id=$project_id";
        }

        /*$access_type = get_array_value($options, "access_type");

        if ($access_type !== "all") {

            $allowed_members = get_array_value($options, "allowed_members");
            if (is_array($allowed_members) && count($allowed_members)) {
                $allowed_members = join(",", $allowed_members);
            } else {
                $allowed_members = '0';
            }
            $login_user_id = get_array_value($options, "login_user_id");
            if ($login_user_id) {
                $allowed_members .= "," . $login_user_id;
            }
            $where .= " AND $attendnace_table.user_id IN($allowed_members)";
        }
*/


        //we'll show the details deport in summary_detials view         
        $extra_inner_select = "";
        $extra_group_by = "";
        $extra_select = "";
        $sort_by = "";
        if (get_array_value($options, "summary_details")) {
            $extra_select = ", start_date ";
            $extra_inner_select = ", MAX(DATE(ADDTIME($attendnace_table.start_time,'$offset'))) AS start_date ";
            $extra_group_by = ", DATE(ADDTIME($attendnace_table.end_time,'$offset')) ";
            $sort_by = "ORDER BY user_id, start_date ASC"; //order by must be with user_id 
        }


        $sql = "SELECT user_id, total_duration,project_id, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar $extra_select
                 FROM (SELECT $attendnace_table.user_id,$attendnace_table.project_id, SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.start_time, $attendnace_table.end_time)) AS total_duration  $extra_inner_select
                    FROM $attendnace_table
                    WHERE $attendnace_table.deleted=0 $where 

                    GROUP BY $attendnace_table.user_id $extra_group_by) AS new_summary_table 
                LEFT JOIN $users_table ON $users_table.id = new_summary_table.user_id
                

                $sort_by    
               ";

        return $this->db->query($sql);
    }
    //end od inside timesheets ot handler

}
