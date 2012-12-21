<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>{site_title} - {title}</title>
	{header_content}
	<link rel="stylesheet" href="{base_url}application/views/new/css/style.css" />
</head>
<body>
<div class="wrapper" id="wrapper">
    <center>
	<div class="container">
		<div class="menu">
			<a href="{base_url}"><li class="item">Home</li></a>
			{not_logged2}
			<a href="{base_url}register"><li class="item">Register</li></a>
			{/not_logged2}
			{logged2}
			<a href="{base_url}profile"><li class="item">Profile</li></a>	
			{/logged2}
			<a href="{base_url}forum"><li class="item">Forums</li></a>
			<a href="{base_url}how"><li class="item">Connection Guide</li></a>
			<a href="{base_url}status"><li class="item">Status</li></a>
			<a href="{base_url}profile/donate"><li class="item last">Donate</li></a>
		</div>
		<div class="logo">
			<span>{site_title}</span>
		</div>
		<div class="body">
			<div class="left_body">
				<div class="slider">
					<div style="text-align: center;padding: 40px 0px 0px 0px;font-size: 30px;">Slider 600x250</div>
				</div>