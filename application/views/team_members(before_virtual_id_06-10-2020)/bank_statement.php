<script>
$(document).ready(function(){

   

    $('#import_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/personal_bank_statement/import",
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(response){
                if (response) {
 location.reload()
}else{
  $('#warning').show();
  setTimeout(function(){
  $('#warning').hide();
}, 3500);


}
                
             

            }
        })
    });
$('#import_form_hdfc').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/personal_bank_statement/import_hdfc",
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(response){
                if (response) {
 location.reload()
}else{
  $('#warning').show();
  setTimeout(function(){
  $('#warning').hide();
}, 3500);


}
                
             

            }
        })
    });
$('#import_form_icici').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/personal_bank_statement/import_icici",
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
             success:function(response){
                if (response) {
 location.reload()
}else{
  $('#warning').show();
  setTimeout(function(){
  $('#warning').hide();
}, 3500);


}
                
             

            }
        })
    });
});


</script>

<script>
$(document).ready(function(){
    $('#bank_id').on('change', function() {
      if ( this.value == '1')
      //.....................^.......
      {
        $("#hdfc_bank").hide();
        $("#icici_bank").hide();
        $("#indian_bank").show();
      }
      else if ( this.value == '2')
      //.....................^.......
      {
        $("#hdfc_bank").hide();
        $("#icici_bank").show();
        $("#indian_bank").hide();
      }else if ( this.value == '4')
      //.....................^.......
      {
        $("#hdfc_bank").show();
        $("#indian_bank").hide();
        $("#icici_bank").hide();
      }
      else
      {
        $("#icici_bank").hide();
        $("#indian_bank").hide();
        $("#hdfc_bank").hide();
      }
    });
});
</script>
<script type="text/javascript">
   
      $('#file').on('change', function() {
     var ext = $('#file').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['xls','xlsx']) == -1) {
   
  $('.ss').prop("disabled", true); // Element(s) are now enabled.
   $('.ss').attr("disabled", true); 
   $(".ss").attr('title', 'Already Delivery challan has been created');
}else{
    $('.ss').prop("disabled", false); // Element(s) are now enabled.
   $('.ss').attr("disabled", false); 
   $(".ss").attr('title', '');
}
    });
$('#file1').on('change', function() {
     var ext = $('#file1').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['xls','xlsx']) == -1) {
   
  $('.ss').prop("disabled", true); // Element(s) are now enabled.
   $('.ss').attr("disabled", true); 
   $(".ss").attr('title', 'Only Excel files can be Imported');
}else{
    $('.ss').prop("disabled", false); // Element(s) are now enabled.
   $('.ss').attr("disabled", false); 
   $(".ss").attr('title', '');
}
    });
