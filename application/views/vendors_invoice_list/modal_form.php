<?php
// Check if $custom_fields is not set or null, then initialize it as an empty array
if (!isset($custom_fields) || is_null($custom_fields)) {
    $custom_fields = array(); // Initialize $custom_fields as an empty array
}
?>

<?php echo form_open(get_uri("vendors_invoice_list/save"), array("id" => "expense-form", "class" => "general-form", "role" => "form")); ?>
<div id="expense-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
         <input type="hidden"  id="company_gst" value="<?php echo get_setting("company_gstin_number_first_two_digits"); ?>" />
        
        <div class="form-group">
            <label for="invoice_no" class=" col-md-3"><?php echo lang('invoice_no'); ?></label>
            <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "invoice_no",
            "name" => "invoice_no",
            "value" => $model_info->invoice_no,
            "class" => "form-control",
            "placeholder" => lang('invoice_no'),
            "autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required")

        ));
        ?>
    </div>
        </div>
        <div class=" form-group">
            <label for="invoice_date" class=" col-md-3"><?php echo lang('invoice_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "invoice_date",
                    "name" => "invoice_date",
                    "value" => $model_info->invoice_date? $model_info->invoice_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <!--div class="form-group">
            <label for="payment_method_id" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2 validate-hidden' id='payment_method_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class="form-group" id="cheque_field"  
        style="display: none;">
            <label for="title" class=" col-md-3"><?php echo lang('cheque_no'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "cheque_no",
                    "name" => "cheque_no",
                    "value" => $model_info->cheque_no ,
                    "class" => "form-control",
                    "placeholder" => lang('cheque_no'),
                    //"autofocus" => true,
                    
                    
                ));
                ?>
            </div>
        </div>
        <div class="form-group" id="utr_no" style="display: none;">
            <label for="title" class=" col-md-3"><?php echo lang('utr_no'); ?></label>
            <div class=" col-md-9" >
                <?php
                echo form_input(array(
                    "id" => "utr_no",
                     "name" => "utr_no",
                    "value" => $model_info->utr_no ,
                    "class" => "form-control",
                    "placeholder" => lang('utr_no'),
                    "autofocus" => true,
                    
                    
                ));
                ?>
            </div>
        </div-->
       
       
        <?php if ($vendor_id) { ?>
        <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>" />
    <?php } else { ?>
        <div class="form-group" id="vendor_app">
            <label for="vendor_name" class="col-md-3"><?php echo lang('vendor_name'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("vendor_id", $vendors_dropdown, array($model_info->vendor_id), "class='select2 validate-hidden' id='vendor_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <?php } ?>
        <div class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('purchase_order_no'); ?></label>
            <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "purchase_order_id",
            "name" => "purchase_order_id",
            "value" => $model_info->purchase_order_id,
            "class" => "form-control",
            "placeholder" => lang('purchase_order_no'),
                        "readonly"=> "true",

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
            <label for="title" class=" col-md-3"><?php echo lang('amount'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_inputnumber(array(
                    "id" => "amount",
                    "name" => "amount",
                    "value" => $model_info->amount ,
                    "class" => "form-control",
                    "min"=>0,
                    "placeholder" => lang('amount'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),

                ));
                ?>
            </div>
        </div>
        <!--div class="form-group">
            <label for="cgst_tax" class=" col-md-3"><?php echo lang('cgst_tax'); ?></label>
            <div class=" col-md-9">
                <?php /*
                echo form_input(array(
                    "id" => "cgst_tax",
                    "name" => "cgst_tax",
                    "value" => $model_info->cgst_tax ,
                    "class" => "form-control",
                    "placeholder" => lang('cgst_tax'),
                    "autofocus" => true,
                    
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
                    "value" => $model_info->sgst_tax ,
                    "class" => "form-control",
                    "placeholder" => lang('sgst_tax'),
                    "autofocus" => true,
                    
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="igst_tax" class=" col-md-3"><?php echo lang('igst_tax'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "igst_tax",
                    "name" => "igst_tax",
                    "value" => $model_info->igst_tax ,
                    "class" => "form-control",
                    "placeholder" => lang('igst_tax'),
                    "autofocus" => true,
                   
                ));
               */ ?>
            </div>
        </div-->
        <!--div class="form-group">
        <label for="state_tax" class=" col-md-3"><?php echo lang('same_state'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('cgst_tax_sgst_tax'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-2">
            <?php
            echo form_radio("state_tax", "yes", ($model_info->state_tax=="yes") ? true : false, "id='state_tax'");
            ?>                       
        </div>
       
        <label for="central_tax" class=" col-md-3"><?php echo lang('other_state'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('igst_tax'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-2">
            <?php
            echo form_radio("state_tax", "no", ($model_info->state_tax=="no") ? true : false, "id='central_tax'");
            ?>                       
        </div>
  
    </div>
    <div id="state_tax_fields" class="<?php if (!$model_info->state_tax) echo "hide"; ?>"--> 
    <div class="form-group">
    <label for="title" class=" col-md-3"><?php echo lang('gst_number'); ?></label>
     <div class=" col-md-9">
        <?php
        echo form_input(array(
            "id" => "gst_number",
            "name" => "gst_number",
            "value" => $model_info->gst_number,
            "class" => "form-control",
            "placeholder" => lang('gst_number')
        ));
        ?>
        
    </div>
</div>
<div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('gstinnumber_firsttwodigits'); ?></label>
         <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "gstin_number_first_two_digits",
                "name" => "gstin_number_first_two_digits",
                "value" => $model_info->gstin_number_first_two_digits,
                "class" => "form-control",
                 "readonly" => "true",
                "placeholder" => lang('gstinnumber_firsttwodigits'),
                "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
               "readonly"=> "true",
            ));
            ?>
            
        </div>
    </div>
        <div class="form-group" id="cgst_app" style="display:none">
            <label for="cgst_tax" class=" col-md-3"><?php echo lang('cgst_tax'); ?></label>
            <div class=" col-md-9">
    <?php
        echo form_inputnumber(array(
            "id" => "cgst_tax",
            "name" => "cgst_tax",
            "value" => $model_info->cgst_tax,
            "class" => "form-control",
            "min"=>0,
            "placeholder" => lang('cgst_tax'),
            "autofocus" => true,
           "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>  
    <div class="form-group" id="sgst_app" style="display:none">
            <label for="sgst_tax" class=" col-md-3"><?php echo lang('sgst_tax'); ?></label>
            <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "sgst_tax",
                "name" => "sgst_tax",
                "value" => $model_info->sgst_tax,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('sgst_tax'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <!--/div-->

    <div id="central_tax_fields" class="<?php if (!isset($model_info->central_tax) || !$model_info->central_tax) echo "hide"; ?>">
    <div class="form-group" id="igst_app" style="display:none">
        <label for="igst_tax" class="col-md-3"><?php echo lang('igst_tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "igst_tax",
                "name" => "igst_tax",
                "value" => isset($model_info->igst_tax) ? $model_info->igst_tax : '',
                "class" => "form-control",
                "min" => 0,
                "placeholder" => lang('igst_tax'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="title" class="col-md-3"><?php echo lang('total'); ?></label>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "total",
            "name" => "total",
            "value" => isset($model_info->total) ? $model_info->total : '',
            "class" => "form-control",
            "placeholder" => lang('amount'),
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
            "readonly" => "true",
        ));
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

<?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => "col-md-9")); ?>

<?php $this->load->view("includes/dropzone_preview"); ?>
<p id="file_alert" style="color: red; display: none">*Uploading files are required</p>

<div class="modal-footer">
    <div class="row">
        <div id="link-of-task-view" class="hide">
            <?php
            echo modal_anchor(get_uri("vendors_invoice_list/task_view"), "", array());
            ?>
        </div>

        <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2">
            <i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?>
        </button>

        <button type="button" class="btn btn-default" data-dismiss="modal">
            <span class="fa fa-close"></span> <?php echo lang('close'); ?>
        </button>

        <button id="save-and-show-button" type="button" class="btn btn-success">
            <span class="fa fa-check-circle"></span> <?php echo lang('save_and_add_payment'); ?>
        </button>

        <button id="file_upload" type="submit" class="btn btn-primary">
            <span class="fa fa-check-circle"></span> <?php echo lang('save'); ?>
        </button>
    </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {

$("#purchase_order_id").select2({
                multiple: false,
                data: <?php echo json_encode($purchase_id_dropdown); ?>
            });
       var uploadUrl = "<?php echo get_uri("vendors_invoice_list/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("vendors_invoice_list/validate_vendor_file"); ?>";

        var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);

       /* $("#expense-form").appForm({
            onSuccess: function (result) {
                if (typeof $EXPENSE_TABLE !== 'undefined') {
                    $EXPENSE_TABLE.appTable({newData: result.data, dataId: result.id});
                } else {
                    location.reload();
                }
            }
        }); */
        window.showAddNewModal = false;

        $("#save-and-show-button").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");

        });

        var taskInfoText = "<?php echo lang('add_payment') ?>";

        window.taskForm = $("#expense-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#monthly-vendors_invoice_list-table").appTable({newData: result.data, dataId: result.id});
                //$("#reload-kanban-button").trigger("click");

                $("#save_and_show_value").append(result.save_and_show_link);

                if (window.showAddNewModal) {
                    var $taskViewLink = $("#link-of-task-view").find("a");
                    $taskViewLink.attr("data-title", taskInfoText + "#" + result.id);
                    $taskViewLink.attr("data-post-id", result.id);

                    $taskViewLink.trigger("click");
                } else {
                    window.taskForm.closeModal();
                }
            }
        });
        
     $("#state_tax").click(function () {
            
            $("#state_tax_fields").removeClass("hide");
            $("#central_tax_fieldss").addClass("hide");
            $("#igst_tax").val();
        });
       $("#central_tax").click(function () {
            
            $("#central_tax_fieldss").removeClass("hide");
            $("#state_tax_fields").addClass("hide");
            $("#cgst_tax").val();
            $("#sgst_tax").val();
        });

$('input[name=igst_tax]').change(function(){
          $("#cgst_tax").val("");
            $("#sgst_tax").val("");

});
$('input[name=cgst_tax]'||'input[name=sgst_tax]').change(function(){
         $("#igst_tax").val(""); 
            

}); 

$("#gst_number").on('keyup',function () {
        $("#gstin_number_first_two_digits").val("").attr('readonly', true)
                    var gst_number =$("#gst_number").val().substr(0,2);

          
          $("#gstin_number_first_two_digits").select2("val", gst_number);

var gst_state_code = $("#gstin_number_first_two_digits").val()
var company_gst = $("#company_gst").val();
if(company_gst==gst_state_code){
     $("#cgst_app").show();
     $("#sgst_app").show();
     $("#igst_app").hide();
}else if(company_gst!==gst_state_code){
    $("#igst_app").show();
    $("#cgst_app").hide();
    $("#sgst_app").hide();
}else{
     $("#igst_app").hide();
     $("#cgst_app").hide();
     $("#sgst_app").hide();
}

        })


$("#cgst_tax, #sgst_tax, #igst_tax, #amount").on('keyup',function () {
        $("#total").val("").attr('readonly', true)
        $("#sgst_tax").val("").attr('readonly', true)      

    var cgst = $("#cgst_tax").val()     
if(cgst){
    $("#sgst_tax").val(cgst)
}       
var amount=$("#amount").val()

var sgst = $("#sgst_tax").val()
var igst = $("#igst_tax").val()

var company_gst = parseFloat(sgst)+parseFloat(cgst)+parseFloat(amount);
var company_gsts = parseFloat(igst)+parseFloat(amount);
var company_amount =parseFloat(amount);
if(company_gst){
    $("#total").val(company_gst);
}else if(company_gsts){
    $("#total").val(company_gsts);
}else if(company_amount){
    $("#total").val(company_amount);
}

        })   

        setDatePicker("#invoice_date");

        <?php if (isset($gst_code_dropdown)) { ?>
            $("#gstin_number_first_two_digits").select2({
                multiple: false,
                data: <?php echo json_encode($gst_code_dropdown); ?>
            });
<?php } ?>

        $("#expense-form .select2").select2();



        //re-initialize item suggestion dropdown on request
        

    });



