<?php

class Kyc_info_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'kyc_info';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $kyc_info_table = $this->db->dbprefix('kyc_info');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $kyc_info_table.id=$id";
        }

         $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where = " AND $kyc_info_table.user_id=$user_id";
        }

        $sql = "SELECT $kyc_info_table.*
        FROM $kyc_info_table
        WHERE $kyc_info_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
