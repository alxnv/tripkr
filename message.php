<?
if(!isset($_GET['uid'])) exit;
error_reporting (E_ALL);

if (!isset($conf35)) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
$title='US Korea. Сообщение';

?>
<? include "ssi-top.php"; ?>


		<p align="center"><?=nl2br(substr(my3::nbsh(urldecode($_GET['uid'])),0,500))?></p>

<? include "ssi-bottom.php" ?>