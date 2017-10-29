<?
	if($_POST['signup_username'] && $_POST['signup_password'] && $_POST['signup_email']) {
		$_POST = mysql_real_escape_array($_POST);
		$errors = array();
		$thisusername = htmlentities($_POST['signup_username']);
		$thispassword = htmlentities($_POST['signup_password']);
		$thisemail = htmlentities($_POST['signup_email']);
		$result = mysql_query("SELECT * FROM users WHERE username = '$thisusername'");
		if(mysql_num_rows($result) > 0) {
			$errors[] = "This username already exists in our system.";
		}
		if(!ctype_alnum($thisusername)) {
			$errors[] = "Username contains invalid characters. Please use only A-Z and 0-9, no spaces are special characters"; 
		}
		if(strlen($thisusername) < 3) {
			$errors[] = "Username must be at least 3 characters long";
		}
		if(strlen($thispassword) < 5) {
			$errors[] = "Password must be at least 5 characters long";
		}
		$result = mysql_query("SELECT * FROM users WHERE email = '$thisemail'");
		if(mysql_num_rows($result) > 0) {
			$errors[] = "This email address already exists in our system.";
		}
		if(!filter_var($thisemail, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Invalid Email Address";
		}
		if($enable_signup_captcha) {
			if(strtolower($_POST['captchaaa']) != strtolower($_SESSION['captcha'])) {
				$errors[] = "Incorrect CAPTCHA Response";
			}
		}
		if(!$errors) {
			if($require_account_confirmation) {
				$validationCode = substr(number_format(time() * rand(),0,'',''),0,20);
			}else{
				$validationCode = '';
	        }
			$time = date("Y-m-d H:i:s");
	        $salt = generateSalt(rand(5,10));
			mysql_query("INSERT INTO users (username,password,salt,email,registration_ip,validate,date_joined) 
	                    VALUES('$thisusername',MD5('".$thispassword.$salt."'),'$salt','$thisemail','".$_SERVER['REMOTE_ADDR']."','$validationCode','$time')") or die();
			$insert_id = mysql_insert_id();
			if($require_account_confirmation) {
				sendVerificationEmail($insert_id,$validationCode);
			} else { 
				sendWelcomeEmail($insert_id); 
			}
	        header("Location: $basehttp/signup?done=true");
	        exit();
		}
	}else{
	    if($_POST){
	        $errors[] = "All fields area mandatory";
	    }
	    
	    if($_GET['panel'] == 1){
	        $thisusername = $_GET['panel_username'];
	        $thisemail = $_GET['panel_email'];
	    }
	}

	$title = 'Sign up now in our Community';
	getTemplate("template.overall_header.php");
	getTemplate("template.signup.php"); 
	getTemplate("template.overall_footer.php"); 

?>
