<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php');
global $wpdb;

//Only accept post requests


if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $message = "Die";
    die ("Invalid action");
}

//Default Value
$message = "Đăng nhập thành công <br/> Đang chuyển trang...";
$success = true;

$user = $_POST['username'];
$pass = $_POST['password'];
//làm sạch thông tin, xóa bỏ các tag html, ký tự đặc biệt
//mà người dùng cố tình thêm vào để tấn công theo phương thức sql injection
$user = strip_tags($user);
$user = addslashes($user);
$pass = strip_tags($pass);
$pass = addslashes($pass);

if ($user == "" || $pass == "") {
    $success = false;
    $message = "username or password is invalid!";
} else {
    $query = "select ID, user_pass from $wpdb->users where user_login='$user'";

    $result = $wpdb->get_results($query);
    // Mysql_num_row is counting table row
    $count = $wpdb->num_rows;

    if ($count == 1) {
        $hash = $result[0]->user_pass;
        if (wp_check_password($pass, $hash, $wpdb->id)) {
            // $_SESSION['loggedin'] = true;
            $_SESSION['userID'] = $result[0]->ID;
            $message = "<strong>Login success!</strong>";
        } else {
            $success = false;
            $message = "<strong>username or password is incorrect!</strong>";
        }

    } else {
        $success = false;
        $message = "<strong>username or password is incorrect!</strong>";
    }
}

//Ajax response login

echo json_encode(["success" => $success, "message" => $message]);

?>
