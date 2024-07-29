<?php echo form_open(get_uri("states/save"), array("id" => "states-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('state'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                //"maxlength" => 2,
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<!--     <div class="form-group">
        <label for="percentage" class=" col-md-3"><?php echo lang('country_code'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "country_code",
                "name" => "country_code",
                "value" => $model_info->country_code ,
                "class" => "form-control",
                "placeholder" => lang('country_code'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div> -->
    <?php if($model_info->country_code) { ?>
    <div class="form-group">
                <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("country_code", $country_dropdown, array($model_info->country_code), "class='select2' id='country'");
                    ?>
                   
                </div>
            </div>
            <?php } else {  ?>
            <div class="form-group">
                <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("country_code", $country_dropdown, array(), "class='select2' id='country'");
                    ?>
                   
                </div>
            </div>
        <?php } ?>
    <div class="form-group">
        <label for="state_code" class=" col-md-3"><?php echo lang('state_code'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "state_code",
                "name" => "state_code",
                "value" => $model_info->state_code,
                "class" => "form-control",
                "placeholder" => lang('state_code'),
                "maxlength" => 2,
                "autofocus" => true, 

                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
        $("#states-form").appForm({
            onSuccess: function(result) {
                $("#states-table").appTable({newData: result.data, dataId: result.id});
            }
        });
                  $("input[type=number]").on("keydown",function(e){["-","+","e"].includes(e.key)&&e.preventDefault()});
      
        $("#title").focus();
                $("#country").select2();

    });
</script>    