<?php
if ($this->login_user->user_type == "staff") {

    $access_company = get_array_value($this->login_user->permissions, "company");
    if ($this->login_user->is_admin || $access_company) {
        ?>
        <li class="hidden-xs">
            <?php echo ajax_anchor(get_uri("companys/show_my_starred_companys/"), "<i class='fa fa-building starred-icon'></i>", array("class" => "dropdown-toggle", "data-toggle" => "dropdown", "data-real-target" => "#companys-quick-list-container","title" => lang('favorite_companys'))); ?>
            <div class="dropdown-menu aside-xl m0 p0 font-100p" style="width: 400px;" >
                <div id="companys-quick-list-container" class="dropdown-details panel bg-white m0">
                    <div class="list-group">
                        <span class="list-group-item inline-loader p20"></span>                          
                    </div>
                </div>
            </div>
        </li>

        <?php
    }
}