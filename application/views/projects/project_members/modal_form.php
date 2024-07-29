 <?php echo form_open(get_uri("projects/save_project_member"), array("id" => "project-member-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
    <?php if(!$is_project_manager){ ?>
    <div class="form-group" style="min-height: 50px">
        <label for="user_id" class=" col-md-3"><?php echo lang('project_manager'); ?></label>
        <div class="col-md-9">
                <div class="select-member-form clearfix pb10">
                    <?php echo form_dropdown("project_manager", $users_dropdown, array($model_info->project_manager), "class='user_select2 col-md-10 p0'"); ?>
                    <?php echo js_anchor("<i class='fa fa-times'></i> ", array("class" => "remove-member delete ml20")); ?>
                </div>                                
        </div>
    </div><?php } ?> <?php if(!$is_purchase_manager){ ?>
    <div class="form-group" style="min-height: 50px">
        <label for="user_id" class=" col-md-3"><?php echo lang('purchase_manager'); ?></label>
        <div class="col-md-9">
                <div class="select-member-form clearfix pb10">
                    <?php echo form_dropdown("purchase_manager", $users_dropdown, array($model_info->user_id), "class='user_select2 col-md-10 p0'"); ?>
                    <?php echo js_anchor("<i class='fa fa-times'></i> ", array("class" => "remove-member delete ml20")); ?>
                </div>                                
        </div>
    </div><?php } ?><?php if(!$is_leader){ ?>
        <div class="form-group" style="min-height: 50px">
        <label for="user_id" class=" col-md-3"><?php echo lang('leader'); ?></label>
        <div class="col-md-9">
                <div class="select-member-form clearfix pb10">
                    <?php echo form_dropdown("leader", $users_dropdown, array($model_info->user_id), "class='user_select2 col-md-10 p0'"); ?>
                    <?php echo js_anchor("<i class='fa fa-times'></i> ", array("class" => "remove-member delete ml20")); ?>
                </div>                                
        </div>
    </div> <?php } ?>     <div class="form-group" style="min-height: 50px">
        <label for="user_id" class=" col-md-3"><?php echo lang('member'); ?></label>
        <div class="col-md-9">
            <div class="select-member-field">
                <div class="select-member-form clearfix pb10">
                    <?php echo form_dropdown("user_id[]", $users_dropdown, array($model_info->user_id), "class='user_select2 col-md-10 p0'"); ?>
                    <?php echo js_anchor("<i class='fa fa-times'></i> ", array("class" => "remove-member delete ml20")); ?>
                </div>                                
            </div>
            <?php echo js_anchor("<i class='fa fa-plus-circle'></i> " . lang('add_more'), array("class" => "add-member", "id" => "add-more-user")); ?>
        </div>
    </div> 
<div class="form-group" style="min-height: 50px">
        <label for="user_id" class=" col-md-3"><?php echo lang('contract_members'); ?></label>
        <div class="col-md-9">
            <div class="select-members-field">
                <div class="select-members-form clearfix pb10">
                    <?php echo form_dropdown("user_id[]", $rm_users_dropdown, array("",$model_info->user_id), "class='user_select2s col-md-10 p0'"); ?>
                    <?php echo js_anchor("<i class='fa fa-times'></i> ", array("class" => "remove-members delete ml20")); ?>
                </div>                                
            </div>
            <?php // echo js_anchor("<i class='fa fa-plus-circle'></i> " . lang('add_more'), array("class" => "add-members", "id" => "add-more-users")); ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#project-member-form").appForm({
            onSuccess: function (result) {
                if (result.id !== "exists") {
                    for (i = 0; i < result.data.length; i++) {
                        $("#project-member-table").appTable({newData: result.data[i], dataId: result.id[i]});
                    }
                }
            }
        });

        var $wrapper = $('.select-member-field'),
                $field = $('.select-member-form:first-child', $wrapper).clone(); //keep a clone for future use.

        $(".add-member", $(this)).click(function (e) {
            var $newField = $field.clone();

            //remove used options
            $('.user_select2').each(function () {
                $newField.find("option[value='" + $(this).val() + "']").remove();
            });

            var $newObj = $newField.appendTo($wrapper);
            $newObj.find(".user_select2").select2();

            $newObj.find('.remove-member').click(function () {
                $(this).parent('.select-member-form').remove();
                showHideAddMore($field);
            });

            showHideAddMore($field);
        });

        showHideAddMore($field);

        $(".remove-member").hide();
        $(".user_select2").select2();

        function showHideAddMore($field) {
            //hide add more button if there are no options 
            if ($('.select-member-form').length < $field.find("option").length) {
                $("#add-more-user").show();
            } else {
                $("#add-more-user").hide();
            }
        }

    });
</script>    
<script type="text/javascript">
    $(document).ready(function () {

        $("#project-member-form").appForm({
            onSuccess: function (result) {
                if (result.id !== "exists") {
                    for (i = 0; i < result.data.length; i++) {
                        $("#project-member-table").appTable({newData: result.data[i], dataId: result.id[i]});
                    }
                }
            }
        });

        var $wrappers = $('.select-members-field'),
                $fields = $('.select-members-form:first-child', $wrappers).clone(); //keep a clone for future use.

        $(".add-members", $(this)).click(function (e) {
            var $newFields = $fields.clone();

            //remove used options
            $('.user_select2s').each(function () {
                $newFields.find("option[value='" + $(this).val() + "']").remove();
            });

            var $newObjs = $newFields.appendTo($wrappers);
            $newObjs.find(".user_select2s").select2();

            $newObjs.find('.remove-members').click(function () {
                $(this).parent('.select-members-form').remove();
                showHideAddMore($fields);
            });

            showHideAddMore($fields);
        });

        showHideAddMore($fields);

        $(".remove-members").hide();
        $(".user_select2s").select2();

        function showHideAddMore($fields) {
            //hide add more button if there are no options 
            if ($('.select-members-form').length < $fields.find("option").length) {
                $("#add-more-users").show();
            } else {
                $("#add-more-users").hide();
            }
        }

    });
</script>    
