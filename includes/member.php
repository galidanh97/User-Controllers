<?php
$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');
global $wpdb;

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo "Welcome to the member's area, " . $_SESSION['username'] . "!";
} else {
    echo "Please log in first to see this page.";
}

$results = $wpdb->get_results( 'SELECT * FROM wp_users);
foreach ($re as $results){
echo "<li>[$re->user_login]</li>";
}

?>