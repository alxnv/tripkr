<?
if (!isset($conf35)) exit;
require_once "tools/funct.php";
//require_once "tools/database3.php";

	//var_dump($_GET['sid']);
if (!isset($_GET['uid'])) {
	$sid='index';
} else {
	$sid=mysql_escape_string(substr($_GET['uid'],0,40));
	//var_dump($sid);
}

if ($sid=='conf') exit;

$row=$db3->qobj("select * from et_html where sid='$sid'");
if (!$row) exit;
$title='US Korea. '.$row->naim;
if($sid=='index') {
	$title='US Korea';
	$is_first_page=1; // признак первой страницы
	};
?>
<? include "ssi-top.php"; ?>

<?
//if ($sid<>'index') 
echo '<h1 class="title">'.my3::nbsh($row->naim).'</h1>';
echo mailaddrreplacer(my3::repldomain($row->html));
?>


<?
$show5=1;
include "ssi-bottom.php" ?>