<?php $this->load->view("includes/cropbox"); ?>
<div id="page-content" class="clearfix">
    <div class="bg-success clearfix">
        <div class="col-md-6">
            <div class="row p20">
               <!--  <?php $this->load->view("users/profile_image_section"); ?> -->
<!-- start country profile --> 
<div class="box">
    <div class="box-content w200 text-center profile-image">
        <?php
        
        echo form_open(get_uri("countries/save_profile_image/".$country_info->id), array("id" => "profile-image-form", "class" => "general-form", "role" => "form"));
        ?>
       
            <div class="file-upload btn mt0 p0" style="vertical-align: top;  margin-left: -45px; ">
                <span><i class="btn fa fa-camera" ></i></span> 
                <input id="profile_image_file" class="upload" name="profile_image_file" type="file" data-height="200" data-width="200" data-preview-container="#profile-image-preview" data-input-field="#profile_image" />
            </div>
            <input type="hidden" id="profile_image" name="profile_image" value=""  />
      
        <span class="avatar avatar-lg"><?php if($country_info->image){ ?><img id="profile-image-preview" src="<?php echo /*get_avatar($country_info->image);*/ get_file_uri(get_general_file_path("country_profile_image", $country_info->id) . $country_info->image) ?>" alt="..."><?php } else { ?><img id="profile-image-preview" src="<?php echo get_avatar($country_info->image); ?>" alt="..."><?php } ?></span> 
        <h4 class=""><?php echo $country_info->countryName; ?></h4>
        <?php echo form_close(); ?>
    </div> 


    <div class="box-content pl15">
        <!-- <p class="p10 m0"> <?php echo lang("country_name").":";?> <label class="label label-info large"><strong> <?php echo $country_info->countryName; ?> </strong></label></p>  -->

       
            
            
                <p class="p10 m0">
                    <?php if ($country_info->numberCode) { ?>
                        <?php echo lang("country_code").":";?> <label class="label label-info large"><strong> <?php echo $country_info->numberCode; ?> </strong></label>
                        <?php } ?>
                 </p>
                 <p class="p10 m0">
                    <?php if ($country_info->iso) { ?>
                        <?php echo lang("country_iso_code").":";?> <label class="label label-info large"><strong> <?php echo $country_info->iso; ?> </strong></label>
                        <?php } ?>
                 </p>
                 <p class="p10 m0">        
         <?php
                    if ($country_info->currency_name) { 
                        ?>
                        <?php echo lang("currency_name").":";?> <label class="label label-info large"><strong> <?php echo $country_info->currency_name; ?> </strong></label> 
                    <?php }  ?>
                </p>
                <p class="p10 m0">        
         <?php
                    if ($country_info->currency) { 
                        ?>
                         <?php echo lang("currency").":";?> <label class="label label-info large"><strong> <?php echo $country_info->currency; ?> </strong></label> 
                    <?php }  ?>
                </p>
                <p class="p10 m0">        
         <?php   if ($country_info->currency_symbol) { ?>
                        
                        <?php echo lang("currency_symbol").":";?> <label class="label label-info large"><strong> <?php echo $country_info->currency_symbol; ?> </strong></label> 
                    <?php } ?>
                </p>
                 
                  
       

       
           
        </div>
    </div>
</div>

<!-- end country profile -->


            </div>
        </div>

        <div class="col-md-6 text-center cover-widget">
           <!--  <div  class="row p20">
                <?php
                //count_project_status_widget($user_info->id);
               // count_total_time_widget($user_info->id);
                ?> 
            </div> -->
        </div>
    </div>


    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        
        <li><a  role="presentation" href="<?php echo_uri("countries/country_info/" . $country_info->id); ?>" data-target="#country-info"><?php echo lang('country_info'); ?></a></li>
         <!-- <li><a  role="presentation" href="<?php echo_uri("countries/payslip_info/" . $country_info->id); ?>" data-target="#payslip-info"> <?php echo lang('payslip_settings'); ?></a></li>
         <li><a  role="presentation" href="<?php echo_uri("countries/payslip_earnings_info/" . $country_info->id); ?>" data-target="#payslip_earnings-info"><?php echo lang('earnings'); ?></a></li>
          <li><a  role="presentation" href="<?php echo_uri("countries/payslip_deductions_info/" . $country_info->id); ?>" data-target="#payslip_deductions-info"><?php echo lang('deductions'); ?></a></li> -->
    </ul>
    <div class="tab-content">
        
        <div role="tabpanel" class="tab-pane fade" id="country-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="payslip-info"></div>
         <div role="tabpanel" class="tab-pane fade" id="payslip_earnings-info"></div>
          <div role="tabpanel" class="tab-pane fade" id="payslip_deductions-info"></div>
        
        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

         $(".upload").change(function () {
            if (typeof FileReader == 'function') {
                showCropBox(this);
            } else {
                $("#profile-image-form").submit();
            }
        });
        $("#profile_image").change(function () {
            $("#profile-image-form").submit();
        });


        $("#profile-image-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "profile_image") {
                        var profile_image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = profile_image;
                    }
                });
            },
            onSuccess: function (result) {
                if (typeof FileReader == 'function') {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    location.reload();
                }
            }
        });

        var tab = "<?php echo $tab; ?>";
        if (tab === "country_info") {
            $("[data-target=#country-info]").trigger("click");
        }else if (tab === "payslip_info") {
            $("[data-target=#payslip-info]").trigger("click");
        }else if (tab === "payslip_earnings_info") {
            $("[data-target=#payslip_earnings-info]").trigger("click");
        }else if (tab === "payslip_deductions_info") {
            $("[data-target=#payslip_deductions-info]").trigger("click");
        }

    });
</script>
<?php
$options = array("country_id" => $country_info->id ,"key_name"=> "basic_salary");

        //$this->update_only_allowed_members($user_id);

        $list_data = $this->Country_earnings_model->get_details($options)->result();
        if($list_data){


/*echo "yes";*/
        }else if (!$list_data){
/*echo "no";*/
$datas[] = array(
                       
           "title" =>  "Basic Salary",
            "percentage" => 40,
            "status" => "active",
            "description" => "-",
            "country_id" =>  $country_info->id,
            "key_name"=>"basic_salary"
                    );

$this->Country_earnings_model->insert($datas);

        }

 ?>


 
