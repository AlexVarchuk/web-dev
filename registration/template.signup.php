<div class="forms-wrapper signup">
	<div>
    	<p class="advertisement">Advertisement</p>
    </div>
    <div class="forms">
	  	<? if(!$errors && $_GET['done'] !== 'true') { ?>	
            <div class="notification info">
                <p>Sign up now! <span>Sign up for a personal account to save videos, leave comments and utilize other advanced features!</span></p>          
            </div>
		<? } ?>		
        <? if($errors) { ?>
            <div class="notification error">
            <p>
                <strong>The following errors have occured:</strong><br>
                <? 
                    foreach($errors as $i) {
                        echo "&bull; $i<br>";
                    } 
                ?>
            </p>	
            </div>
        <? } ?>
		
        <? if(isset($_GET[done]) && $_GET[done] == 'true'){ ?>
            <div class="notification success">
               <p>
                    <? if($require_account_confirmation){ ?>
                        Verification mail was sent to you email address.<br />
                        To login please <strong>verify your account</strong> through email verification link.
                    <? } else { ?>
                        You may now login at the <a href="/login">login page</a>
                    <? } ?>
                </p>
            </div>
        <? } else {  ?>				
            <form class="authentication_form" name="loginForm" method="post" action="" autocomplete='on'>
                <div class="form_row">
                    <label for="af_username">Username</label>
                    <div class="input_wrapper">
                        <input id="af_username" name="signup_username" type="text" maxlength="25" value="<? echo $thisusername; ?>"/></div>
                    </div>
                <div class="form_row">
                    <label for="af_password">Password</label>
                    <div class="input_wrapper">
                        <input id="af_password" name="signup_password" maxlength="35" autocomplete='off' type="password" />
                    </div>
                </div>
                <div class="form_row">
                    <label for="af_email">Email</label>
                    <div class="input_wrapper">
                        <input id="af_email" name="signup_email" type="text" maxlength="35"  value="<? echo $thisemail; ?>" />
                    </div>
                </div>

                <? if($enable_signup_captcha) { ?>	
                    <div class="form_row">
                        <label for="af_captcha">Human?</label>
                        <div class="input_wrapper">
                            <img class="captcha_image" src="/captcha.php?<? echo time(); ?>" />
                            <input class="captcha_text" id="af_captcha" type="text" name="captchaaa" />
                        </div>
                    </div>
                <? } ?>	
                <div class="form_row">
                    <div class="input_wrapper">
                        <input type="submit" name="Submit" value="Register" class="standart_button"/>
                    </div>
                </div>
            </form>
		<? } ?>
    </div>
</div>
