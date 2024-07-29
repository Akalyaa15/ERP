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
                    <?php echo $model_info->vendor_name; ?>
                </div>
                <p> 
                    <span class='label label-light mr5' title='Point'><?php echo $model_info->points; ?></span>

                    <?php echo $labels . " " . "<span class='label' style='background:$model_info->status_color; '>" . ($model_info->status_key_name ? lang($model_info->status_key_name) : $model_info->status_title) . "</span>"; ?>
                </p>
            </div>
        </div>
    </div>

<?php if ($model_info->invoice_no) { ?>
        <div class=" form-group">
            <label for="invoice_date" class=" col-md-3"><?php echo lang('po_no'); ?></label>
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
            <label for="invoice_date" class=" col-md-3"><?php echo lang('po_date'); ?></label>
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
            <label for="igst_tax" class=" col-md-3"><?php echo lang('tax'); ?></label>
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
       
        <?php if ($model_info->total) { ?>   
        <div class=" form-group">
            <label for="total" class=" col-md-3"><?php echo lang('total'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                     "id"=>"client_total",
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
                    "id"=>"client_paid",
                    //"value" => $model_info->paid_amount,
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
                   "id"=>"client_due",
                   //"value" => $model_info->paid_amount,
                    "class" => "form-control",
                    "readonly"=>"true"
                ));
                ?>
            </div>
        </div>
        
        

       
        <!--checklist-->
        <?php echo form_open(get_uri("clients_po_list/save_checklist_item"), array("id" => "checklist_form", "class" => "general-form", "role" => "form")); ?>
        <div id="expense-dropzone" class="post-dropzone">
        <div class="col-md-12 mb15 b-t">
            <div class="pb10 pt10">
                <strong><?php echo lang("payments"); ?></strong>
            </div>

            <input type="hidden" name="task_id" id="task_id"  value="<?php echo $task_id; ?>" />
            <input type="hidden" name="paid_total" id="paid_total" value="<?php echo $model_info->paid_amount; ?>" />
             <input type="hidden" name="total" id="total" value="<?php echo $model_info->total; ?>" />
            <div class="checklist-items">

            </div>
            <br>
           <div class="form-group">
            <label for="payment_method_id" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2 validate-hidden' id='payment_method_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class="form-group">
                    <label for="reference_number"  class=" col-md-3"><span id="ref_name"></span><!-- <?php /*echo lang('reference_number');*/ ?> --></label>
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
                    <label for="add_amount" class=" col-md-3"><?php echo lang('add_amount'); ?></label>
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
                     <label for="payment_date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
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
                <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                    <button type="submit"  id="checklist-options-panels" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('add_payment'); ?></button> 
                    <button id="checklist-options-panel-close" type="button" class="btn btn-default"><span class="fa fa-close"></span> <?php echo lang('cancel'); ?></button>
                </div>
                </div>

                <div class="form-group">
            <label class=" col-md-3"></label>
            <!--div class="col-md-9">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->file));
                ?>
            </div-->
           
        </div>
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
<div id="link-of-task-view" class="hide">
            <?php
           // echo modal_anchor(get_uri("attendance/todo_view"), "", array());
            echo modal_anchor(get_uri("clients_po_list/task_view/"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => lang('add_todo')));
           // echo modal_anchor(get_uri("vendors_invoice_list/modal_form/"), "<i class='fa fa-pencil'></i> " . lang('edit_vendor_invoice_list'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => lang('edit_vendor_invoice_list')));
            ?>
        </div>
    <?php 
    
    echo modal_anchor(get_uri("clients_po_list/modal_form/"), "<i class='fa fa-pencil'></i> " . lang('edit_client_po_list'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => lang('edit_client_po_list')));

    ?>

    <!--button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button-->
    <button id="save-and-show-button" style="display:none" type="button" class="btn btn-success" ><span class="fa fa-check-circle"></span> <?php echo lang('save_and_add_payment'); ?></button>
    <button id="vendor_save" type="button" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>

<?php /*
$task_link = anchor(get_uri("projects/view/$model_info->project_id/tasks?task=" . $model_info->id), '<i class="fa fa-external-link"></i>', array("target" => "_blank", "class" => "p15"));
*/?>

