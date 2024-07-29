<?php

 class Announcements_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'announcements';
        parent::__construct($this->table);
    }

    function get_unread_announcements($user_id, $team_id, $user_type) {
        $announcements_table = $this->db->dbprefix('announcements');

        $where = "";
        $now = get_my_local_time("Y-m-d");


        $where .= " AND FIND_IN_SET($user_id,$announcements_table.read_by) = 0";
       /* if ($user_type === "staff") {
            $where .= " AND FIND_IN_SET('all_members',$announcements_table.share_with)";
        } else if ($user_type === "client") {
            $where .= " AND FIND_IN_SET('all_clients',$announcements_table.share_with)";
        }else if ($user_type === "vendor") {
            $where .= " AND FIND_IN_SET('all_vendors',$announcements_table.share_with)";
        } */
        if ($user_type === "staff") {
        $where .= " AND (FIND_IN_SET('all_members',$announcements_table.share_with ) OR (FIND_IN_SET('member:$user_id',$announcements_table.share_with))OR(FIND_IN_SET('team:$team_id', 
            $announcements_table.share_with)))";
        } else if ($user_type === "client") {
            $where .= " AND (FIND_IN_SET('all_clients',$announcements_table.share_with) OR (FIND_IN_SET('contact:$user_id', $announcements_table.share_with)))";
        }else if ($user_type === "vendor") {
            $where .= " AND (FIND_IN_SET('all_vendors',$announcements_table.share_with) OR (FIND_IN_SET('vendor_contact:$user_id', $announcements_table.share_with)))";
        }else if ($user_type === "resource") {
            $where .= " AND (FIND_IN_SET('all_resource',$announcements_table.share_with ) OR (FIND_IN_SET('outsource_member:$user_id',$announcements_table.share_with)))";
        }
        $sql = "SELECT $announcements_table.*
        FROM $announcements_table
        WHERE $announcements_table.deleted=0 AND start_date<='$now' AND end_date>='$now' $where";
        return $this->db->query($sql);
    }

