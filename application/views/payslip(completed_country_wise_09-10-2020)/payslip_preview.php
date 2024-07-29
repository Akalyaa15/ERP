<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

     <div class="invoice-preview">


        <?php
        if ($show_close_preview)
            echo "<div class='text-center'>" . anchor("payslip/view/" . $payslip_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
            ?>
           <?php 
           if ($this->login_user->user_type === "staff"&& $this->login_user->is_admin == 0) {
                    echo "<div class='text-center'>" . anchor("payslip/download_pdf/" . $payslip_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
                }
                    ?>

        <div class="bg-white mt15 p30">


            <?php
            echo $payslip_preview;
            ?><br>
            <br>
            <?php /*echo get_setting("payslip_footer");*/ 
              
            $payslip_table = $this->Payslip_model->get_one($payslip_info->id);
        $user_table = $this->Users_model->get_one($payslip_table->user_id);
        $user_country = $payslip_table->country;
if($user_country){ 
    $user_country_options = array("numberCode"=> $user_country);
    $get_user_country_info = $this->Countries_model->get_details($user_country_options)->row();
    $get_country_prefix = $get_user_country_info->payslip_footer;
}
echo  '<div style="text-align:center">'.$get_country_prefix .'<div>';

            ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payment-amount").change(function () {
            var value = $(this).val();
            $(".payment-amount-field").each(function () {
                $(this).val(value);
            });
        });
    });



</script>
