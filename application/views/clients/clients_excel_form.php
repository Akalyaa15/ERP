
<script>
$("#format_file").select2();

$("#format_file").click(function(e) {
    if($("#format_file").val()== 1){
       
                        /*alert($("#format_file").val());*/
                        $("#excel").show();
                        $("#csv").hide();

    }else if($("#format_file").val()== 2){
                
                
/*alert($("#format_file").val());*/
                       $("#excel").hide();
                        $("#csv").show();
                        
    }
    });
</script>
<script>
$(document).ready(function(){

    load_data();

    function load_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/clients/fetch",
            method:"POST",
            success:function(data){
                $('#customer_data').html(data);
            }
        })
    }

    $('#import_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/clients/import",
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

    $('#import_form_csv').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/clients/upload_file_csv",
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
<div class="modal-body clearfix">
   <div class="form-group" >
                        <label for="select_format_file" class=" col-md-3"><?php echo lang('select_your_format_file'); ?></label>
                        <div class=" col-md-9">
        
       <select class='select2 validate-hidden'  style="width: 300px;" id='format_file' name='format_file' required>
             <option value="0">Select</option>
             <option value="1">Excel</option>
             <option value="2">CSV</option>
 </select>
            </div>
            </div>
            <br/>
        <br/>
<div class="form-group" style='display:none;' id='excel'>
                        <label for="select_bank" class=" col-md-3"><?php echo lang('select_excel_file'); ?></label>
                        <div class=" col-md-9">
        <form method="post" id="import_form" enctype="multipart/form-data">
            <!--label>Select Excel File</label-->
            <input type="file" name="file" class="btn btn-default" id="file" required accept=".xls, .xlsx, .ods" />
            <br />
            <!-- <input type="submit" name="import" class="btn btn-primary" value="Import" class="btn btn-info" /> -->
            <button  type="submit" name="import" class="btn btn-primary ss"><i class="fa fa-upload" aria-hidden="true"></i> Import</button> <span id="alert_message" ></span>
            </form>
            </div>
    </div>
            
     <div class="form-group"  style='display:none;' id='csv'>
                        <label for="select_bank" class=" col-md-3"><?php echo lang('select_csv_file'); ?></label>
                        <div class=" col-md-9">
    <form  method="post" id="import_form_csv" enctype="multipart/form-data" >
                <input type="file" name="file" class="btn btn-default" required accept=".csv"  id="file1"/>
                
                <br>
               <!--  <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT"> -->
               <button  type="submit" name="importSubmit" class="btn btn-primary ss"><i class="fa fa-upload" aria-hidden="true"></i> Import</button> <span id="csv_alert_message" ></span>
            </form>
            </div>
        </div>
            
        
    
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <!-- <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button> -->
  </div>
</div>


<!-- aler validate file -->
<script type="text/javascript">
   
      $('#file').on('change', function() {
     var ext = $('#file').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['xls','xlsx']) == -1) {
   
  $('.ss').prop("disabled", true); // Element(s) are now enabled.
   $('.ss').attr("disabled", true); 
   $(".ss").attr('title', 'Only Excel files can be Imported');
    
    $('#alert_message').html('Only Excel file').css('color', 'red').show();
}else{
    $('.ss').prop("disabled", false); // Element(s) are now enabled.
   $('.ss').attr("disabled", false); 
   $(".ss").attr('title', '');
   $('#alert_message').hide();
}
    });
$('#file1').on('change', function() {
     var ext = $('#file1').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['csv']) == -1) {
   
  $('.ss').prop("disabled", true); // Element(s) are now enabled.
   $('.ss').attr("disabled", true); 
   $(".ss").attr('title', 'Only CSV files can be Imported');
   $('#csv_alert_message').html('Only CSV file').css('color', 'red').show();
}else{
    $('.ss').prop("disabled", false); // Element(s) are now enabled.
   $('.ss').attr("disabled", false); 
   $(".ss").attr('title', '');
   $('#csv_alert_message').hide();
}
    });
</script>