//Marquee announcements widget

    function get_marquee_announcements($user_id, $team_id, $user_type) {
        $announcements_table = $this->db->dbprefix('announcements');

        $where = "";
        $now = get_my_local_time("Y-m-d");


      /*  $where .= " AND FIND_IN_SET($user_id,$announcements_table.read_by) = 0";*/
       
        if ($user_type === "staff") {
        $where .= " AND (FIND_IN_SET('all_members',$announcements_table.share_with ) OR (FIND_IN_SET('member:$user_id',$announcements_table.share_with))OR(FIND_IN_SET('team:$team_id', 
            $announcements_table.share_with)))";
        } else if ($user_type === "client") {
            $where .= " AND (FIND_IN_SET('all_clients',$announcements_table.share_with) OR (FIND_IN_SET('contact:$user_id', $announcements_table.share_with)))";
        }else if ($user_type === "vendor") {
            $where .= " AND (FIND_IN_SET('all_vendors',$announcements_table.share_with) OR (FIND_IN_SET('vendor_contact:$user_id', $announcements_table.share_with)))";
        }else if ($user_type === "resource") {
            $where .= " AND (FIND_IN_SET('all_resource',$announcements_table.share_with ) OR (FIND_IN_SET('outsource_member:$user_id',$announcements_table.share_with)))";
        }
        $sql = "SELECT $announcements_table.*
        FROM $announcements_table
        WHERE $announcements_table.deleted=0 AND end_date>='$now' $where ORDER BY date(end_date) ASC";
        return $this->db->query($sql);
    }




    function get_details($options = array()) {
        $announcements_table = $this->db->dbprefix('announcements');
        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');
        $partners_table = $this->db->dbprefix('partners');
        $vendors_table = $this->db->dbprefix('vendors');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $announcements_table.id=$id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {

            //find events where share with the user and his/her team
            $team_ids = get_array_value($options, "team_ids");
            $team_search_sql = "";

            //searh for teams
            if ($team_ids) {
                $teams_array = explode(",", $team_ids);
                foreach ($teams_array as $team_id) {
                    $team_search_sql .= " OR (FIND_IN_SET('team:$team_id', $announcements_table.share_with)) ";
                }
            }
             $is_partner = get_array_value($options, "is_partner");
            $is_vendor = get_array_value($options, "is_vendor");
            $is_client = get_array_value($options, "is_client");
            $is_resource = get_array_value($options, "is_resource");
            
           if ($is_partner) {
                //client user's can't see the events which has shared with all team members
                $where .= " AND ($announcements_table.created_by=$user_id 
                OR $announcements_table.share_with='all_partners' 
                  OR (FIND_IN_SET('partner_contact:$user_id', $announcements_table.share_with)))";
            } else if ($is_client) {
                //client user's can't see the events which has shared with all team members
                $where .= " AND ($announcements_table.created_by=$user_id 
                OR $announcements_table.share_with='all_clients' OR (FIND_IN_SET('contact:$user_id', $announcements_table.share_with)))";
            }else if ($is_vendor) {
                //client user's can't see the events which has shared with all team members
                $where .= " AND ($announcements_table.created_by=$user_id 
                OR $announcements_table.share_with='all_vendors' 
                  OR (FIND_IN_SET('vendor_contact:$user_id', $announcements_table.share_with)))";
            }else if ($is_resource) {
                //client user's can't see the events which has shared with all team members
                $where .= " AND ($announcements_table.created_by=$user_id 
                OR $announcements_table.share_with='all_resource' 
                  OR (FIND_IN_SET('outsource_member:$user_id', $announcements_table.share_with))
                        $team_search_sql
                        )";
            } else {
                //searh for user and teams
                $where .= " AND ($announcements_table.created_by=$user_id 
                OR $announcements_table.share_with='all_members' 
                    OR (FIND_IN_SET('member:$user_id', $announcements_table.share_with))
                        $team_search_sql
                        )";
            }
        }
$partner_id = get_array_value($options, "partner_id");
        if ($partner_id) {
            $where .= " AND $announcements_table.partner_id=$partner_id";
        }
        
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $announcements_table.client_id=$client_id";
        }

        $vendor_id = get_array_value($options, "vendor_id");
        if ($vendor_id) {
            $where .= " AND $announcements_table.vendor_id=$vendor_id";
        }
        


      /*  $share_with = get_array_value($options, "share_with");
        if ($share_with) {
            $where .= " AND FIND_IN_SET('$share_with', $announcements_table.share_with)";
        } */

        $sql = "SELECT $announcements_table.*, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user, $users_table.image AS created_by_avatar
        FROM $announcements_table
        LEFT JOIN $users_table ON $users_table.id= $announcements_table.created_by
        LEFT JOIN $partners_table ON $partners_table.id = $announcements_table.partner_id
         LEFT JOIN $clients_table ON $clients_table.id = $announcements_table.client_id
         LEFT JOIN $vendors_table ON $vendors_table.id = $announcements_table.vendor_id 
        WHERE $announcements_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function mark_as_read($id, $user_id) {
        $announcements_table = $this->db->dbprefix('announcements');
        $sql = "UPDATE $announcements_table SET $announcements_table.read_by = CONCAT($announcements_table.read_by,',',$user_id)
        WHERE $announcements_table.id=$id AND FIND_IN_SET($user_id,$announcements_table.read_by) = 0";
        return $this->db->query($sql);
    }

    function get_response_by_users($user_ids_array) {
        $users_table = $this->db->dbprefix('users');
        $user_ids = implode(",", $user_ids_array);

        if ($user_ids) {
            $sql = "SELECT $users_table.id,  $users_table.user_type, $users_table.image, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name FROM $users_table WHERE (FIND_IN_SET($users_table.id, '$user_ids')) AND deleted=0";

            return $this->db->query($sql);
        } else {
            return false;
        }
    }

}
