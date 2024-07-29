<?php echo form_open(get_uri("credentials/save"), array("id" => "tax-form", "class" => "general-form", "role" => "form")); ?>
<div id="credentials-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class=" col-md-3"><?php echo lang('username'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "username",
                "name" => "username",
                "value" => $model_info->username,
                "class" => "form-control",
                "placeholder" => lang('username'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="category" class=" col-md-3"><?php echo lang('password'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "password",
                "name" => "password",
                "value" => $model_info->password,
                "class" => "form-control",
                "placeholder" => lang('password'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
        <div class="form-group">
        <label for="url" class=" col-md-3"><?php echo lang('url'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "url",
                "name" => "url",
                "value" => $model_info->url,
                "class" => "form-control",
                "placeholder" => lang('url'),
               
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">                <div class="notepad">
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
        </div></div> 
        
        </div>
<div class="form-group">
        <label for="label" class=" col-md-3"><?php echo lang('label'); ?></label>
            <div class="col-md-9">
                <div class="notepad">
                    <?php
                    echo form_input(array(
                        "id" => "note_labels",
                        "name" => "labels",
                        "value" => $model_info->labels,
                        "class" => "form-control",
                        "placeholder" => lang('labels')
                    ));
                    ?>
                </div>
            </div>
    </div>

    <div class="form-group">
            <div class="col-md-12">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>

        <?php $this->load->view("includes/dropzone_preview"); ?>

</div>

<div class="modal-footer">
<button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class="fa fa-camera"></i> <?php echo lang("upload_file"); ?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
         var uploadUrl = "<?php echo get_uri("credentials/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("credentials/validate_notes_file"); ?>";

        var dropzone = attachDropzoneWithForm("#credentials-dropzone", uploadUrl, validationUri);
        $("#tax-form").appForm({
            onSuccess: function(result) {
                $("#tools-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
         $("#note_labels").select2({
            tags: <?php echo json_encode($label_suggestions); ?>,
            'minimumInputLength': 0
        });
    });
</script>    