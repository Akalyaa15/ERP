<!--table style="border: 1px solid #666;margin:auto;">-->
<!--span style="padding-left: 43% !important;text-align:center;font-size: 20px;">INVOICE</span-->

 <span style="padding-left: 43% !important;text-align:center;font-size: 20px;">INVOICE</span>
 <div style=" margin: auto;">
    <?php
    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $invoice_style = get_setting("invoice_style");
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "invoice_info" => $invoice_info
    );

    if ($invoice_style === "style_2") {
        $this->load->view('invoices/invoice_parts/header_style_2.php', $data);
    } else {
        $this->load->view('invoices/invoice_parts/header_style_1.php', $data);
    }

 /*   $discount_row = "<tr>
                        <td colspan='3' style='text-align: right;'>" . lang("discount") . "</td>
                        <td style='text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;'> " . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . "</td>
                    </tr>"; */
    ?>


</div>

<?php 
$DB4 = $this->load->database('default', TRUE);
$DB4->select ("discount_percentage");
 $DB4->from('invoice_items');
 $DB4->where('invoice_items.invoice_id',$invoice_info->id);
  $DB4->where('invoice_items.discount_percentage!=','0');

 $DB4->where('invoice_items.deleted','0');
 
$query=$DB4->get();
$s=$query->result();
$k= sizeof($s);
?>
<?php 
$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('invoice_items');
 $DB10->where('invoice_items.invoice_id',$invoice_info->id);
 $DB10->where('invoice_items.gst!=','0');
 $DB10->where('invoice_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
?>
<?php if($hsn_size>0) { ?>
<table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
    <th style="width: 7%; text-align: center; border-right: 1px solid #eee;"> <?php echo lang("s.no"); ?> </th>
        <th style="width: 32%; border-right: 1px solid #eee;"> <?php echo lang("item_description"); ?> </th>
        <!-- <th style="text-align: center;  width: 10%; border-right: 1px solid #eee;"> <?php /* echo lang("category"); */?></th>
        <th style="text-align: center;  width: 10%; border-right: 1px solid #eee;"> <?php /*echo lang("make"); */?></th> -->
    <th style="text-align: center;  width: 6%; border-right: 1px solid #eee;"> <?php echo lang("hsn_code"); ?></th>
    <th style="text-align: center;  width: 6%; border-right: 1px solid #eee;"> <?php echo lang("gst"); ?></th>
 <th style="text-align: center;  width: 6%; border-right: 1px solid #eee;font-size:14px;"> <?php echo ("Qty"); ?></th>
        
        <th style="text-align: center;  width: 12%; border-right: 1px solid #eee;"> <?php echo lang("rate"); ?></th>
        <th style="text-align: right;  width: 13%; border-right: 1px solid #eee;"> <?php echo lang("total"); ?></th>
        
        <!--th style="text-align: right;  width: 11%; border-right: 1px solid #eee;"> <?php echo lang("tax_amount"); ?></th-->
       <?php if($k>0){?> <th style="text-align: center;  width: 6%; border-right: 1px solid #eee;"> <?php echo ("Disc"); ?></th> <?php }?>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("net_total"); ?></th>
        
        
    </tr>
    <?php
    $counter = 0;
    foreach ($invoice_items as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
        <td style="width: 7%; border: 1px solid #fff; padding: 10px;text-align: center;"><?php echo ++$counter;  ?>
                </td>
            <td style="width: 32%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <?php if($item->description) { ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
                <?php } ?>
                <?php if($item->category) { ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php if($item->invoice_type == 0){
             $category_name = $this->Product_categories_model->get_one($item->category); 
             }else if($item->invoice_type == 1){
             $category_name = $this->Service_categories_model->get_one($item->category); }
            if($category_name->title){
                    $category = $category_name->title ? $category_name->title:"-";
                    echo lang("category") ." :".$category;} ?></span>
                    <?php } ?>
                    <?php if($item->make) { ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php $make_name = $this->Manufacturer_model->get_one($item->make); if($make_name->title){
                    $make = $make_name->title ? $make_name->title:"-";
                    echo lang("make") ." :".$make;}  ?></span>
                    <?php } ?>
            </td>
            <!-- <td style="width: 10%; border: 1px solid #fff; padding: 10px;max-width: 10px;word-wrap: break-word;"><?php /*if($item->invoice_type == 0){
             $category_name = $this->Product_categories_model->get_one($item->category); 
            }else if($item->invoice_type == 1){
          $category_name = $this->Service_categories_model->get_one($item->category);  

          }
          if($category_name->title){
                    $category = $category_name->title ? $category_name->title:"-";
                    echo $category;} ?>
                </td>
            <td style="width: 10%; border: 1px solid #fff;font-size: 90%; padding: 10px;max-width: 10px;word-wrap: break-word;"><?php $make_name = $this->Manufacturer_model->get_one($item->make);
             echo $make_name->title ? $make_name->title:"-"; */?>
                </td> -->
            <td style="width: 6%; border: 1px solid #fff; padding: 10px;max-width: 10px;word-wrap: break-word;"><?php echo $item->hsn_code; ?>
                </td>
                <td style="width: 6%; border: 1px solid #fff; padding: 10px;"><?php echo $item->gst.'%'; ?>
                </td>
            
            <td style="text-align: center; width: 6%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            
            
            <td style="text-align: right; width: 12%; border: 1px solid #fff;max-width:85px;word-wrap: break-word;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            <td style="text-align: right; width: 13%; border: 1px solid #fff;max-width:90px;word-wrap: break-word;padding: 10px;"> <?php echo to_currency($item->quantity_total, $item->currency_symbol); ?></td>
            
            <!--td style="text-align: right; width: 11%; border: 1px solid #fff;max-width: 80px;word-wrap: break-word;"> <?php echo to_currency($item->tax_amount, $item->currency_symbol); ?></td-->
            <?php if($k>0){?><td style="width: 6%; border: 1px solid #fff; padding: 10px;"><?php echo $item->discount_percentage.'%'; ?>
                </td><?php } ?>
                <td style="text-align: right; width: 14%; border: 1px solid #fff;max-width:100px;word-wrap: break-word;"> <?php echo to_currency($item->total, $item->currency_symbol); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="6" style="text-align: right;color:#181919;"><?php echo lang("sub_total"); ?></td>
        <td style="text-align: right; width: 13%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_quantity_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td>
        
        <!--td style="text-align:right; width: 11%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td-->
       <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:100px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    

     <?php if($invoice_total_summary->freight_amount){?>
     <tr>
        <td colspan="7" style="text-align: right;color:#181919;"><?php echo lang("freight"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>

        </td>
    </tr>
    <?php } ?>
<?php if($invoice_total_summary->installation_total) { ?>
<tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'Installation Total'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_total, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>

<?php } ?>

<?php 
$company_setup_country = get_setting("company_setup_country");
if($company_setup_country ==$client_info->country) {?> 

<?php if (empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_state = get_setting("company_state");
if($company_state !==$client_info->state) {?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'IGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
        <?php if($invoice_total_summary->installation_tax) { ?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'INSTALLATION IGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
         <?php } ?>
    <?php } ?>
    <?php/* } */?>

<?php }  else if (!empty($client_info->gstin_number_first_two_digits)) { ?>

    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$client_info->gstin_number_first_two_digits) {?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'IGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
        <?php if($invoice_total_summary->installation_tax) { ?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'INSTALLATION IGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
         <?php } ?>
    <?php } ?>
    <?php } ?>


<?php if (empty($client_info->gstin_number_first_two_digits)) { ?>

    <?php 
$company_state = get_setting("company_state");
if($company_state ==$client_info->state) {?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'CGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'SGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <?php if($invoice_total_summary->installation_tax) { ?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'INSTALLATION CGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'INSTALLATION SGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <?php } ?>
    <?php } ?>
    <?php/* } */?>

<?php } else if (!empty($client_info->gstin_number_first_two_digits)) { ?>
    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$client_info->gstin_number_first_two_digits) {?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'CGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'SGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <?php if($invoice_total_summary->installation_tax) { ?>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'INSTALLATION CGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo 'INSTALLATION SGST OUTPUT'; ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:80px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?>
            </td>
        </tr>
        <?php } ?>
    <?php } ?>
    <?php } ?>
    
<?php } ?>
   
      

   
   
   <tr>
        <td colspan="7" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_net_subtotal_default, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php $c= to_currency($invoice_total_summary->invoice_net_subtotal_default); $d=substr($c,-2); if($d>0){ ?>
    <tr>
        <td colspan="7" style="text-align: right;color:#181919;"><?php echo lang("round_off"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php $c= to_currency($invoice_total_summary->invoice_net_subtotal_default); $d=substr($c,-2); if($d>=50){
            $e=(100-$d);
            echo "(+)0.".$e;
            }elseif($d<50){
                echo "(-)0.".$d;
                } ?>
        </td>
    </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->total_paid) { ?>     
        <tr>
            <td colspan="7" style="text-align: right;color:#181919;"><?php echo lang("paid"); ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;color:black;"></td><?php } ?>
            <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="7" style="text-align: right;color:#181919;"><?php echo lang("balance_due"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 14%; background-color: <?php echo $color; ?>; color: #fff;max-width:90px;word-wrap: break-word;">
            <?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php } else { ?>

<table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
    <th style="width: 7%; text-align: center; border-right: 1px solid #eee;"> <?php echo lang("s.no"); ?> </th>
        <th style="width: 37%; border-right: 1px solid #eee;"> <?php echo lang("item_description"); ?> </th>
        <!-- <th style="text-align: center;  width: 11%; border-right: 1px solid #eee;"> <?php /* echo lang("category"); */?></th>
        <th style="text-align: center;  width: 12%; border-right: 1px solid #eee;"> <?php /* echo lang("make"); */?></th> -->
    
 <th style="text-align: center;  width: 8%; border-right: 1px solid #eee;font-size:14px;"> <?php echo ("Qty"); ?></th>
        
        <th style="text-align: center;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("rate"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("total"); ?></th>
        
        <!--th style="text-align: right;  width: 11%; border-right: 1px solid #eee;"> <?php echo lang("tax_amount"); ?></th-->
       <?php if($k>0){?> <th style="text-align: center;  width: 6%; border-right: 1px solid #eee;"> <?php echo ("Disc"); ?></th> <?php }?>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("net_total"); ?></th>
        
        
    </tr>
    <?php
    $counter = 0;
    foreach ($invoice_items as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
        <td style="width: 7%; border: 1px solid #fff; padding: 10px;"><?php echo ++$counter;  ?>
                </td>
            <td style="width: 37%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
            <?php if($item->description) { ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
                <?php } ?>
                <?php if($item->category) { ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php if($item->invoice_type == 0){
             $category_name = $this->Product_categories_model->get_one($item->category); 
             }else if($item->invoice_type == 1){
             $category_name = $this->Service_categories_model->get_one($item->category); }
            if($category_name->title){
                    $category = $category_name->title ? $category_name->title:"-";
                    echo lang("category") ." :".$category;} ?></span>
                    <?php } ?>
                    <?php if($item->make) { ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php $make_name = $this->Manufacturer_model->get_one($item->make); if($make_name->title){
                    $make = $make_name->title ? $make_name->title:"-";
                    echo lang("make") ." :".$make;}  ?></span>
                    <?php } ?>
                    
            </td>
            <!-- <td style="width: 11%; border: 1px solid #fff; padding: 10px;max-width: 10px;word-wrap: break-word;"><?php /* if($item->invoice_type == 0){
             $category_name = $this->Product_categories_model->get_one($item->category); 
            }else if($item->invoice_type == 1){
          $category_name = $this->Service_categories_model->get_one($item->category);  

          }
          if($category_name->title){
                    $category = $category_name->title ? $category_name->title:"-";
                    echo $category;} ?>
                </td>
            <td style="width: 12%; border: 1px solid #fff; padding: 10px;max-width: 10px;word-wrap: break-word;"><?php $make_name = $this->Manufacturer_model->get_one($item->make);
             echo $make_name->title ? $make_name->title:"-";  */ ?>
                </td> -->
            
            
            <td style="text-align: center; width: 8%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            
            
            <td style="text-align: right; width: 14%; border: 1px solid #fff;max-width:85px;word-wrap: break-word;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;max-width:90px;word-wrap: break-word;padding: 10px;"> <?php echo to_currency($item->quantity_total, $item->currency_symbol); ?></td>
            
            
            <?php if($k>0){?><td style="width: 6%; border: 1px solid #fff; padding: 10px;"><?php echo $item->discount_percentage.'%'; ?>
                </td><?php } ?>
                <td style="text-align: right; width: 15%; border: 1px solid #fff;max-width:100px;word-wrap: break-word;"> <?php echo to_currency($item->total, $item->currency_symbol); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="4" style="text-align: right;color:#181919;"><?php echo lang("sub_total"); ?></td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_quantity_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td>
        
        
       <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:100px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    

     <?php if($invoice_total_summary->freight_rate_amount){?>
     <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("freight"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_rate_amount, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php } ?>
    <?php if($invoice_total_summary->installation_total) { ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_total"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_total, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php } ?>

<?php 
$company_setup_country = get_setting("company_setup_country");
if($company_setup_country ==$client_info->country) {?>  
<?php if($invoice_total_summary->freight_tax_amount||$invoice_total_summary->installation_tax) { ?>

<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>

    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$client_info->gstin_number_first_two_digits) {?>
<?php if($invoice_total_summary->freight_tax3) { ?>
<tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("igst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
       <?php } ?>
    <?php if($invoice_total_summary->installation_tax) { ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_igst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php } ?>
    <?php } ?>
    <?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_state = get_setting("company_state");
if($company_state !==$client_info->state) {?>
<?php if($invoice_total_summary->freight_tax3) { ?>
<tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("igst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
       <?php } ?>
    <?php if($invoice_total_summary->installation_tax) { ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_igst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php } ?>
<?php } ?>
<?php } ?>


<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>

    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$client_info->gstin_number_first_two_digits) {?>
<?php if($invoice_total_summary->freight_tax3) { ?>
<tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("cgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("sgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
    </tr>
    <?php } ?>
    <?php if($invoice_total_summary->installation_tax) { ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_cgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
     <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_sgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php } ?>
    <?php } ?>
    <?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_state = get_setting("company_state");
if($company_state ==$client_info->state) {?>
<?php if($invoice_total_summary->freight_tax3) { ?>
<tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("cgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("sgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
    </tr>
    <?php } ?>
    <?php if($invoice_total_summary->installation_tax) { ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_cgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
     <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("installation_sgst_output"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>

    

   
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_net_subtotal_default, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php $c= to_currency($invoice_total_summary->invoice_net_subtotal_default); $d=substr($c,-2); if($d>0){ ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("round_off"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
            <?php $c= to_currency($invoice_total_summary->invoice_net_subtotal_default); $d=substr($c,-2); if($d>=50){
            $e=(100-$d);
            echo "(+)0.".$e;
            }elseif($d<50){
                echo "(-)0.".$d;
                } ?>
        </td>
    </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->total_paid) { ?>     
        <tr>
            <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("paid"); ?></td>
            <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;color:black;"></td><?php } ?>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;max-width:90px;word-wrap: break-word;color:#181919;">
                <?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="5" style="text-align: right;color:#181919;"><?php echo lang("balance_due"); ?></td>
        <?php if($k>0){?> <td style="text-align: right; width: 6%; border: 1px solid #fff; background-color: #f4f4f4;"></td><?php } ?>
        <td style="text-align: right; width: 15%; background-color: <?php echo $color; ?>; color: #fff;max-width:90px;word-wrap: break-word;">
            <?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php } ?>

<!--<table>

</table>-->


<br>
<br>


<table style="width: 99%; color: #444;line-height: 15px">
    <tr style="font-weight: bold; background-color:white; color: black;  ">
        <th style="width: 100%; border-right: 1px solid #eee;font-weight: bold;font-size: 14px;"><?php
 if($invoice_total_summary->balance_due){

$numbers = $invoice_total_summary->balance_due;
$number=ltrim($numbers,"-");
$client_currency=$client_info->currency;
function convertToIndianCurrency($number,$client_currency) {
$client_currency=$client_currency;
    //$company_currency = get_setting("default_currency");
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And  " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])." Paise"  : '';
    return ($Rupees ? $client_currency." " . $Rupees : '') . $paise . " Only";

}

if($numbers<0){
        echo "Balance Rupees = negative ". convertToIndianCurrency($number,$client_currency);

}else{
echo "Amount Chargeable(in words) : " . convertToIndianCurrency($number,$client_currency);
}
}

?>

</th>
</tr>
</table>
<br>
<br>
<table style="width: 99%; color: #444;line-height: 20px">
    <tr style="font-weight: bold; background-color:white; color: black;  ">
        <th style="width: 100%; border-right: 1px solid #eee;font-weight: bold;font-size: 14px;"><?php
 if($invoice_total_summary->total_paid){

$number = $invoice_total_summary->total_paid;
$client_currency=$client_info->currency;
function convertToIndianCurrencys($number,
    $client_currency) {

    $company_currency = $client_currency;
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
   if($decimal>19){
    $paise = ($decimal) ? "And  " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])." Paise"  : '';
    }else{
       $paise = ($decimal) ? "And  " . ($words[$decimal]) ." Paise"  : ''; 
    }
    return ($Rupees ?  $company_currency." " . $Rupees : '') . $paise . " Only";

}


 echo "Paid Amount(in words) : " . convertToIndianCurrencys($number,$client_currency);
}


?></th>
</tr>
</table>




