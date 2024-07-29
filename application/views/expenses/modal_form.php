
<?php if($model_info->member_type=='tm') {?>
<script type="text/javascript">
    $(document).ready(function () {
                $("#otherss").hide()
                $("#outsource").hide()
                 $("#clients").hide()
                 $("#vendors").hide()
                 $("#team").show()
$("#member_type").select2("destroy").val("tm");
});
</script>

<?php }else if($model_info->member_type=='om') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#otherss").hide()
                $("#outsource").show()
                 $("#team").hide()
                  $("#clients").hide()
                 $("#vendors").hide()
                 $("#member_type").select2("destroy").val("om");
});
</script>

<?php }else if($model_info->member_type=='others') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#otherss").show()
                $("#outsource").hide()
                 $("#team").hide()
                  $("#clients").hide()
                 $("#vendors").hide()
                 $("#member_type").select2("destroy").val("others");
});
</script>

<?php }else if($model_info->member_type=='clients') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#otherss").hide()
                $("#outsource").hide()
                 $("#team").hide()
                 $("#clients").show()
                 $("#vendors").hide()
                 $("#member_type").select2("destroy").val("clients")
                 $("#client_member_contact").show();
});
</script>
<?php }else if($model_info->member_type=='vendors') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#otherss").hide()
                $("#outsource").hide()
                 $("#team").hide()
                 $("#clients").hide()
                 $("#vendors").show()
                 $("#member_type").select2("destroy").val("vendors");
                 $("#vendor_member_contact").show();
});
</script>
<?php } ?>
<?php if($model_info->with_gst=="no") { ?>
<style>
      #s,#y,#z,#a{
        display:none;
      }
