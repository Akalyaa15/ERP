 <div id="reply-content-container-<?php echo $reply_info->id; ?>"  class="media b-b p15 m0 bg-white js-message-reply" data-message_id="<?php echo $reply_info->id; ?>" href="#reply-<?php echo $reply_info->id; ?>">
        <div class="media-left">
        <span class="avatar avatar-sm">
            <img src="<?php echo get_avatar($reply_info->user_image); ?>" alt="..." />
        </span>
    </div>
        <div class="media-body w100p">
            <div class="media-heading">
              <strong><?php
                if ($reply_info->from_user_id === $this->login_user->id) {
                    echo lang("me");
                } else {
                    if ($reply_info->user_type == "client") {
                        echo get_client_contact_profile_link($reply_info->from_user_id, $reply_info->user_name, array("class" => "dark strong"));
                    } else {
                        echo get_team_member_profile_link($reply_info->from_user_id, $reply_info->user_name, array("class" => "dark strong"));
                    }
                }
                ?>
            </strong>
                <small><span class="text-off pull-right"><?php echo format_to_relative_time($reply_info->created_at); ?></span></span></small>


                <?php if ($this->login_user->is_admin || $reply_info->from_user_id == $this->login_user->id) { ?>
            <span class="pull-right dropdown" style="position: absolute; right: 30px; margin-top: 15px;">
                    <div class="text-off dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true" >
                        <i class="fa fa-chevron-down clickable"></i>
                    </div>
                    <ul class="dropdown-menu" role="menu">
                         <li role="presentation"><?php echo ajax_anchor(get_uri("messages/delete/$reply_info->id"), "<i class='fa fa-times'></i> " . lang('delete'), array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#reply-content-container-$reply_info->id")); ?> </li>
                         <!--  <li role="presentation"> <?php echo modal_anchor(get_uri("messages/forward_modal_form/"), "<i class='fa fa-forward'></i> " . lang('forward'), array("title" => lang("forward"), "data-post-reply_info_id" => $reply_info->id)); ?> </li> -->
                    </ul>
                </span>

<?php } ?>
            </div>

            <p><?php echo nl2br(link_it($reply_info->message)); ?></p>
             <p>
            <?php
            $files = unserialize($reply_info->files);
            $total_files = count($files);

            if ($total_files) {
                $download_caption = lang('download');
                if ($total_files > 1) {
                    $download_caption = sprintf(lang('download_files'), $total_files);
                }
                echo "<i class='fa fa-paperclip pull-left font-16'></i>";
                echo anchor(get_uri("messages/download_message_files/" . $reply_info->id), $download_caption, array("class" => "", "title" => $download_caption));
            }
            ?>
        </p>
        </div>
    </div>

<?php /* 
//orginal reply form 
<!-- <div class="media b-b p15 m0 bg-white js-message-reply" data-message_id="<?php echo $reply_info->id; ?>" href="#reply-<?php echo $reply_info->id; ?>" >
    <div class="media-left">
        <span class="avatar avatar-sm">
            <img src="<?php echo get_avatar($reply_info->user_image); ?>" alt="..." />
        </span>
    </div>
    <div class="media-body w100p">
        <div class="media-heading">
            <strong><?php
                if ($reply_info->from_user_id === $this->login_user->id) {
                    echo lang("me");
                } else {
                    if ($reply_info->user_type == "client") {
                        echo get_client_contact_profile_link($reply_info->from_user_id, $reply_info->user_name, array("class" => "dark strong"));
                    } else {
                        echo get_team_member_profile_link($reply_info->from_user_id, $reply_info->user_name, array("class" => "dark strong"));
                    }
                }
                ?>
            </strong>
            <span class="text-off pull-right"><?php echo format_to_relative_time($reply_info->created_at); ?></span>
        </div>
        <p><?php echo nl2br(link_it($reply_info->message)); ?></p>

        <p>
            <?php
            $files = unserialize($reply_info->files);
            $total_files = count($files);

            if ($total_files) {
                $download_caption = lang('download');
                if ($total_files > 1) {
                    $download_caption = sprintf(lang('download_files'), $total_files);
                }
                echo "<i class='fa fa-paperclip pull-left font-16'></i>";
                echo anchor(get_uri("messages/download_message_files/" . $reply_info->id), $download_caption, array("class" => "", "title" => $download_caption));
            }
            ?>
        </p>
    </div>
</div> -->  */
?>