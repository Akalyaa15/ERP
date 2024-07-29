<?php if($model_info->member_type=='tm') {?>
<script type="text/javascript">
    $(document).ready(function () {
                $("#otherss").hide()
                $("#outsource").hide()
                 $("#team").show()
                 $("#clients").hide()
                 $("#vendors").hide()
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
<?php if($model_info->r_member_type=='tm') {?>
<script type="text/javascript">
    $(document).ready(function () {
                $("#r_otherss").hide()
                $("#r_outsource").hide()
                 $("#r_team").show()
                 $("#r_clients").hide()
                 $("#r_vendors").hide()
$("#r_member_type").select2("destroy").val("tm");
$("#project_managers").hide()
$("#purchase_managers").hide()
});
</script>

<?php }else if($model_info->r_member_type=='om') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#r_otherss").hide()
                $("#r_outsource").show()
                 $("#r_team").hide()
                 $("#r_clients").hide()
                 $("#r_vendors").hide()
                 $("#r_member_type").select2("destroy").val("om");
                 $("#project_managers").hide()
$("#purchase_managers").hide()
});
</script>

<?php }else if($model_info->r_member_type=='others') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#r_otherss").show()
                $("#r_outsource").hide()
                 $("#r_team").hide()
                 $("#r_clients").hide()
                 $("#r_vendors").hide()
                 $("#r_member_type").select2("destroy").val("others");
                 $("#project_managers").hide()
$("#purchase_managers").hide()
});
</script>
<?php }else if($model_info->r_member_type=='clients') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#r_otherss").hide()
                $("#r_outsource").hide()
                 $("#r_team").hide()
                 $("#r_clients").show()
                 $("#r_vendors").hide()
                 $("#r_member_type").select2("destroy").val("clients")
                 $("#r_client_member_contact").show();
                 $("#purchase_managers").hide()
});
</script>
<?php }else if($model_info->r_member_type=='vendors') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#r_otherss").hide()
                $("#r_outsource").hide()
                 $("#r_team").hide()
                 $("#r_clients").hide()
                 $("#r_vendors").show()
                 $("#r_member_type").select2("destroy").val("vendors");
                 $("#r_vendor_member_contact").show();
                 $("#project_managers").hide()


});
</script>
<?php } ?>
<?php 
$payment_methods_id = $this->Voucher_model->get_all_where(array("deleted" => 0,"id" => $estimate_id))->row();
$payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0,"id"=>$payment_methods_id->payment_method_id))->row();
$voucher_type_id = $this->Voucher_model->get_all_where(array("deleted" => 0,"id" => $estimate_id))->row();
$voucher_type = $this->Voucher_types_model->get_all_where(array("deleted" => 0,"id"=>$voucher_type_id->voucher_type_id))->row();
 ?>

<?php echo form_open(get_uri("voucher/save_item"), array("id" => "expense-form", "class" => "general-form", "role" => "form")); ?>   <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
 <input type="hidden" name="line_manager" id="line_manager"  >
