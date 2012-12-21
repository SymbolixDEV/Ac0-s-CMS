<?php
	include('./application/config/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>404 Page Not Found</title>
	<style>
		@font-face {
			font-family:morpheus;
			src:url('<?php echo  $config['base_url']; ?>content/fonts/MORPHEUS.TTF');	
		}
		body {
			letter-spacing:1px;
			color: #FFFFFF;
			font-family:morpheus;
			background: #000000 url("<?php echo  $config['base_url']; ?>content/img/background.gif") no-repeat top;
		}
		.container {
			width: 980px;
		}
		.error{
			margin-right: 20px;
			margin-top: 110px;
			float: right;
			width: 400px;
			height: 200px;
		}
		button, input {
			font-family: inherit;
			font-size: inherit;
		}
		button::-moz-focus-inner,
		input::-moz-focus-inner {
			border: 0;
		}
		.cool {
			display: inline-block;
			min-width: 46px;
			text-align: center;
			color: #444;
			font-size: 11px;
			font-weight: bold;
			height: 27px;
			padding: 0 8px;
			line-height: 27px;
			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			border-radius: 2px;
			-webkit-transition: all 0.218s;
			-moz-transition: all 0.218s;
			-ms-transition: all 0.218s;
			-o-transition: all 0.218s;
			transition: all 0.218s;
			border: 1px solid #dcdcdc;
			background-color: #f5f5f5;
			background-image: -webkit-gradient(linear,left top,left bottom,from(#f5f5f5),to(#f1f1f1));
			background-image: -webkit-linear-gradient(top,#f5f5f5,#f1f1f1);
			background-image: -moz-linear-gradient(top,#f5f5f5,#f1f1f1);
			background-image: -ms-linear-gradient(top,#f5f5f5,#f1f1f1);
			background-image: -o-linear-gradient(top,#f5f5f5,#f1f1f1);
			background-image: linear-gradient(top,#f5f5f5,#f1f1f1);
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
			cursor: default;
		}
		.cool:active {
			border: 1px solid blue;
		}
		*+html .cool {
		min-width: 70px;
		}
		button.cool,
		input[type=submit].cool {
			height: 29px;
			line-height: 29px;
			vertical-align: bottom;
			margin: 0;
		}
		*+html button.cool,
		*+html input[type=submit].cool {
			overflow: visible;
		}
		.cool:hover {
			border: 1px solid #c6c6c6;
			color: #333;
			text-decoration: none;
			-webkit-transition: all 0.0s;
			-moz-transition: all 0.0s;
			-ms-transition: all 0.0s;
			-o-transition: all 0.0s;
			transition: all 0.0s;
			background-color: #f8f8f8;
			background-image: -webkit-gradient(linear,left top,left bottom,from(#f8f8f8),to(#f1f1f1));
			background-image: -webkit-linear-gradient(top,#f8f8f8,#f1f1f1);
			background-image: -moz-linear-gradient(top,#f8f8f8,#f1f1f1);
			background-image: -ms-linear-gradient(top,#f8f8f8,#f1f1f1);
			background-image: -o-linear-gradient(top,#f8f8f8,#f1f1f1);
			background-image: linear-gradient(top,#f8f8f8,#f1f1f1);
			-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
			-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
			box-shadow: 0 1px 1px rgba(0,0,0,0.1);
		}
		.cool:active {
			background-color: #f6f6f6;
			background-image: -webkit-gradient(linear,left top,left bottom,from(#f6f6f6),to(#f1f1f1));
			background-image: -webkit-linear-gradient(top,#f6f6f6,#f1f1f1);
			background-image: -moz-linear-gradient(top,#f6f6f6,#f1f1f1);
			background-image: -ms-linear-gradient(top,#f6f6f6,#f1f1f1);
			background-image: -o-linear-gradient(top,#f6f6f6,#f1f1f1);
			background-image: linear-gradient(top,#f6f6f6,#f1f1f1);
			-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
			-moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
			box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
		}
		.cool:visited {
			color: #666;
		}
		.cool-submit {
			border: 1px solid #3079ed;
			color: #fff;
			text-shadow: 0 1px rgba(0,0,0,0.1);
			background-color: #4d90fe;
			background-image: -webkit-gradient(linear,left top,left bottom,from(#4d90fe),to(#4787ed));
			background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
			background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
			background-image: -ms-linear-gradient(top,#4d90fe,#4787ed);
			background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
			background-image: linear-gradient(top,#4d90fe,#4787ed);
		}
		
		.cool-submit:hover {
			border: 1px solid #2f5bb7;
			color: #fff;
			text-shadow: 0 1px rgba(0,0,0,0.3);
			background-color: #357ae8;
			background-image: -webkit-gradient(linear,left top,left bottom,from(#4d90fe),to(#357ae8));
			background-image: -webkit-linear-gradient(top,#4d90fe,#357ae8);
			background-image: -moz-linear-gradient(top,#4d90fe,#357ae8);
			background-image: -ms-linear-gradient(top,#4d90fe,#357ae8);
			background-image: -o-linear-gradient(top,#4d90fe,#357ae8);
			background-image: linear-gradient(top,#4d90fe,#357ae8);
		}
		
		.cool-submit:active {
			-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
			-moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
			box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
		}
		
		.cool-submit:focus {
			-webkit-box-shadow: inset 0 0 0 1px #fff;
			-moz-box-shadow: inset 0 0 0 1px #fff;
			box-shadow: inset 0 0 0 1px #fff;
		}
		.cool-submit:focus:hover {
			-webkit-box-shadow: inset 0 0 0 1px #fff, 0 1px 1px rgba(0,0,0,0.1);
			-moz-box-shadow: inset 0 0 0 1px #fff, 0 1px 1px rgba(0,0,0,0.1);
			box-shadow: inset 0 0 0 1px #fff, 0 1px 1px rgba(0,0,0,0.1);
		}
		.cool-submit:focus:hover {
			-webkit-box-shadow: inset 0 0 0 1px #fff, 0 1px 1px rgba(0,0,0,0.1);
			-moz-box-shadow: inset 0 0 0 1px #fff, 0 1px 1px rgba(0,0,0,0.1);
			box-shadow: inset 0 0 0 1px #fff, 0 1px 1px rgba(0,0,0,0.1);
		}
	</style>
	<script>
		 <!--
		netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
		-->
		function return_back()
		{
			var ex = history.length;
			if(ex<2)
				window.location="<?php echo  $config['base_url']; ?>";
			else
				history.back();
		}
	</script>
</head>
<body>
	<center><div class="container">
		<div class="error">
			<h2><?php echo $heading; ?></h2>
			<h3><?php echo $message; ?></h3>
			<center>
				<script> document.write('<input type="button" class="cool cool-submit" value="<<< Back " onClick="return_back();"/>');</script>
				<noscript><a href="<?php echo  $config['base_url']; ?>"><input type="button" class="cool cool-submit" value="<<< Back " /></a></noscript>
			</center>
		</div>
	</div></center>
</body>
</html>