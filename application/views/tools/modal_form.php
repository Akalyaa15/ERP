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
<?php echo form_open(get_uri("tools/save"), array("id" => "tax-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('product_id'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('product_id'),
                "autofocus" => true,
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
        <label for="quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <input type="hidden" name="add_new_category_to_library" value="" id="add_new_category_to_library" />
    <div class="form-group">
        <label for="category" class=" col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "category",
                "name" => "category",
                "value" => $model_info->category,
                "class" => "form-control",
                "placeholder" => lang('category')
                
            ));
            ?>
        </div>
    </div>
   <!--  <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "make",
                "name" => "make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make')
                
            ));
            */?>
        </div>
    </div> -->
    <input type="hidden" name="add_new_make_to_library" value="" id="add_new_make_to_library" />
    <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php
            //echo form_dropdown("make", $make_dropdown, array($model_info->make), "class='select2'");
            echo form_input(array(
                "id" => "make",
                "name" => "make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make')
                
            ));
         
            ?>
        </div>
    </div>
   
    <div class="form-group">
        <label for="unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "unit_type",
                "name" => "unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_rate",
                "name" => "item_rate",
                "value" => $model_info->rate ? $model_info->rate : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <?php if (isset($model_info->user_type) && $model_info->user_type == "resource") { ?>
    <div class="form-group">
        <label for="income_user_id" class="col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("income_user_id", $rm_members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='income_user_id'");
            ?>
        </div>
    </div>
<?php } else if (isset($model_info->user_type) && $model_info->user_type == "staff") {  ?>
    <div class="form-group">
        <label for="income_user_id" class="col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("income_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='income_user_id'");
            ?>
        </div>
    </div>
           
        
        <div class="form-group">
            <label for="income_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("income_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='income_user_id'");
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
                echo form_dropdown("income_user_id", $members_dropdown, array($model_info->user_id), "class='select2 validate-hidden' id='income_user_id' ");
                ?>
            </div>
        </div>
<div class="form-group" id="outsource" style="display: none">
            <label for="income_user_id" class=" col-md-3"><?php echo lang('outsource_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("income_user_ids", $rm_members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='income_user_ids'");
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
            <label for="income_user_id" class=" col-md-3"><?php echo lang('others'); ?></label>
            <div class="col-md-9">
                <?php
                /*echo form_dropdown("income_user_idss", $others_dropdown, $model_info->phone, "class='select2 validate-hidden' id='income_user_idss'");*/

                echo form_input(array(
                        "id" => "others_name",
                        "name" => "others_name",
                         "value" => $model_info->others_name,
                        "class" => "form-control",
                        "placeholder" => lang('others'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                ?>
            </div>
        </div>
        <?php  } ?>
    <div class="form-group">
        <label for="location" class=" col-md-3"><?php echo lang('location'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "tool_location",
                "name" => "tool_location",
                "value" => $model_info->tool_location,
                "class" => "form-control",
                "placeholder" => lang('tool_location')
                
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#tax-form").appForm({
            onSuccess: function(result) {
                $("#tools-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        <?php  if (isset($unit_type_dropdown)) { ?>
            $("#unit_type").select2({
                multiple: false,
                data: <?php echo json_encode($unit_type_dropdown); ?>
            });
<?php }  ?>
$("#client_contact").select2({
                multiple: false,
                data: <?php echo json_encode($client_members_dropdown); ?>
            }); 
$("#vendor_contact").select2({
                multiple: false,
                data: <?php echo json_encode($vendor_members_dropdown); ?>
            });
            $("#make").select2({
            multiple: false,
            data: <?php echo ($make_dropdown); ?>
        });
        $("#category").select2({
            multiple: false,
            data: <?php echo ($product_categories_dropdown); ?>
        }); 
     $("#client_contact,#vendor_contact").select2("readonly", true);

        $("#title").focus();
         $("#tax-form .select2").select2();
    });
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

$("#make").on("change",function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                
                $("#make").select2("destroy").val("").focus();
                $("#add_new_make_to_library").val(1); //set the flag to add new item in library
            }
        });
        $("#category").on("change",function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                
                $("#category").select2("destroy").val("").focus();
                $("#add_new_category_to_library").val(1); //set the flag to add new item in library
            }
        });

</script>    