<?php /*
<div class="tab-content">
    <?php echo form_open(get_uri("student_desk/save/"), array("id" => "student_desk-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('student_desk_info'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class=" col-md-2"><?php echo lang('name'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "name",
                        "name" => "name",
                        "value" => $model_info->name,
                        "class" => "form-control",
                        "placeholder" => lang('name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="college_name" class=" col-md-2"><?php echo lang('college_name'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "college_name",
                        "name" => "college_name",
                        "value" => $model_info->college_name,
                        "class" => "form-control",
                        "placeholder" => lang('mailing_address')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="department" class=" col-md-2"><?php echo lang('department'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "department",
                        "name" => "department",
                        "value" => $model_info->department,
                        "class" => "form-control",
                        "placeholder" => lang('department')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="mailing_address" class=" col-md-2"><?php echo lang('mailing_address'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                        "value" => $model_info->address,
                        "class" => "form-control",
                        "placeholder" => lang('mailing_address')
                    ));
                    ?>
                </div>
            </div>
<div class="form-group">
                <label for="pincode" class=" col-md-2"><?php echo lang('pincode'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "pincode",
                        "name" => "pincode",
                        "value" => $model_info->pincode,
                        "class" => "form-control",
                        "placeholder" => lang('pincode'),
                        "maxlength"=>6,
                       
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="district" class=" col-md-2"><?php echo lang('district'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "district",
                        "name" => "district",
                        "value" => $model_info->district,
                        "class" => "form-control",
                        "placeholder" => lang('district'),
                        
                    ));
                    ?>
                </div>
            </div>
             <div class="form-group">
                <label for="state" class=" col-md-2"><?php echo lang('district'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "state",
                        "name" => "state",
                        "value" => $model_info->state,
                        "class" => "form-control",
                        "placeholder" => lang('state'),
                        
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class=" col-md-2"><?php echo lang('email'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "value" => $model_info->email,
                        "class" => "form-control",
                        
                        "placeholder" => lang('email')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-2"><?php echo lang('phone'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                        "value" => $model_info->phone,
                        "class" => "form-control",
                        "maxlength"=>15,
                        "placeholder" => lang('phone')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_phone" class=" col-md-2"><?php echo lang('alternative_phone'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "alternative_phone",
                        "name" => "alternative_phone",
                        "value" => $model_info->alternative_phone,
                        "class" => "form-control",
                        "maxlength"=>15,
                        "placeholder" => lang('alternative_phone')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="skype" class=" col-md-2">Skype</label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "skype",
                        "name" => "skype",
                        "value" => $model_info->skype ? $user_info->skype : "",
                        "class" => "form-control",
                        "placeholder" => "Skype"
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="dob" class=" col-md-2"><?php echo lang('date_of_birth'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "dob",
                        "name" => "dob",
                        "value" => $model_info->dob,
                        "class" => "form-control",
                        "placeholder" => lang('date_of_birth')
                    ));
                    ?>
                </div>
            </div>
             <div class="form-group">
                <label for="gender" class=" col-md-2"><?php echo lang('gender'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_radio(array(
                        "id" => "gender_male",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "male", ($model_info->gender === "female") ? false : true);
                    ?>
                    <label for="gender_male" class="mr15"><?php echo lang('male'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "gender_female",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "female", ($model_info->gender === "female") ? true : false);
                    ?>
                    <label for="gender_female" class=""><?php echo lang('female'); ?></label>
                </div>
            </div>


           

            <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-2", "field_column" => " col-md-10")); ?> 

        </div>
        <div class="panel-footer">
            <button id="form-submit" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
*/ ?>
<div class="tab-content">
    <?php echo form_open(get_uri("student_desk/save/"), array("id" => "student_desk-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php //echo lang('client_info'); ?></h4>
        </div>
        <div class="panel-body">
            <?php $this->load->view("student_desk/student_desk_fields"); ?>
        </div>
        <div class="panel-footer">
            <button  id="savebutton" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#student_desk-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
               /* setTimeout(function () {
                    window.location.href = "<?php echo get_uri("student_desk/view/" . $user_info->id); ?>" + "/general";
                }, 500); */
            }
        });
        setDatePicker("#start_date, #end_date,#date,#dob");
        setTimePicker("#start_time, #end_time"); 
        $("#student_desk-info-form .select2").select2();
       //setDatePicker("#date");
        //setDatePicker("#dob");
        

    });
    

</script>   
