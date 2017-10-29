<?
// если у дочерних страниц есть картинки, то эти страницы выводятся в виде таблицы
if (!isset($conf35)) die;
//if (!isset($_GET['uid'])) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
require_once "funct1.php";
/*$uid2=intval($_GET['uid']);
$row2=$db3->qobj("select a.naim,b.html,a.idtree from et_tree a left join et_ta_html b
        on a.uid=b.uid where a.uid=$uid2");
if (!$row2) my3::gotomessage('Страница не найдена');
*/

$title='US Korea. Видео о Корее';
$menu='';
	
include "ssi-top.php";
echo '<h1 class="title">Видео о Корее</h1>';

	$arr=my3::qlist("select a.uid,a.naim,b.pict from et_tree a, et_ta_html b
				where a.idtree=14 and a.topid=0 and a.uid=b.uid order by a.ordr");

			$menu='';
			if (count($arr)>0) {
				$menu.='<table class="kontent bw0">';
				for ($i=0;$i<count($arr);$i++) {
					if ($arr[$i]->pict<>'') {
						$menu.='<tr><td width="150">';
						$img_dir=my3::basepath().'img2/';
						$img_http=my3::baseurl().'img2/';
						$noimg=($arr[$i]->pict=='' || ($szs=@getimagesize($img_dir.$arr[$i]->pict))===false || $szs[0]==0);
						if ($noimg) {
							//echo '<font color=green>Нет изображения</font>';
						} else {
							$menu.='<a href="'.my3::baseurl().'video2/'.$arr[$i]->uid.'"><img border="0" src="'.$img_http.$arr[$i]->pict.'" '.$szs[3].'></a>';
						}
						
						$menu.='</td><td width="450"><h3><a href="'.my3::baseurl().'video2/'.$arr[$i]->uid.'">'.my3::nbsh($arr[$i]->naim).
							'</a></h3></td></tr>';
					}
				}
				$menu.='</table>';
			}
			echo $menu.'<br />';


//var_dump($ar2);

include "ssi-bottom.php";
?>