
<!DOCTYPE html>
<html>
<style type="text/css">
    .inputfile {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
</style>
<head>
    <title>Import Excel Data into Database</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/bootstrap.min.css" />
    <script src="<?php echo base_url(); ?>asset/jquery.min.js"></script>
</head>


</html>

<script>
$(document).ready(function(){

    load_data();

    function load_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/excel_import/fetch",
            method:"POST",
            success:function(data){
                $('#customer_data').html(data);
            }
        })
    }

    $('#import_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/excel_import/import",
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(data){
                $('#file').val('');
                location. reload(true);

            }
        })
    });

$('#import_form_icici').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/excel_import/import_icici",
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(data){
                $('#file').val('');
                location. reload(true);

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
        $("#icici_bank").hide();
        $("#indian_bank").show();
      }
      else if ( this.value == '2')
      //.....................^.......
      {
        $("#icici_bank").show();
        $("#indian_bank").hide();
      }
      else
      {
        $("#icici_bank").hide();
        $("#indian_bank").hide();
      }
    });

    //select bank account number

$("#bank_id").on('change',function () {
    $("#account_number").val("").attr('readonly', false)
                    var account_number =$("#account_number").val();

          $("#account_number,#account_numbers").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("excel_import/get_account_number_suggestion"); ?>",
                dataType: 'json',
               data: function (account_number, page) {
                    return {
                        q: account_number,
                        bank_id:$("#bank_id").val()// search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })



});
</script>
<div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
     <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("bank_statement"); ?></h4></li>
            <li><a id="monthly-monthly-bankstatement-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-bankstatement"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("excel_import/yearly/"); ?>" data-target="#yearly-bankstatement"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("excel_import/custom/"); ?>" data-target="#custom-bankstatement"><?php echo lang('custom'); ?></a></li>
            
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                   <div class="title-button-group">
                <?php  echo modal_anchor(get_uri("excel_import/excel_import_form"), "<i class='fa fa-upload' aria-hidden='true'></i> " . lang('import'), array("class" => "btn btn-default", "title" => lang('import')));  ?>
            </div> 
                
                </div>
            </div>
        </ul>
        <div class="page-title clearfix">
        </div>
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
       
        <div style='display:none;' id='indian_bank'>
        <div class="form-group">
                        <label for="select_bank" class=" col-md-2"><?php echo lang('select_excel_file'); ?></label>
                        <div class=" col-md-10">
        
        <form method="post" id="import_form" enctype="multipart/form-data">
        <!-- select acount number -->
        <input  type="text" id="account_numbers" name="account_number"  placeholder="Account Number" style ="width:304px" required="true" /></br><span id ="indianbank_message"></span></br>
            <p><!--label>Select Excel File</label-->
            <input type="file" name="file" class="sasa" id="file" required accept=".xls, .xlsx" /></p>
            <br />
            <input type="submit" name="import" value="Import" class="btn btn-info ss" id="subs" />
            
        </form>
        </div>
        </div>
        </div>
       <div style='display:none;' id='icici_bank'>
        <div class="form-group">
                        <label for="select_bank" class=" col-md-2"><?php echo lang('select_excel_file'); ?></label>
                        <div class=" col-md-10">
        
        <form method="post" id="import_form_icici" enctype="multipart/form-data">
        <!-- select acount number -->
          <input type="text" id="account_number" name="account_number"  placeholder="Account Number" style ="width:304px" /></br><span id ="icicibank_message"></span></br>
            <p><!--label>Select Excel File</label-->
            <input type="file" name="file" class="sasa" id="file1" required accept=".xls, .xlsx" /></p>
            <br />
            <input type="submit" name="import_icici" value="Import" class="btn btn-info ss" id="subss" />
    </form>
    </div>
    </div>
    </div>
        
    </div>
    <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-bankstatement">
        <div class="table-responsive">
            <table id="import_excel-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="yearly-bankstatement"></div>
    <div role="tabpanel" class="tab-pane fade" id="custom-bankstatement"></div>
</div>

<script type="text/javascript">
    loadImportExcelTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        } 

       $(selector).appTable({
            source: '<?php echo_uri("Excel_import/list_data") ?>',
           dateRangeType: dateRange,
            order: [[0, 'asc']],
            rangeDatepicker: customDatePicker,
            displayLength: 100,
            columns: [
            {title: "<?php echo lang('Bank_name') ?> ", "class": "w15p"},
             {title: "<?php echo lang('account_number') ?> ", "class": "w15p"},
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
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8,9,10],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8,9,10],

            summation: [{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 10, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
         
        });
    };
 $("#select_bank").select2();
 $("#bank_id.select2").select2();
    $(document).ready(function () {
   $("#monthly-bankstatement-button").trigger("click");
        loadImportExcelTable("#import_excel-table","monthly");
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
</script>

<!-- account number required -->
<script>
 
$('#subs').click(function () {
    var country =$("#account_numbers").val();
    if(country)
    {
    //$('#message').html('Select the State Name').css('color', 'white').hide()
    $('#indianbank_message').html('Select the Account Number').css('color', 'white').hide()
    return true;
    }
    else
    {
    //$('#message').html('Select the State Name').css('color', 'red').show()
    $('#indianbank_message').html('Select the Account Number ').css('color', 'red').show()
    return false;
    }
});

$('#subss').click(function () {
    var country =$("#account_number").val();
    if(country)
    {
    //$('#message').html('Select the State Name').css('color', 'white').hide()
    $('#icicibank_message').html('Select the Account Number').css('color', 'white').hide()
    return true;
    }
    else
    {
    //$('#message').html('Select the State Name').css('color', 'red').show()
    $('#icicibank_message').html('Select the Account Number').css('color', 'red').show()
    return false;
    }
});
    </script>