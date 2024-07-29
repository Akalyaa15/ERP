<?php

class Department_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'department';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $department_table = $this->db->dbprefix('department');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $department_table.id=$id";
        }
$department_code = get_array_value($options, "department_code");
        if ($department_code) {
            $where = " AND $department_table.department_code=$department_code";
        }
        $sql = "SELECT $department_table.*
        FROM $department_table
        WHERE $department_table.deleted=0 $where";
        return $this->db->query($sql);
    }
function is_department_exists($department_code) {
        $result = $this->get_all_where(array("department_code" => $department_code, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
    function is_department_name_exists($department_name) {
        $result = $this->get_all_where(array("title" => $department_name, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
}
