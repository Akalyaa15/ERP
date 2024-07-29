<?php

class Voucher_comments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'voucher_comments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $voucher_comments_table = $this->db->dbprefix('voucher_comments');
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $sort= "ASC";
        
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $voucher_comments_table.id=$id";
        }

        $ticket_id = get_array_value($options, "voucher_id");
        if ($ticket_id) {
            $where .= " AND $voucher_comments_table.voucher_id=$ticket_id";
        }
        
        $sort_decending = get_array_value($options, "sort_as_decending");
        if ($sort_decending) {
            $sort = "DESC";
        }



        $sql = "SELECT $voucher_comments_table.*, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar, $users_table.user_type
        FROM $voucher_comments_table
        LEFT JOIN $users_table ON $users_table.id= $voucher_comments_table.created_by
        WHERE $voucher_comments_table.deleted=0 $where
        ORDER BY $voucher_comments_table.created_at $sort";

        return $this->db->query($sql);
    }

}
