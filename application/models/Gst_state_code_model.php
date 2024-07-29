<?php

class Gst_state_code_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'gst_state_code';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $gst_table = $this->db->dbprefix('gst_state_code');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $gst_table.id=$id";
        }

        $sql = "SELECT $gst_table.*
        FROM $gst_table
        WHERE $gst_table.deleted=0 $where";
        return $this->db->query($sql);
    }

  /*  function get_item_suggestions_gst_state($gstin_number_first_two_digits = "") 
     {
     
      $country_table = $this->db->dbprefix('gst_state_code');        
      //$states_table = $this->db->dbprefix('states');

        $sql = "SELECT $country_table.gstin_number_first_two_digits,$country_table.title
        FROM $country_table
        
        WHERE $country_table.deleted=0  AND $country_table.gstin_number_first_two_digits ='$gstin_number_first_two_digits'
         LIMIT 1000 
        ";

        return $this->db->query($sql)->result();
     } */

}
