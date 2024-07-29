<?php 
$options = array("purchase_order_id" => $purchase_order_info->id);
$list_data = $this->Vendors_invoice_list_model->get_details($options)->result();

$optionss = array("id" =>$purchase_order_info->id);
$modifed_data = $this->Purchase_orders_model->get_details($optionss)->row();
?>

<div class="panel panel-default p15 no-border m0">
    <span><?php 
        echo lang("status") . ":";
        if ($list_data && $modifed_data->modified == '0') {
            $purchase_order_status_class = "label-danger"; 
            $purchase_status = "<span class='label $purchase_order_status_class large'>" . lang('created_vendor_invoice') . "</span>";
            echo $purchase_status;
        } else {  
            echo $purchase_order_status_label;
        }
    ?></span>
    <span class="ml15">
        <?php
        echo lang("vendor") . ": ";
        echo anchor(get_uri("vendors/view/" . $purchase_order_info->vendor_id), $purchase_order_info->company_name);
        ?>
    </span>
    <span class="ml15">
        <?php
        if (isset($estimate_info->project_id)) {
            echo lang("project") . ": ";
            echo anchor(get_uri("projects/view/" . $estimate_info->project_id), $estimate_info->project_title);
        }
        ?>
    </span>
</div>
