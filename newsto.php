<?
if (!isset($conf35)) die;
if (!isset($_GET['uid'])) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
require_once "funct1.php";

$uid2=intval($_GET['uid']);
$row2=$db3->qobj("select * from et_newsto where uid=$uid2");
$title='US Korea. Новость';
if (!testlogged()) {
	header('Location: '.my3::SITE);
	exit;
}

include "ssi-top.php";

echo '<h1 class="title"><a href="'.BS.'allnewsto/0">Новости</a> / '.my4::txtesc($row2->naim).'</h1>';
echo mailaddrreplacer(my3::repldomain($row2->html));


include "ssi-bottom.php";
?>