</style>
<?php } ?>
<?php echo form_open(get_uri("expenses/save"), array("id" => "expense-form", "class" => "general-form", "role" => "form")); ?>
 <div id="expense-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
   
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
         <?php if ($model_infos->user_type=="resource") { ?>
           
        
        <div class="form-group">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("expense_user_id", $rm_members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='expense_user_id'");
                ?>
            </div>
        </div>

<?php }else if ($model_infos->user_type=="staff") {  ?>
           
        
        <div class="form-group">
            <label for="expense_user_id" class=" col-md-3"> <?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("expense_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='expense_user_id'");
                ?>
            </div>
        </div>
<?php  }else{ ?>
 <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('member'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='member_type' name='member_type'>
                    <option value="-">-</option>

  <option value="tm">Team members </option>
  <option value="om">Outsource members </option>
  <option value="clients">Clients </option>
  <option value="vendors">Vendors </option>
  <option value="others">Others </option>
</select>
            </div>
        </div>
<div class="form-group" id="team" style="display: none">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("expense_user_id", $members_dropdown, array($model_info->user_id), "class='select2 validate-hidden' id='expense_user_id' ");
                ?>
            </div>
        </div>
<div class="form-group" id="outsource" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('outsource_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("expense_user_ids", $rm_members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='expense_user_ids'");
                ?>
            </div>
        </div>
        <div class="form-group" id="vendors" style="display: none">
            <label for="vendor_member" class=" col-md-3"><?php echo lang('vendors_company'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("vendor_member", $vendors_dropdown, $model_info->vendor_company, "class='select2 validate-hidden' id='vendor_member'");
                ?>
            </div>
        </div>  
        <div class="form-group" id="clients" style="display: none">
            <label for="client_member" class=" col-md-3"><?php echo lang('clients_company'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("client_member", $clients_dropdown, $model_info->company, "class='select2 validate-hidden' id='client_member'");
                ?>
            </div>
        </div>
        <div class="form-group" id="client_member_contact" style="display: none">
            <label for="category_id" class=" col-md-3"><?php echo lang('client_contact_member'); ?></label>
            <div class="col-md-9">
       <?php
                    echo form_input(array(
                        "id" => "client_contact",
                        "name" => "client_contact",
                         "value" => $model_info->user_id,
                        "class" => "form-control",
                        "placeholder" => lang('client_contact_member'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
    </div>
        </div>
        
        <div class="form-group" id="vendor_member_contact"  style="display: none">
            <label for="category_id" class=" col-md-3"><?php echo lang('vendor_contact_member'); ?></label>
            <div class="col-md-9">
         <?php
                    echo form_input(array(
                        "id" => "vendor_contact",
                        "name" => "vendor_contact",
                         "value" => $model_info->user_id,
                        "class" => "form-control",
                        "placeholder" => lang('vendor_contact_member'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
    </div>
        </div>

        <div class="form-group" id="otherss" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('others'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("expense_user_idss", $others_dropdown, $model_info->phone, "class='select2 validate-hidden' id='expense_user_idss'");
                ?>
            </div>
        </div>
        <?php  } ?>
        <div class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('voucher_no'); ?></label>
            <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "voucher_no",
            "name" => "voucher_no",
            "value" => $model_info->voucher_no,
            "class" => "form-control validate-hidden",
            "placeholder" => lang('voucher_no'),
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
            "readonly"=> "true",


        ));
        ?>
    </div>
        </div>
        <div class="form-group" id="v_filess" style="display: none">
            <label  class=" col-md-3">Voucher files</label>
            <div class="col-md-9" id="filess">
        
    </div>
        </div>  
        <div class="form-group" id="v_status" style="display: none">
            <label  class=" col-md-3">Voucher status</label>
            <div class="col-md-9" id="voucher_status">
        
    </div>
        </div>                  <div class=" form-group">
            <label for="expense_date" class=" col-md-3"><?php echo lang('date_of_expense'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "expense_date",
                    "name" => "expense_date",
                    "value" => $model_info->expense_date? $model_info->expense_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('category'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("category_id", $categories_dropdown, $model_info->category_id, "class='select2 validate-hidden'  id='category_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class=" col-md-3"><?php echo lang('amount'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "amount",
                    "name" => "amount",
                    "value" => $model_info->amount ? to_decimal_format($model_info->amount) : "",
                    "class" => "form-control",
                    "placeholder" => lang('amount'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
         <div class="form-group">
                        <label for="currency" class=" col-md-3"><?php echo lang('currency'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "currency",
                                "name" => "currency",
                                "value" => $model_info->currency,
                                "class" => "form-control",
                                "placeholder" => lang('currency'),
                                 "readonly"=>true,
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="currency_symbol" class=" col-md-3"><?php echo lang('currency_symbol'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "currency_symbol",
                                "name" => "currency_symbol",
                                "value" => $model_info->currency_symbol,
                                "class" => "form-control",
                                "placeholder" => lang('currency_symbol'),
                                "data-rule-required" => true,
                                 "readonly"=>true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>       <div class="form-group" style=" display: none";>
            <label for="title" class=" col-md-3"><?php echo lang('total'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "total",
                    "name" => "total",
                    "value" => $model_info->total ? to_decimal_format($model_info->total) : "",
                    "class" => "form-control",
                    "placeholder" => lang('amount'),
                   
                    
                ));
                ?>
            </div>
        </div>
        <div class=" form-group">
            <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "placeholder" => lang("title"),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description ? $model_info->description : "",
                    "class" => "form-control",
                    "placeholder" => lang('description'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>

            </div>
        </div>

        <div class="form-group">
            <label for="expense_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("expense_project_id", $projects_dropdown, $model_info->project_id, "class='select2 validate-hidden' id='expense_project_id' ");
                ?>
            </div>
        </div>
        <div class="form-group">
                <label for="invoice_recurring" class=" col-md-3"><?php echo lang('gst_applicable'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('gst_applicable'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "with_gst",
                        "name" => "with_gst",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->with_gst === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "without_gst",
                        "name" => "with_gst",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->with_gst === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>

 <div id="s">
 <div class="form-group"  id ="w">
        <label for="expense_gst_number" class=" col-md-3"><?php echo lang('gst_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "expense_gst_number",
                "name" => "expense_gst_number",
                "value" => $model_info->gst_number,
                "class" => "form-control",
                "placeholder" => lang('gst_number'),
              "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                
            ));
            ?>
        </div>
    </div>
    <input type="hidden" name="add_new_item_to_librarys" value="" id="add_new_item_to_librarys" />
    <div class="form-group">
        <label for="hsn_code" class=" col-md-3"><?php echo lang('hsn_sac_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "expense_item_hsn_code",
                "name" => "expense_item_hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_hsn_code'),
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close_hsn_code">×</span></a>
        </div>
    </div>
    </div>
     <div class="form-group"  id ="y">
        <label for="invoice_item_gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "expense_item_gst",
                "name" => "expense_item_gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "placeholder" => lang('gst'),
               "readonly"=>"true",
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="z">
        <label for="expense_item_hsn_description" class="col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
             "id" => "expense_item_hsn_code_description",
            "name" => "expense_item_hsn_code_description",
             "value" => $model_info->hsn_description ? $model_info->hsn_description : "",
                "class" => "form-control",
                "placeholder" => lang('hsn_description'),
                "readonly"=>"true",
            ));
            ?> 
        </div>
    </div>

    <div class="form-group" id="a">
                <label for="expense_recurring" class=" col-md-3"><?php echo lang('inclusive_tax'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('gst_applicable'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "with_inclusive_tax",
                        "name" => "with_inclusive_tax",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->with_inclusive_tax === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "without_inclusive_tax",
                        "name" => "with_inclusive_tax",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->with_inclusive_tax === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>

        

        <!--div class="form-group">
        <label for="state_tax" class=" col-md-3"><?php echo lang('state_tax'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('state_tax'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-2">
            <?php

            if($model_info->sgst_tax&&$model_info->cgst_tax){
                 $model_info->state_tax=1;
            }
            echo form_radio("state_tax", "1", $model_info->state_tax ? true : false, "id='state_tax'");
            ?>                       
        </div>
       
        <label for="central_tax" class=" col-md-3"><?php echo lang('central_tax'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('central_tax'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-2">
            <?php
            if($model_info->igst_tax){
                 $model_info->central_tax=1;
            }
            echo form_radio("state_tax", "1", $model_info->central_tax ? true : false, "id='central_tax'");
            ?>                       
        </div>
  
    </div>
    <div id="state_tax_fields" class="<?php if (!$model_info->state_tax) echo "hide"; ?>"> 
        <div class="form-group">
            <label for="cgst_tax" class=" col-md-3"><?php echo lang('cgst_tax'); ?></label>
            <div class=" col-md-9">
    <?php
        echo form_input(array(
            "id" => "cgst_tax",
            "name" => "cgst_tax",
            "value" => $model_info->cgst_tax,
            "class" => "form-control",
            "placeholder" => lang('cgst_tax'),
            "autofocus" => true,
           "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>  
    <div class="form-group">
            <label for="sgst_tax" class=" col-md-3"><?php echo lang('sgst_tax'); ?></label>
            <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "sgst_tax",
                "name" => "sgst_tax",
                "value" => $model_info->sgst_tax,
                "class" => "form-control",
                "placeholder" => lang('sgst_tax'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    </div>

         
    <div id="central_tax_fieldss" class="<?php if (!$model_info->central_tax) echo "hide"; ?>"> 
        <div class="form-group">
            <label for="igst_tax" class=" col-md-3"><?php echo lang('igst_tax'); ?></label>
            <div class=" col-md-9">
    <?php
        echo form_input(array(
            "id" => "igst_tax",
            "name" => "igst_tax",
            "value" => $model_info->igst_tax,
            "class" => "form-control",
            "placeholder" => lang('igst_tax'),
            "autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>  
 </div-->
        <div class="form-group">
            <label for="payment_status" class=" col-md-3"><?php echo lang('payment_status'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("payment_status", $payment_status_dropdown, $model_info->payment_status, "class='select2 validate-hidden' id='payment_status'");
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class=" col-md-3"></label>
            <div class="col-md-9">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>

        <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

        <?php $this->load->view("includes/dropzone_preview"); ?> 
        <p id="file_alert" style="color: red;display: none">*Uploading files are required</p>   
        
        
    </div>
    <div class="modal-footer">

            <div class="row">

                <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
                <button id="file_upload" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#voucher_no").select2({
                multiple: false,
                data: <?php echo json_encode($voucher_id_dropdown); ?>
            });

$("#client_contact").select2({
                multiple: false,
                data: <?php echo json_encode($client_members_dropdown); ?>
            }); 
$("#vendor_contact").select2({
                multiple: false,
                data: <?php echo json_encode($vendor_members_dropdown); ?>
            }); 
     $("#client_contact,#vendor_contact").select2("readonly", true);
        var uploadUrl = "<?php echo get_uri("expenses/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("expenses/validate_expense_file"); ?>";

        var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);

        $("#expense-form").appForm({
            onSuccess: function (result) {
                if (typeof $EXPENSE_TABLE !== 'undefined') {
                    $EXPENSE_TABLE.appTable({newData: result.data, dataId: result.id});
                } else {
                    location.reload();
                }
            }
        });
        
        $("#state_tax").click(function () {
            
            $("#state_tax_fields").removeClass("hide");
            $("#central_tax_fieldss").addClass("hide");
            $("#igst_tax").val("");
        });
       $("#central_tax").click(function () {
            
            $("#central_tax_fieldss").removeClass("hide");
            $("#state_tax_fields").addClass("hide");
            $("#cgst_tax").val("");
            $("#sgst_tax").val("");
        });

        //setDatePicker("#expense_date");

        $("#expense-form .select2").select2();

var ishsnUpdate = "<?php echo $model_info->id; ?>";
        if (!ishsnUpdate) {
            applySelect2OnHsnTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnHsnTitle();
        })
$('#expense_item_hsn_code').attr('readonly', true);
$("#payment_status").select2();

    });
function applySelect2OnHsnTitle() {
        $("#expense_item_hsn_code").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("items/get_invoice_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                //$("#invoice_item_hsn_code").select2("destroy").val("").focus();
                $("#expense_item_hsn_code").select2("destroy").val("").focus().attr('readonly', false);
                $("#expense_item_gst").val("").attr('readonly', false);
                $("#expense_item_hsn_code_description").val("").attr('readonly', false);
                $("#add_new_item_to_librarys").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_librarys").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val,},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#expense_item_gst").val()) {
                                $("#expense_item_gst").val(response.item_info.gst);
                            }
                           if (!$("#expense_item_hsn_code_description").val()) {
                                $("#expense_item_hsn_code_description").val(response.item_info.hsn_description);
                            }

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }


</script>
<script type="text/javascript">
    $("#client_member").change(function () {
    $("#client_contact").val("").attr('readonly', false)
                    var team_member =$("#client_member").val();

          $("#client_contact").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("income/get_client_contacts"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        team_member: team_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })
</script>
<script type="text/javascript">
    $("#vendor_member").change(function () {
    $("#vendor_contact").val("").attr('readonly', false)
                    var team_member =$("#vendor_member").val();

          $("#vendor_contact").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("income/get_vendor_contacts"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        team_member: team_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })
</script>
<script type="text/javascript">
    $("#expense_item_hsn_code").on("change", function() {
   
        $("#expense_item_gst").val("")
       
        $("#expense_item_hsn_code_description").val("")
});
</script>
<script type="text/javascript">
    $("#close_hsn_code").on("click", function() {
   $("#expense_item_hsn_code").val("").attr('readonly', false)
        $("#expense_item_gst").val("")
       
        $("#expense_item_hsn_code_description").val("")
});
</script>
<script type="text/javascript">

    $("#expense_user_id").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var team_member =$("#expense_user_id").val();

          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("expenses/get_voucher_id"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        team_member: team_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })
        $("#expense_user_ids").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var team_member =$("#expense_user_ids").val();

          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("expenses/get_voucher_id"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        team_member: team_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })
        $("#expense_user_idss").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var phone =$("#expense_user_idss").val();
          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("expenses/get_voucher_id_others"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        phone: phone // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })
        $("#client_contact").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var team_member =$("#client_contact").val();

          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("expenses/get_client_voucher_id"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        team_member: team_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })

$("#vendor_contact").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var team_member =$("#vendor_contact").val();

          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("expenses/get_client_voucher_id"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        team_member: team_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })
</script>
<script type="text/javascript">
     $("#voucher_no").change(function () {
        var voucher_no =$("#voucher_no").val();
        $("#v_filess,#v_status").hide()   

        $.ajax({
                    url: "<?php echo get_uri("expenses/get_voucher_details"); ?>",
                    data: {item_name: voucher_no},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
 
                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                           /*$("#description").val(response.item_info.description);
                            $("#expense_date").val(response.item_info.expense_date);
                            $("#category_id").select2("val", response.item_info.category_id);
                            $("#amount").val(response.item_info.amount);
                               $("#expense_project_id").select2("val",response.item_info.project_id);
                               $("#expense_user_id").select2("val",response.item_info.user_id);
*/  
$("#v_filess,#v_status").show()   
   $("#filess").html(response.item_files);
     $("#voucher_status").html(response.item_status);

$("#description").val(response.item_info.description).attr('readonly', true);
                    $("#expense_date").val(response.item_info.expense_date).attr('readonly', true);
                            $("#category_id").select2("val", response.item_info.category_id);
                            $("#amount").val(response.item_info.amount).attr('readonly', true);
                            $("#currency").val(response.item_info.currency).attr('readonly', true);
                            $("#currency_symbol").val(response.item_info.currency_symbol).attr('readonly', true);
                               $("#expense_project_id").select2("val",response.item_info.project_id);
                               $("#expense_user_id").select2("val",response.item_info.user_id);

         $("#expense_project_id").select2('readonly', true)
    $("#category_id").select2('readonly', true)

                      }
                    }
                });

})

</script><?php 

if($model_info->voucher_no)
{?>
<script type="text/javascript">
            var voucher_no =$("#voucher_no").val();
        $.ajax({
                    url: "<?php echo get_uri("expenses/get_voucher_details"); ?>",
                    data: {item_name: voucher_no},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
                        if (response && response.success) {
   $("#filess").html(response.item_files);
     $("#voucher_status").html(response.item_status);
               $("#expense_project_id").select2('readonly', true)
    $("#category_id").select2('readonly', true)                }
                    }
                });
</script>
<?php } ?>

<script type="text/javascript">
    $("#without_gst").on("click", function() {
   
        // $("#expense_item_hsn_code").hide()
        $("#s").hide()
        $("#y").hide()
        $("#z").hide()
        $("#a").hide()
       $("#without_inclusive_tax").click()
        $("#expense_item_hsn_code_description").hide()
        $("#expense_item_gst").hide()
});
</script>
<script type="text/javascript">
    $("#with_gst").on("click", function() {
   
        // $("#expense_item_hsn_code").show()
        $("#s").show()
        $("#y").show()
        $("#z").show()
       $("#a").show()
        $("#expense_item_hsn_code_description").show()
        $("#expense_item_gst").show()
});
</script>
<?php 

if($model_info->with_inclusive_tax=="yes")
{?>
<script type="text/javascript" >
$( document ).ready(function() {
var total = $("#total").val();
$("#amount").val(total);

});
</script>
<?php } ?>

   
    
<!-- <?php  if(!$model_info->files){ ?>   
 <script>
$('#file_upload').click(function () {
    var team_member =$("[name='file_names[]']").val();
    if(team_member)
    {
    $('#file_alert').hide()
    return true;
    }
    else
    {
    $('#file_alert').show()
    return false;
    }
});
    </script>
    <?php }  ?>  -->
    <script>
$( "#member_type").change(function(e) {
            $("#v_filess,#v_status").hide()   

    if($("#member_type").val()=="others"){
       
                        $("#otherss").show()
                        $("#team").hide()
$("#outsource").hide()
$("#clients").hide()
           $("#vendors").hide()
           $("#client_member_contact").hide()
                $("#vendor_member_contact").hide()
    }else if($("#member_type").val()=="tm"){
                
                $("#otherss").hide()
                        $("#team").show()
$("#outsource").hide()
$("#clients").hide()
           $("#vendors").hide()
           $("#client_member_contact").hide()
                $("#vendor_member_contact").hide()

    }else if($("#member_type").val()=="om"){
                
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").show()
                 $("#clients").hide()
           $("#vendors").hide()
           $("#client_member_contact").hide()
                $("#vendor_member_contact").hide()

    }else if($("#member_type").val()=="clients"){
                $("#clients").show()
$("#vendors").hide()
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").hide()
                $("#client_member_contact").show()
                $("#vendor_member_contact").hide()

    }else if($("#member_type").val()=="vendors"){
                $("#clients").hide()
$("#vendors").show()
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").hide()
$("#client_member_contact").hide()
                $("#vendor_member_contact").show()    }
    });
$(document).ready(function () {
                //$("#id").hide()
                //$("#pd").hide()



    });
</script>


<script >
    $("#expense_user_id,#vendor_contact,#client_contact,#expense_user_ids,#expense_user_idss").change(function () {
        $("#v_filess,#v_status").hide()   

         $("#description").val("").attr('readonly', false)
                            $("#expense_date").val("").attr('readonly', false)
                            $("#category_id").select2("val", " ")
                            $("#amount").val("").attr('readonly', false)
                             $("#expense_project_id").select2("val"," ")
                              
    });

    <?php if($model_info->voucher_no){ ?>
                $("#v_filess,#v_status").show()   

$("#description").attr('readonly', true);
                            $("#expense_date").attr('readonly', true);
                            $("#category_id").attr('readonly', true);
                            $("#amount").attr('readonly', true);
                               $("#expense_project_id").attr('readonly', true);
                               


         <?php  } ?>
</script>