<script type="text/javascript">
    $(document).ready(function () {
var uploadUrl = "<?php echo get_uri("clients_po_list/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("clients_po_list/validate_vendor_file"); ?>";

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
                    url: '<?php echo_uri("clients_po_list/save_checklist_items_sort") ?>',
                    type: "POST",
                    data: {sort_values: data},
                    success: function () {
                        appLoader.hide();
                    }
                });
            }
        });
 $("#checklist_form .select2").select2();
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
            $("#payment_method_id").val("");
            $("#description").val("");
            $("#reference_number").val("");
        });

        $("#checklist_form").appForm({
            isModal: false,
            onSuccess: function (response) {
                $("#checklist-add-item").val("");
                $("#checklist-add-item-date").val("");
                $("#checklist-add-item").focus();
                $("#payment_method_id").select2("val","");
                $("#description").val("");
                 $("#reference_number").val("");
                $("#remove").click();
                $(".checklist-items").append(response.data);

                //paid and due amount update
                $.ajax({
                    url: "<?php echo get_uri("clients_po_list/get_vendors_invoice_paid_suggestion"); ?>",
                    data: {item_name: $("#task_id").val(),},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                    if (response && response.success == true) {

                            
      
      var total_paid = response.item_info.paid;
      var  total = $("#client_total").val();
      var amount =  $("#client_paid").val(total_paid);
      if(total_paid){
       var total_due_amount =  parseFloat(total) - parseFloat(total_paid);
       $("#client_due").val(total_due_amount);
      }else{
        $("#client_due").val(total);
        $("#client_paid").val(0);
          }
     
      }
    }
}); 
                //end paid amount update

            }


        });
        setDatePicker("#checklist-add-item-date");
        $('body').on('click', '[data-act=update-checklist-item-status-checkbox]', function () {
            var status_checkbox = $(this).find("span");
            status_checkbox.addClass("inline-loader");
            $.ajax({
                url: '<?php echo_uri("clients_po_list/save_checklist_item_status") ?>/' + $(this).attr('data-id'),
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
                    url: "<?php echo get_uri("clients_po_list/get_vendors_invoice_paid_suggestion"); ?>",
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
           $("#checklist-options-panels").attr("disabled", true).prop("title", "Please check your due amount");
           //$("#checklist-add-item-date").val(total_paid_amount);
      }
      else {

     $("#checklist-options-panels").attr("disabled", false).prop("title", "");
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
<script type="text/javascript">
$("#vendor_save").on('click', function (){
          location.reload();
        /* setTimeout(function() {
  location.reload();
}, 1000); */


    });
</script>
<script type="text/javascript">
function dd(){
                        setTimeout(function(){ 

             $("#save-and-show-button ").click();
             //$("#timecard-clock-out ").click();  
         }, 1000);
     
                     
 
}
</script>
<script type="text/javascript">

$(document).ready(function () {

             window.showAddNewModal = false;

        $("#save-and-show-button").click(function () {
            window.showAddNewModal = true;
            if (window.showAddNewModal) {
                    var $taskViewLink = $("#link-of-task-view").find("a");
                   // $taskViewLink.attr("data-title", taskInfoText + "#" + result.id);
                  //  $taskViewLink.attr("data-post-id", result.id);

                    $taskViewLink.trigger("click");
                } else {
                    window.taskForm.closeModal();
                }

        });

        //$("#timecard-clock-out ").click(); 

        
//paid and due update amount 

$.ajax({
                    url: "<?php echo get_uri("clients_po_list/get_vendors_invoice_paid_suggestion"); ?>",
                    data: {item_name: $("#task_id").val(),},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                    if (response && response.success == true) {

                            
      
      var total_paid = response.item_info.paid;
      var  total = $("#client_total").val();
      var amount =  $("#client_paid").val(total_paid);
      if(total_paid){
       var total_due_amount =  parseFloat(total) - parseFloat(total_paid);
       $("#client_due").val(total_due_amount);
      }else{
        $("#client_due").val(total);
        $("#client_paid").val(0);
          }
     
      }
    }
});  

// updae paid and due amount

//  payment dropdown name dynamic reference 

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

// end payment dropdown name dynamic reference 





              });
     
                     
 
</script>


