<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <?php
        
        if ($show_close_preview)
            echo "<div class='text-center'>" . anchor("voucher/view/" . $estimate_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
            ?>
            <?php if($estimate_info->status=="applied"){ ?>
 <div class="title-button-group text-center"><br>
                    <?php echo modal_anchor(get_uri("voucher/item_modal_form"),  lang('edit'), array("class" => "btn btn-default round", "title" => lang('add_voucher'), "data-post-id" => $estimate_items[0]->id)); ?>
                </div>
            <?php } ?>
        <div class="bg-white mt15 p30">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $estimate_status_label; ?></div>
            </div>

            <?php
            echo $estimate_preview;
            ?><br>
            <?php echo get_setting("voucher_footer"); ?>
            <!--p style="text-align:center;color:#2371bd;font-size: 15px">Registered Office : Gemicates Technologies Private Limited<br>CIN:U29253TN2014PTC098558&nbsp;<br>11/6,Sundareshwarer Koil Street,Saidapet, Chennai-600015.<br>Phone No:044-48534375/044-48527269 | www.gemicates.com | info@gemicates.com</p-->
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
