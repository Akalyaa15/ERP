<?php

class Payslip_deductions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslip_deductions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payslip_deductions_table = $this->db->dbprefix('payslip_deductions');
        $payslip_table = $this->db->dbprefix('payslip');
        
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $payslip_deductions_table.id=$id";
        }
        $payslip_id = get_array_value($options, "payslip_id");
        if ($payslip_id) {
            $where .= " AND $payslip_deductions_table.payslip_id=$payslip_id";
        }

        $sql = "SELECT $payslip_deductions_table.*
        FROM $payslip_deductions_table
        LEFT JOIN $payslip_table ON $payslip_table.id=$payslip_deductions_table.payslip_id
        WHERE $payslip_deductions_table.deleted=0 $where";
        return $this->db->query($sql);  
    }

  }