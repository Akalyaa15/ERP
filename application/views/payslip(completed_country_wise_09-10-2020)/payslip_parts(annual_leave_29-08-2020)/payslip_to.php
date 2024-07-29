<table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height:140px;">
<div><b><?php echo lang("payslip_to"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong style="font-weight: bold;color:black;">Employee Details</strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($user_info->first_name) { ?>
        <div><?php  echo lang("employee_name") . ": " . ($user_info->first_name)." ".$user_info->last_name;?>
            <?php if ($user_info->employee_id) { ?>
                <br /><?php echo lang("employee_id") . ": " .  $user_info->employee_id; ?>
            <?php } ?>
             <?php if ($user_info->job_title) { ?>
                <br /><?php echo lang("designation") . ": " .  $user_info->job_title; ?>
            <?php } ?>
            <?php if ($user_info->email) { ?>
                <br /><?php echo lang("email") . ": " . $user_info->email; ?>
            <?php } ?>
             <?php /*if ($user_info->salary) { ?>
                <br /><?php echo lang("salary") . ": " . $user_info->salary; ?>
            <?php } ?>
            <!--<?php if ($user_info->salary_term) { ?>
                <br /><?php echo lang("salary_term") . ": " . $user_info->salary_term; ?>-->
            <?php } */?>
            <?php if ($user_info->date_of_hire) { ?>
                <br /><?php echo lang("date_of_joining") . ": " . $user_info->date_of_hire; ?>
            <?php }  ?>
        </div>
    <?php } ?>
</span>
</td></tr>
</table>