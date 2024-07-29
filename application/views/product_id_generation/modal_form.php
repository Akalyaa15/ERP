<?php echo form_open(get_uri("product_id_generation/save"), array("id" => "product_id_generation-form", "class" => "general-form", "role" => "form")); ?>
 <div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="col-md-12 text-off"> <?php echo lang('product_id_edit_instruction'); ?></div>
        </div>
    <?php } ?>
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
    <!-- <div class="form-group">
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
           */ ?>
        </div>
    </div> -->
    <input type="hidden" name="add_new_make_to_library" value="" id="add_new_make_to_library" />
    <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php 
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
     <!-- <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_dropdown("make", $make_dropdown, array($model_info->make), "class='select2'");
           */ ?>
        </div>
    </div> -->
    <div class="form-group">
                        <label for="associated_with_part_no" class=" col-md-3"><?php echo lang('associated_with_part_no'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "associated_with_part_no",
                                "name" => "associated_with_part_no",
                                "value" => $model_info->associated_with_part_no,
                                "class" => "form-control validate-hidden",
                                "placeholder" => lang('associated_with_part_number'),
                                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
   <!--div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "item_rate",
                "name" => "item_rate",
                "value" => $model_info->rate ? $model_info->rate : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            */?>
        </div>
    </div-->
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#product_id_generation-form").appForm({
            onSuccess: function(result) {
                $("#product_id_generation-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#associated_with_part_no").select2({
            multiple: true,
            data: <?php echo ($part_no_dropdown); ?>
        });
        $("#make").select2({
            multiple: false,
            data: <?php echo ($make_dropdown); ?>
        });
        $("#category").select2({
            multiple: false,
            data: <?php echo ($product_categories_dropdown); ?>
        });
         $("#product_id_generation-form .select2").select2();
        $("#title").focus();
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
    });
</script>    