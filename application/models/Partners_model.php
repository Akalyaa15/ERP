<?php

class Partners_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'partners';
        parent::__construct($this->table);
    }
    public function getDetails($options = []) {
        // Retrieve table prefix
        $prefix = $this->db->dbprefix;
        
        // Use the prefix to construct table names
        $clientsTable = $prefix . 'partners';
        $projectsTable = $prefix . 'projects';
        $usersTable = $prefix . 'users';
        $invoicesTable = $prefix . 'invoices';
        $invoicePaymentsTable = $prefix . 'invoice_payments';
        $invoiceItemsTable = $prefix . 'invoice_items';
        $clientGroupsTable = $prefix . 'partner_groups';
        
        $where = [];
        $id = $options['id'] ?? null;
        if ($id) {
            $where[] = "$clientsTable.client_id = $id";
        }
        
        $groupId = $options['group_id'] ?? null;
        if ($groupId) {
            $where[] = "FIND_IN_SET('$groupId', $clientsTable.group_ids)";
        }
        
        // Prepare custom field binding query
        $customFields = $options['custom_fields'] ?? [];
        $customFieldQueryInfo = $this->prepareCustomFieldQueryString('partners', $customFields, $clientsTable);
        $selectCustomFields = $customFieldQueryInfo['select_string'] ?? '';
        $joinCustomFields = $customFieldQueryInfo['join_string'] ?? '';
        
        $freightAmount = "IFNULL($invoicesTable.freight_amount, 0)";
        
        $invoiceValueCalculationQuery = "ROUND(
            SUM(IFNULL(items_table.invoice_value, 0) + $freightAmount)
        )";
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
        
        // Corrected SQL query
        $sql = "SELECT $clientsTable.*, 
                       CONCAT($usersTable.first_name, ' ', $usersTable.last_name) AS primary_contact, 
                       $usersTable.id AS primary_contact_id, 
                       $usersTable.image AS contact_avatar,  
                       project_table.total_projects, 
                       IFNULL(invoice_details.invoice_value, 0) AS invoice_value, 
                       IFNULL(invoice_details.payment_received, 0) AS payment_received 
                       $selectCustomFields,
                       (SELECT GROUP_CONCAT($clientGroupsTable.title) 
                        FROM $clientGroupsTable 
                        WHERE FIND_IN_SET($clientGroupsTable.id, $clientsTable.group_ids)) AS `groups`
                FROM $clientsTable
                LEFT JOIN $usersTable 
                    ON $usersTable.client_id = $clientsTable.client_id 
                    AND $usersTable.deleted = 0 
                    AND $usersTable.is_primary_contact = 1 
                LEFT JOIN (
                    SELECT client_id, COUNT(id) AS total_projects 
                    FROM $projectsTable 
                    WHERE deleted = 0 
                    GROUP BY client_id
                ) AS project_table 
                    ON project_table.client_id = $clientsTable.client_id
                LEFT JOIN (
                    SELECT client_id, 
                           SUM(payments_table.payment_received) AS payment_received, 
                           $invoiceValueCalculationQuery AS invoice_value 
                    FROM $invoicesTable
                    LEFT JOIN (
                        SELECT invoice_id, SUM(amount) AS payment_received 
                        FROM $invoicePaymentsTable 
                        WHERE deleted = 0 
                        GROUP BY invoice_id
                    ) AS payments_table 
                        ON payments_table.invoice_id = $invoicesTable.id 
                        AND $invoicesTable.deleted = 0 
                        AND $invoicesTable.status = 'not_paid'
                    LEFT JOIN (
                        SELECT invoice_id, SUM(net_total) AS invoice_value 
                        FROM $invoiceItemsTable 
                        WHERE deleted = 0 
                        GROUP BY invoice_id
                    ) AS items_table 
                        ON items_table.invoice_id = $invoicesTable.id 
                        AND $invoicesTable.deleted = 0 
                        AND $invoicesTable.status = 'not_paid'
                    GROUP BY $invoicesTable.client_id    
                ) AS invoice_details 
                    ON invoice_details.client_id = $clientsTable.client_id
                $joinCustomFields               
                WHERE $clientsTable.deleted = 0";
        
        if (!empty($where)) {
            $sql .= ' AND ' . implode(' AND ', $where);
        }
        
        return $this->db->query($sql);
    }
    

    function get_primary_contact($client_id = 0, $info = false) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.id, $users_table.first_name, $users_table.last_name
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.client_id=$client_id AND $users_table.is_primary_contact=1";
        $result = $this->db->query($sql);
        if ($result->num_rows()) {
            if ($info) {
                return $result->row();
            } else {
                return $result->row()->id;
            }
        }
    }
    public function get_by_id($id) {
        $this->db->select('*'); // Select all columns or specify needed columns
        $this->db->from('partners'); // Replace with your actual table name
        $this->db->where('id', $id);
        
        return $this->db->get()->row(); // Return a single row
    }

    function add_remove_star($project_id, $user_id, $type = "add") {
        $clients_table = $this->db->dbprefix('clients');

        $action = " CONCAT($clients_table.starred_by,',',':$user_id:') ";
        $where = " AND FIND_IN_SET(':$user_id:',$clients_table.starred_by) = 0"; //don't add duplicate

        if ($type != "add") {
            $action = " REPLACE($clients_table.starred_by, ',:$user_id:', '') ";
            $where = "";
        }

        $sql = "UPDATE $clients_table SET $clients_table.starred_by = $action
        WHERE $clients_table.id=$project_id $where";
        return $this->db->query($sql);
    }

    function get_starred_clients($user_id) {
        $clients_table = $this->db->dbprefix('clients');

        $sql = "SELECT $clients_table.id,  $clients_table.company_name
        FROM $clients_table
        WHERE $clients_table.deleted=0 AND FIND_IN_SET(':$user_id:',$clients_table.starred_by)
        ORDER BY $clients_table.company_name ASC";
        return $this->db->query($sql);
    }

    function delete_client_and_sub_items($client_id) {
        $clients_table = $this->db->dbprefix('partners');
        $clients_tabless = $this->db->dbprefix('clients');
        $general_files_table = $this->db->dbprefix('general_files');
        $users_table = $this->db->dbprefix('users');

        // Get client files info to delete the files from directory 
        $client_files_sql = "SELECT * FROM $general_files_table WHERE $general_files_table.deleted=0 AND $general_files_table.client_id=$client_id";
        $client_files = $this->db->query($client_files_sql)->result();

        $client_original = "SELECT id FROM $clients_tabless WHERE $clients_tabless.partner_id= $client_id";
        $client_id_result = $this->db->query($client_original)->result();

        // Delete client and sub items
        $delete_client_sqls = "UPDATE $clients_table SET $clients_table.deleted=1 WHERE $clients_table.id=$client_id";
        $this->db->query($delete_client_sqls);

        if (!empty($client_id_result)) {
            $delete_client_sql = "UPDATE $clients_tabless SET $clients_tabless.deleted=1 WHERE $clients_tabless.partner_id=$client_id";
            $this->db->query($delete_client_sql);
        }

        // Delete contacts
        $delete_contacts_sql = "UPDATE $users_table SET $users_table.deleted=1 WHERE $users_table.client_id=$client_id";
        $this->db->query($delete_contacts_sql);

        // Delete the project files from directory
        $file_path = get_general_file_path("client", $client_id);
        foreach ($client_files as $file) {
            delete_file_from_directory($file_path . "/" . $file->file_name);
        }

        return true;
    }

    function is_duplicate_company_name($company_name, $id = 0) {
        $result = $this->get_all_where(array("company_name" => $company_name, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

    // Excel file get data clients 
    function get_import_detailss($options = array()) {
        $vendors_table = $this->db->dbprefix('partners');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $vendors_table.id=$id";
        }
        $company_name = get_array_value($options, "company_name");
        if ($company_name) {
            $where .= " AND $vendors_table.company_name='$company_name'";
        }
        $city = get_array_value($options, "city");
        if ($city) {
            $where .= " AND $vendors_table.city='$city'";
        }
        $state = get_array_value($options, "state");
        if ($state) {
            $where .= " AND $vendors_table.state='$state'";
        }
        $country = get_array_value($options, "country");
        if ($country) {
            $where .= " AND $vendors_table.country='$country'";
        }
        $website = get_array_value($options, "website");
        if ($website) {
            $where .= " AND $vendors_table.website='$website'";
        }
        $zip = get_array_value($options, "zip");
        if ($zip) {
            $where .= " AND $vendors_table.zip='$zip'";
        }
        $phone = get_array_value($options, "phone");
        if ($phone) {
            $where .= " AND $vendors_table.phone='$phone'";
        }
        $gst_number = get_array_value($options, "gst_number");
        if ($gst_number) {
            $where .= " AND $vendors_table.gst_number='$gst_number'";
        }
        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= " AND $vendors_table.currency='$currency'";
        }
        $currency_symbol = get_array_value($options, "currency_symbol");
        if ($currency_symbol) {
            $where .= " AND $vendors_table.currency_symbol='$currency_symbol'";
        }
        $gstin_number_first_two_digits = get_array_value($options, "gstin_number_first_two_digits");
        if ($gstin_number_first_two_digits) {
            $where .= " AND $vendors_table.gstin_number_first_two_digits='$gstin_number_first_two_digits'";
        }

        $sql = "SELECT $vendors_table.*
                FROM $vendors_table
                WHERE $vendors_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    private function prepareCustomFieldQueryString($tableName, $customFields, $tablePrefix)
    {
        $selectString = '';
        $joinString = '';

        foreach ($customFields as $field) {
            $selectString .= ", $tablePrefix.$field AS $field";
            $joinString .= "LEFT JOIN custom_fields_table AS $field ON $field.table_name = '$tableName' AND $field.field_name = '$field'";
        }

        return [
            'select_string' => $selectString,
            'join_string' => $joinString
        ];
    }
}
