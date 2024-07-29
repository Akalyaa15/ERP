<span style="color:#444; line-height: 14px;">

    <?php      
    	if(get_setting("estimate_footer")>0){
    		$invoice_footers = $this->Terms_conditions_templates_model->get_one(get_setting("estimate_footer"));
echo $invoice_footers->custom_message;
}else{
	    		$invoice_footers = $this->Terms_conditions_templates_model->get_default()->row();
echo $invoice_footers->custom_message;

}
       
 ?>
</span>