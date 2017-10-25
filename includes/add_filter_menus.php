<?php
/**
add_filter( 'wp_nav_menu_items', 'your_custom_menu_item', 10, 2 );
function your_custom_menu_item ( $items, $args ) {
    if (is_single() && $args->theme_location == 'primary') {
        $items .= '<li>Show whatever</li>';
    }
    return $items;
}
**/
add_filter('wp_nav_menu_items','add_search_box_to_menu', 10, 2);
function add_search_box_to_menu( $items, $args ) {
    if( $args->theme_location == 'primary' )
        return $items."<li class='menu-header-search'><form action='https://dllnv.cf/' id='searchform' method='get'><input type='text' name='s' id='s' placeholder='Search'></form></li>";
 
    return $items;
}

add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    global $user;
    $site_url = site_url();

    $logoutLink= plugins_url("logout.php", __FILE__);

    if ($args->theme_location == 'primary') {
        if ($user !== false) {
            $items .= <<<EOD
    <li class="profile-nav">
        <a href="#">
            <span> {$user->display_name}</span>
            <span class="avatar"><img src="{$user->user_avatar}"></span>
        </a>
        <ul class="dropdown-menu">
           <li class="dropdown-item"><a href="{$site_url}/profile"><span>Thông tin</span></a></li>
           <li class="dropdown-item"><a href="{$logoutLink}/"><span>Đăng xuất</span></a></li>
        </ul>
     </li>
EOD;

    //	wp_logout_url();
            }
        else{
            $items .= '<li><a href="/login">Đăng nhập</a></li>';
            $items .= '<li><a href="/register">Đăng ký</a></li>';
        }
    }
    return $items;
}

