<?php
DEFINE('BASEPATH', '');
include_once('application/config/config.php');

$icon = (isset($_GET['icon'])) ? $_GET['icon'] : null;

if(empty($icon) || !preg_match("/^[0-9a-zA-Z_]+$/", $icon))
	die();

$icon = strtolower($icon);

header ("Content-type: image/png");
$img = imagecreatefrompng($config['base_url'].'content/img/slots/back.png');
imagealphablending($img, true);
imagesavealpha($img, true);
if ($icon) 
{
	$dest = imagecreatetruecolor(45, 45);
	if (@file_exists($config['base_url'].'content/img/icon/items/large/'.$icon.'.jpg')) 
	{
		$icon_create = @imagecreatefromjpeg($config['base_url'].'content/img/icon/items/large/'.$icon.'.jpg');
	} 
	else 
	{
		$icon_create = @imagecreatefromjpeg("http://static.wowhead.com/images/wow/icons/large/$icon.jpg");
		@imagejpeg($icon_create, $config['base_url'].'content/img/icon/items/large/'.$icon.'.jpg', 100);
	}
	if ($icon) 
	{ 
		imagecopyresized($dest, $icon_create, 0, 0, 0, 0, 45, 45, 56, 56);
		imagecopy($img, $dest, 6, 6, 0, 0, 45, 45);
	}
}

imagepng($img);
imagedestroy($img);
imagedestroy($dest);
imagedestroy($icon);
?>