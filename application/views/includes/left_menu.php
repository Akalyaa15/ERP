<div id="sidebar" class="box-content ani-width">
    <div class="input-group">
        <input type="text" id="mySearch" onkeyup="myFunctionss()" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
        </span>
    </div>
    <div id="sidebar-scroll">
        <ul class="" id="sidebar-menu">
            <?php
            if (!$is_preview) {
                $sidebar_menu = get_active_menu($sidebar_menu);
            }

            foreach ($sidebar_menu as $main_menu) {
                if (isset($main_menu["name"])) {
                    $submenu = get_array_value($main_menu, "submenu");
                    $expend_class = $submenu ? " expand " : "";
                    $active_class = isset($main_menu["is_active_menu"]) ? "active" : "";

                    $submenu_open_class = "";
                    if ($expend_class && $active_class) {
                        $submenu_open_class = " open ";
                    }
                    $devider_class = ($show_devider && get_array_value($main_menu, "devider")) ? "devider" : "";
                    $badge = get_array_value($main_menu, "badge");
                    $badge_class = get_array_value($main_menu, "badge_class");
                    $target = (isset($main_menu['is_custom_menu_item']) && isset($main_menu['open_in_new_tab']) && $main_menu['open_in_new_tab']) ? "target='_blank'" : "";
                    $url = isset($main_menu['url']) ? (isset($main_menu['is_custom_menu_item']) ? $main_menu['url'] : get_uri($main_menu['url'])) : '#';
                    ?>
                    <li class="<?php echo $active_class . " " . $expend_class . " " . $submenu_open_class . " $devider_class"; ?> main">
                        <a <?php echo $target; ?> href="<?php echo $url; ?>">
                            <i class="fa <?php echo ($main_menu['class']); ?>"></i>
                            <span><?php echo isset($main_menu['is_custom_menu_item']) ? $main_menu['name'] : lang($main_menu['name']); ?></span>
                            <span style="display:none">
                                <?php if ($submenu) {
                                    foreach ($submenu as $s_menu) {
                                        if (isset($s_menu['name'])) {
                                            if (isset($s_menu['is_custom_menu_item'])) {
                                                echo $s_menu['name'];
                                            } else {
                                                echo lang($s_menu['name']);
                                            }
                                        }
                                    }
                                } ?>
                            </span>
                            <?php
                            if ($badge) {
                                echo "<span class='badge $badge_class'>$badge</span>";
                            }
                            ?>
                        </a>
                        <?php
                        if ($submenu) {
                            echo "<ul>";
                            foreach ($submenu as $s_menu) {
                                if (isset($s_menu['name'])) {
                                    $sub_menu_target = (isset($s_menu['is_custom_menu_item']) && isset($s_menu['open_in_new_tab']) && $s_menu['open_in_new_tab']) ? "target='_blank'" : "";
                                    $sub_url = isset($s_menu['url']) ? (isset($s_menu['is_custom_menu_item']) ? $s_menu['url'] : get_uri($s_menu['url'])) : '#';
                                    ?>
                                    <li class='false'>
                                        <a <?php echo $sub_menu_target; ?> href="<?php echo $sub_url; ?>">
                                            <i class="fa <?php echo ($s_menu['class']); ?>"></i>
                                            <span><?php echo isset($s_menu['is_custom_menu_item']) ? $s_menu['name'] : lang($s_menu['name']); ?></span>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            echo "</ul>";
                        }
                        ?>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <br>
        <script>
            function myFunctionss() {
                var input, filter, ul, li, a, i;
                input = document.getElementById("mySearch");
                filter = input.value.toUpperCase();
                ul = document.getElementById("sidebar-menu");
                li = ul.getElementsByTagName("li");

                for (i = 0; i < li.length; i++) {
                    a = li[i].getElementsByTagName("a")[0];
                    if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
                var x = document.getElementsByClassName("false");
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "block";
                }
            }
        </script>
    </div>
</div>

