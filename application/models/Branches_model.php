<?php

class Branches_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'branches';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $branches_table = $this->db->dbprefix('branches');
        $clients_table = $this->db->dbprefix('companys');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $branches_table.id=$id";
        }

        $branch_code = get_array_value($options, "branch_code");
        if ($branch_code) {
            $where = " AND $branches_table.branch_code='$branch_code'";
        }

        $company_name = get_array_value($options, "company_name");
        if ($company_name) {
            $where .= " AND $branches_table.company_name='$company_name'";
        }

        $buid = get_array_value($options, "buid");
        if ($buid) {
            $where = " AND $branches_table.buid='$buid'";
        }

        $sql = "SELECT $branches_table.*,$clients_table.company_name as company
        FROM $branches_table
        Left join $clients_table on $clients_table.cr_id=$branches_table.company_name 
        WHERE $branches_table.deleted=0 $where";
        return $this->db->query($sql);
    }
function is_branch_exists($branch_code) {
        $result = $this->get_all_where(array("branch_code" => $branch_code, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }
function branch_count($branch_code) {
        $result = $this->get_all_where(array("company_name" => $branch_code));
        if ($result->num_rows()) {
            return $result->num_rows();
        } else {
            return false;
        }
    }    function is_branch_name_exists($branch_name,$company_name) {
        $result = $this->get_all_where(array("title" => $branch_name,"company_name" => $company_name, "deleted" => 0));
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }


    function get_item_suggestions_country_name($keyword = "",$keywords = "") {
        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('states');
        

        $sql = "SELECT $states_table.title,$states_table.id
        FROM $items_table
        LEFT JOIN $states_table ON $states_table.country_code= $items_table.numberCode  
        /*WHERE $items_table.deleted=0  AND $items_table.countryName = '$keywords' AND $states_table.title like '%$keyword%'*/
        WHERE $items_table.deleted=0  AND $items_table.numberCode = '$keywords' AND $states_table.title like '%$keyword%' AND $states_table.deleted=0
        LIMIT 500 
        ";
        return $this->db->query($sql)->result();
     }


     function get_item_suggestions_branch_name($keyword = "",$keywords = "") {
        $items_table = $this->db->dbprefix('country');
        $states_table = $this->db->dbprefix('branches');
        

        $sql = "SELECT $states_table.title,$states_table.id,$states_table.branch_code,$states_table.buid,$states_table.company_name
        FROM $items_table
        LEFT JOIN $states_table ON $states_table.company_setup_country= $items_table.numberCode  
       
        WHERE $items_table.deleted=0  AND $items_table.numberCode = '$keywords' AND $states_table.title like '%$keyword%' AND $states_table.deleted=0
        LIMIT 500 
        ";
        return $this->db->query($sql)->result();
     }

     function get_company_item_suggestions_branch_name($keyword = "") {
        $states_table = $this->db->dbprefix('branches');
        

        $sql = "SELECT $states_table.title,$states_table.id,$states_table.branch_code
        FROM $states_table
        WHERE $states_table.company_name = '$keyword'  AND $states_table.deleted=0
        LIMIT 500 
        ";
        return $this->db->query($sql)->result();
     }





}
