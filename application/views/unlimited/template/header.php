<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link type="text/css" rel="stylesheet" href="{base_url}application/views/unlimited/css/style.css" />
<script type="text/javascript" src="{base_url}application/views/unlimited/js/general.js"> </script>
<script type="text/javascript" src="{base_url}application/views/unlimited/js/swfobject/swfobject.js"></script>
<script type="text/javascript">
		var flashvars = {};
		flashvars.xml = "{base_url}application/views/unlimited/config.xml";
		flashvars.font = "{base_url}application/views/unlimited/font.swf";
		var attributes = {};
		attributes.wmode = "transparent";
		attributes.id = "slider";
		swfobject.embedSWF("{base_url}application/views/unlimited/cu3er.swf", "cu3er-container", "600", "300", "9", "{base_url}application/views/unlimited/expressInstall.swf", flashvars, attributes);
</script>
<title>{site_title} - {title}</title>
{header_content}
</head>


<body>
<div align="center">
 <div class="nothing" style=" position:relative; height:304px;"></div>
 <div class="header">
  <div class="menu" align="center">
   <a href="{base_url}">Home</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <a href="{base_url}forum">Forums</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <a href="{base_url}how">How To Connecto</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <a href="{base_url}status">Status</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <a href="{base_url}profile/donate">Donate</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
  <table cellpadding="0" cellspacing="0">
   <tr>
    <td valign="top">
	 <div class="slider">
	  <div id="cu3er-container">
       <a href="http://www.adobe.com/go/getflashplayer">
        <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
       </a>
      </div>
	 </div>
	</td>
	<td valign="top" align="left">
   <div class="login">
    <div class="membership"></div>
	{not_logged}<form action="{base_url}login/validation_login" method="post" accept-charset="utf-8">
	<input type="hidden" name="return_link" value="{current_url}" />
	 <input  class="logininput"  name="login_username" type="text" value="Username" onfocus='if (this.value == "Username") this.value = "";' onblur='if (!this.value) this.value = "Username";'/>  <br/>
	 <input class="logininput" name="login_password" type="password"  value="Password" onfocus='if (this.value == "Password") this.value = "";' onblur='if (!this.value) this.value = "Password";'/> <br />
	  <table cellpadding="0" cellspacing="0">
       <tr>
	    <td valign="top" class="login_b_links">
	    <input type="submit" class="login_btn" value="Login" /></td>
	    <td valign="top" class="login_b_links">
	     <a href="{base_url}forget" class="login_b_links">Password Recovery</a><br/>
	     <a href="{base_url}register" class="login_b_links">Create New Free Account</a>
	    </td>
	   </tr>
	  </table>
	  </div>
	 </form>
	 {/not_logged}
	 {logged}
		<font class="logget_box_text"> Welocome back, <font color="#333333">{username}</font></font>
	   <br/>
	  <div class="logget_box_text_latest_login">Vote Points(vp): {user_vp}<br />Donate Points(dp): {user_dp}<br />Nickname: {user_nickname} <a href="{base_url}profile/nickname"><img src="{base_url}content/img/edit.png" height="10px" width="10px"></a></div>
	  <div class="logget_box_links">
	  <br/>
	  <a href="{base_url}profile" class="logget_box_links">Profile</a>
	  <br/>
	  <a href="{base_url}home/out" class="logget_box_links">Logout</a>
	  </div>
	 {/logged}
	</div>
   <div class="bonus_buttons">
   <a href="{base_url}register"><img class="bonus_b_style" style="cursor:pointer; display:inline;" height="40" width="148" src="{base_url}application/views/unlimited/img/register_b_off.png" id="register" onClick= "document.newthreadform.submit();" onMouseOver="MM_swapImage('register','','{base_url}application/views/unlimited/img/register_b_on.png',0)" onMouseOut="MM_swapImgRestore()"></a>
   <a href="{base_url}profile/donate"><img class="bonus_b_style" style="cursor:pointer; display:inline;" height="40" width="148" src="{base_url}application/views/unlimited/img/donate_b_off.png" id="donate" onClick= "document.newthreadform.submit();" onMouseOver="MM_swapImage('donate','','{base_url}application/views/unlimited/img/donate_b_on.png',0)" onMouseOut="MM_swapImgRestore()"></a>
  </div>
  <div class="connection_guide" align="right">
   <a href="{base_url}how"><img style="cursor:pointer; display:inline;" height="53" width="17" src="{base_url}application/views/unlimited/img/read_more_b_off.png" id="readmore" onClick= "document.newthreadform.submit();" onMouseOver="MM_swapImage('readmore','','{base_url}application/views/unlimited/img/read_more_b_on.png',0)" onMouseOut="MM_swapImgRestore()"></a>
  </div>
	</td>
   </tr>
  </table>
 </div>
<div class="body" align="center">
 <table cellpadding="0" cellspacing="0">
<tr>
 <td valign="top">