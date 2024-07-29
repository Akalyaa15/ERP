
<table id="payslip-earnings-table" class="table display dataTable text-right strong table-responsive">   
<!--<tr>
        <td><?php echo lang("basic_salary"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($earnings_total_summary->earnings_subtotal, $earnings_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>-->
<?php
/*$servername = "localhost";
$username = "root";
$password = "admin@developer";
$dbname = "gemsManager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
*/
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('earnings');
 $DB2->where('earnings.status','active');
 $DB2->where('earnings.deleted','0');
 $DB2 ->where('earnings.title','Basic Salary ');
 $DB2 ->or_where('earnings.title ','BasicSalary ');
 $query=$DB2->get();
$query->result();

$DB1 = $this->load->database('default', TRUE);
$DB1->select ("title,percentage");
 $DB1->from('earnings');
 $DB1->where('earnings.status','active');
 $DB1->where('earnings.status !=','inactive');
 $DB1->where('earnings.deleted','0');
 $DB1 ->where('earnings.title !=','Basic Salary '); 
 $DB1 ->where('earnings.title !=','BasicSalary ');
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

<!--<tr>
        <td><?php echo lang("total_earnings"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($earnings_total_summary->earningsadd_subtotal, $earnings_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($earnings_total_summary->earningsadd_total, $earnings_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>-->
</table>