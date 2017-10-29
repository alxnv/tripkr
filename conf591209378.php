<?
if (!isset($conf35)) exit;
require_once "tools/funct.php";
//require_once "tools/database3.php";

	//var_dump($_GET['sid']);

$sid='conf';

$row=$db3->qobj("select * from et_html where sid='$sid'");
if (!$row) exit;
$title='US Korea. '.$row->naim;
if($sid=='index') $title='US Korea';
?>
<? include "ssi-top.php"; ?>

<?
if ($sid<>'index') echo '<h1 class="title">'.my3::nbsh($row->naim).'</h1>';
echo mailaddrreplacer($row->html);
?>


<? include "ssi-bottom.php" ?>