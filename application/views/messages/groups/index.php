<div id="page-content" class="clearfix p20">

    <div class="row">

        <div class="box">
            <div class="box-content message-button-list">
                <ul class="list-group ">
                    <?php echo modal_anchor(get_uri("messages/modal_form"), lang('compose'), array("class" => "list-group-item", "title" => lang('send_message'))); ?> 

                    <?php echo anchor(get_uri("messages/inbox"), lang('inbox'), array("class" => "list-group-item")); ?>

                    <?php echo anchor(get_uri("messages/sent_items"), lang('sent_items'), array("class" => "list-group-item")); ?>

                     <?php  if($this->login_user->user_type === "staff"){
                        echo anchor(get_uri("messages/groups_items"), lang('groups'), array("class" => "list-group-item"));
                        } ?>
                </ul>
            </div>


            <div class="box-content message-view" >
                <div class="col-sm-12 col-md-5">
                    <div id="message-list-box" class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <div class="pull-left p5">
                                <?php
                                if ($mode === "inbox") {
                                    echo "<i class='fa fa-inbox'></i> " . lang('inbox');
                                } else if ($mode === "sent_items") {
                                    echo "<i class='fa fa-send'></i> " . lang('sent_items');
                                }else if ($mode === "group_items") {
                                    echo "<i class='fa fa-users'></i> " . lang('group_items');
                                }
                                ?>
                            </div>
                            <div class="pull-right">
                                <!-- <input type="text" id="search-messages" class="datatable-search" placeholder="<?php echo lang('search') ?>"> -->
                                <?php if($this->login_user->is_admin){ echo modal_anchor(get_uri("messages/group_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_group'), array("class" => "btn btn-default", "title" => lang('add_group'))); } ?>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="group-table" class="display clickable no-thead b-b-only" cellspacing="0" width="100%">            
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div id="message-details-section" class="panel panel-default"> 
                        <div id="empty-message" class="text-center mb15 box">
                            <div class="box-content" style="vertical-align: middle; height: 100%"> 
                                <div><?php echo lang("select_a_message"); ?></div>
                                <span class="fa fa-envelope-o" style="font-size: 1100%; color:#f6f8f8"></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<!-- <style type="text/css">
    .datatable-tools:first-child {
        display:  none;
    }
</style> -->

<script type="text/javascript">
    $(document).ready(function () {
        var autoSelectIndex = "<?php echo $auto_select_index; ?>";
        $("#group-table").appTable({
            source: '<?php echo_uri("messages/group_list_data") ?>',
            columns: [
                //{title: "<?php /* echo lang("title"); */?>"},
                {title:'<?php echo lang("title") ?>', "class": "w60p"},
                {title: "<?php echo lang("team_members"); ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],

            onInitComplete: function () {

                if (autoSelectIndex) {
                    //automatically select the message
                    var $tr = $("[data-index=" + autoSelectIndex + "]").closest("tr");
                    if ($tr.length)
                        $tr.trigger("click");
                }
                var $role_list = $("#message-list-box"),
                        $empty_role = $("#empty-message");
                if ($empty_role.length && $role_list.length) {
                    $empty_role.height($role_list.height());
                }
            },
            displayLength: 1000,
           // printColumns: [0, 1]
        });

            /*load a message details*/
        $("body").on("click", "tr", function () {
            //don't load this message if already has selected.
            $(this).find(".badge").remove();
            if (!$(this).hasClass("active")) {
                appLoader.show();
                var project_id = $(this).find(".message-row").attr("data-id");
                if (project_id) {
                    $("tr.active").removeClass("active");
                    $(this).addClass("active");
                    $.ajax({
                        url: "<?php echo get_uri("messages/group_view"); ?>/" + project_id,
                        success: function (result) {
                            appLoader.hide();
                            $("#message-details-section").html(result);
                        }
                    });
                }
            }
        });
    });
</script>

