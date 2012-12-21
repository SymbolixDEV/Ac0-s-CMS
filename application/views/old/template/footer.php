</div>
			<div class="right_body">
				<div class="box_title">Membership</div>
				<div class="box_content">
					{not_logged}
						<form action="{base_url}login/validation_login" method="post" accept-charset="utf-8">
							<input type="hidden" name="return_link" value="{current_url}" />
							<span class="left" style="margin-top:10px;">
								<label for="login_username" style="cursor: pointer;">
									<strong>Account Name</strong>
								</label>
							</span>
							<span class="right"><input type="text" name="login_username" value="Username" id="login_username" class="login_input" onfocus='if (this.value == "Username") this.value = "";' onblur='if (!this.value){ this.value = "Username"; }' /></span>
							<span class="clear"></span>
							<span class="left" style="margin-top:10px">
								<label for="login_password" style="cursor: pointer;">
									<strong>Password</strong>
								</label>
							</span>
							<span class="right"><input type="password" name="login_password" value="Password" id="login_password" class="login_input" onfocus='if (this.value == "Password") this.value = "";' onblur='if (!this.value){ this.value = "Password"; }' /></span>
							<span class="clear"></span>
							<span class="left">
								<input type="checkbox" name="login_remember_me" value="1" id="login_remember_me" style="cursor: pointer;float:left;"  /> 
								<label for="login_remember_me" style="float:left;cursor: pointer;margin-top: 2px;">&nbsp;<strong>Remember me</strong></label>
							</span>
							<span class="right" style="margin-top: 2px;"><a href="{base_url}forget"><strong>Forgot Password?</strong></a></span><span class="clear"></span>
							{login_status}
							<span class="right"><input type="submit" name="submit" value="Login"></span><span class="clear"></span>
						</form>
					{/not_logged}
					{logged}
						<div class="membership">
							<div class="content">
								<span style="float: left;">Welcome</span><span style="float: right;font-weight:bold;">{username}</span><span style="clear: both; display: block;"></span>
								<span style="float: left;">Account ID</span><span style="float: right;font-weight:bold;">{user_id}</span><span style="clear: both; display: block;"></span>
								<span style="float: left;">Vote Points (vp)</span><span style="float: right;font-weight:bold;">{user_vp}</span><span style="clear: both; display: block;"></span>
								<span style="float: left;">Donate Points (dp)</span><span style="float: right;font-weight:bold;">{user_dp}</span><span style="clear: both; display: block;"></span>
								<span style="float: left;">Posts</span><span style="float: right;font-weight:bold;">{user_posts}</span><span style="clear: both; display: block;"></span>
								<span style="float: left;">Current IP</span><span style="float: right;font-weight:bold;">{user_ip}</span><span style="clear: both; display: block;"></span>
								<hr style="margin:5px 0px 5px 0px;"></hr>
								<span style="float: left;">Nickname</span><span style="float: right;font-weight:bold;">{user_nickname} <a href="{base_url}profile/nickname"><img src="{base_url}content/img/edit.png"></a></span><span style="clear: both; display: block;"></span>
								<hr style="margin:5px 0px 0px 0px;"></hr>
								<span style="float: left;font-weight:bold;"><a href="{base_url}profile">Profile</a></span><span style="float: right;font-weight:bold;"><a href="{base_url}home/out">Logout</a></span><span style="clear: both; display: block;"></span>
							</div>
						</div>
					{/logged}
				</div>
				<div class="box_title">Realm Status</div>
				<div class="box_content">
					{realms_status}
					<div style="padding: 5px;font-weight: bold;margin: 5px 0px 5px 0px;background-color: rgba(15, 15, 15, 0.9);-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;font-family:Verdana,sans-serif;font-size:12px;line-height:17px;color:white;border: 1px solid #d5d5d5;text-align:center;">set realmlist {realmlist}</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	</center>
	<div class="clearfooter"></div>
</div>
<div class="push"></div>
<center>
	<div class="container">
		<div class="footer">
			<span class="left">&copy;2012 {core}'s CMS</span><span class="right">
			The page load for {elapsed_time}</span>
		</div>
	</div>
</center>
</body>
</html>
