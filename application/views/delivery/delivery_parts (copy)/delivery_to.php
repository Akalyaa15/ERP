 <table> 
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:145px;">
<strong style="font-weight: bold;color:black;"><?php echo lang("deliver_to"); ?></strong><br>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<!--div style="line-height: 3px;"> </div-->
<!--strong style="font-weight: bold;color:black;"><?php echo $client_info->first_name." ".$client_info->last_name; ?> </strong-->
<!--div style="line-height: 3px;"> </div-->
<?php if($estimate_info->member_type=='tm'||$estimate_info->member_type=='om') { ?>
<strong><?php echo $client_info->first_name." ".$client_info->last_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->job_title) { ?>
        <div><?php echo nl2br($client_info->job_title); ?>
            
        </div>
    <?php } ?>
</span>    <?php }elseif ($estimate_info->member_type=='others') {
 ?><strong><?php echo $estimate_info->f_name." ".$estimate_info->l_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
        <div><?php echo nl2br($estimate_info->address); ?>
            
        </div>
        <div><?php echo  nl2br('Contact No:'.$estimate_info->phone); ?>
            
        </div>
</span> 
    <?php } ?>

</td></tr>
</table>