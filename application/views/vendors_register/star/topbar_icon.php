<?php
if ($this->login_user->user_type == "staff") {

    $access_vendor = get_array_value($this->login_user->permissions, "vendor");
    if ($this->login_user->is_admin || $access_vendor) {
        ?>
        <li class="hidden-xs">
            <?php echo ajax_anchor(get_uri("vendors/show_my_starred_vendors/"), "<i class='fa fa-industry'></i>", array("class" => "dropdown-toggle", "data-toggle" => "dropdown", "data-real-target" => "#vendors-quick-list-container","title" => lang('favorite_vendors'))); ?>
            <div class="dropdown-menu aside-xl m0 p0 font-100p" style="width: 400px;" >
                <div id="vendors-quick-list-container" class="dropdown-details panel bg-white m0">
                    <div class="list-group">
                        <span class="list-group-item inline-loader p20"></span>                          
                    </div>
                </div>
            </div>
        </li>

        <?php
    }
}