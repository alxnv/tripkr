<?
if (!isset($conf35)) die;
include_once "tools/my4.php";
require_once "funct1.php";
$spp=10;
$title='US Korea. Новости для туроператоров';
if (!testlogged()) {
	header('Location: '.my3::SITE);
	exit;
}
//$row2=$db3->qobj("select * from mg_html where sid='".$db3->escape($uid)."'");
include "ssi-top.php";

echo '<h1 class="title">Новости для туроператоров</h1>';
//echo $row2->html;

if (isset($_GET['uid'])) $_SESSION['page482']=intval($_GET['uid']);
 else $_SESSION['page482']=0;
$page=$_SESSION['page482'];
$sth = $db3->q("SELECT count(*) as cnt from et_newsto");
$row=mysql_fetch_object($sth);
$nrows=$row->cnt;
$page++;
do {
 $page--;
 $lm=$page*$spp;
 $sth=$db3->q("select *,date_format(data1,'%d.%m.%Y') as df from et_newsto order by data1 desc,uid desc limit $lm,$spp");
 $nrowspage=mysql_num_rows($sth);
 } while (($nrowspage==0) && ($page>0));
$_SESSION['page482']=$page;

$arr=array();
//getmonth35($arr);
while ($row=mysql_fetch_object($sth)) {
    echo '<p class="newsdate">'.$row->df.'
                    </p><p class="newsnaim">
                    <a href="'.BS.'newsto/'.$row->uid.'"><strong>'.my4::txtesc($row->naim).'</strong></p>
                    <p class="newsanons">'.nl2br(my4::txtesc($row->anons)).'</a>
                    </p>';
 };
?>
<p align="left">
<?php
$nrs=max(0,ceil($nrows/$spp)-1);
if ($nrowspage<$nrows) {
 echo 'Страницы: ';
 for($i=0;$i<=$nrs;$i++) {
  if (($i!=0) && (floor($i/20)==$i/20)) {
   echo '<br>';
   };
  if ($i==$page) {
   echo '<b> ['.($i+1).'] </b>';
   } else {
   echo '<a href="'.BS.'allnewsto/'.$i.'">['.($i+1).']</a> ';
   };
  };
 };
?>
</p>
<?

include "ssi-bottom.php";
?>