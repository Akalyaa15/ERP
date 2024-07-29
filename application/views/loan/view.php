<?php $max_paid_amount =$model_info->total-$model_info->paid_amount; ?>
<div class="modal-body clearfix general-form ">
    <div class="p10 clearfix">
        <div class="media m0 bg-white">
            <div class="media-left">
                <span class="avatar avatar-sm">
                    <img src="<?php echo get_avatar($model_info->assigned_to_avatar); ?>" alt="..." />
                </span>
            </div>
           
            <div class="media-body w100p pt5">
                <div class="media-heading m0">
                  <?php  if($model_info->member_type=="tm") { ?>
                   <?php echo $model_info->linked_user_name; ?>
                   <?php } else if($model_info->member_type=="clients") {?>
                   <?php echo $model_info->client_company; ?>
                   <?php } else if($model_info->member_type=="vendors")  {?>
                   <?php echo $model_info->vendor_company; ?>
                    <?php } else if($model_info->member_type=="om") { ?>
                   <?php echo $model_info->linked_user_name; ?>
                   <?php } else if($model_info->member_type=="others") { ?>
                   <?php echo $model_info->phone ?>
                   <?php } ?>
                </div>
                <p> 
                    <span class='label label-light mr5' title='Point'><?php echo $model_info->points; ?></span>

                    <?php echo $labels . " " . "<span class='label' style='background:$model_info->status_color; '>" . ($model_info->status_key_name ? lang($model_info->status_key_name) : $model_info->status_title) . "</span>"; ?>
                </p>
            </div>
        </div>
    </div>

