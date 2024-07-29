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
                    <?php echo $model_info->vendor_name; ?>
                </div>
                <p> 
                    <span class='label label-light mr5' title='Point'><?php echo $model_info->points; ?></span>

                    <?php echo $labels . " " . "<span class='label' style='background:$model_info->status_color; '>" . ($model_info->status_key_name ? lang($model_info->status_key_name) : $model_info->status_title) . "</span>"; ?>
                </p>
            </div>
        </div>
    </div>
<?php /*
    <div class="form-group clearfix">
        <!--div  class="col-md-12 mb15">
            <strong><?php echo lang('invoice_no') . ": "; ?></strong><strong><?php echo $model_info->invoice_no; ?></strong>
        </div>
        <div class="col-md-12 mb15">
            <strong><?php echo lang('invoice_date') . ": "; ?></strong> <?php echo format_to_date($model_info->invoice_date, false); ?>
        </div>
   <?php if ($model_info->description) { ?>
        <div class="col-md-12 mb15">
            <strong><?php echo lang('description') . ": "; ?></strong><?php echo $model_info->description ? nl2br(link_it($model_info->description)) : "-"; ?>
        </div>
 <?php } ?>
        <?php if ($model_info->amount) { ?>
            <div class="col-md-12 mb15">
                <strong><?php echo lang('amount') . ": "; ?></strong> <?php echo $model_info->amount; ?>
            </div>
        <?php } ?>
        <?php if ($model_info->igst_tax) { ?>
            <div class="col-md-12 mb15">
                <strong><?php echo lang('igst_tax') . ": "; ?></strong> <?php echo $model_info->igst_tax; ?>
            </div>
        <?php } ?>
         <?php if ($model_info->cgst_tax) { ?>
            <div class="col-md-12 mb15">
                <strong><?php echo lang('cgst_tax') . ": "; ?></strong> <?php echo $model_info->cgst_tax; ?>
            </div>
        <?php } ?>
        <?php if ($model_info->sgst_tax) { ?>
            <div class="col-md-12 mb15">
                <strong><?php echo lang('sgst_tax') . ": "; ?></strong> <?php echo $model_info->sgst_tax; ?>
            </div>
        <?php } ?>
        <?php if ($model_info->total) { ?>
            <div class="col-md-12 mb15">
                <strong><?php echo lang('total') . ": "; ?></strong> <?php echo $model_info->total; ?>
            </div-->
        <?php } ?>
        */ ?>
