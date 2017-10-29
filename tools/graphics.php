<?php
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
session_start();
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