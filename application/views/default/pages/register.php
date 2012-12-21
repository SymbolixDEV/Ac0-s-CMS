<div class="post">
<div class="post_header"><div class="post_title">Register Account</div></div>
<div class="post_bg">
<div class="post_text">
	<div style="width: 520px;margin:20px;">
	{register_status}
    <form action="{base_url}register/validation_register" method="post" accept-charset="utf-8">
	<span style="float: left;margin: 12px 0px 0px 0px;">
		<label for="register_username" style="cursor: pointer;"> Username</label>
	</span>
	<span style="float: right;">
		<span id="username_availability"></span> 
		<input type="text" name="register_username" value="" id="register_username" class="cool" onkeyup='javascript:ajaxLoaderContent("username_availability", "{base_url}ajax/username_availability/" + register_username.value, "{base_url}content/img/loader.gif") ;' />
	</span>
	<span style="clear: both; display: block;"></span>
	<span style="float: left;margin: 12px 0px 0px 0px;">
		<label for="register_password" style="cursor: pointer;">Password</label>
	</span>
	<span style="float: right;">
		<span id="password_availability"></span> 
		<input type="password" name="register_password" value="" id="register_password" class="cool" onkeyup='javascript:ajaxLoaderContent("password_availability", "{base_url}ajax/password_availability/" + register_password.value, "{base_url}content/img/loader.gif") ;' />
	</span>
	<span style="clear: both; display: block;"></span>
	<span style="float: left;margin: 12px 0px 0px 0px;">
		<label for="register_re_password" style="cursor: pointer;">Password confirm</label>
	</span>
	<span style="float: right;">
		<span id="password_re_availability"></span> 
		<input type="password" name="register_re_password" value="" id="register_re_password" class="cool" onkeyup='javascript:ajaxLoaderContent("password_re_availability", "{base_url}ajax/password_re_availability/" + register_password.value + "/" + register_re_password.value, "{base_url}content/img/loader.gif") ;' />
	</span>
	<span style="clear: both; display: block;"></span>
	<span style="float: left;margin: 12px 0px 0px 0px;">
		<label for="register_email" style="cursor: pointer;">Email</label>
	</span>
	<span style="float: right;">
	<span id="email_availability"></span> 
		<input type="text" name="register_email" value="" id="register_email" class="cool" onkeyup='javascript:ajaxLoaderContent("email_availability", "{base_url}ajax/username_email/" + register_email.value, "{base_url}content/img/loader.gif") ;' />
	</span>
	<span style="clear: both; display: block;"></span>
	<span style="float: left;margin: 12px 0px 0px 0px;">Security question</span>
	<span style="float: right;" id="register_security_question">
		<input type="radio" name="register_security_question" value="1"  /> Your middle name?<br />
		<input type="radio" name="register_security_question" value="2" checked="checked"  /> Your birth town?<br />
		<input type="radio" name="register_security_question" value="3"  /> Your pet's name?<br />
		<input type="radio" name="register_security_question" value="4"  /> Your mother maiden name?<br />
	</span>
	<span style="clear: both; display: block;"></span>
	<span style="float: left;margin: 12px 0px 0px 0px;">
		<label for="register_security_answer" style="cursor: pointer;">Security answer</label>
	</span>
	<span style="float: right;">
		<input type="text" name="register_security_answer" value="" id="register_security_answer" class="cool"  />
	</span><span style="clear: both; display: block;"></span>
	
	<span style="float: left;margin: 12px 0px 0px 0px;font-weight: bold;"></span><span style="float: right;"><input type="submit" name="register_submit" value="Register Account" id="register_submit" class="cool"  /></span><span style="clear: both; display: block;"></span></form>
	</div>
</div>
</div>
</div>