</script><div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
     <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("bank_statement"); ?></h4></li>
            <li><a id="monthly-bankstatement-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-bankstatement"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("personal_bank_statement/yearly/"); ?>" data-target="#yearly-bankstatement"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("personal_bank_statement/custom/"); ?>" data-target="#custom-bankstatement"><?php echo lang('custom'); ?></a></li>
            
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    
                
                </div>
            </div>
        </ul>
       
    <div class="container">
        <br>
        <!--div class="form-group">
                        <label for="select_bank" class=" col-md-2"><?php echo lang('select_bank'); ?></label>
                        <div class=" col-md-10">
        <select id='select_bank' style="width: 300px;">
         <option value="0">Indian Bank</option>
         <option value="1">ICICI Bank</option>
        </select>
        </div>
        </div-->

        <div class="form-group">
            <label for="bank_list" class=" col-md-2"><?php echo lang('select_your_bank'); ?></label>
            <div class="col-md-10" >
                <?php 
                echo form_dropdown("bank_id", $bank_list_dropdown, array($model_info->bank_id), "class='select2'id='bank_id' style='width: 300px'");
                ?>
            </div>
        </div>
        <br/>
        <p style="display: none;color:red;padding-left:200px;padding-top: 15px" id="warning">This file cannot be imported</p>
       
        <div style='display:none;' id='indian_bank'>
        <div class="form-group">
                        <label for="select_bank" class=" col-md-2"><?php echo lang('select_excel_file'); ?></label>
                        <div class=" col-md-10">
        
        <form method="post" id="import_form" enctype="multipart/form-data">
            <p><!--label>Select Excel File</label-->
             <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            <input type="file" name="file" class="sasa" id="file" required accept=".xls, .xlsx" /></p>
            <br />
            <input type="submit" name="import" value="Import" class="btn btn-info ss" id="subs" />
            
        </form>
        </div>
        </div>
        </div>
       <div style='display:none;' id='hdfc_bank'>
        <div class="form-group">
                        <label for="select_bank" class=" col-md-2"><?php echo lang('select_excel_file'); ?></label>
                        <div class=" col-md-10">
        
        <form method="post" id="import_form_hdfc" enctype="multipart/form-data">
            <p><!--label>Select Excel File</label-->
             <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            <input type="file" name="file" class="sasa" id="file" required accept=".xls, .xlsx" /></p>
            <br />
            <input type="submit" name="import_hdfc" value="Import" class="btn btn-info ss" id="subs" />
            
        </form>
        </div>
        </div>
        </div> 
       <div style='display:none;' id='icici_bank'>
        <div class="form-group">
                        <label for="select_bank" class=" col-md-2"><?php echo lang('select_excel_file'); ?></label>
                        <div class=" col-md-10">
        
        <form method="post" id="import_form_icici" enctype="multipart/form-data">
            <p><!--label>Select Excel File</label-->
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            <input type="file" name="file" class="sasa" id="file1" required accept=".xls, .xlsx" /></p>
            <br />
            <input type="submit" name="import_icici" value="Import" class="btn btn-info ss" id="subs" />
    </form>
    </div>
    </div>
    </div>
        
    </div>
   <!--  <div class="table-responsive">
        <table id="expense-table" class="display" cellspacing="0" width="100%">
        </table>
    </div> -->
     <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-bankstatement">
        <div class="table-responsive">
            <table id="personal_bank-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="yearly-bankstatement"></div>
    <div role="tabpanel" class="tab-pane fade" id="custom-bankstatement"></div>
</div>
</div>
<script type="text/javascript">
   loadExpenseTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        } 

       $(selector).appTable({
            source: '<?php echo_uri("personal_bank_statement/list_data") ?>',
           dateRangeType: dateRange,
            order: [[0, 'asc']],
             filterParams: {user_id: "<?php echo $user_id; ?>"},
            rangeDatepicker: customDatePicker,
            displayLength: 100,
            columns: [
            {title: "<?php echo lang('bank_name') ?> ", "class": "w15p"},
            {title: "<?php echo lang('value_date') ?> ", "class": "w15p"},
            {title: "<?php echo lang('post_date') ?>", "class": "w15p"},
                {title: "<?php echo lang('remitter_branch') ?> ","class": "w15p"},
             {title: "<?php echo lang('description') ?> ", "class": "w40p"},
             {title: "<?php echo lang('cheque_number') ?> ", "class": "w15p"},
             {title: "<?php echo lang('transaction_id') ?> ", "class": "w10p"},
             
             {title: "<?php echo lang('debit_amount') ?>", "class": "w15p"},
                {title: "<?php echo lang('credit_amount') ?>", "class": "w100"},
                {title: "<?php echo lang('account_balance') ?>", "class": "w15p"},
                {title: "<?php echo lang('remark') ?>", "class": "w15p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8,9],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8,9],

            summation: [{column: 7, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
         
        });
    };
 $("#select_bank").select2();
 $("#bank_id.select2").select2();
    $(document).ready(function () {
   $("#monthly-bankstatement-button").trigger("click");
        loadExpenseTable("#personal_bank-table","monthly");
    });
</script><head>
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/bootstrap.min.css" />
    <script src="<?php echo base_url(); ?>asset/jquery.min.js"></script>
</head>
