<?php
    if($_POST['ahd_username'] && $_POST['ahd_password']) {
        $_POST = mysql_real_escape_array($_POST);
        $pass = $_POST[ahd_password];

        $result = dbQuery("SELECT * FROM users WHERE username = '".$_POST['ahd_username']."' AND password = MD5(CONCAT('$pass',salt))",false);
        if(count($result) > 0) {
			if($result[0]['validate'] == '') { 
				$_SESSION[username] = $result[0]['username'];
				$_SESSION[userid] = $result[0]['record_num'];
				$_SESSION[email] = $result[0]['email'];
				$_SESSION[user_level] = $result[0]['user_level'];
				$_SESSION[premium] = $result[0]['premium'];
				$_SESSION[password] = $result[0]['password'];
				$_SESSION[password_plain] = $pass; 
				$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
				$time = time();
				mysql_query("UPDATE users SET lastlogin = '$time' WHERE record_num = '".$result[0]['record_num']."'");
				if($_REQUEST['ref']) {
					header("Location: ".urldecode($_REQUEST['ref']));
				}	
				header("Location: $basehttp/my-profile");exit();
			} else { 
				$errors[] = 'Sorry, you must verify your email before logging in. <a href="/action.php?action=resendVerification&id='.$result[0]['username'].'">Click here to resend verification email</a>.';
			}
        }else{
            $errors[] = 'The login information you have provided was incorrect. Please try again.';
        }
    }else{
        if($_POST){
            $errors[] = 'Incorrect username and password';
        }
    }
    $title = 'Login';
    $headertitle = 'Login to your account';        
    getTemplate("template.overall_header.php");
    getTemplate("template.login.php");
    getTemplate("template.overall_footer.php");
?>
