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

<?php echo form_open(get_uri("loan/save"), array("id" => "loan-form", "class" => "general-form", "role" => "form")); ?>
 <div id="loan-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
   
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
         <?php if ($model_infos->user_type=="resource") { ?>
           
        
        <div class="form-group">
            <label for="loan_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("loan_user_id", $rm_members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='loan_user_id'");
                ?>
            </div>
        </div>

<?php }else if ($model_infos->user_type=="staff") {  ?>
           
        
        <div class="form-group">
            <label for="loan_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("loan_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='loan_user_id'");
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
                echo form_dropdown("loan_user_id", $members_dropdown, array($model_info->user_id), "class='select2 validate-hidden' id='loan_user_id' ");
                ?>
            </div>
        </div>
<div class="form-group" id="outsource" style="display: none">
            <label for="loan_user_id" class=" col-md-3"><?php echo lang('outsource_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("loan_user_ids", $rm_members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='loan_user_ids'");
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
            <label for="loan_user_id" class=" col-md-3"><?php echo lang('others'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("loan_user_idss", $others_dropdown, $model_info->phone, "class='select2 validate-hidden' id='loan_user_idss'");
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
            "readonly"=> "true",
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),

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
        </div> 
        <div class=" form-group">
            <label for="loan_date" class=" col-md-3"><?php echo lang('loan_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "loan_date",
                    "name" => "loan_date",
                    "value" => $model_info->loan_date? $model_info->loan_date: get_my_local_time("Y-m-d"),
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
                echo form_dropdown("category_id", $categories_dropdown, $model_info->category_id, "class='select2 validate-hidden' id='category_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
       
        <div class="form-group" style=" display: none";>
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
                    "readonly" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

<div class="form-group">
        <label for="interest" class=" col-md-3"><?php echo lang('interest'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "interest",
                "name" => "interest",
                "value" => $model_info->interest,
                "class" => "form-control",
                "placeholder" => lang('interest'),
              "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                
            ));
            ?>
        </div>
    </div>


        <!--div class="form-group" >
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
        </div-->
        <div class=" form-group">
            <label for="due_date" class=" col-md-3"><?php echo lang('due_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "due_date",
                    "name" => "due_date",
                    "value" => $model_info->due_date? $model_info->due_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="loan_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("loan_project_id", $projects_dropdown, $model_info->project_id, "class='select2 validate-hidden' id='loan_project_id'");
                ?>
            </div>
        </div>
       
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
        <p  id="file_alert" style="color: red;display: none">*Uploading files are required</p>  

    </div>
        <div class="modal-footer">
<div id="link-of-task-view" class="hide">
            <?php
            echo modal_anchor(get_uri("loan/loan_view"), "", array());
            ?>
        </div>
            <div class="row">

                <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                <button id="save-and-show-button" type="button" class="btn btn-success" ><span class="fa fa-check-circle"></span> <?php echo lang('save_and_add_payment'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
                <button id="file_upload" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
    </div>
        <?php echo form_close(); ?>
     <!--div class="panel panel-default">
                <div class="tab-title clearfix">
                    <h4> <?php echo lang('invoice_payment_list'); ?></h4>
                </div>
                <div class="table-responsive">
                    <table id="loan-payment-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        <?php echo form_open(get_uri("loan/save_checklist_item"), array("id" => "checklist_form", "class" => "general-form", "role" => "form")); ?>
        <div class="col-md-12 mb15 b-t">
            <div class="pb10 pt10">
                <strong><?php echo lang("vendor_invoice_payments_list"); ?></strong>
            </div>
            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
            
            
             <input type="hidden" name="paid_total" id="paid_total" value="<?php echo $model_info->total; ?>" />
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
                    <button type="submit"   id="checklist-options-panels" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('add_payment'); ?></button> 
                    <button id="checklist-options-panel-close" type="button" class="btn btn-default"><span class="fa fa-close"></span> <?php echo lang('cancel'); ?></button>
                </div>
            
        </div>
        <?php echo form_close(); ?>
   
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

     /*   $("#checklist_form").appForm({
            isModal: false,
            onSuccess: function (response) {
                $("#checklist-add-item").val("");
                $("#checklist-add-item-date").val("");
                $("#checklist-add-item").focus();
                $(".checklist-items").append(response.data);
                $("#loan-payment-table").appTable({newData: result.data, dataId: result.id});
            }


        }); */
        $("#checklist_form").appForm({
            onSuccess: function(result) {
                $("#loan-payment-table").appTable({newData: result.data, dataId: result.id});
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
                
                
               
                {title: '<?php echo lang("amount") ?>', "class": "text-center w25p"},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w25p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w10"}
            ],
            onDeleteSuccess: function (result) {
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
            }
        });
    });
       
</script-->
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

        var uploadUrl = "<?php echo get_uri("loan/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("loan/validate_loan_file"); ?>";

        var dropzone = attachDropzoneWithForm("#loan-dropzone", uploadUrl, validationUrl);


        window.showAddNewModal = false;

        $("#save-and-show-button").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");

        });

        var taskInfoText = "<?php echo lang('add_payment') ?>";

window.taskForm = $("#loan-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#monthly-loan-table").appTable({newData: result.data, dataId: result.id});
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
      /*  $("#loan-form").appForm({
            onSuccess: function (result) {
                if (typeof $LOAN_TABLE !== 'undefined') {
                    $LOAN_TABLE.appTable({newData: result.data, dataId: result.id});
                } else {
                    location.reload();
                }
            }
        }); */
        
        
        setDatePicker("#due_date");

        $("#loan-form .select2").select2();

         $("#payment_status").select2();


        //re-initialize item suggestion dropdown on request
       

    });