<?php if ($model_info->invoice_no) { ?>
        <div class=" form-group">
            <label for="invoice_date" class=" col-md-3"><?php echo lang('invoice_no'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->invoice_no,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
 <?php } ?>  
    <?php if ($model_info->invoice_date) { ?>  
        <div class=" form-group">
            <label for="invoice_date" class=" col-md-3"><?php echo lang('invoice_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->invoice_date,
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
        <?php if ($model_info->igst_tax) { ?> 
        <div class=" form-group">
            <label for="igst_tax" class=" col-md-3"><?php echo lang('igst_tax'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->igst_tax,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?> 
        <?php if ($model_info->cgst_tax) { ?>  
        <div class=" form-group">
            <label for="cgst_tax" class=" col-md-3"><?php echo lang('cgst_tax'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->cgst_tax,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        <?php } ?>
        <?php if ($model_info->sgst_tax) { ?>   
        <div class=" form-group">
            <label for="sgst_tax" class=" col-md-3"><?php echo lang('sgst_tax'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                   
                    "value" => $model_info->sgst_tax,
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
        

        <?php /*if (is_date_exists($model_info->start_date)) { ?>
            <div class="col-md-12 mb15">
                <strong><?php echo lang('start_date') . ": "; ?></strong> <?php echo format_to_date($model_info->start_date, false); ?>
            </div>
        <?php } */?>
        
        

        <?php /*
        if (count($custom_fields_list)) {
            foreach ($custom_fields_list as $data) {
                if ($data->value) {
                    ?>
                    <div class="col-md-12 mb15">
                        <strong><?php echo $data->title . ": "; ?> </strong> <?php echo $this->load->view("custom_fields/output_" . $data->field_type, array("value" => $data->value), true); ?>
                    </div>
                    <?php
                }
            }
        }
        */?>

        <!--div class="col-md-12 mb15">
            <strong><?php echo lang('project') . ": "; ?> </strong> <?php echo anchor(get_uri("projects/view/" . $model_info->project_id), $model_info->project_title); ?>
        </div-->


        <!--checklist-->
        <?php echo form_open(get_uri("vendors_invoice_list/save_checklist_item"), array("id" => "checklist_form", "class" => "general-form", "role" => "form")); ?>
        <div class="col-md-12 mb15 b-t">
            <div class="pb10 pt10">
                <strong><?php echo lang("vendor_invoice_payments_list"); ?></strong>
            </div>
            <input type="hidden" name="task_id" id="task_id"  value="<?php echo $task_id; ?>" />
            <input type="hidden" name="paid_total" id="paid_total" value="<?php echo $model_info->paid_amount; ?>" />
             <input type="hidden" name="total" id="total" value="<?php echo $model_info->total; ?>" />
            <div class="checklist-items">

            </div>
           
                <div class="form-group">
                    <div class="mt5 col-md-12 p0">
                        <?php
                        echo form_inputnumber(array(
                            "id" => "checklist-add-item",
                            "name" => "checklist-add-item",
                            "class" => "form-control",
                            "placeholder" => lang('add_amount'),
                            "autofocus" => "true",
                            "max" => $model_info->total,
                            "data-rule-required" => true,
                            "data-msg-required" => lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="mt5 col-md-12 p0">
                        <?php
                        echo form_input(array(
                            "id" => "checklist-add-item-date",
                            "name" => "checklist-add-item-date",
                            "class" => "form-control",
                            "placeholder" => lang('payment_date'),
                            "data-rule-required" => true,
                            "data-msg-required" => lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
                <div id="checklist-options-panel" class="col-md-12 mb15 p0 hide">
                    <button type="submit"  id="checklist-options-panels" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('add_payment'); ?></button> 
                    <button id="checklist-options-panel-close" type="button" class="btn btn-default"><span class="fa fa-close"></span> <?php echo lang('cancel'); ?></button>
                </div>
            
        </div>
        <?php echo form_close(); ?>



    </div>

    <div class="row clearfix">
        <div class="col-md-12 b-t pt10 list-container">
            <?php /* if ($can_comment_on_tasks) { ?>
                <?php $this->load->view("projects/comments/comment_form"); ?>
            <?php } ?>
            <?php $this->load->view("projects/comments/comment_list");  */?>
        </div>
    </div>

    <?php /* if ($this->login_user->user_type === "staff") { ?>
        <div class="box-title"><span ><?php echo lang("activity"); ?></span></div>
        <div class="pl15 pr15 mt15 list-container">
            <?php activity_logs_widget(array("limit" => 20, "offset" => 0, "log_type" => "task", "log_type_id" => $model_info->id)); ?>
        </div>
    <?php } */?>
</div>

<div class="modal-footer">
    <?php 
    
    echo modal_anchor(get_uri("vendors_invoice_list/modal_form/"), "<i class='fa fa-pencil'></i> " . lang('edit_vendor_invoice_list'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => lang('edit_vendor_invoice_list')));

    ?>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>

<?php /*
$task_link = anchor(get_uri("projects/view/$model_info->project_id/tasks?task=" . $model_info->id), '<i class="fa fa-external-link"></i>', array("target" => "_blank", "class" => "p15"));
*/?>

<script type="text/javascript">
    $(document).ready(function () {

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

        $("#checklist_form").appForm({
            isModal: false,
            onSuccess: function (response) {
                $("#checklist-add-item").val("");
                $("#checklist-add-item-date").val("");
                $("#checklist-add-item").focus();
                $(".checklist-items").append(response.data);

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
                    url: "<?php echo get_uri("vendors_invoice_list/get_vendors_invoice_paid_suggestion"); ?>",
                    data: {item_name: $("#task_id").val(),},
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
     //$("#checklist-add-item-date").val(total_paid_amount);
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