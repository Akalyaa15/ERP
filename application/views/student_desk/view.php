
<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php 
if($student_desk_info){

    echo lang('student_desk_details');

    } ?>
</h1>
        <!--span id="star-mark">
            <?php /*
            if ($is_starred) {
                $this->load->view('clients/star/starred', array("client_id" => $client_info->id));
            } else {
                $this->load->view('clients/star/not_starred', array("client_id" => $client_info->id));
            }
            */ ?>
        </span-->    
   
               
                <?php /*echo anchor(get_uri("student_desk/download_pdf/" . $student_desk_info->id . "/1"), "<i class='fa fa-download'></i>" . lang('download_pdf'), array("class" => "btn btn-default", "title" => lang('download_pdf'), )); */?>
                <?php /* echo anchor(get_uri("student_desk/preview/" . $student_desk_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('student_desk_preview'), array("class" => "btn btn-default", "title" => lang('student_desk_preview'), ));  */ ?>
                <div class="title-button-group" style="float: left;margin-left:55%">
                         <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" >
                        <li role="presentation"><?php echo anchor(get_uri("student_desk/download_pdf/" . $student_desk_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("student_desk/preview/" . 
                        $student_desk_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('student_desk_preview'), array("title" => lang('student_desk_preview')), array("target" => "_blank")); ?> </li>
                        <li role="presentation" class="divider"></li>
                       

                        
                       
                    </ul>
                </span>
                    </div>
</div>

<div id="page-content" class="clearfix">
    <div class="mt15">
        <?php /* $this->load->view("clients/info_widgets/index"); */?>

    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        
        <li><a  role="presentation" href="<?php echo_uri("student_desk/student_desk_info_tab/" . $student_desk_info->id); ?>" data-target="#student_desk-info"><?php echo lang('student_desk_info'); ?></a></li>
    </ul>
    <div class="tab-content">
        
        <div role="tabpanel" class="tab-pane fade" id="student_desk-info"></div>
        
        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var tab = "<?php echo $tab; ?>";
        if (tab === "info") {
            $("[data-target=#student_desk]").trigger("click");
        }

    });
</script>
