<?php

class Student_desk_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'student_desk';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $student_desk_table = $this->db->dbprefix('student_desk');
        $vap_category_table = $this->db->dbprefix('vap_category');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $student_desk_table.id=$id";
        }

         $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($student_desk_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $student_desk_table.*,$vap_category_table.title AS vap_category_title
        FROM $student_desk_table
        LEFT JOIN $vap_category_table ON $vap_category_table.id= $student_desk_table.vap_category
        WHERE $student_desk_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_student_desk_email_exists($email, $id = 0) {
        $result = $this->get_all_where(array("email" => $email, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }
    


}
