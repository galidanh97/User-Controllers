<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
global $wpdb;


//Captcha
//cấu hình thông tin do google cung cấp
$api_url     = 'https://www.google.com/recaptcha/api/siteverify';
$site_key    = '6Lc6nTUUAAAAANx6psO2Xia94nImHApJNmXo1FMh';
$secret_key  = '6Lc6nTUUAAAAADp1Q1yl5MjT-7EZZQ-LbswEh0Kc';
  



//Only accept post requests

    if ($_SERVER['REQUEST_METHOD'] !== "POST")
    {
        die ("Invalid action");
    }

//lấy dữ liệu được post lên
    $site_key_post    = $_POST['g-recaptcha-response'];
      
    //tạo link kết nối
    $api_url = $api_url.'?secret='.$secret_key.'&response='.$site_key_post;
    //lấy kết quả trả về từ google
    $response = file_get_contents($api_url);
    //dữ liệu trả về dạng json
    $response = json_decode($response);
if(isset($response->success) &&$response->success == true ){
    $message = "Đăng nhập thành công <br/> Đang chuyển trang...";
    $success = true;

    $name = $_POST['name'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $repass = $_POST['repassword'];

    //làm sạch thông tin, xóa bỏ các tag html, ký tự đặc biệt 
    //mà người dùng cố tình thêm vào để tấn công theo phương thức sql injection
    $user = strip_tags($user);
    $user = addslashes($user);
    $name = strip_tags($name);
    $name = addslashes($name);
    $pass = strip_tags($pass);
    $pass = addslashes($pass);
    $repass = strip_tags($repass);
    $repass = addslashes($repass);

    if ($user == "" || $pass =="" || $name == "" || $repass == "") {
        $success = false;
        $message = "Enter full information, please!!";
    }
    else {
        $query="select * from $wpdb->users where user_login='$user'";
        $wpdb->get_results($query);

        // Mysql_num_row is counting table row
        $count= $wpdb->num_rows;

        if($count == 0){
        	if($pass==$repass){
        		wp_create_user($user, $pass);
				$message = "Register Successful!!";
        	}
        	else{
        		$success = false;
            	$message = "password is not confirmed" ;
        	}
		
        }
        else
        {
            $success = false;
            $message = "username has existed!!" ;
        }
     
    
    }
}
else{
	$success = false;
        $message = "confirm that you are not a robot??" ;
}
//Ajax response
    echo json_encode(["success"=> $success, "message"=>$message]);
?>