<div class="modal-body clearfix">
    <div id="expense-dropzone" class="post-dropzone">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <label for="payment_methods" class=" col-md-3"><?php echo lang('voucher_type'); ?></label>
            <div class=" col-md-9">
               <?php
                echo form_input(array(
                    "id" => "voucher_type",
                    "value" => $voucher_type->title ,
                    "class" => "form-control",
                    "readonly"=>"true",
                    "placeholder" => lang('voucher_type'),
                    "autofocus" => true,
                    
                    
                ));
                ?>
            </div>
        </div>
         <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('issuer_type'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='member_type' name='member_type' required>
                    <option value="">-</option>

  <option value="tm">Team members </option>
  <option value="om">Outsource members </option>
   <option value="clients">Clients </option>
  <option value="vendors">Vendors </option>
  <option value="others">Others </option>

</select>
            </div>
        </div>
        

        <div id="otherss" style="display: none">
            <div class="form-group">
                <label for="name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "first_name",
                        "name" => "first_name",
                         "value" => $model_info->f_name,
                        "class" => "form-control",
                        "placeholder" => lang('first_name'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class=" col-md-3"><?php echo lang('last_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "last_name",
                        "name" => "last_name",
                         "value" => $model_info->l_name,
                        "class" => "form-control",
                        "placeholder" => lang('last_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class=" col-md-3"><?php echo lang('mailing_address'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                         "value" => $model_info->address,
                        "class" => "form-control",
                        "placeholder" => lang('mailing_address'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                         "value" => $model_info->phone,
                        "class" => "form-control",
                        "placeholder" => lang('phone'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            </div>
 <div class="form-group" id="team" style="display: none">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('issuer'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("estimate_client_id", $members_dropdown, array($model_info->user_id), "class='select2 validate-hidden' id='estimate_client_ids' ");
                ?>
            </div>
        </div>
<div class="form-group" id="outsource" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('issuer'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("estimate_client_idss", $rm_members_dropdown, $model_info->user_id, " class='select2 validate-hidden' id='rm_member'");
                ?>
            </div>
        </div>
<div class="form-group" id="vendors" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('issuer'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("vendor_member", $vendors_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='vendor_member'");
                ?>
            </div>
        </div>  
        <div class="form-group" id="clients" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('issuer'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("client_member", $clients_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='client_member'");
                ?>
            </div>
        </div>
         <div class="form-group" id="client_member_contact" style="display: none">
            <label for="category_id" class=" col-md-3"><?php echo lang('represented_by'); ?></label>
            <div class="col-md-9">
       <?php
                    echo form_input(array(
                        "id" => "client_contact",
                        "name" => "i_represent",
                         "value" => $model_info->i_represent,
                        "class" => "form-control",
                        "placeholder" => lang('represented_by'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
    </div>
        </div>
        
        <div class="form-group" id="vendor_member_contact"  style="display: none">
            <label for="category_id" class=" col-md-3"><?php echo lang('represented_by'); ?></label>
            <div class="col-md-9">
         <?php
                    echo form_input(array(
                        "id" => "vendor_contact",
                        "name" => "i_represents",
                         "value" => $model_info->i_represent,
                        "class" => "form-control",
                        "placeholder" => lang('represented_by'),
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
                echo form_dropdown("category_id", $categories_dropdown, $model_info->category_id, "class='select2 validate-hidden' id='category_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class=" form-group">
            <label for="expense_date" class=" col-md-3"><?php echo lang('date'); ?></label>
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
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('receiver_type'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='r_member_type' name='r_member_type' required>
                    <option value="">-</option>

  <option value="tm">Team members </option>
  <option value="om">Outsource members </option>
   <option value="clients">Clients </option>
  <option value="vendors">Vendors </option>
  <option value="others">Others </option>

</select>
            </div>
        </div>
        
        <div id="r_otherss" style="display: none">
            <div class="form-group">
                <label for="name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "r_f_name",
                        "name" => "r_f_name",
                         "value" => $model_info->r_f_name,
                        "class" => "form-control",
                        "placeholder" => lang('first_name'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class=" col-md-3"><?php echo lang('last_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "r_l_name",
                        "name" => "r_l_name",
                         "value" => $model_info->r_l_name,
                        "class" => "form-control",
                        "placeholder" => lang('last_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class=" col-md-3"><?php echo lang('mailing_address'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "r_address",
                        "name" => "r_address",
                         "value" => $model_info->r_address,
                        "class" => "form-control",
                        "placeholder" => lang('mailing_address'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "r_phone",
                        "name" => "r_phone",
                         "value" => $model_info->r_phone,
                        "class" => "form-control",
                        "placeholder" => lang('phone'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            </div>
 <div class="form-group" id="r_team" style="display: none">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('receiver'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("r_estimate_client_id", $members_dropdown, array($model_info->r_user_id), "class='select2 validate-hidden' id='estimate_client_ids' ");
                ?>
            </div>
        </div>
<div class="form-group" id="r_outsource" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('receiver'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("r_estimate_client_idss", $rm_members_dropdown, $model_info->r_user_id, " class='select2 validate-hidden' id='rm_member'");
                ?>
            </div>
        </div>
<div class="form-group" id="r_vendors" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('receiver'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("r_vendor_member", $vendors_dropdown, $model_info->r_user_id, "class='select2 validate-hidden' id='r_vendor_member'");
                ?>
            </div>
        </div>  
        <div class="form-group" id="r_clients" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('receiver'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("r_client_member", $clients_dropdown, $model_info->r_user_id, "class='select2 validate-hidden' id='r_client_member'");
                ?>
            </div>
        </div>
         <div class="form-group" id="r_client_member_contact" style="display: none">
            <label for="category_id" class=" col-md-3"><?php echo lang('represented_by'); ?></label>
            <div class="col-md-9">
       <?php
                    echo form_input(array(
                        "id" => "r_client_contact",
                        "name" => "r_represent",
                         "value" => $model_info->r_represent,
                        "class" => "form-control",
                        "placeholder" => lang('represented_by'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
    </div>
        </div>
        
        <div class="form-group" id="r_vendor_member_contact"  style="display: none">
            <label for="category_id" class=" col-md-3"><?php echo lang('represented_by'); ?></label>
            <div class="col-md-9">
       <?php
                    echo form_input(array(
                        "id" => "r_vendor_contact",
                        "name" => "r_represents",
                         "value" => $model_info->r_represent,
                        "class" => "form-control",
                        "placeholder" => lang('represented_by'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
    </div>
        </div>
        <div class="form-group">
            <label for="payment_methods" class=" col-md-3"><?php echo lang('payment_methods'); ?></label>
            <div class=" col-md-9">
               <?php
                echo form_input(array(
                    "id" => "payment_methods",
                    "value" => $payment_methods->title ,
                    "class" => "form-control",
                    "readonly"=>"true",
                    "placeholder" => lang('cheque_no'),
                    "autofocus" => true,
                    
                    
                ));
                ?>
            </div>
        </div>
        <div class="form-group" id="cheque_field" style="display: none;">
            <label for="title" class=" col-md-3"><?php if($payment_methods_id->payment_method_id==8){echo lang('dd_no');}else if($payment_methods_id->payment_method_id==7){echo lang('cheque_no');} ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "cheque_nos",
                    "value" => $model_info->cheque_no ,
                    "class" => "form-control",
                    "placeholder" => lang('field_type_number'),
                    "autofocus" => true,
                    
                    
                ));
                ?>
            </div>
        </div>
       
        <div class=" form-group" id="cheque_drawn" style="display: none;">
            <label for="expense_date" class=" col-md-3"><?php echo lang('drawn_on'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "drawn_on",
                    "name" => "drawn_on",
                    "value" => $model_info->drawn_on? $model_info->drawn_on: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    
                    
                ));
                ?>
            </div>
        </div>
         <div class="form-group" id="utr_no" style="display: none;">
            <label for="title" class=" col-md-3"><?php echo lang('utr_no'); ?></label>
            <div class=" col-md-9" >
                <?php
                echo form_input(array(
                    "id" => "utr_nos",
                    "value" => $model_info->cheque_no ,
                    "class" => "form-control",
                    "placeholder" => lang('utr_no'),
                    "autofocus" => true,
                    
                    
                ));
                ?>
            </div>
        </div>
          <div class="form-group">
            <label for="country_id" class=" col-md-3"><?php echo lang('country'); ?></label>
            <div class="col-md-9">
       <?php
                    echo form_input(array(
                        "id" => "country_id",
                        "name" => "country_id",
                         "value" => $model_info->country_id,
                        "class" => "form-control validate-hidden",
                        "placeholder" => lang('country'),
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
        <!--div class=" form-group">
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
        </div-->
        <div class="form-group">
            <label for="expense_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("expense_project_id", $projects_dropdown, $model_info->project_id, "class='select2 validate-hidden' id='expense_project_id'");
                ?>
            </div>
        </div>
              <div class="form-group" id="project_managers" style="display: none;">
            <label  class=" col-md-3"><?php echo lang('project_manager'); ?></label>
            <div class="col-md-9" id="project_manager">
        
    </div>
        </div>       
              <div class="form-group" id="purchase_managers" style="display: none;">
            <label  class=" col-md-3"><?php echo lang('purchase_manager'); ?></label>
            <div class="col-md-9" id="purchase_manager">
        
    </div>
        </div>         <div class="form-group">
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
            <label class=" col-md-3"></label>
            <div class="col-md-9">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div> 
        </div>

        <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

        <?php $this->load->view("includes/dropzone_preview"); ?>    
        <div class="modal-footer">
            <div class="row">
                <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save_preview'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
$("#client_contact,#vendor_contact,#r_client_contact,#r_vendor_contact").select2({
                multiple: false,
                data: <?php echo json_encode($client_members_dropdown); ?>
            }); 

$("#country_id").select2({
                multiple: false,
                data: <?php echo json_encode($country_dropdown); ?>
            }); 


     $("#client_contact,#vendor_contact,#r_client_contact,#r_vendor_contact").select2("readonly", true);

        var uploadUrl = "<?php echo get_uri("voucher/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("voucher/validate_expense_file"); ?>";

        var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);

        $("#expense-form").appForm({
            onSuccess: function (result) {
                if (typeof $EXPENSE_TABLE !== 'undefined') {
                    $EXPENSE_TABLE.appTable({newData: result.data, dataId: result.id});
                } else {
                    window.location = "<?php echo site_url('voucher/preview'); ?>/" + result.estimate_id;
                }
            }
        });



        
       

        setDatePicker("#expense_date");
setDatePicker("#drawn_on");
        $("#expense-form .select2").select2();




      
            
                //get existing item info
               $("#country_id").change(function () {
                var country_name =$("#country_id").val();
                $.ajax({

                    url: "<?php echo get_uri("clients/get_country_item_info_suggestion"); ?>",
                    data: {item_name: country_name},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                           
                                $("#currency_symbol").val(response.item_info.currency_symbol);
                           

                           
                                $("#currency").val(response.item_info.currency);
                          
                            


                            
                       

                        }
                    }
                });
            });


    });
</script>
<script type="text/javascript">
    $("#client_member").change(function () {
    $("#client_contact").val("").attr('readonly', false)
                    var team_member =$("#client_member").val();

          $("#client_contact").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("voucher/get_client_contacts"); ?>",
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
                url: "<?php echo get_uri("voucher/get_vendor_contacts"); ?>",
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
    $("#r_client_member").change(function () {
    $("#r_client_contact").val("").attr('readonly', false)
                    var team_member =$("#r_client_member").val();

          $("#r_client_contact").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("voucher/get_client_contacts"); ?>",
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
    $("#r_vendor_member").change(function () {
    $("#r_vendor_contact").val("").attr('readonly', false)
                    var team_member =$("#r_vendor_member").val();

          $("#r_vendor_contact").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("voucher/get_vendor_contacts"); ?>",
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
    $("#expense_project_id").change(function () {
                    var project_id =$("#expense_project_id").val();
                    if(project_id>0){
     if($("#r_member_type").val()!="vendors"){
                          $.ajax({

                    url: "<?php echo get_uri("voucher/get_project_manager"); ?>",
                    data: {project_id: project_id},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                        $("#line_manager").val(response.id)

                        $("#project_managers").show()                        
                        $("#project_manager").html(response.text)
                        if(!response.id){
                        $("#line_manager").val(0)                            
                        $("#project_manager").html("Not Assigned")
                        }                        }
                    }
                });
     }else if($("#r_member_type").val()=="vendors"){
                          $.ajax({

                    url: "<?php echo get_uri("voucher/get_purchase_manager"); ?>",
                    data: {project_id: project_id},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                        $("#line_manager").val(response.id)
                        $("#purchase_managers").show()                        
                        $("#purchase_manager").html(response.text)
                        }
                    }
                });
     }                       
 }else{
                            $("#project_manager").html("Not Assigned")

 }


        })
</script>
<?php if($model_info->project_id>0){ ?>
    <script type="text/javascript">
    $(document).ready(function () {
var project_id =$("#expense_project_id").val();
     if($("#r_member_type").val()!="vendors"){
                          $.ajax({

                    url: "<?php echo get_uri("voucher/get_project_manager"); ?>",
                    data: {project_id: project_id},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                        $("#line_manager").val(response.id)
                        $("#project_managers").show()                        
                        $("#project_manager").html(response.text)
                        }
                    }
                });
     }else if($("#r_member_type").val()=="vendors"){
                          $.ajax({

                    url: "<?php echo get_uri("voucher/get_purchase_manager"); ?>",
                    data: {project_id: project_id},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                        $("#line_manager").val(response.id)
                        $("#purchase_managers").show()                        
                        $("#purchase_manager").html(response.text)
                        }
                    }
                });
     }
 });</script>
<?php } ?>    
<script>
$( "#member_type").change(function(e) {
    if($("#member_type").val()=="others"){
       
                        $("#otherss").show()
                        $("#team").hide()
$("#outsource").hide()
$("#clients").hide()
$("#vendors").hide()
    }else if($("#member_type").val()=="tm"){
                
               
                        $("#team").show()
$("#otherss").hide()
$("#outsource").hide()
$("#clients").hide()
$("#vendors").hide()
$("#client_member_contact").hide()
$("#vendor_member_contact").hide()
    }else if($("#member_type").val()=="om"){
                $("#clients").hide()
$("#vendors").hide()
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").show()
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
</script>
<script>
$( "#r_member_type").change(function(e) {
$("#expense_project_id").select2('val','0');

    if($("#r_member_type").val()=="others"){
       
                        $("#r_otherss").show()
                        $("#r_team").hide()
$("#r_outsource").hide()
$("#r_clients").hide()
$("#r_vendors").hide()
$("#project_managers").hide()
$("#purchase_managers").hide()
                $("#r_client_member_contact").hide()
                $("#r_vendor_member_contact").hide()
    }else if($("#r_member_type").val()=="tm"){
                
               
                        $("#r_team").show()
$("#r_otherss").hide()
$("#r_outsource").hide()
$("#r_clients").hide()
$("#r_vendors").hide()
$("#project_managers").hide()
$("#purchase_managers").hide()
                $("#r_client_member_contact").hide()
                $("#r_vendor_member_contact").hide()
    }else if($("#r_member_type").val()=="om"){
                $("#r_clients").hide()
$("#vendors").hide()
                $("#r_otherss").hide()
                $("#r_team").hide()
                $("#r_outsource").show()
$("#project_managers").hide()
$("#purchase_managers").hide()
                $("#r_client_member_contact").hide()
                $("#r_vendor_member_contact").hide()
    }else if($("#r_member_type").val()=="clients"){
                $("#r_clients").show()
$("#r_vendors").hide()
                $("#r_otherss").hide()
                $("#r_team").hide()
                $("#r_outsource").hide()
                $("#r_client_member_contact").show()
                $("#r_vendor_member_contact").hide()
$("#purchase_managers").hide()
    }else if($("#r_member_type").val()=="vendors"){
                $("#r_clients").hide()
$("#r_vendors").show()
                $("#r_otherss").hide()
                $("#r_team").hide()
                $("#r_outsource").hide()
$("#r_client_member_contact").hide()
                $("#r_vendor_member_contact").show()
                $("#project_managers").hide()
    }
    });
</script>
<?php if($payment_methods_id->payment_method_id==7||$payment_methods_id->payment_method_id==8){ ?>
<script>
$(document).ready(function () { 
                $("#cheque_field").show()
                $("#cheque_drawn").show()
 $('#cheque_nos').attr('name', 'cheque_no');


    });
</script>

<?php } ?>
<?php if($payment_methods_id->payment_method_id==4||$payment_methods_id->payment_method_id==5||$payment_methods_id->payment_method_id==6){ ?>
<script>
$(document).ready(function () { 
                $("#utr_no").show()    
                 $('#utr_nos').attr('name', 'cheque_no');
       
    });
</script>

<?php } ?>