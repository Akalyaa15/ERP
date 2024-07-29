<div class="panel panel-default mb15">
    <div class="panel-heading text-center">
        <?php if (get_setting("show_logo_in_signin_page") === "yes") { ?>
            <img class="p20" src="<?php echo get_file_uri(get_setting("system_file_path") . get_setting("site_logo")); ?>" />
        <?php } else { ?>
            <h2><?php echo lang('signin'); ?></h2>
        <?php } ?>
    </div>
    <div class="panel-body p30">
        <?php echo form_open("signin", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>

        <?php if (validation_errors()) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo validation_errors(); ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control p10",
                "placeholder" => lang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-email" => true,
                "data-msg-email" => lang("enter_valid_email")
            ));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_password(array(
                "id" => "password",
                "name" => "password",
                "class" => "form-control p10",
                "placeholder" => lang('password'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required")
            ));
            ?>
        </div><span class="p-viewer2">
                  <i class="fa fa-eye close" id="open" aria-hidden="true">                     </i>
<i class="fa fa-eye-slash close" id="close" aria-hidden="true" style="display: none"></i>
                </span>
<span id="caps-error" style="color: red;display: none;" class="help-block ">Caps lock is ON.</span>        <input type="hidden" name="redirect" value="<?php
        if (isset($redirect)) {
            echo $redirect;
        }
        ?>" />

       
        <?php $this->load->view("signin/re_captcha"); ?>
     

        <div class="form-group mb0">
            <button class="btn btn-lg btn-primary btn-block mt15" type="submit"><?php echo lang('signin'); ?></button>
        </div>
        <?php echo form_close(); ?>
        <div class="mt5"><?php echo anchor("signin/request_reset_password", lang("forgot_password")); ?></div>

        <?php if (!get_setting("disable_client_signup")) { ?>
            <div class="mt20"><?php echo lang("you_dont_have_a_client_account") ?> &nbsp; <?php echo anchor("signup", lang("signup")); ?></div>
        <?php } ?>
        <?php if (!get_setting("disable_vendor_signup")) { ?>
            <div class="mt20"><?php echo lang("you_dont_have_an_vendor_account") ?> &nbsp; <?php echo anchor("vendor_signup", lang("signup")); ?></div>
        <?php } ?>
        <?php if (!get_setting("disable_partner_signup")) { ?>
            <div class="mt20"><?php echo lang("you_dont_have_an_partner_account") ?> &nbsp; <?php echo anchor("signup_partner/partner", lang("signup")); ?></div>
        <?php } ?>
        <?php if (!get_setting("disable_company_signup")) { ?>
            <div class="mt20"><?php echo lang("you_dont_have_a_company_account") ?> &nbsp; <?php echo anchor("signup/company", lang("signup")); ?></div>
        <?php } ?>
        <?php if (!get_setting("disable_student_desk_registration")) { ?>
            <div class="mt20"><?php echo lang("you_dont_have_an_student_desk") ?> &nbsp; <?php echo anchor("student_desk_signup", lang("register")); ?></div>
        <?php } ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
//  $.ajax({
//   url: 'https://api.postalpincode.in/pincode/609001',
//   type: 'GET',
//   dataType: 'json',
//   jsonp: 'jsoncallback',
  
//    success: function (data) {
//     var state = data[0].PostOffice[0].State;
//     var country = data[0].PostOffice[0].Country;
//  alert(state) 
//  alert(country) 
//   }
// });     
  $("#signin-form").appForm({ajaxSubmit: false, isModal: false});
    });
</script>    
<script>
var input = document.getElementById("password");
var text = document.getElementById("caps-error");
input.addEventListener("keyup", function(event) {

if (event.getModifierState("CapsLock")) {
    text.style.display = "block"
  } else {
    text.style.display = "none"
  }
});
</script>
<style type="text/css">.p-viewer2{
  float: right;
  margin-top: -50px;  
color:green;
}</style>
<script type="text/javascript">
    $(document).ready(function () {
    $('#open').on('click', function () {
   $("#password").attr('type', 'text'); 
   $("#close").show(); 
      $("#open").hide(); 
});
     $('#close').on('click', function () {
   $("#password").attr('type', 'password'); 
   $("#open").show(); 
      $("#close").hide(); 
});   
     //get the current user time zone 
});   


</script>