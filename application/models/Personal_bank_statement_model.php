<?php
class Personal_bank_statement_model extends Crud_model
{
	private $table = null;

     function __construct() {
        $this->table = 'personal_bank_statement';
        parent::__construct($this->table);
    }

	function get_details($options = array()) {
        $bank_statement_table = $this->db->dbprefix('personal_bank_statement');
        $where = "";
        $id = get_array_value($options, "id");
         $user_id = get_array_value($options, "user_id");
        if ($id) {
            $where .= " AND $bank_statement_table.id=$id";
        }
        if ($user_id) {
            $where .= " AND $bank_statement_table.user_id=$user_id";
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
		$query = $this->db->get('personal_bank_statement');
		return $query;
	}

	function insert($data)
	{
		$this->db->insert_batch('personal_bank_statement', $data);
	}
}
