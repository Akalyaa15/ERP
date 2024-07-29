<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

     <div class="invoice-preview">


        <?php
        if ($show_close_preview)
            echo "<div class='text-center'>" . anchor("student_desk/view/" . $student_desk_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
            ?>
           <?php 
           if ($this->login_user->user_type === "staff"&& $this->login_user->is_admin == 0) {
                    echo "<div class='text-center'>" . anchor("student_desk/download_pdf/" . $student_desk_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
                }
                    ?>

        <div class="bg-white mt15 p30">


            <?php
            echo $student_desk_preview;
            ?><br>
            <table >
<tbody>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:60px !important;padding-top:10px; "><p style="color:black ;">Name:<?php echo $student_desk_info->name." ".$student_desk_info->last_name; ?>
         </p></td><td style="width:35%;font-size:16px;text-align:center;padding-top:10px;"><p style="color:black ;">&nbsp;Name:<?php echo $student_desk_info->parent_name; ?><br></p></td>
<td style="width:40%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;For Gemicates Technologies Pvt Ltd <br></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Student Signature
         </p></td><td style="width:35%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Parent / Guardian Signature<br></p></td>
<td style="width:40%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Authorised Signature <br></p></td></tr>
<!-- <tr><td style="width:50%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Signature</p><p><b style="color:white;">.</b></p></td>
<td style="width:50%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Authorised Signature<br></p></td></tr> --></tbody></table>
            <br>
            <?php echo get_setting("payslip_footer"); ?>
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