</script>
<script type="text/javascript">
    $("#client_member").change(function () {
    $("#client_contact").val("").attr('readonly', false)
                    var team_member =$("#client_member").val();

          $("#client_contact").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("loan/get_client_contacts"); ?>",
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
                url: "<?php echo get_uri("loan/get_vendor_contacts"); ?>",
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

    $("#loan_user_id").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var team_member =$("#loan_user_id").val();

          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("loan/get_voucher_id"); ?>",
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
        $("#loan_user_ids").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var team_member =$("#loan_user_ids").val();

          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("loan/get_voucher_id"); ?>",
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
        $("#loan_user_idss").change(function () {
    $("#voucher_no").val("").attr('readonly', false)
                    var phone =$("#loan_user_idss").val();
          $("#voucher_no").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("loan/get_voucher_id_others"); ?>",
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
                url: "<?php echo get_uri("loan/get_client_voucher_id"); ?>",
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
                url: "<?php echo get_uri("loan/get_client_voucher_id"); ?>",
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
        $.ajax({
                    url: "<?php echo get_uri("loan/get_voucher_details"); ?>",
                    data: {item_name: voucher_no},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
 
                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                           /*$("#description").val(response.item_info.description);
                            $("#loan_date").val(response.item_info.expense_date);
                            $("#category_id").select2("val", response.item_info.category_id);
                            $("#amount").val(response.item_info.amount);
                               $("#loan_project_id").select2("val",response.item_info.project_id);
                               $("#loan_user_id").select2("val",response.item_info.user_id);*/

    $("#v_filess,#v_status").show()   
    $("#filess").html(response.item_files);
    $("#voucher_status").html(response.item_status);

                            $("#description").val(response.item_info.description).attr('readonly', true);
                            $("#loan_date").val(response.item_info.expense_date).attr('readonly', true);
                            $("#category_id").select2("val", response.item_info.category_id);
                            $("#amount").val(response.item_info.amount).attr('readonly', true);
                            $("#loan_project_id").select2("val",response.item_info.project_id);
                            $("#loan_user_id").select2("val",response.item_info.user_id);
                             $("#currency").val(response.item_info.currency).attr('readonly', true);
                            $("#currency_symbol").val(response.item_info.currency_symbol).attr('readonly', true);

                               
                        }
                    }
                });

})

</script>

<?php 

if($model_info->voucher_no)
{?>
<script type="text/javascript">
            var voucher_no =$("#voucher_no").val();
        $.ajax({
                    url: "<?php echo get_uri("loan/get_voucher_details"); ?>",
                    data: {item_name: voucher_no},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
                        if (response && response.success) {
   $("#filess").html(response.item_files);
     $("#voucher_status").html(response.item_status);
               $("#loan_project_id").select2('readonly', true)
    $("#category_id").select2('readonly', true)                }
                    }
                });
</script>
<?php } ?>


   
    
 <!-- <?php /*if(!$model_info->files){ ?> 
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
    <?php } */ ?> -->
    <script>
$( "#member_type").change(function(e) {
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

<script >
    $("#loan_user_id,#vendor_contact,#client_contact,#loan_user_ids,#loan_user_idss").change(function () {
$("#v_filess,#v_status").hide()   
         $("#description").val("").attr('readonly', false);
                            $("#loan_date").val("").attr('readonly', false);
                            $("#category_id").select2("val", " ");
                            $("#amount").val("").attr('readonly', false);
                            $("#loan_project_id").select2("val"," ");
                                                            
    });

    <?php if($model_info->voucher_no){ ?>
        $("#v_filess,#v_status").show()
$("#description").attr('readonly', true);
                            $("#loan_date").attr('readonly', true);
                            $("#category_id").attr('readonly', true);
                            $("#amount").attr('readonly', true);
                               $("#loan_project_id").attr('readonly', true);
                               


         <?php  } ?> 
</script>