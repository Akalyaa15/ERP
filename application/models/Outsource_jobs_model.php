<?php

class Outsource_jobs_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'outsource_jobs';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $outsource_jobs_table = $this->db->dbprefix('outsource_jobs');
        //$taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $outsource_jobs_table.id=$id";
        }

        $sql = "SELECT $outsource_jobs_table.*
        FROM $outsource_jobs_table
        
        WHERE $outsource_jobs_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_outsource_job_exists($title, $id = 0) {
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    }

}
