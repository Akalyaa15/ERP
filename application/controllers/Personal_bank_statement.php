<?php  
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_bank_statement extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('personal_bank_statement_model');
		$this->load->library('excel');
	}

	
    function yearly() {
        $this->load->view("personal_bank_statement/yearly_bank_statement");
    }

    //load custom expenses list
    function custom() {
        $this->load->view("personal_bank_statement/custom_bank_statement");
    }
    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $view_data['model_info'] = $this->Personal_bank_statement_model->get_one($this->input->post('id'));
        $this->load->view('personal_bank_statement/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric"
            
        ));

        $id = $this->input->post('id');
        $data = array(
            "remark" => $this->input->post('remark')
            
        );
        $save_id = $this->Personal_bank_statement_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    function list_data() {
$user_id=$this->input->post("user_id");
if($user_id){
	$options = array(
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "user_id" => $this->input->post("user_id")
        );
}else{
	$options = array(
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),

        );
}
        
        $list_data = $this->Personal_bank_statement_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }
    
    private function _row_data($id) {
        
        $options = array("id" => $id);
        $data = $this->Personal_bank_statement_model->get_details($options)->row();
        return $this->_make_item_row($data);
    }

    /* prepare a row of import file list  list table */

    private function _make_item_row($data) {

        return array(
        	$data->BankName,
            $data->ValueName,
            $data->PostDate,
            nl2br($data->RemitterBranch),
            nl2br($data->Description),
            $data->ChequeNo,
            $data->TransactionId,
            $data->DebitAmount,
            $data->CreditAmount,
            $data->Balance,
            $data->remark,
            modal_anchor(get_uri("personal_bank_statement/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_remark'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("personal_bank_statement/delete"), "data-action" => "delete"))
        );
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Personal_bank_statement_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Personal_bank_statement_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }


	
	
	function import()
	{
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=21; $row<=($highestRow-4); $row++)
				{
					$customer_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$address = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$city = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$postal_code = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$country = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$countrya = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$countrys = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$countryd = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

					$origDate = $customer_name;
 
$date = str_replace('/', '-', $origDate );
$newDate = date("Y-m-d", strtotime($date));
					$data[] = array(
						'BankName'		=>'Indian Bank',
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'Description'		=>	$postal_code,
						'ChequeNo'			=>	$country,
						
						'DebitAmount'		=>	$countrya,
						
						'CreditAmount'		=>	$countrys,
						
						'Balance'		=>	$countryd,
						'user_id'=>$this->input->post("user_id")
					);
				}
				$bn = $worksheet->getCellByColumnAndRow(3,1)->getValue();
			}
			if($bn=='INDIAN BANK'){
			$this->personal_bank_statement_model->insert($data);
echo json_encode(array("success" => true));}
		}	
	}

	function import_icici()
	{
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=8; $row<=($highestRow); $row++)
				{
                    $transaction_id = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

					$customer_name = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$address = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$city = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$postal_code = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$country = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$countrya = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$countrys = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$countryd = $worksheet->getCellByColumnAndRow(8, $row)->getValue();

					$origDate = $customer_name;
 
$date = str_replace('/', '-', $origDate );
$newDate = date("Y-m-d", strtotime($date));

if($countrya=='DR'){
					$data[] = array(
                        'BankName'=> 'ICICI Bank',
						'TransactionId'		=>	$transaction_id,
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'Description'		=>	$postal_code,
						'ChequeNo'			=>	$country,
						
						'DebitAmount'		=>	$countrys,
						'CreditAmount'		=>	0,
						'Balance'		=>	$countryd,
						'user_id'=>$this->input->post("user_id")
					);
				}else if($countrya=='CR'){

$data[] = array(

	                    'BankName'=> 'ICICI Bank',
						'TransactionId'		=>	$transaction_id,
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'Description'		=>	$postal_code,
						'ChequeNo'			=>	$country,
						
						'CreditAmount'		=>	$countrys,
						
						'DebitAmount'		=>0,
						
						'Balance'		=>	$countryd,
						'user_id'=>$this->input->post("user_id")
					);
				}
				}
			$bn = $worksheet->getCellByColumnAndRow(0,1)->getValue();
			}
			if($bn=='DETAILED STATEMENT'){
			$this->personal_bank_statement_model->insert($data);
echo json_encode(array("success" => true));}
		}	
	}
	function import_hdfc()
	{
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=23; $row<=($highestRow-18); $row++)
				{
					$p_date = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$desc = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$cheque = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$value_date = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$debit = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$credit = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$balance = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					
					$origDate = $value_date;
 
 $d = explode("/",$origDate); $newDate = date('Y-m-d', strtotime($d[2]."-".$d[1]."-".$d[0]));
					$data[] = array(
						'BankName'		=>'HDFC Bank',
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$p_date,
						'Description'		=>	$desc,
						'ChequeNo'			=>	$cheque,
						
						'DebitAmount'		=>	$debit,
						
						'CreditAmount'		=>	$credit,
						
						'Balance'		=>	$balance,
						'user_id'=>$this->input->post("user_id")
					);
				}
			$bn = $worksheet->getCellByColumnAndRow(1,21)->getValue();
			}
			if($bn=='Narration'){
			$this->personal_bank_statement_model->insert($data);
echo json_encode(array("success" => true));}
		}	
	}

}

?>