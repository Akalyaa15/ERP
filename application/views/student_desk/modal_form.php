<?php echo form_open(get_uri("student_desk/save"), array("id" => "student_desk-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    
    <?php $this->load->view("student_desk/student_desk_fields"); ?>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button id="savebutton"type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#student_desk-form").appForm({
            onSuccess: function(result) {
                $("#monthly-student_desk-table").appTable({newData: result.data, dataId: result.id});
                
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                   
                    //window.location = 'Payslip/pays';
                    window.location = "<?php echo site_url('student_desk/view'); ?>/" + result.id;
                }
            }
        });
        //setDatePicker("#date");
        //setDatePicker("#dob");
        setDatePicker("#start_date, #end_date,#date,#dob");

        setTimePicker("#start_time, #end_time");
        $("#student_desk-form .select2").select2();
    });
</script>    