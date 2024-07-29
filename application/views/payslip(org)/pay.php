<?php 

 $DB1 = $this->load->database('default', TRUE);
 $DB1->select ("id");
 $DB1->from('payslip');
 $DB1->order_by('id','desc');
 $DB1->limit(1);

 
 $query1=$DB1->get();
 $query1->result();  
foreach ($query1->result() as $rows)
    {
    $b=$rows->id;
   
   
        }
        

 $DB2 = $this->load->database('default', TRUE);
 $DB2->select ("id");
 $DB2->from('payslip_earnings');
 $DB2->order_by('id','desc');
 $DB2->limit(1);
 $query2=$DB2->get();
 $query2->result();  
 foreach ($query2->result() as $rows1)
    {
    $c=$rows1->id;
    
   
        }

 $DB3 = $this->load->database('default', TRUE);
 $DB3->select ("id");
 $DB3->from('payslip_attendance');
 $DB3->order_by('id','desc');
 $DB3->limit(1);
 $query3=$DB3->get();
 $query3->result();  
 foreach ($query3->result() as $rows2)
    {
    $d=$rows2->id;
   
   
        }

$DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $c);
 $DB4->update('payslip_earnings', array('payslip_id' => $b));

 $DB5 = $this->load->database('default', TRUE);

 $DB5->where('id', $d);
 $DB5->update('payslip_attendance', array('payslip_id' => $b));

$g=$_SERVER['HTTP_REFERER'];
$url='/view/';

 header('Location:'.$g.$url.$b);

?>


