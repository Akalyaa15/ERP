<?php
class Excel_import_model extends Crud_model
{
	private $table = null;

     function __construct() {
        $this->table = 'bank_statement';
        parent::__construct($this->table);
    }

	function get_details($options = array()) {
        $bank_statement_table = $this->db->dbprefix('bank_statement');
        $where = "";
        $id = get_array_value($options, "id");
        $ValueName = get_array_value($options, "ValueName");
        $PostDate = get_array_value($options, "PostDate");
        $TransactionId = get_array_value($options, "TransactionId");
        $RemitterBranch = get_array_value($options, "RemitterBranch");
        $Description = get_array_value($options, "Description");
        $ChequeNo = get_array_value($options, "ChequeNo");
        $CreditAmount = get_array_value($options, "CreditAmount");
        $DebitAmount = get_array_value($options, "DebitAmount");
        $Balance = get_array_value($options, "Balance");
        $account_number = get_array_value($options, "account_number");
        $BankName = get_array_value($options, "BankName");

        if ($id) {
            $where .= " AND $bank_statement_table.id=$id";
        }
        if ($ValueName) {
            $where .= " AND $bank_statement_table.ValueName='$ValueName'";
        }
        if ($PostDate) {
            $where .= " AND $bank_statement_table.PostDate='$PostDate'";
        }
        if ($TransactionId) {
            $where .= " AND $bank_statement_table.TransactionId='$TransactionId'";
        }
        if ($RemitterBranch) {
            $where .= " AND $bank_statement_table.RemitterBranch='$RemitterBranch'";
        }  
        if ($Description) {
            $where .= " AND $bank_statement_table.Description='$Description'";
        }
        if ($ChequeNo) {
            $where .= " AND $bank_statement_table.ChequeNo='$ChequeNo'";
        }
        if ($CreditAmount) {
            $where .= " AND $bank_statement_table.CreditAmount='$CreditAmount'";
        }
        if ($DebitAmount) {
            $where .= " AND $bank_statement_table.DebitAmount='$DebitAmount'";
        }
        if ($Balance) {
            $where .= " AND $bank_statement_table.Balance='$Balance'";
        }

        if ($BankName) {
            $where .= " AND $bank_statement_table.BankName=$BankName";
        }
        if ($account_number) {
            $where .= " AND $bank_statement_table.account_number='$account_number'";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($bank_statement_table.ValueName BETWEEN '$start_date' AND '$end_date') ";
        } 

        $sql = "SELECT $bank_statement_table.*
        FROM $bank_statement_table
        WHERE $bank_statement_table.deleted=0 $where";
        return $this->db->query($sql);
    }

	function select()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('bank_statement');
		return $query;
	}

	function insert($data)
	{
		$this->db->insert_batch('bank_statement', $data);
	}
}
