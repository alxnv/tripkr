<?php
@session_start();
unset($_SESSION['ttfcnt']);
/*
if (!isset($_GET['pm'])) {
 header('HTTP/1.1 303 See Other');
 header('Date: Tue, 10 Jun 2003 11:41:54 GMT');
 header('Server: Apache/1.3.27 (Red Hat Linux)');
 header('Location: '.$_SESSION['PHP_SELF'].'?pm=1&r='.rand(1,100000000));
 exit;
 };

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
*/

header("Content-type: image/jpeg");
$s='';
for($i=0; $i < 4; $i++)
	{
	if ($i==0)
		{
		$s.=rand(1,9);
		}
	else
		{
		$s.=rand(0,9);
		};
	};
$_SESSION['ttf4l']=$s;
$im = imagecreate(70,27);
if (isset($_GET['m']))
	{
	if (intval($_GET['m'])==1)
		{
		$clr = imagecolorallocate($im, 201,189,163);
		}
	else
		{
		$clr = imagecolorallocate($im, 244,244,244);
		};
	@imagefill($im,0,0,$clr);
	};
	
$white = imagecolorallocate($im, 255,255,255); 
//$black = imagecolorallocate($im, 99,94,75);
$black = imagecolorallocate($im, 0,0,0);
for ($i=0;$i<4;$i++)
	{
	$a=rand(-10,10);
	imagettftext($im, 14, $a, 5+16*$i, 22, $black, "verdana.ttf",substr($s,$i,1));
	};
//imagecolortransparent ($im,$white);
imagejpeg($im);
imagedestroy($im);
?>