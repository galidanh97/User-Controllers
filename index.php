<?php
/*
Plugin Name:  UserControllers
Plugin URI:   https://dllnv.cf/plugins/usercontrollers/
Description:  UserController by DLLNV team
Version:      1.0
Author:       DLLNV
Author URI:   https://dllnv.cf/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  languages/
*/
if(session_id() == '')
    session_start();

include_once ("includes/bootstrap.php");
include_once ("includes/add_short_code.php");
include_once ("includes/add_filter_menus.php");


//Include main plugin's style
wp_enqueue_style( 'uc_style', plugins_url("assets/css/style.css", __FILE__),false,'1.1','all');
wp_enqueue_style( 'toastr_style', plugins_url("assets/css/toastr.css", __FILE__),false,'1.1','all');
wp_enqueue_script( 'toastr_script', "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js",false,'1.1','all');
wp_enqueue_script( 'uc_script', plugins_url("assets/js/main.js", __FILE__),false,'1.1','all');



/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
	add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', __FILE__, 'my_plugin_options' );
}

/** Step 3.*/ 
function my_plugin_options() {

$result = $wpdb->get_results ( "
    SELECT *
    FROM  $wpdb->users" );


echo "<table>";
foreach ( $result as $page )
{
   echo "<tr>";
   echo "<td>$page->ID</td>";
   echo "<td>$page->user_login</td>";
   echo "<td>$page->user_pass</td>";
   echo "</tr>";
}
echo "</table>";

echo "ahihi";
}

?>
