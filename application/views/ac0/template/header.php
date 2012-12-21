<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>{site_title} - {title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="language" content="en" />
	<meta name="description" content="New World Of Warcraft Design" />
	<meta name="keyword" content="WoW, Ac0" />
	<meta name="author" content="Ac0" />
	{header_content}
	<link type="text/css" rel="stylesheet" href="{base_url}application/views/ac0/css/style.css" />
	<link type="text/css" rel="stylesheet" href="{base_url}application/views/ac0/css/skitter.css" />
	<script type="text/javascript" src="{base_url}application/views/ac0/js/menu.js"></script>
	<script type="text/javascript" src="{base_url}application/views/ac0/js/script.js"></script>
</head>
<body>
	<div class="wrapper" id="wrapper">
		<div id="menu">
			<center>
			<div class="container">
				<ul class="menu">
					<li><a href="{base_url}"><span>{lang_home}</span></a></li>
					{not_logged2}
						<li><a href="{base_url}index.php/register" class="parent"><span>{lang_account}</span></a>
							<div style="left: 0;"><ul>
								<li><a href="{base_url}index.php/register"><span>{lang_register_account}</span></a></li>
								<li><a href="{base_url}index.php/forget"><span>{lang_forgot_password}</span></a></li>
							</ul></div>
						</li>
					{/not_logged2}
					{logged2}
						<li><a href="{base_url}index.php/profile" class="parent"><span>{lang_profile}</span></a>
							<div style="left: 0;"><ul>
								<li><a href="{base_url}index.php/profile" class="parent"><span>{lang_characters}</span></a>
									<div style="left: 0;"><ul>
										{realm_characters}
											<li><a href="{link}" class="parent"><span>{name}</span></a>
												<div style="left: 0;"><ul>
													{characters}
														<li><a href="{char_link}"><span><img src="{base_url}content/img/icon/race/{char_race}-{char_gender}.gif" width="12" height="12" />&nbsp;&nbsp;{char_name}&nbsp;&nbsp;<img src="{base_url}content/img/icon/class/{char_class}.gif" width="12" height="12" /></span></a></li>
													{/characters}
												</ul></div>
											</li>
										{/realm_characters}
									</ul></div>
								</li>
								<li><a href="{base_url}index.php/profile" class="parent"><span>Change</span></a>
									<div style="left: 0;"><ul>
										<li><a href="{base_url}index.php/profile/change_password"><span>Password</span></a></li>
										<li><a href="{base_url}index.php/profile/change_settings"><span>Settings</span></a></li>
										<li><a href="{base_url}index.php/profile/change_expansion"><span>Expansion</span></a></li>
									</ul></div>
								</li>
								<li><a href="{base_url}index.php/profile/donate"><span>{lang_donate}</span></a></li>
								<li><a href="{base_url}index.php/profile/donate_shop"><span>{lang_donate_shop}</span></a></li>
								<li><a href="{base_url}index.php/profile/vote_shop"><span>{lang_vote_shop}</span></a></li>
								<li><a href="{base_url}index.php/home/out"><span>{lang_logout}</span></a></li>
							</ul></div>
						</li>
					{/logged2}
					<li><a href="#" class="parent"><span>{lang_status}</span></a>
						<div style="left: 0;"><ul>
							{realms}
								<li><a href="{link}"><span>{name}</span></a></li>
							{/realms}
						</ul></div>
					</li>
					<li><a href="{base_url}index.php/forum"><span>{lang_forum}</span></a></li>
					<li><a href="#"><span>{lang_tools}</span></a>
						<div style="left: 0;"><ul>
							{realms_tools}
								<li><a href="#" class="parent"><span>{name}</span></a>
									<div style="left: 0;"><ul>
											<li><a href="#" class="parent"><span>Top Arena Teams</span></a>
												<div style="left: 0;"><ul>
													<li><a href="{base_url}index.php/top_arenas/index/2/{id}"><span>2v2</span></a></li>
													<li><a href="{base_url}index.php/top_arenas/index/3/{id}"><span>3v3</span></a></li>
													<li><a href="{base_url}index.php/top_arenas/index/5/{id}"><span>5v5</span></a></li>
												</ul></div>
											</li>
											<li><a href="{top_link}"><span>Top Killers</span></a></li>
											<li><a href="{char_ban_link}"><span>Character Ban List</span></a></li>
									</ul></div>
								</li>
							{/realms_tools}
							<li><a href="#" class="parent"><span>Ban Lists</span></a>
								<div style="left: 0;"><ul>
									<li><a href="{base_url}index.php/bans/account"><span>Account</span></a></li>
									<li><a href="{base_url}index.php/bans/ip"><span>IP</span></a></li>
								</ul></div>
							</li>
						</ul></div>
					</li>
					<li class="last"><a href="{base_url}index.php/how"><span>{lang_how_to_connect}</span></a></li>
				</ul>
			</div>
			</center>
		</div>
		<center>
		<div class="container">
			<a href="{base_url}"><div class="logo">Cheer - World</div></a>
			{not_logged}
				<div class="membership">
					<form action="{base_url}index.php/login/validation_login" method="post" accept-charset="utf-8">
						<input type="hidden" name="return_link" value="{current_url}" />
						<input type="hidden" name="login_remember_me" value="1" /> 
						<input class="left login" type="text" name="login_username" id="login_username" placeholder="{lang_account}..." /> 
						<input class="left login" type="password" name="login_password" id="login_password" placeholder="{lang_password}..." />
						<input type="submit" class="login_btn" name="submit" value="Login"/>
					</form>
					<span class="clear"></span>
				</div>
			{/not_logged}
			{logged}
				<div class="membership" id="membership">
					<div class="content">
						<span style="float: left;">{lang_welcome}</span><span style="float: right;font-weight:bold;">{username} - <a href="{base_url}index.php/profile">{lang_profile}</a> - <a href="{base_url}index.php/home/out">{lang_logout}</a></span><span style="clear: both; display: block;"></span>
						<div class="addition" id="addition">
							<span style="float: left;">{lang_account} ID</span><span style="float: right;font-weight:bold;">{user_id}</span><span style="clear: both; display: block;"></span>
							<span style="float: left;">{lang_vote_points} (vp)</span><span style="float: right;font-weight:bold;">{user_vp}</span><span style="clear: both; display: block;"></span>
							<span style="float: left;">{lang_donate_points} (dp)</span><span style="float: right;font-weight:bold;">{user_dp}</span><span style="clear: both; display: block;"></span>
							<span style="float: left;">{lang_posts}</span><span style="float: right;font-weight:bold;">{user_posts}</span><span style="clear: both; display: block;"></span>
							<span style="float: left;">{lang_your_current} IP</span><span style="float: right;font-weight:bold;">{user_ip}</span><span style="clear: both; display: block;"></span>
							<hr style="margin:5px 0px 5px 0px;"></hr>
							<span style="float: left;">{lang_nickname}</span><span style="float: right;font-weight:bold;">{user_nickname} <a href="{base_url}index.php/profile/change_settings" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, 'Change Nickname');"><img src="{base_url}content/img/edit.png"></a></span><span style="clear: both; display: block;"></span>
						</div>
					</div>
					<span class="clear"></span>
				</div>
			{/logged}
			<div class="body">
				{full_content_false}<div class="left_body">{/full_content_false}
				{full_content_true}<div class="left_body_full">{/full_content_true}