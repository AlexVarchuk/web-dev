<div class="forms-wrapper signup">
    <div class="forms">	
        <? if($errors){ ?>
        <div class="notification error">
		<p>
            <?
                foreach($errors as $err){
                    echo $err.'<br />';
                }
            ?>
		</p>	
        </div>
        <? } else { ?>
        <div class="notification info">
		<p>Login <br/>
           <span> You may login to your account using the form below.
            <a href='<? echo $basehttp; ?>/signup'>Not a member? Click here to sign up, its free!</a></span>
        </p>
	    </div>	
		<? } ?>		
        <form class="authentication_form" name="loginForm" method="post" action="" autocomplete='on'>
            <div class="form_row">
                <label for="af_username">Username</label>
                <div class="input_wrapper">
                    <input id="af_username" name="ahd_username" type="text" maxlength="255" />
                </div>
            </div>
            <div class="form_row">
                <label for="af_password">Password</label>
                <div class="input_wrapper">
                    <input id="af_password" name="ahd_password" autocomplete='off' type="password" />
                </div>
            </div>
            <div class="form_row">
                <div class="input_wrapper">
                    <a class="form_link" href="/forgot-pass">Forgot Password?</a>
                </div>
            </div>
            <div class="form_row">
                <div class="input_wrapper">
                    <input type="submit" name="Submit" value="Login" class="standart_button" />
                </div>
            </div>
        </form>
    </div>
</div>
</section>