<?php

class Designation_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'designation';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $designation_table = $this->db->dbprefix('designation');
        $department_table = $this->db->dbprefix('department');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $designation_table.id=$id";
        }

        $designation_code = get_array_value($options, "designation_code");
        if ($designation_code) {
            $where .= " AND $designation_table.designation_code='$designation_code'";
        }

        $department_code = get_array_value($options, "department_code");
        if ($department_code) {
            $where .= " AND $designation_table.department_code='$department_code'";
        }

        $sql = "SELECT $designation_table.*, $department_table.title as department_title
        FROM $designation_table
        LEFT JOIN $department_table ON $department_table.department_code = $designation_table.department_code 
        WHERE $designation_table.deleted=0 AND $department_table.deleted =0 $where";
        return $this->db->query($sql);
    }
function is_designation_exists($department_code, $designation_code = 0) {
        $result = $this->get_all_where(array("department_code" => $department_code,"designation_code" => $designation_code, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
    function get_designation_details($dep_code = "") {

        $items_table = $this->db->dbprefix('designation');
        $department_table = $this->db->dbprefix('department');


        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.department_code = '$dep_code'
        ORDER BY id 
        ";
        return $this->db->query($sql)->result();

        

    }
}
