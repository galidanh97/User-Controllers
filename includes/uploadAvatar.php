<?php

include_once "../index.php";
changeAvatar();
function changeAvatar() {
    global $user;
    $site_url = site_url();
    $post = isset($_POST) ? $_POST: array();
    $max_width = "500";
    $path = $_SERVER["DOCUMENT_ROOT"].'/wp-content/uploads/avatar';

    $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];
    if(strlen($name))
    {
        list($txt, $ext) = explode(".", $name);
        if(in_array($ext,$valid_formats))
        {
            if($size<(1024*1024)) // Image size max 1 MB
            {
                $actual_image_name = md5(time()).'.'.$ext;
                $filePath = $path .'/'.$actual_image_name;
                $tmp = $_FILES['photoimg']['tmp_name'];
                if(move_uploaded_file($tmp, $filePath))
                {
//                    $width = $this->getWidth($filePath);
//                    $height = $this->getHeight($filePath);
//                    //Scale the image if it is greater than the width set above
//                    if ($width > $max_width){
//                        $scale = $max_width/$width;
//                        $uploaded = $this->resizeImage($filePath,$width,$height,$scale);
//                    }else{
//                        $scale = 1;
//                        $uploaded = $this->resizeImage($filePath,$width,$height,$scale);
//                    }
//                    $res = $this->Profile->saveAvatar(array(
//                        'userId' => isset($userId) ? intval($userId) : 0,
//                        'avatar' => isset($actual_image_name) ? $actual_image_name : '',
//                    ));

                    //mysql_query("UPDATE users SET profile_image='$actual_image_name' WHERE uid='$session_id'");
                    echo "<img id='photo' class='' src='{$site_url}//wp-content/uploads/avatar/{$actual_image_name}' class='preview'/>";
                }
                else
                    echo "failed";
            }
            else
                echo "Image file size max 1 MB";
        }
        else
            echo "Invalid file format..";
    }
    else
        echo "Please select image..!";
    exit;


}