<?php

class Groups_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'groups';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $team_table = $this->db->dbprefix('groups');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND `$team_table`.id=$id"; // Add backticks around table name and column name
        }
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND FIND_IN_SET('$user_id', `$team_table`.members) "; // Add backticks around table name
        }
    
        $sql = "SELECT `$team_table`.*
                FROM `$team_table`
                WHERE `$team_table`.deleted=0 $where";
    
        return $this->db->query($sql);
    }
    
    function get_members($team_ids = array()) {
        $team_table = $this->db->dbprefix('team');
        $team_ids = implode(",", $team_ids);

        $sql = "SELECT $team_table.members
        FROM $team_table
        WHERE $team_table.deleted=0 AND id in($team_ids)";
        return $this->db->query($sql);
    }

    function is_group_title_exists($title, $id = 0) {
        $result = $this->get_all_where(array("title" => $title, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id ) {
            return $result->row();
        } else {
            return false;
        }
    } 

}
