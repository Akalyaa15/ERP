<?php echo form_open(get_uri("bank_name/save"), array("id" => "earnings-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('Bank_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('Bank_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="account_number" class=" col-md-3"><?php echo lang('account_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "account_number",
                "name" => "account_number",
                "value" => $model_info->account_number,
                "class" => "form-control",
                "placeholder" => lang('account_number')
            ));
            ?><!-- <span><i class="fa fa-pencil-square-o" id='edit' aria-hidden="true"></i></span> -->

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
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class=" col-md-3"><?php echo lang('status'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_radio(array(
                "id" => "status_active",
                "name" => "status",
                "data-msg-required" => lang("field_required"),
                    ), "active", ($model_info->status === "active") ? true : (($model_info->status !== "inactive") ? true : false));
            ?>
            <label for="status_active" class="mr15"><?php echo lang('active'); ?></label>
            <?php
            echo form_radio(array(
                "id" => "status_inactive",
                "name" => "status",
                "data-msg-required" => lang("field_required"),
                    ), "inactive", ($model_info->status === "inactive") ? true : false);
            ?>
            <label for="status_inactive" class=""><?php echo lang('inactive'); ?></label>
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
//var s= "<?php echo $account_number_suggestions[0]; ?>"; alert(s)
//$("#edit").hide()
        $("#earnings-form").appForm({
            onSuccess: function(result) {
                $("#earnings-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
        $("#account_number").select2({
            tags: <?php echo json_encode($account_number_suggestions); ?>
        });
    });
    $("ul li").dblclick(function () {
         $("#account_number").select2('destroy'); 
        
        })
$("#s2id_account_number,.select2-choices").dblclick(function () {
         $("#account_number").select2('destroy'); 
        
        })
</script> 
<script type="text/javascript">
//     $("li").on("click", function() {
//  var str = $(this).text();
//     alert(str);
// });
</script>   <script type="text/javascript">
   $('li,#account_number').on("keypress", function(e) {
if((e.which === 32) || (e.which  > 64 && e.which  < 91) || (e.which > 96 && e.which < 123))
    return false;
if(e.which === 13){
   
$("#account_number").select2({
            tags: <?php echo json_encode($account_number_suggestions); ?>
        }); return false;
}
})
</script>