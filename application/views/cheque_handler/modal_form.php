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
                 $("#member_type").select2("destroy").val("clients");
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
});
</script>
<?php } ?><?php echo form_open(get_uri("cheque_handler/save"), array("id" => "tax-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div id="expense-dropzone" class="post-dropzone">

    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('member'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='member_type' name='member_type' required>
                    <option value="">-</option>

  <option value="tm">Team members </option>
  <option value="om">Outsource members </option>
  <option value="clients">Clients </option>
  <option value="vendors">Vendors </option>
  <option value="others">Other Members </option>
</select>
            </div>
        </div>
 <div class="form-group" id="team" style="display: none">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("tm_member", $tm_dropdown, array($model_info->member_id), "class='select2 validate-hidden' id='tm_member' ");
                ?>
            </div>
        </div>
        
<div class="form-group" id="outsource" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('outsource_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("rm_member", $rm_dropdown, $model_info->member_id, "class='select2 validate-hidden' id='rm_member'");
                ?>
            </div>
        </div>   
        <div class="form-group" id="vendors" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('vendors'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("vendor_member", $vendors_dropdown, $model_info->member_id, "class='select2 validate-hidden' id='vendor_member'");
                ?>
            </div>
        </div>  
        <div class="form-group" id="clients" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('clients'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("client_member", $clients_dropdown, $model_info->member_id, "class='select2 validate-hidden' id='client_member'");
                ?>
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
                         "value" => $model_info->first_name,
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
                         "value" => $model_info->last_name,
                        "class" => "form-control",
                        "placeholder" => lang('last_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <!--div class="form-group">
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
            </div-->
            </div> 
    <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='payment_mode' name='payment_mode' required>

<option value="cheque">Cheque </option>
  <option value="dd">Demand Draft </option>
</select>
            </div>
        </div>              
    <div class="form-group">
        <label for="bank_name" class=" col-md-3"><?php echo lang('bank_name'); ?></label>
        <div class=" col-md-9">
           <?php 
                echo form_dropdown("bank_name", $bank_list_dropdown, array($model_info->bank_name),  "class='select2 validate-hidden' id='bank_name' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
        </div>
    </div>
   <div class="form-group">
        <label for="account_no" class=" col-md-3"><?php echo lang('account_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "account_number",
                "name" => "account_number",
                "value" => $model_info->account_number,
                "class" => "form-control",
                "placeholder" => lang('account_number'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cheque_no" class=" col-md-3"><?php echo lang('cheque_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "cheque_no",
                "name" => "cheque_no",
                "value" => $model_info->cheque_number,
                "class" => "form-control",
                "placeholder" => lang('cheque_no'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
     <div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount ? $model_info->amount : "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<div class=" form-group">
            <label for="issue_date" class=" col-md-3"><?php echo lang('issue_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "issue_date",
                    "name" => "issue_date",
                    "value" => $model_info->issue_date,
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class=" form-group">
            <label for="drawn_on" class=" col-md-3"><?php echo lang('drawn_on'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "drawn_on",
                    "name" => "drawn_on",
                    "value" => $model_info->drawn_on,
                    "class" => "form-control"
                   
                ));
                ?>
            </div>
        </div>
        <div class=" form-group">
            <label for="valid_upto" class=" col-md-3"><?php echo lang('valid_upto'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "valid_upto",
                    "name" => "valid_upto",
                    "value" => $model_info->valid_upto,
                    "class" => "form-control"
                ));
                ?>
            </div>
        </div>
    <div class="form-group">
        <label for="cheque_category" class=" col-md-3"><?php echo lang('cheque_category'); ?></label>
        <div class="col-md-9">
           <?php 
                echo form_dropdown("cheque_category", $cheque_category_dropdown, array($model_info->cheque_category_id    ), "class='select2 validate-hidden' id='cheque_category' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
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
                "value" => $model_info->description,
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<div class="form-group">
        <label for="status" class=" col-md-3"><?php echo lang('status'); ?></label>
        <div class=" col-md-9">
           <?php 
                echo form_dropdown("status_id", $status_dropdown, array($model_info->status_id    ), "class='select2 validate-hidden' id='status_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
        </div>
    </div>
    <div class="form-group">
    <label class="col-md-3"></label>
    <div class="col-md-9">
        <?php
        $this->load->view("includes/file_list", array("files" => $model_info->files));
        ?>
    </div>
</div>

<?php
if (isset($custom_fields) && !empty($custom_fields)) {
    $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => "col-md-9"));
}
?>

<?php $this->load->view("includes/dropzone_preview"); ?>

<div class="modal-footer">


            <div class="row">
<p id="file_alert" style="color: red;display: none">*Uploading files are required</p>
                <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
                <button id="file_upload" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php if($model_info->payment_mode=='cheque') {?>
<script type="text/javascript">
    $(document).ready(function () {
$("#payment_mode").select2("destroy").val("cheque");
});
</script>

<?php }else if($model_info->payment_mode=='dd') { ?>
<script type="text/javascript">
    $(document).ready(function () {
          $("#payment_mode").select2("destroy").val("dd");
});
</script><?php } ?>
<script>
 <?php if(!$model_info->files){ ?>   
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
    
    <?php } ?></script>
<script type="text/javascript">
    $(document).ready(function() {
        var uploadUrl = "<?php echo get_uri("expenses/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("expenses/validate_expense_file"); ?>";
var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);
        $("#tax-form").appForm({
            onSuccess: function(result) {
                $("#tools-table").appTable({newData: result.data, dataId: result.id});
                location.reload()
            }
        });
        $("#title").focus();
        $("#member_type").select2();
        $("#tm_member").select2();
        $("#rm_member").select2();
        $("#vendor_member").select2();
        $("#client_member").select2();
        $("#bank_name").select2();
        $("#cheque_category").select2();
        $("#status_id").select2();
        $("#payment_mode").select2();
 setDatePicker("#issue_date, #drawn_on,#valid_upto");
    });
</script>    <script>
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

    }else if($("#member_type").val()=="om"){
                $("#clients").hide()
$("#vendors").hide()
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").show()

    }else if($("#member_type").val()=="clients"){
                $("#clients").show()
$("#vendors").hide()
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").hide()

    }else if($("#member_type").val()=="vendors"){
                $("#clients").hide()
$("#vendors").show()
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").hide()

    }
    });
</script>
<script type="text/javascript">
$("#drawn_on").on("change", function(){
       var date = new Date($("#drawn_on").val()),
           days = 90;
        
       date.setDate(date.getDate() + days);
       $("#valid_upto").val(date.toInputFormat());
       

    });
    
    
    //From: http://stackoverflow.com/questions/3066586/get-string-in-yyyymmdd-format-from-js-date-object
    Date.prototype.toInputFormat = function() {
       var yyyy = this.getFullYear().toString();
       var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
       var dd  = this.getDate().toString();
       return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
    };
    </script> <!-- <?php  /* if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        echo $ip;  */?> -->