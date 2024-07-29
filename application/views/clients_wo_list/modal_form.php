<?php echo form_open(get_uri("clients_wo_list/save"), array("id" => "expense-form", "class" => "general-form", "role" => "form")); ?>
<div id="expense-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
    
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        
        <div class="form-group">
            <label for="invoice_no" class=" col-md-3"><?php echo lang('wo_no'); ?></label>
            <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "invoice_no",
            "name" => "invoice_no",
            "value" => $model_info->invoice_no,
            "class" => "form-control",
            "placeholder" => lang('wo_no'),
            "autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required")

        ));
        ?>
    </div>
        </div>
        <div class=" form-group">
            <label for="invoice_date" class=" col-md-3"><?php echo lang('wo_date'); ?></label>
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
       
       <?php if ($vendor_id) { ?>
        <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class=" col-md-9">
                 <?php
                echo form_dropdown("vendor_id", $vendors_dropdown, array($model_info->vendor_id), "class='select2 validate-hidden' id='vendor_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <?php } ?>
       <!-- <div class="form-group">
            <label for="vendor_name" class=" col-md-3"><?php echo lang('clients'); ?></label>
            <div class=" col-md-9">
                <?php /*
                echo form_dropdown("vendor_id", $vendors_dropdown, array($model_info->vendor_id), "class='select2 validate-hidden' id='vendor_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
               */ ?>
            </div>
        </div> -->
        <div class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('proforma_no'); ?></label>
            <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "purchase_order_id",
            "name" => "purchase_order_id",
            "value" => $model_info->purchase_order_id,
            "class" => "form-control",
            "placeholder" => lang('proforma_no'),
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

        <div class="form-group" id="igst_app" >
            <label for="igst_tax" class=" col-md-3"><?php echo lang('tax'); ?></label>
            <div class=" col-md-9">
    <?php
        echo form_inputnumber(array(
            "id" => "igst_tax",
            "name" => "igst_tax",
            "value" => $model_info->igst_tax,
            "class" => "form-control",
            "min"=>0,
            "placeholder" => lang('tax_amount'),
            "autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>
<!--/div-->  
        <div class="form-group" >
            <label for="title" class=" col-md-3"><?php echo lang('total'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "total",
                    "name" => "total",
                    "value" => $model_info->total ,
                    "class" => "form-control",
                    "placeholder" => lang('amount'),
                   "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                   "readonly"=> "true",
                    
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

<?php
// Check if $custom_fields is set before loading the view
if (isset($custom_fields)) {
    $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9"));
}
?>

<?php $this->load->view("includes/dropzone_preview"); ?>
<p id="file_alert" style="color: red;display: none">*Uploading files are required</p>

</div>

    <div class="modal-footer">

            <div class="row">
      <div id="link-of-task-view" class="hide">
            <?php
            echo modal_anchor(get_uri("clients_wo_list/task_view"), "", array());
            ?>
        </div>

            

                <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>

                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
                 <button id="save-and-show-button" type="button" class="btn btn-success" ><span class="fa fa-check-circle"></span> <?php echo lang('save_and_add_payment'); ?></button>
                <button id="file_upload" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
</div>

<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {

$("#purchase_order_id").select2({
                multiple: false,
                data: <?php echo json_encode($purchase_id_dropdown); ?>
            });
       var uploadUrl = "<?php echo get_uri("clients_wo_list/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("clients_wo_list/validate_vendor_file"); ?>";

        var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);

      
        window.showAddNewModal = false;

        $("#save-and-show-button").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");

        });

        var taskInfoText = "<?php echo lang('add_payment') ?>";

        window.taskForm = $("#expense-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#monthly-clients_wo_list-table").appTable({newData: result.data, dataId: result.id});
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
        
     



$("#igst_tax, #amount").on('keyup',function () {
        $("#total").val("").attr('readonly', true)
           
      
var amount=$("#amount").val()


var igst = $("#igst_tax").val()


var company_gsts = parseFloat(igst)+parseFloat(amount);
var company_amount =parseFloat(amount);
if(company_gsts){
    $("#total").val(company_gsts);
}else if(company_amount){
    $("#total").val(company_amount);
}

})   

        setDatePicker("#invoice_date");

        

        $("#expense-form .select2").select2();


});



</script>
<script type="text/javascript">

    $("#vendor_id").change(function () {
    $("#purchase_order_id").val("").attr('readonly', false)
                    var vendor_member =$("#vendor_id").val();

          $("#purchase_order_id").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("clients_wo_list/get_purchase_orderid"); ?>",
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

       <?php if($vendor_id){ ?>
        <script type="text/javascript">

   
    $("#purchase_order_id").val("").attr('readonly', false)
                    var vendor_member ='<?php echo $vendor_id ?>';

          $("#purchase_order_id").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("clients_wo_list/get_purchase_orderid"); ?>",
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