<?php /* echo form_open(get_uri("invoice_payments/pay_invoice_via_stripe"), array("id" => "stripe-checkout-form", "class" => "pull-left", "role" => "form")); */?>


<button 
    type="button" 
    id="net_banking-payment-button" 
    class="btn btn-primary mr15"
  data-key="<?php /* echo get_array_value($payment_method, "publishable_key"); */?>"
  
   onclick="location.href= 'https://infinity.icicibank.com/corp/AuthenticationController?FORMSGROUP_ID__=AuthenticationFG&__START_TRAN_FLAG__=Y&FG_BUTTONS__=LOAD&ACTION.LOAD=Y&AuthenticationFG.LOGIN_FLAG=1&BANK_ID=ICI&ITM=nli_primer_login_btn_desk&_ga=2.70783376.1836024278.1559113503-1506387026.1559113503'"
    data-name="Purchase Order #<?php echo $purchase_order_info->id; ?>"
    data-description="<?php echo lang("pay_purchase_order"); ?>: (<?php echo to_currency($balance_due, $currency . " "); ?>)"
    data-image="<?php echo get_file_uri("assets/images/stripe-payment-logo.png"); ?>"
    data-locale="auto"
    > <?php echo get_array_value($payment_method, "pay_button_text"); ?></button>
    <?php echo form_close(); ?>

    <?php /* onclick="location.href='https://www.icicibank.com/Personal-Banking/insta-banking/internet-banking/index.page'" */?>


<!--script src="https://checkout.stripe.com/v2/checkout.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var currency = "<?php echo $currency . ' '; ?>",
                payInvoiceText = "<?php echo lang("pay_purchase_order"); ?>";
        var $button = $("#net_banking-payment-button");

        $button.on('click', function (event) {

            //show an error message if user attempt to pay more than the invoice due and exit
            if (unformatCurrency($("#payment-amount").val()) > "<?php echo $balance_due; ?>") {
                appAlert.error("<?php echo lang("invoice_over_payment_error_message"); ?>");
                return false;
            }

            var $button = $(this),
                    $form = $button.parents('form'),
                    opts = $.extend({}, $button.data(),
                            {
                                token: function (result) {
                                    $form.append($('<input>').attr({type: 'hidden', name: 'stripe_token', value: result.id})).submit();
                                },
                                opened: function () {
                                    $button.removeClass("inline-loader").addClass("btn-primary");
                                }
                            });

            $button.addClass("inline-loader").addClass("btn-default").removeClass("btn-primary");
            StripeCheckout.open(opts);
        });



        var minimumPaymentAmount = "<?php echo get_array_value($payment_method, 'minimum_payment_amount'); ?>" * 1;
        if (!minimumPaymentAmount || isNaN(minimumPaymentAmount)) {
            minimumPaymentAmount = 1;
        }

        $("#payment-amount").change(function () {
            //changed the amount. update the description on stripe payment form
            var value = $(this).val(),
                    buttonData = $button.data();
            $button.removeData();


            buttonData.description = payInvoiceText + " (" + toCurrency(unformatCurrency(value), currency) + ")";
            $button.data(buttonData);

            //change stripe payment amount field value as inputed/ don't use unformatCurrency we'll do it in controller
            $("#net_banking-payment-amount-field").val(value);

            //check minimum payment amount and show/hide payment button
            if (value < minimumPaymentAmount) {
                $("#net_banking-payment-button").hide();
            } else {
                $("#net_banking-payment-button").show();
            }

        });

    });
</script-->