<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Excel_import extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('excel_import_model');
		$this->init_permission_checker("company_bank_statement");

		$this->load->library('excel');
		// $this->access_only_allowed_members();
	}

	//get categories dropdown
    private function _get_bank_dropdown() {
        $categories = $this->Bank_name_model->get_all_where(array("deleted" => 0 ,"status" => "active"), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("bank_list") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

	function index() {
		$this->check_module_availability("module_company_bank_statement");
		$view_data['bank_dropdown'] = $this->_get_bank_dropdown();
		$view_data['bank_list_dropdown'] = array("" => "-") + $this->Bank_name_model->get_dropdown_list(array("title"),"id",array("status" => "active"));
		/*$view_data['bank_list_dropdown'] = array("" => "-") + $this->Bank_name_model->get_dropdown_list(array("title"));*/
//$this->template->rander("excel/excel_import",$view_data);
        if ($this->login_user->is_admin == "1")
        { 

            $this->template->rander("excel/excel_import",$view_data);
        }
        else if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
            $this->template->rander("excel/excel_import",$view_data);
        }else {


        $this->template->rander("excel/excel_import",$view_data);
    } 
    }

    //Import excel ,csv modal form  for vendors 
function excel_import_form() {
	$view_data['bank_list_dropdown'] = array("" => "-") + $this->Bank_name_model->get_dropdown_list(array("title"),"id",array("status" => "active"));

        $this->load->view('excel/excel_import_form',$view_data);
    }
    
    function yearly() {
        $this->load->view("excel/yearly_bankstatement");
    }

    //load custom expenses list
    function custom() {
        $this->load->view("excel/custom_bankstatement");
    }
    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $view_data['model_info'] = $this->Excel_import_model->get_one($this->input->post('id'));
        $this->load->view('excel/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric"
            
        ));

        $id = $this->input->post('id');
        $data = array(
            "remark" => $this->input->post('remark'),
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
            
        );
        $save_id = $this->Excel_import_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    function list_data() {

        $options = array(
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "BankName" => $this->input->post('BankName')
        );
        $list_data = $this->Excel_import_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }
    
    private function _row_data($id) {
        
        $options = array("id" => $id);
        $data = $this->Excel_import_model->get_details($options)->row();
        return $this->_make_item_row($data);
    }

    /* prepare a row of import file list  list table */

    private function _make_item_row($data) {
    $bank_name = $this->Bank_name_model->get_one($data->BankName);
    //last activity user name and date start 
         $last_activity_by_user_name= "-";
        if($data->last_activity_user){
        $last_activity_user_data = $this->Users_model->get_one($data->last_activity_user);
        $last_activity_image_url = get_avatar($last_activity_user_data->image);
        $last_activity_user = "<span class='avatar avatar-xs mr10'><img src='$last_activity_image_url' alt='...'></span> $last_activity_user_data->first_name $last_activity_user_data->last_name";
        
        if($last_activity_user_data->user_type=="resource"){
          $last_activity_by_user_name= get_rm_member_profile_link($data->last_activity_user, $last_activity_user );   
        }else if($last_activity_user_data->user_type=="client") {
          $last_activity_by_user_name= get_client_contact_profile_link($data->last_activity_user, $last_activity_user);
        }else if($last_activity_user_data->user_type=="staff"){
             $last_activity_by_user_name= get_team_member_profile_link($data->last_activity_user, $last_activity_user); 
       }else if($last_activity_user_data->user_type=="vendor"){
             $last_activity_by_user_name= get_vendor_contact_profile_link($data->last_activity_user, $last_activity_user); 
        }
       }
      
       $last_activity_date = "-";
       if($data->last_activity){
       $last_activity_date = format_to_relative_time($data->last_activity);
       }
       // end last activity 
        return array(
        	//$data->BankName,
        	$bank_name->title?$bank_name->title:"-",
        	$data->account_number,
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
            $last_activity_by_user_name,
            $last_activity_date,
            modal_anchor(get_uri("excel_import/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_remark'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("excel_import/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        $data = array(
            
            "last_activity_user"=>$this->login_user->id,
            "last_activity" => get_current_utc_time(),
        );
         $save_id = $this->Excel_import_model->save($data, $id);
        if ($this->input->post('undo')) {
            if ($this->Excel_import_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Excel_import_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

// get account number suggestion
function get_account_number_suggestion() {
        $key = $_REQUEST["q"];
        $bank_id=$_REQUEST["bank_id"];
        $itemss =  $this->Bank_name_model->get_item_account_number_suggestions($key,$bank_id);
        //$itemss =  $this->Countries_model->get_item_suggestions_country_name('india');
        $suggestions = array();
      /*foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->account_number, "text" => $items->account_number);
       }*/
       $ss = explode(",",  $itemss->account_number);
       /*if(!$key){
       foreach ($ss as $s) {
            $suggestions[] = array("id" => $s, "text" => $s);

       }
   }else if ($key){
   
   		if ($key && !in_array($key,$ss)) {
   				foreach ($ss as $s) {
            $suggestions[] = array("id" => $s, "text" => $s);

       }}
   }*/

   if(!$key){
       foreach ($ss as $s) {
            $suggestions[] = array("id" => $s, "text" => $s);

       }
   }else if ($key){
   
   				foreach ($ss as $s) {
   					$len=strlen($key);
       $keys = substr($s,0,$len);
   					if(in_array($key, array($keys))){
            $suggestions[] = array("id" => $s, "text" => $s);
}
          }
        }

        
        echo json_encode($suggestions);
    }

	
	function fetch()
	{
		$data = $this->excel_import_model->select();
		$output = '
		<h3 align="center">Total Data - '.$data->num_rows().'</h3>
		<table class="table table-striped table-bordered">
			<tr>
				<th>Customer Name</th>
				<th>Address</th>
				<th>City</th>
				<th>Postal Code</th>
				<th>Country</th>
				<th>City</th>
				<th>Postal Code</th>
				<th>Country</th>
			</tr>
		';
		foreach($data->result() as $row)
		{
			$output .= '
			<tr>
				<td>'.$row->ValueName.'</td>
				<td>'.$row->PostDate.'</td>
				<td>'.$row->RemitterBranch.'</td>
				<td>'.$row->Description.'</td>
				<td>'.$row->DebitAmount.'</td>
				<td>'.$row->ChequeNo.'</td>
				<td>'.$row->CreditAmount.'</td>
				<td>'.$row->Balance.'</td>
			</tr>
			';
		}
		$output .= '</table>';
		echo $output;
	}

	function import()
	{
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				/*Get the Account Number row  */
				$get_accountnorow =	$worksheet->getCellByColumnAndRow(3, 5)->getValue();
				$remove_textaccountrow =str_replace("Account Number :","",$get_accountnorow);
				if($remove_textaccountrow!=$_POST['account_number']){
					 
                 exit();
				}
				/* acoount no  row end */


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
$options = array(

	                    'BankName'		=>$_POST['BankName'],
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'ChequeNo'			=>	$country,
						
						'DebitAmount'		=>	$countrya,
						
						'CreditAmount'		=>	$countrys,
						'account_number'=> $_POST['account_number'],
						
						'Balance'		=>	$countryd
					);
				
$list_data = $this->Excel_import_model->get_details($options)->row();	
if(!$list_data){	
					$data[] = array(
						'BankName'		=>$_POST['BankName'],
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'Description'		=>	$postal_code,
						'ChequeNo'			=>	$country,
						
						'DebitAmount'		=>	$countrya,
						
						'CreditAmount'		=>	$countrys,
						'account_number'=> $_POST['account_number'],
						
						'Balance'		=>	$countryd,
						'last_activity_user'=>$this->login_user->id,
                        'last_activity' => get_current_utc_time(),
					);
				}
			}
		}
			$this->excel_import_model->insert($data);
			echo 'Data Imported successfully';
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


              /*Get the Account Number row  */
				/*$get_accountnorow =	$worksheet->getCellByColumnAndRow(3, 5)->getValue();
				$remove_textaccountrow =str_replace("Account Number :","",$get_accountnorow);
				if($remove_textaccountrow!=$_POST['account_number']){
					 
                 exit();
				}*/
				/* acoount no  row end */

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
					$options = array(
                        'BankName'=> $_POST['BankName'],
						'TransactionId'		=>	$transaction_id,
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'ChequeNo'			=>	$country,
						
						'DebitAmount'		=>	$countrys,
						'CreditAmount'		=>	0,
						
						'account_number'=> $_POST['account_number'],
						
						'Balance'		=>	$countryd
					);
				}else if($countrya=='CR'){

$options = array(

	                    'BankName'=> $_POST['BankName'],
						'TransactionId'		=>	$transaction_id,
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'ChequeNo'			=>	$country,
						
						'CreditAmount'		=>	$countrys,
						
						'DebitAmount'		=>0,

						'account_number'=> $_POST['account_number'],
						
						'Balance'		=>	$countryd
					);
				}

$list_data = $this->Excel_import_model->get_details($options)->row();	
if(!$list_data){			
if($countrya=='DR'){
					$data[] = array(
                        'BankName'=> $_POST['BankName'],
						'TransactionId'		=>	$transaction_id,
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'Description'		=>	$postal_code,
						'ChequeNo'			=>	$country,
						
						'DebitAmount'		=>	$countrys,
						'CreditAmount'		=>	0,

						'account_number'=> $_POST['account_number'],
						
						
						
						'Balance'		=>	$countryd,
						'last_activity_user'=>$this->login_user->id,
                        'last_activity' => get_current_utc_time(),
					);
				}else if($countrya=='CR'){

$data[] = array(

	                    'BankName'=> $_POST['BankName'],
						'TransactionId'		=>	$transaction_id,
						'ValueName'		=>	$newDate,
						'PostDate'			=>	$address,
						'RemitterBranch'				=>	$city,
						'Description'		=>	$postal_code,
						'ChequeNo'			=>	$country,
						
						'CreditAmount'		=>	$countrys,
						
						'DebitAmount'		=>0,

						'account_number'=> $_POST['account_number'],
						
						'Balance'		=>	$countryd,
						'last_activity_user'=>$this->login_user->id,
                        'last_activity' => get_current_utc_time(),
					);
				}
			}
				}
			}
				
			$this->excel_import_model->insert($data);
			echo 'Data Imported successfully';
		}	
	}
}

?>