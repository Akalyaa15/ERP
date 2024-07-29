 <table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:145px;">
<strong style="font-weight: bold;color:black;"><?php echo lang("voucher_to"); ?></strong><br>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>

<?php
    foreach ($estimate_items as $item) {
 $client_countries=$this->Countries_model->get_one($item->receiver_client_country);
 $vendor_countries=$this->Countries_model->get_one($item->receiver_vendor_country);
    	if($item->r_member_type=='tm'||$item->r_member_type=='om'){
        ?>

<strong><?php echo "Name:" .$item->r_linked_user_name; ?> </strong><br>
<strong><?php echo "Employee ID:".$item->r_employee_id; ?> </strong><br>
<strong><?php echo "Designation:".$item->r_job_title; ?> </strong><br>
<?php }elseif ($item->r_member_type=='others') { ?>
<strong><?php echo "Name:" .$item->r_f_name." ".$item->r_l_name; ?> </strong><br>
<strong><?php echo "Address:".$item->r_address; ?> </strong><br>
<strong><?php echo "Contact:".$item->r_phone; ?> </strong><br>
<?php }elseif ($item->r_member_type=='clients') { ?>
<strong><?php echo $item->r_rep; ?> </strong><br>
<strong><?php echo $item->receiver_client_name; ?> </strong><br>
<strong><?php echo $item->receiver_client_address; ?> </strong><br>
<?php if($item->receiver_client_city&&$client_countries->numberCode=='356'){ ?>
<strong><?php echo $item->receiver_client_city.'-'.$item->receiver_client_pincode.','; ?> </strong><br>
<?php }else if($item->receiver_client_city){ ?>
<strong><?php echo $item->receiver_client_city.','; ?> </strong><br>
<?php } ?>
<?php if($client_countries->countryName){?>
<strong><?php echo $client_countries->countryName.'.'; ?> </strong><br>
<?php } ?>
<?php }elseif ($item->r_member_type=='vendors') { ?>
<strong><?php echo $item->r_rep; ?> </strong><br>
<strong><?php echo $item->receiver_vendor_name; ?> </strong><br>
<strong><?php echo $item->receiver_vendor_address; ?> </strong><br>
<?php if($item->receiver_vendor_city&&$vendor_countries->numberCode=='356'){ ?>
<strong><?php echo $item->receiver_vendor_city.'-'.$item->receiver_vendor_pincode.','; ?> </strong><br>
<?php }else if($item->receiver_vendor_city){ ?>
<strong><?php echo $item->receiver_vendor_city.','; ?> </strong><br>
<?php } ?>
<?php if($vendor_countries->countryName){?>
<strong><?php echo $vendor_countries->countryName.'.'; ?> </strong><br>
<?php } ?>
<?php  }        ?><?php   }   ?>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    
</span>
</td></tr>
</table>