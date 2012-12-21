<html>
<head>
<title>{site_title} - {title}</title>
<link rel="stylesheet" href="{base_url}application/views/default/css/style.css">
<meta name="google-site-verification" content="o9iCjrmkpmv7L96SZM37Gm4D-ZyQHJYLdGbDjho5sMo" />
<meta property="og:title" content="Cheers WoW" />
<meta property="og:type" content="game" />
<meta property="og:url" content="http://www.facebook.com/CheerWow" />
<meta property="og:image" content="http://cheer-world.info/content/img/cheer-logo.jpg" />
<meta property="og:site_name" content="Cheers WoW" />
<meta property="fb:admins" content="100000491017605,100001181900339" />
{header_content}
</head>

<body>
<div id="top_bar">
<a href="{base_url}"><img src="{img_url}logo.png" id="logo"></a>
</div>

<div id="bg">
<div id="container">

<div id="content_fix"></div>


<div id="left_content">

<div id="menu">
<div id="menu_header"></div>
<a href="{base_url}"><div id="menu_btn"><div id="menu_btn_txt">Home</div></div></a>
{not_logged2}
<a href="{base_url}register"><div id="menu_btn"><div id="menu_btn_txt">Register</div></div></a>
{/not_logged2}
{logged2}
<a href="{base_url}profile"><div id="menu_btn"><div id="menu_btn_txt">Profile</div></div></a>	
{/logged2}
<a href="{base_url}forum"><div id="menu_btn"><div id="menu_btn_txt">Forums</div></div></a>
<a href="{base_url}how"><div id="menu_btn"><div id="menu_btn_txt">Connection Guide</div></div></a>
<a href="{base_url}status"><div id="menu_btn"><div id="menu_btn_txt">Status</div></div></a>
<a href="{base_url}profile/donate"><div id="menu_btn"><div id="menu_btn_txt">Donate</div></div></a>
<div id="menu_footer"></div>
</div>

<div id="login_box">
<div id="login_header"></div>
{not_logged}
<form action="{base_url}login/validation_login" method="post" accept-charset="utf-8">
<input type="hidden" name="return_link" value="{current_url}" />
<div id="login_subheader">
<div id="login_subheader_txt"><label for="login_username" style="cursor: pointer;"><strong>Account Name</strong></label></div>
</div>
<div id="login_input">
<input type="text" name="login_username" value="Username" id="login_username" class="login_input" onfocus='if (this.value == "Username") this.value = "";' onblur='if (!this.value){ this.value = "Username"; }' />
</div>
<div id="login_subheader">
<div id="login_subheader_txt"><label for="login_password" style="cursor: pointer;"><strong>Password</strong></label></div>
</div>
<div id="login_input">
<input type="password" name="login_password" value="Password" id="login_password" class="login_input" onfocus='if (this.value == "Password") this.value = "";' onblur='if (!this.value){ this.value = "Password"; }' />
</div>
<div class="membership">
    <div class="content">
        <div style="float:left;margin-left: 18px;"><input type="checkbox" name="login_remember_me" value="1" id="login_remember_me" style="cursor: pointer;float:left;"  /> <label for="login_remember_me" style="cursor: pointer;">&nbsp;<strong>Remember me</strong></label></div><div style="float:right;margin-right:10px;"><a href="{base_url}forget"><strong>Forgot Password?</strong></a></div><div class="clear"></div>
        {login_status}
    </div>
</div>
<input type="image" id="login_btn" src="{img_url}login_btn.png"></form><form action="{base_url}register" method="post" accept-charset="utf-8"><input type="image" id="register_btn" src="{img_url}register_btn.png"></form>
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
    </div></div>
{/logged}
</div>

<div id="srv_status">
<div id="srv_status_header"></div>
{realms_status}
</div>

<div style="margin-left: 4px;">
	<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FCheerWow&amp;width=252&amp;height=427&amp;colorscheme=dark&amp;show_faces=false&amp;border_color&amp;stream=true&amp;header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:252px; height:427px;" allowTransparency="true"></iframe>
</div>
<div style="clear: both;display: block;height:40px;"></div>
</div>


<div id="right_content">