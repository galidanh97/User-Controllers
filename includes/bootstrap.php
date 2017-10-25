<?php
/**
 * Created by PhpStorm.
 * User: luant
 * Date: 10/24/2017
 * Time: 14:09
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );

//Get current user info
function getUserInfo(){
    global $wpdb;

    if (isset($_SESSION['userID']) && $_SESSION['userID'] != ""){

        $result = $wpdb->get_results ("SELECT * FROM  $wpdb->users where ID='{$_SESSION['userID']}'" );

        $user =$result[0];

        //Use default avatar incase user haven't set
        if ($user->user_avatar == "")
            $user->user_avatar = "https://dllnv.cf/wp-content/uploads/default.png";

        return $user;
    }
    else
        return false;
}


$user = getUserInfo();
//echo var_dump($user);