<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
require_once ("../index.php");

if(isset($_GET['action']) && $_GET['action'] != "" && function_exists($_GET['action']))
{
    call_user_func($_GET['action']);
}
else die ("Invalid action");

function updateProfile(){
    global $wpdb, $user;
    $errorItems = array();

//Config reCaptcha info
    $api_url     = 'https://www.google.com/recaptcha/api/siteverify';
    $site_key    = '6Lc6nTUUAAAAANx6psO2Xia94nImHApJNmXo1FMh';
    $secret_key  = '6Lc6nTUUAAAAADp1Q1yl5MjT-7EZZQ-LbswEh0Kc';


//Only accept post requests
    if ($_SERVER['REQUEST_METHOD'] !== "POST")
    {
        die ("Invalid action");
    }

//Check reCaptcha
    $site_key_post    = $_POST['g-recaptcha-response'];
    $api_url = $api_url.'?secret='.$secret_key.'&response='.$site_key_post;
    $response = file_get_contents($api_url);
    $response = json_decode($response);

    if(isset($response->success) && $response->success == true ) {
        $message = "Cập nhật thông tin thành công";
        $success = true;

        $name = $_POST['name'];
        //$user = $_POST['username'];
        $email = $_POST['email'];
        $currentPassword = $_POST['current-password'];
        $newPassword = $_POST['new-password'];
        $reNewPassword = $_POST['re-new-password'];

        if(!isset($name) ||$name =="")
            $errorItems['name'] = "Không được bỏ trống họ và tên.";

        if(!isset($email) ||$email =="")
            $errorItems['email'] = "Không được bỏ trống Email.";

        elseif(filter_var($email, FILTER_VALIDATE_EMAIL)==false)
            $errorItems['email'] = "Địa chỉ email không hợp lệ.";

        if(!isset($currentPassword) ||$currentPassword =="")
            $errorItems['current-password'] = "Không được bỏ trống mật khẩu hiện tại.";
        else {
            $result = $wpdb->get_results("select ID, user_pass from $wpdb->users where ID='{$user->ID}'");
            if (wp_check_password($currentPassword, $result[0]->user_pass, $user->ID) == false)
                $errorItems['current-password'] = "Mật khẩu hiện tại không đúng";
        }

        if( (isset($newPassword) && $newPassword != '') || (isset($reNewPassword) && $reNewPassword != ''))
            if($newPassword !== $reNewPassword)
                $errorItems['re-new-password'] = "Mật khẩu nhập lại không khớp";
        if(count($errorItems) === 0)
        {
            $data = array();
            $data['display_name'] = $name;
            $data['user_email'] = $email;
            if ($newPassword != "")
                wp_set_password($newPassword, $user->ID);
            $wpdb->update('wp_users', $data, array('ID' => $user->ID));
            $success = true;
            $message = "Đã cập nhật thông tin thành công";
        }
        else {
            //Error:
            $success = false;
            $message = $errorItems;
        }
    }
    else{
        $success = false;
        $message = array('captcha'=>"Xác minh bạn không phải robot") ;
    }
//Ajax response
    echo json_encode(["success"=> $success, "message"=>$message]);
}

/**
 *
 */
function uploadAvatar(){
    global $wpdb, $user;
    $site_url = site_url();

    $success = false;
    $message = "";
    $redirect = false;

    $path = $_SERVER["DOCUMENT_ROOT"].'/wp-content/uploads/avatar';
    $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");

    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];
    if(strlen($name))
    {
        list($txt, $ext) = explode(".", $name);
        if(in_array($ext,$valid_formats))
        {
            if($size<(1024*1024))
            {
                $actual_image_name = md5(time()).'.'.$ext;
                $filePath = $path .'/'.$actual_image_name;
                $tmp = $_FILES['photoimg']['tmp_name'];
                if(move_uploaded_file($tmp, $filePath))
                {
                    $width = getWidth($filePath);
                    $height = getHeight($filePath);
                    if ($width  < 512 && $height < 512) {
                        $wpdb->update('wp_users', array('user_avatar' => $site_url."/wp-content/uploads/avatar/".$actual_image_name), array('ID' => $user->ID));
                        $message = array('img' => "{$site_url}//wp-content/uploads/avatar/{$actual_image_name}");
                        $success = true;
                    }
                    else $message = "Height anh width of image must < 512px";
                }
                else $message = "Upload failed";
            }
            else $message = "Image file size max 1 MB";
        }
        else $message = "Invalid file format..";
    }
    else $message = "Please select image..!";

    echo json_encode(array('success'=>$success, 'message'=> $message, 'redirect'=>$redirect));

}

function saveAvatar(){
    $post = isset($_POST) ? $_POST: array();

    $path =$_SERVER["DOCUMENT_ROOT"].'/wp-content/uploads/avatar/';
    $t_width = 300; // Maximum thumbnail width
    $t_height = 300;    // Maximum thumbnail height

    if(isset($_POST['t']) and $_POST['t'] == "ajax")
    {
        extract($_POST);

        //$img = get_user_meta($userId, 'user_avatar', true);
        $imagePath = $path.$_POST['image_name'];
        $ratio = ($t_width/$w1);
        $nw = ceil($w1 * $ratio);
        $nh = ceil($h1 * $ratio);
        $nimg = imagecreatetruecolor($nw,$nh);
        $im_src = imagecreatefromjpeg($imagePath);
        imagecopyresampled($nimg,$im_src,0,0,$x1,$y1,$nw,$nh,$w1,$h1);
        imagejpeg($nimg,$imagePath,90);

    }
    echo $imagePath.'?'.time();;
    exit(0);
}


function resizeImage($image,$width,$height,$scale) {
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
    //$source = imagecreatefromjpeg($image);
    //imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
    //imagejpeg($newImage,$image,90);
    //chmod($image, 0777);
    return $image;
}
/*********************************************************************
Purpose            : get image height.
Parameters         : null
Returns            : height
 ***********************************************************************/
function getHeight($image) {
    $sizes = getimagesize($image);
    $height = $sizes[1];
    return $height;
}
/*********************************************************************
Purpose            : get image width.
Parameters         : null
Returns            : width
 ***********************************************************************/
function getWidth($image) {
    $sizes = getimagesize($image);
    $width = $sizes[0];
    return $width;
}