</script>
<script type="text/javascript">

    $("#vendor_id").change(function () {
    $("#purchase_order_id").val("").attr('readonly', false)
                    var vendor_member =$("#vendor_id").val();

          $("#purchase_order_id").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("vendors_invoice_list/get_purchase_orderid"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        vendor_member: vendor_member // search term
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
<?php /* if(!$model_info->files){ ?>   
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
<?php }  */ ?>

<?php /*<script>
$("#payment_method_id").change(function () { 

   var payment = $("#payment_method_id").val() ;
   if(payment==7||payment==8) {
                
                 $("#cheque_field").show()
                 $("#utr_no").hide() 
                //$("#cheque_drawn").show()
            // $('#cheque_nos').attr('name', 'cheque_no');
             }else if(payment==4||payment==5||payment==6){
                  $("#utr_no").show()  
                   $("#cheque_field").hide()  
                 //$('#utr_nos').attr('name', 'cheque_no');
             }else{
                $("#utr_no").hide()  
                   $("#cheque_field").hide()  
             }
       
    });
</script>
<?php 
if ($model_info->payment_method_id==7||$model_info->payment_method_id==8){ ?>
<script>
$(document).ready(function () { 
                $("#cheque_field").show()
                });
</script>

<?php } ?>
<?php 
if ($model_info->payment_method_id==4||$model_info->payment_method_id==5||$model_info->payment_method_id==6){ ?>
<script>
$(document).ready(function () { 
                $("#utr_no").show()  
                });
</script>

<?php } ?> */ ?>
<?php if (!empty($model_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_gst = get_setting("company_gstin_number_first_two_digits");
if($model_info->gstin_number_first_two_digits==$company_gst)
{ ?>
<script type="text/javascript" >

$( document ).ready(function() {
$("#sgst_app").show() 
$("#cgst_app").show() 
});
</script>
<?php }  ?>
<?php  
$company_gst = get_setting("company_gstin_number_first_two_digits");
if($model_info->gstin_number_first_two_digits!==$company_gst)
{ ?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#igst_app").show() 
 
});
</script>
<?php }  ?>
<?php } ?>

<?php if($vendor_id){ ?>
        <script type="text/javascript">

   
    $("#purchase_order_id").val("").attr('readonly', false)
                    var vendor_member ='<?php echo $vendor_id ?>';

          $("#purchase_order_id").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("vendors_invoice_list/get_purchase_orderid"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        vendor_member: vendor_member // search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
    
        </script>
    <?php } ?>

    <?php if($model_info->id) { ?>
    <script type="text/javascript">
        $("#vendor_app").hide();
    </script>

    <?php } ?>

<?php  /*

if($model_info->state_tax=="no")
{?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#central_tax").click() 
//$("#").click() 
//$("#cgst_tax").hide()
//$("#sgst_tax").hide() 


});
</script>
<?php } ?>
<?php 

if($model_info->state_tax=="yes")
{?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#state_tax").click() 
//$("#").click() 
//$("#igst_tax").hide()
//$("#cgst_tax").show() 
//$("#sgst_tax").show() 



});
</script>
<?php }  */?>