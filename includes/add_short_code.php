<?php

$result = $wpdb->get_results("SELECT * FROM  $wpdb->users where ID=1");

function uc_login_form()
{
    $site_url = site_url();
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

    echo <<<EOD
<form class="uc_form login" action="{$site_url}/wp-content/plugins/UserControllers/includes/login.php">
  
  <fieldset>
    
  	<legend>Đăng nhập</legend>
    
    <div class="input">
    	<input type="text" name="username" placeholder="Tên đăng nhập" required />
      <span><i class="fa fa-users"></i></span>
    </div>
    
    <div class="input">
    	<input type="password" name="password" placeholder="Mật khẩu" required />
      <span><i class="fa fa-lock"></i></span>
    </div>

    <button type="submit" class="submit"><i class="fa fa-long-arrow-right"></i></button>
    
  </fieldset>
  <div class="feedback"></div>
  
</form>
EOD;
}


function uc_register_form()
{
    $site_url = site_url();

    if (isset($_SESSION['userID']) && $_SESSION['userID'] != "") {
        header("Location: {$site_url}", 200);
        exit();
    }

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
    echo "<script src='https://www.google.com/recaptcha/api.js'></script>";

    echo <<<EOD
<form class="uc_form register" action="{$site_url}/wp-content/plugins/UserControllers/includes/register.php">
  
  <fieldset>
    
  	<legend>Đăng ký thành viên</legend>
    
    <div class="input">
    	<input type="text" name="name" placeholder="Họ và tên" required />
      <span><i class="fa fa-user-o"></i></span>
    </div>
    
    <div class="input">
    	<input type="text" name="username" placeholder="Tên đăng nhập" required />
      <span><i class="fa fa-users"></i></span>
    </div>
    
    <div class="input">
    	<input type="password" name="password" placeholder="Mật khẩu" required />
      <span><i class="fa fa-lock"></i></span>
    </div>
    <div class="input">
    	<input type="password" name="repassword" placeholder="Nhập lại mật khẩu" required />
      <span><i class="fa fa-lock"></i></span>
    </div>
    <div class="input">
        <div class="g-recaptcha" data-sitekey="6Lc6nTUUAAAAANx6psO2Xia94nImHApJNmXo1FMh" style="margin:0 15px;"></div>
    </div>
    <div class="feedback"></div>
    <button type="submit" class="submit"><i class="fa fa-long-arrow-right"></i></button>
    
  </fieldset>
  
</form>
EOD;
}

//FORM VIEW PROFILE
function uc_profile_form()
{
    global $user;
    $site_url = site_url();
    $plugin_url = plugins_url("../", __FILE__);

    if ($user === false) {
        header("Location: {$site_url}/login", 403);
        exit();
    }

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
    echo "<script src='https://www.google.com/recaptcha/api.js'></script>";


    echo <<<END
<form class="uc_form profile" action="{$site_url}/wp-content/plugins/UserControllers/includes/profile.php?action=updateProfile">
  
  <fieldset>
    
  	<legend>Thông tin</legend>
    
	<label>Họ và tên</label>
    <div class="input" id="name">
    	<input type="text" name="name" value="{$user->display_name}" required />
    	<div class="input-error"></div>
      <span><i class="fa fa-user-o"></i></span>
    </div>
    
    <label>Tên đăng nhập</label>
    <div class="input" id="username">
    	<input type="text" name="username" value="{$user->user_login}" readonly />
    	<div class="input-error"></div>
      <span><i class="fa fa-users"></i></span>
    </div>

    <label>Email</label>
    <div class="input" id="email">
    	<input type="email" name="email" value="{$user->user_email}" required/>
    	<div class="input-error"></div>
      <span><i class="fa fa-users"></i></span>
    </div>
    
    <label>Mật khẩu hiện tại</label>
    <div class="input" id="current-password">
    	<input type="password" name="current-password" required/>
    	<div class="input-error"></div>
      <span><i class="fa fa-lock"></i></span>
    </div>

    <label>Mật khẩu mới</label>
    <div class="input" id="new-password">
    	<input type="password" name="new-password" placeholder="Bỏ trống nếu không muốn thay đổi"/>
    	<div class="input-error"></div>
      <span><i class="fa fa-lock"></i></span>
    </div>
    
    <label>Nhập lại mật khẩu mới</label>
    <div class="input" id="re-new-password">
    	<input type="password" name="re-new-password" placeholder="Nhập lại mật khẩu" />
    	<div class="input-error"></div>
      <span><i class="fa fa-lock"></i></span>
    </div>
    <div class="input" id="captcha">
        <div class="g-recaptcha" data-sitekey="6Lc6nTUUAAAAANx6psO2Xia94nImHApJNmXo1FMh" style="margin:0 15px;"></div>
        <div class="input-error"></div>
    </div>
    <div class="input">
        <button type="submit" class="submit"><i class="fa fa-long-arrow-right"></i></button>
    </div>
  </fieldset>
  
  <div class="feedback"></div>
  
</form>

<div class="profile-avatar">
    <div><img id="avatar-edit-img" class="img-circle"  height="128" style="width: 140px; height: 140px;" src="{$user->user_avatar}"/></div>
    <div id="changePic">
        <form id="cropimage" method="POST" enctype="multipart/form-data" action="{$plugin_url}includes/profile.php?action=uploadAvatar">
            <!--<input type="file" name="photoimg" id="photoimg" />-->
            <input type="hidden" name="action" value="" id="action" />
            <input type="hidden" name="image_name" value="" id="image_name" />
            
            <div class="upload-btn-wrapper">
              <button class="btn">Thay đổi</button>
              <input type="file" name="photoimg" id="photoimg" />
            </div>
        </form>
    </div>
</div>
END;
}

//Register shordcode
add_shortcode("uc_login_form", "uc_login_form");
add_shortcode("uc_register_form", "uc_register_form");
add_shortcode("uc_profile_form", "uc_profile_form");