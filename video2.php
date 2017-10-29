<?
// если у дочерних страниц есть картинки, то эти страницы выводятся в виде таблицы
if (!isset($conf35)) die;
if (!isset($_GET['uid'])) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
require_once "funct1.php";
$uid2=intval($_GET['uid']);
$row2=$db3->qobj("select a.naim,b.html,b.html2,a.idtree from et_tree a left join et_ta_html b
        on a.uid=b.uid where a.uid=$uid2 and a.idtree=14");
if (!$row2) my3::gotomessage('Страница не найдена');

$title='US Korea. Видео о Корее. '.my3::nbsh($row2->naim);
$menu='';
	
include "ssi-top.php";
echo '<h1 class="title"><a href="'.BS.'video">Видео о Корее</a> / '.my3::nbsh($row2->naim).'</h1>';
echo '<br /><br /><center>'.$row2->html2;
echo '</center><br /><br />'.$row2->html;

include "ssi-bottom.php";
?>