<?php if ($model_info->title) { ?>
        <div class=" form-group">
            <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
 <?php } ?>  
    <?php if ($model_info->loan_date) { ?>  
        <div class=" form-group">
            <label for="loan_date" class=" col-md-3"><?php echo lang('loan_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->loan_date,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?> 
        
         <?php if ($model_info->due_date) { ?>  
        <div class=" form-group">
            <label for="due_date" class=" col-md-3"><?php echo lang('due_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->due_date,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?> 
         <?php if ($model_info->description) { ?>   
        <div class=" form-group">
            <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                   
                    "value" => $model_info->description,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?>
          <?php if ($model_info->amount) { ?> 
        <div class=" form-group">
            <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->amount,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?> 
        <?php if ($model_info->interest) { ?>  
        <div class=" form-group">
            <label for="interest" class=" col-md-3"><?php echo lang('interest'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->interest,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?> 
        <?php if ($model_info->interest_amount) { ?>  
        <div class=" form-group">
            <label for="interest_amount" class=" col-md-3"><?php echo lang('interest_amount'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->interest_amount,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?>  
        
        <?php if ($model_info->total) { ?>   
        <div class=" form-group">
            <label for="total" class=" col-md-3"><?php echo lang('total'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->total,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?>  
        <div class=" form-group">
            <label for="total" class=" col-md-3"><?php echo lang('paid_amount'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    //"id"=>"client_paid",
                    "value" => $model_info->paid_amount,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
       

        <div class=" form-group">
            <label for="total" class=" col-md-3"><?php echo lang('due'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   //"id"=>"client_due",
                    "value" => $max_paid_amount,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        

        <div class="panel panel-default">
                <div class="tab-title clearfix">
                    <h4> <?php echo lang('loan_payments_list'); ?></h4>
                </div>
                <div class="table-responsive">
                    <table id="loan-payment-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        <?php echo form_open(get_uri("loan/save_checklist_item"), array("id" => "checklist_form", "class" => "general-form", "role" => "form")); ?>
        <div id="expense-dropzone" class="post-dropzone">
        <div class="col-md-12 mb15 b-t">
            <div class="pb10 pt10">
                <strong><?php echo lang("add_payment_list"); ?></strong>
            </div>
            <input type="hidden" name="id" id="loan_id" value="<?php echo $model_info->id; ?>" />
            
            
             <input type="hidden" name="paid_total" id="paid_total" value="<?php echo $model_info->total; ?>" />
             <input type="hidden" name="total" id="total" value="<?php echo $model_info->total; ?>" />
            <div class="checklist-items">

            </div>
           <div class="form-group">
            <label for="payment_method_id" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2 validate-hidden' id='payment_method_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class="form-group">
                    <label for="reference_number" class=" col-md-3"><span id="ref_name"></span><!-- <?php echo lang('reference_number'); ?> --></label>
            <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "reference_number",
                            "name" => "reference_number",
                            "class" => "form-control",
                            "placeholder" => lang('reference_number'),
                           
                            
                            "data-rule-required" => true,
                            "data-msg-required" => lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_inputnumber(array(
                            "id" => "checklist-add-item",
                            "name" => "checklist-add-item",
                            "class" => "form-control",
                            "placeholder" => lang('add_amount'),
                            "autofocus" => "true",
                            "min"=>0,
                            "max" => $max_paid_amount,
                            "data-rule-required" => true,
                            "data-msg-required" => lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "checklist-add-item-date",
                            "name" => "checklist-add-item-date",
                            "class" => "form-control",
                            "placeholder" => lang('payment_date'),
                            "data-rule-required" => true,
                            "data-msg-required" => lang("field_required"),
                            "data-rule-lessThanOrEqual" =>get_my_local_time("Y-m-d"),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
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
                    //"value" => $model_info->description ? $model_info->description : "",
                    "class" => "form-control",
                    "placeholder" => lang('description'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>

            </div>
        </div>

 <div class="post-file-dropzone-scrollbar hide">
    <div  class="post-file-previews clearfix b-t"> 
        <div class="post-file-upload-row dz-image-preview dz-success dz-complete pull-left">
            <div class="preview" style="width:85px;">
                <img data-dz-thumbnail class="upload-thumbnail-sm" />
                <span id="remove" data-dz-remove="" class="delete">×</span>
                <div class="progress progress-striped upload-progress-sm active m0" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
            </div>
        </div>
    </div>
</div>





         <div class="modal-footer">
                <div id="checklist-options-panel" class="col-md-12 mb15 p0 hide">
                <div id="link-of-task-view" class="hide">
            <?php
            echo modal_anchor(get_uri("loan/loan_view"), "", array());
            ?>
        </div>
        <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                    <button type="submit"   id="checklist-options-panels" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('add_payment'); ?></button> 
                    <button id="checklist-options-panel-close" type="button" class="btn btn-default"><span class="fa fa-close"></span> <?php echo lang('cancel'); ?></button>
                </div>
            
        </div>
    </div>
</div>

    
        <?php echo form_close(); ?>
</div>
        <div class="row clearfix">
        <div class="col-md-12 b-t pt10 list-container">
           </div>
    </div>
</div>

<div class="modal-footer">
    <?php 
    
    echo modal_anchor(get_uri("loan/modal_form/"), "<i class='fa fa-pencil'></i> " . lang('edit_loan'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => lang('edit_loan')));

    ?>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>

   
<script type="text/javascript">
    $(document).ready(function () {

        var uploadUrl = "<?php echo get_uri("loan/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("loan/validate_loan_file"); ?>"; 
        var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);

        //make the checklist items sortable
        var $selector = $(".checklist-items");
        Sortable.create($selector[0], {
            animation: 150,
            chosenClass: "sortable-chosen",
            ghostClass: "sortable-ghost",
            onUpdate: function (e) {
                appLoader.show();
                //prepare checklist items indexes 
                var data = "";
                $.each($selector.find(".checklist-item-row"), function (index, ele) {
                    if (data) {
                        data += ",";
                    }

                    data += $(ele).attr("data-id") + "-" + parseInt(index + 1);
                });
                
                //update sort indexes
                $.ajax({
                    url: '<?php echo_uri("vendors_invoice_list/save_checklist_items_sort") ?>',
                    type: "POST",
                    data: {sort_values: data},
                    success: function () {
                        appLoader.hide();
                    }
                });
            }
        });

        $("#checklist_form .select2").select2();

/*window.showAddNewModal = false;

        $("#checklist-options-panels").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");

        });

        var taskInfoText = "<?php echo lang('add_payment') ?>";



        window.taskForm = $("#checklist_form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#loan-payment-table").appTable({newData: result.data, dataId: result.id});
                //$("#reload-kanban-button").trigger("click");

                //$("#save_and_show_value").append(result.save_and_show_link);

                if (window.showAddNewModal) {
                    var $taskViewLink = $("#link-of-task-view").find("a");
                    $taskViewLink.attr("data-title", taskInfoText + "#" + result.id);
                    $taskViewLink.attr("data-post-id", result.id);

                    $taskViewLink.trigger("click");
                } else {
                    window.taskForm.closeModal();
                }
            }
        }); */
        //add a clickable link in task title.
        $("#ajaxModalTitle").append('<?php echo $task_link ?>');

        //show the items in checklist
        $(".checklist-items").html(<?php echo $checklist_items; ?>);

        //show save & cancel button when the checklist-add-item-form is focused
        $("#checklist-add-item").focus(function () {
            $("#checklist-options-panel").removeClass("hide");
            $("#checklist-add-item-error").removeClass("hide");
        });

        $("#checklist-add-item-date").focus(function () {
            $("#checklist-options-panel").removeClass("hide");
            $("#checklist-add-item-error").removeClass("hide");
        });

        $("#checklist-options-panel-close").click(function () {
            $("#checklist-options-panel").addClass("hide");
            $("#checklist-add-item-error").addClass("hide");
            $("#checklist-add-item").val("");
            $("#checklist-add-item-date").val("");
        });

      /*  $("#checklist_form").appForm({
            isModal: false,
            onSuccess: function (response) {
                $("#checklist-add-item").val("");
                $("#checklist-add-item-date").val("");
                $("#checklist-add-item").focus();
                $(".checklist-items").append(response.data);

            } 
}); */
$("#checklist_form").appForm({
            onSuccess: function(result) {
                $("#loan-payment-table").appTable({newData: result.data, dataId: result.id});
                 location. reload(true);

            }
        }); 

        setDatePicker("#checklist-add-item-date");
        $('body').on('click', '[data-act=update-checklist-item-status-checkbox]', function () {
            var status_checkbox = $(this).find("span");
            status_checkbox.addClass("inline-loader");
            $.ajax({
                url: '<?php echo_uri("vendors_invoice_list/save_checklist_item_status") ?>/' + $(this).attr('data-id'),
                type: 'POST',
                dataType: 'json',
                data: {value: $(this).attr('data-value')},
                success: function (response) {
                    if (response.success) {
                        status_checkbox.closest("div").html(response.data);
                    }
                }
            });
        });
    });

    $("#checklist-add-item").on('keyup', function (){
   // Your stuff...
     $.ajax({
                    url: "<?php echo get_uri("loan/get_vendors_invoice_paid_suggestion"); ?>",
                    data: {item_name: $("#loan_id").val(),},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                    if (response && response.success == true) {

                            
      
      var total_paid = response.item_info.paid;
      var  total = $("#total").val();
      var amount =  $("#checklist-add-item").val();
      var total_paid_amount =  parseFloat(total_paid) + parseFloat(amount);
      if(total_paid_amount>total)
      {
           $("#checklist-options-panels").attr("disabled", true);
           //$("#checklist-add-item-date").val(total_paid_amount);
      }
      else {

     $("#checklist-options-panels").attr("disabled", false);
     
      } 


                          
                        //$('#message').html('This Invoice number already exit').css('color', 'red').hide();
                       //$("#checklist-add-item-date").val(total_paid);
                        
                        //$("#checklist-options-panels").attr("disabled", true);
                        //$("#checklist-options-panel").hide();

                       }




                    }
                }); 




});


</script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#loan-payment-table").appTable({
            source: '<?php echo_uri("loan/loan_payment_list_data/" . $model_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                
                {title: '<?php echo lang("payment_date") ?> ', "class": "w25p"},
                {title: '<?php echo lang("payment_method") ?>', "class": "text-center w25p"},
                {title: '<?php echo lang("reference_number") ?>', "class": "text-center w25p"},
                {title: '<?php echo lang("amount") ?>', "class": "text-center w25p"},
                {title: '<?php echo lang("files") ?>', "class": "text-center w25p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w10"}
            ],
           /* onDeleteSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            } */
        });

        $('#payment_method_id').on('change', function() {
      var data = $("#payment_method_id option:selected").text();
       if(data.toLowerCase().includes('cash')){
         $("#ref_name").html("Reference No");
        
      $('#reference_number').attr('placeholder', 
                "Reference No"); 
     
  }else{
      $("#ref_name").html(data+"No");
       $('#reference_number').attr('placeholder', 
                data+"No"); 
   }
     // alert(data);
    })

      var data = $("#payment_method_id option:selected").text();
       if(data.toLowerCase().includes('cash')){
         $("#ref_name").html("Reference No");
        
      $('#reference_number').attr('placeholder', 
                "Reference No"); 
     
  }else{
      $("#ref_name").html(data+"No");
      $('#reference_number').attr('placeholder', 
                data+"No");
                } 
    });
       
</script>
