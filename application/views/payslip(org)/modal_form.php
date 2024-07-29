<?php  
$payslip_user_id=$model_info->user_id;
$options = array(
            "id" => $model_info->user_id,
                   );
        $list_data = $this->Users_model->get_details($options)->row();
        $pays=$list_data->first_name." ".$list_data->last_name ;

if($payslip_user_id)
{?>
<html>
<input type="hidden" name="pay" id="pay" value="<?php echo $pays; ?>" />
</html>
<script type="text/javascript" >

$( document ).ready(function() {
var payslip =$("#pay").val(); 

});
</script>
<?php } ?>

<?php echo form_open(get_uri("payslip/save"), array("id" => "payslip-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
       
         <div class=" form-group">
            <label for="payslip_date" class=" col-md-3"><?php echo lang('date_of_payslip'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "payslip_date",
                    "name" => "payslip_date",
                    "value" => $model_info->payslip_date? $model_info->payslip_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ) );
                ?>
            </div>
        </div>

        <?php if(!$model_info->user_id) { ?>
        <div class="form-group" id= "aa" >
        <label for="title" class=" col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_user_id",
                "name" => "payslip_user_id",
                "value" =>$model_info->user_id,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_employee_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="payslip_user_id_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="close">×</span></a>
        </div>
    </div>
  <?php } else { ?>
  <div class="form-group" id= "aa" style="display:none";>
        <label for="title" class=" col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_user_id",
                "name" => "payslip_user_id",
                "value" =>$model_info->user_id,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_employee_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="payslip_user_id_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="close">×</span></a>
        </div>
    </div>
   <?php } ?>


<?php if($model_info->user_id) { ?>
   
        <div class="form-group" id= "bb">
        <label for="title" class=" col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_user_ids",
                "name" => "payslip_user_ids",
                //"value" =>$model_info->user_id,
                "value"=> "$pays", 
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_employee_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="payslip_user_id_title_dropdwon_icons" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="closes">×</span></a>
        </div>
    </div>

<?php } ?>
      
        <!--div class="form-group">
            <label for="payslip_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php /*
                echo form_dropdown("payslip_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='payslip_user_id'");
                */?>
            </div>
        </div-->

        <div class="modal-footer">
            <div class="row">
                
                <button id="submit_payslip" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
       $("#payslip-form").appForm({
            onSuccess: function (result) {
                $("#monthly-payslip-table").appTable({newData: result.data, dataId: result.id});
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                   
                    //window.location = 'Payslip/pays';
                    window.location = "<?php echo site_url('payslip/view'); ?>/" + result.id;
                }
            }
        });

       var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }
/*if (isUpdate) {
    var payslip =$("#pay").val(); 

         $("#payslip_user_id").val(payslip)
        } */

       

        $("#payslip_user_id_title_dropdwon_icon").click(function () {
             applySelect2OnItemTitle();
        })

        $("#payslip_user_id_title_dropdwon_icons").click(function () {
         $("#payslip_user_ids").hide();
         $("#payslip_user_id").show();
         $("#aa").show();
         $("#bb").hide();
             applySelect2OnItemTitle();
        })
        
        setDatePicker("#payslip_date");

        $("#payslip-form .select2").select2();
      
    });

    function applySelect2OnItemTitle() {
    //var datepayslip =$("#payslip_date").val();
        $("#payslip_user_id").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("payslip/get_payslip_user_id_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page,datepayslip) {
                    return {
                        q: term,payslip_date:$("#payslip_date").val() // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
    }
/*function applySelectPayslipUserId() {
    var datepayslip =$("#payslip_date").val();
    var emp_userid =$("#payslip_user_id").val();
    $.ajax({
                     url: "<?php echo get_uri("payslip/get_emp_monthly_payslip_info"); ?>",
                    data: {payslip_date:datepayslip ,user_id: emp_userid },
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {


if (response && response.success) {
 
                            if (response.item_info>0) 
                            {
                                $("#submit_payslip").prop('disabled', true);
                            }

                            
                        }
                        //auto fill the description, unit type and rate fields.

 /*var no_user_id=$("#payslip_dates").val();  
                    if  (no_user_id>0)
                    {


                        $("#submit-payslip").prop('disabled', true);
                        $('#message').html('Create the Employee Payslip Monthly Once').css('color', 'red');
                        //$("#submit-payslip").hide();
                        //alert('create the employee payslip monthly once ');
                    }else {
                        //alert('success');
                        //$("#submit-payslip").show();
                        $("#submit-payslip").prop('disabled', false)
                        $('#message').html('Discount Percentage should less than profit percentage').css('color', 'white');
                    } 
         
                    } 


                });

} */
</script>
 



