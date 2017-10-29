<?php
require_once('/db.php');
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
				$last_login_result = dbQuery("SELECT * FROM users WHERE record_num = '".$result[0]['record_num']."' ",false);
				if(!$last_login_result[0]['lastlogin']==''){
					$res['info']  = $basehttp.'/my-profile';
					$res['error'] = 'false';
					echo json_encode($res);					
				}else{
					$res['info']  = $basehttp.'/edit-profile';
					$res['error'] = 'false';
					echo json_encode($res);
				}
				mysql_query("UPDATE users SET lastlogin = '$time' WHERE record_num = '".$result[0]['record_num']."'");
				if($_REQUEST['ref']) {
					$res['error'] = 'false';
					$res['info']  = urldecode($_REQUEST['ref']);
					echo json_encode($res);	
				}				
				exit();
			} else { 
				$res['error'] = 'true';
				$res['info'] = '<div id="notificaError">
					Sorry, you must verify your email before logging in. <a href="'.$basehttp.'/action.php?action=resendVerification&id='.$result[0]['username'].'">Click here to resend verification email</a>.
				</div>';
	        	echo json_encode($res);
			}
        }else{
            $res['error'] = 'true';
			$res['info'] = '<div id="notificaError">
           	The login information you have provided was incorrect. Please try again.
           	</div>';
        	echo json_encode($res);            
       	}        
    }else{
        if($_POST){
            $res['error'] = 'true';
			$res['info'] = '<div id="notificaError">
            Incorrect username and password
            </div>';       
            }
        echo json_encode($res);
    }
?>
