

<?php $user_table = $this->Users_model->get_one($payslip_info->user_id);
//$user_country = $user_table->country;
$user_country =$payslip_info->country;
if($user_country){
    $user_country_options = array("numberCode"=> $user_country);
    $get_user_country_info = $this->Countries_model->get_details($user_country_options)->row();
    

    

}

//echo $user_country;
//echo $get_user_country_info->id;
/*$user_earnings_country_options = array("country_id"=> $get_user_country_info->id);
$get_user_earnings_country_info = $this->Country_earnings_model->get_details($user_earnings_country_options)->result();

foreach ($get_user_earnings_country_info as $country_earnings){

echo $country_earnings->percentage;
}*/


?>
<table id="payslip-earnings-table" class="table display dataTable text-right strong table-responsive">   

<?php if($get_user_country_info->id){ 

$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('country_earnings');
 $DB2->where('country_earnings.status','active');
 $DB2->where('country_earnings.deleted','0');
 $DB2->where('country_earnings.country_id',$get_user_country_info->id);
 
 $DB2 ->where('country_earnings.key_name','basic_salary');
 $query=$DB2->get();
$query->result();

$DB1 = $this->load->database('default', TRUE);
$DB1->select ("title,percentage");
 $DB1->from('country_earnings');
 $DB1->where('country_earnings.status','active');
 $DB1->where('country_earnings.status !=','inactive');
 $DB1->where('country_earnings.deleted','0');
 $DB1->where('country_earnings.country_id',$get_user_country_info->id);
 $DB1 ->where('country_earnings.key_name !=','basic_salary');
 
  $query1=$DB1->get();
  $query1->result();

}else{
 $DB2 = $this->load->database('default', TRUE);
 $DB2->select ("title,percentage");
 $DB2->from('earnings');
 $DB2->where('earnings.status','active');
 $DB2->where('earnings.deleted','0');
 $DB2 ->where('earnings.key_name','basic_salary');
 $query=$DB2->get();
 $query->result();

 $DB1 = $this->load->database('default', TRUE);
 $DB1->select ("title,percentage");
 $DB1->from('earnings');
 $DB1->where('earnings.status','active');
 $DB1->where('earnings.status !=','inactive');
 $DB1->where('earnings.deleted','0');
 $DB1 ->where('earnings.key_name !=','basic_salary');
 $query1=$DB1->get();
 $query1->result();
}



 $salary=$earnings_total_summary->earnings_subtotal/100;
 
?>
    <?php foreach ($query->result() as $rows):?>

     <tr>
        <td><?php echo $rows->title; ?></td>
        <td style="width: 120px;"><?php
         $b=$salary * $rows->percentage ;echo to_currency($b,$earnings_total_summary->currency_symbol) ;
         ?></td>
        <td style="width: 100px;"> </td>

    </tr>
<?php endforeach;?> 


<?php 
$c = $b/100; 
$total=0;
foreach ($query1->result() as $rows1):?>
     <tr>
        <td><?php echo $rows1->title; ?></td>
        <td style="width: 120px;"><?php
         $a=$c * $rows1->percentage ;echo to_currency($a,$earnings_total_summary->currency_symbol);$total+=$a; 
      ?></td>
        <td style="width: 100px;"> </td>

    </tr>
<?php endforeach;?> 
 


   
    <tr>
        <td><?php echo lang("total_earnings"); ?></td>
        <td style="width: 120px;"><?php $g = $b+$total;echo to_currency($g,$earnings_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr> 


</table>

<?php /*
<!-- <table id="payslip-earnings-table" class="table display dataTable text-right strong table-responsive">   
<?php

$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('earnings');
 $DB2->where('earnings.status','active');
 $DB2->where('earnings.deleted','0');
 
 $DB2 ->where('earnings.key_name','basic_salary');
 $query=$DB2->get();
$query->result();

$DB1 = $this->load->database('default', TRUE);
$DB1->select ("title,percentage");
 $DB1->from('earnings');
 $DB1->where('earnings.status','active');
 $DB1->where('earnings.status !=','inactive');
 $DB1->where('earnings.deleted','0');
 $DB1 ->where('earnings.key_name !=','basic_salary');
 
$query1=$DB1->get();
$query1->result();

 $salary=$earnings_total_summary->earnings_subtotal/100;
 
  
   foreach ($query->result() as $rows):?>

     <tr>
        <td><?php echo $rows->title; ?></td>
        <td style="width: 120px;"><?php
         $b=$salary * $rows->percentage ;echo to_currency($b) ;
         ?></td>
        <td style="width: 100px;"> </td>

    </tr>
<?php endforeach;?> 


<?php 
$c = $b/100; 
$total=0;
foreach ($query1->result() as $rows1):?>
     <tr>
        <td><?php echo $rows1->title; ?></td>
        <td style="width: 120px;"><?php
         $a=$c * $rows1->percentage ;echo to_currency($a);$total+=$a; 
      ?></td>
        <td style="width: 100px;"> </td>

    </tr>
<?php endforeach;?> 
 


   
    <tr>
        <td><?php echo lang("total_earnings"); ?></td>
        <td style="width: 120px;"><?php $g = $b+$total;echo to_currency($g); ?></td>
        <td style="width: 100px;"> </td>
    </tr> 


</table> -->

*/?>