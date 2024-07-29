<table id="work_order-item-table" class="table display dataTable text-right strong table-responsive" style="width: 100%;">
    <tr>
        <td style="width: 85px;"><?php echo lang("sub_total"); ?></td>
        <td style="width: 96px;"><?php echo to_currency($work_order_total_summary->estimate_quantity_subtotal, $work_order_total_summary->currency_symbol); ?></td>
        <td style="width: 80px;"><?php echo to_currency($work_order_total_summary->estimate_tax_subtotal, $work_order_total_summary->currency_symbol); ?></td>
        <td style="width: 36px;"><?php echo to_currency($work_order_total_summary->estimate_subtotal, $work_order_total_summary->currency_symbol); ?></td>
        <td style="width: 30px;"></td>
    </tr>
    <?php
    if (isset($work_order_info)) {
        $freight_row = "<tr>
                            <td></td>
                            <td></td>
                            <td style='padding-top:13px;'>" . lang("freight") . "</td>
                            <td style='padding-top:13px;'>" . to_currency($work_order_total_summary->freight_rate_amount, $work_order_total_summary->currency_symbol) . "</td>
                            <td class='text-center option w10p'>" . modal_anchor(get_uri("work_orders/freight_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-work_order_id" => $work_order_info->id, "title" => lang('edit_freight'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                        </tr>";
        echo $freight_row;
    }
    ?>
    <!-- Check if `$invoice_total_summary` is needed -->
    <?php
    if (isset($invoice_total_summary)) {
        ?>
        <tr>
            <td style="width: 85px;"><?php echo lang("igst_output"); ?></td>
            <td style="width: 96px;"><?php echo to_currency($invoice_total_summary->freight_tax, $invoice_total_summary->currency_symbol); ?></td>
        </tr>
        <?php
    }
    ?>
    <tr><td></td>
        <td></td>
        <td><?php echo lang("total"); ?></td>
        <td><?php echo to_currency($work_order_total_summary->estimate_net_subtotal_default, $work_order_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td><?php echo lang("round_off"); ?></td>
        <td><?php 
            $c= to_currency($work_order_total_summary->estimate_net_subtotal_default); 
            $d=substr($c,-2); 
            if($d>=50){
                $e=(100-$d);
                echo "(+)0.".$e;
            } elseif($d<50){
                echo "(-)0.".$d;
            } 
        ?></td>
        <td></td> 
    </tr>
    <?php if ($work_order_total_summary->total_paid) { ?>
        <tr>
            <td></td>
            <td></td>
            <td><?php echo lang("paid"); ?></td>
            <td><?php echo to_currency($work_order_total_summary->total_paid, $work_order_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <tr>
        <td></td>
        <td></td>
        <td><?php echo lang("balance_due"); ?></td>
        <td><?php echo to_currency($work_order_total_summary->balance_due, $work_order_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
</table>
