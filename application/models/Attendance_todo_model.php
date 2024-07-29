<?php
class Attendance_todo_model extends Crud_model {
private $table = null;
function __construct() {
        $this->table = 'attendance_to_do';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $todo_table = $this->db->dbprefix('attendance_to_do');

        $where = "";
       $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $todo_table.id=$id";
        }

        $todo_id = get_array_value($options, "todo_id");
        if ($todo_id) {
            $where .= " AND $todo_table.todo_id=$todo_id";
        }
 $start_date=get_array_value($options, "start_date");
   if ($start_date) {
            $where .= " AND $todo_table.start_date= '$start_date' ";
        }

     $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $todo_table.user_id=$user_id";
        }
if ($user_id&&$start_date) {
            $where .= " AND $todo_table.user_id=$user_id";
        } 

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND FIND_IN_SET($todo_table.status,'$status')";
        } 

         $task_id = get_array_value($options, "task_id");
        if ($task_id) {
            $where .= " AND $todo_table.task_id = $task_id";
        }


        $sql = "SELECT $todo_table.*
        FROM $todo_table
        WHERE $todo_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_status_info_suggestion($item_name = "") {

        $product_id_generation_table = $this->db->dbprefix('attendance_to_do');

        $sql = "SELECT $product_id_generation_table.*
        FROM $product_id_generation_table
        WHERE $product_id_generation_table.deleted=0  AND $product_id_generation_table.todo_id ='$item_name' AND $product_id_generation_table.status ='to_do'
        ORDER BY id 
        ";
        
        return $this->db->query($sql)->result(); 

      /*  if ($result->num_rows()) {
            return $result->row();
        } */

    }

}
