<?
// если у дочерних страниц есть картинки, то эти страницы выводятся в виде таблицы
if (!isset($conf35)) die;
if (!isset($_GET['uid'])) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
require_once "funct1.php";
$uid2=intval($_GET['uid']);
$row2=$db3->qobj("select a.naim,b.html,a.idtree from et_tree a left join et_ta_html b
        on a.uid=b.uid where a.uid=$uid2");

if (!$row2) my3::gotomessage('Страница не найдена');

$t2=$row2->naim;
$title='US Korea. '.$t2;
$ar2=$db3->getkrohi('et_tree',$uid2);
$tourpriceintree=$db3->hasintreefrom(2,299,$ar2); // является ли страница подразделом раздела "цены и туры"
	// тогда проверим потом открыт ли доступ к этой странице

$hotel_info=($db3->toplid==292 && $db3->prcnt>=4); // это страница с информацией об одном отеле
$hotelsincity=($ar2['uid2']==292); // инфо о всех отелях города
$tourop_page_not_logged=false; // страница туроператоров, но не зашли под логином

// проверяем на конфиденциальность раздел туроператоры подраздел "цены и туры"

if ($row2->idtree==12) { // раздел туроператорам
	if ($tourpriceintree) {
		$valid5=0;
		if (testlogged()) {
			$row46=my3::qobj("select b.uid,b.naim,c.html from et_zayuser a, et_tree b, et_ta_html c, et_zayav d where a.user_id=$lgnuid3->uid and a.zay_id=$uid2 and a.user_id=d.uid and d.ismoderated=1 and b.idtree=12 and a.zay_id=b.uid and b.uid=c.uid order by b.ordr");
			if ($row46) $valid5=1;
		}
		if (!$valid5) {
			header('Location: '.my3::baseurl().'login');
			exit;
		}
	} else {
		$tlg4=testlogged();
		if (!$tlg4 && $uid2<>41) {
			header('Location: '.my3::baseurl().'login');
			exit;
		} else if (!$tlg4 && $uid2==41) {
			$tourop_page_not_logged=true;
		}
	}
}


$menu='';

if ((!$hotel_info && !$hotelsincity && $uid2<>299 && !$tourop_page_not_logged) && !($tourpriceintree || $uid2==299) ) {

	$arr=my3::qlist("select a.uid,a.naim,b.pict from et_tree a, et_ta_html b
				where a.topid=$uid2 and a.uid=b.uid order by a.ordr");
	//my3::log('q',$arr);
	if (count($arr)>0) {
		$menu.='<ul class="ul3" style="display:block">';
		for ($i=0;$i<count($arr);$i++) {
			if ($arr[$i]->pict=='') $menu.='<li><a class="menu3link" href="'.my3::baseurl().'page/'.$arr[$i]->uid.'">'.my3::nbsh($arr[$i]->naim).'</a></li>';
		}
		$menu.='</ul>';
	}
        $menu99=$menu;
}
	
include "ssi-top.php";

//echo '<h1>'.my3::nbsh($t2).'</h1>';
$ar22=$ar2; // массив крох
if ($hotel_info) {
	// убираем промежуточный тип отеля
	$ar22=$db3->kroh_delone($ar22,2);
	$db3->prcnt--;
	echo '<h1 class="title">'.$db3->printkrohi($ar22,my3::baseurl().'page/',1).'</h1>';
	$db3->prcnt++;
} else {
	echo '<h1 class="title">'.$db3->printkrohi($ar22,my3::baseurl().'page/',1).'</h1>';
}

if ($uid2==41) {
	// туроператоры
    //var_dump($_COOKIE);
	include "ssi-turopreglogin.php";
}

switch ($uid2) {
    case 64:
        $ttype='tours';
        include "tours.php";
        break;
    case 65:
        $ismed=1;
        $ttype='tours';
        include "tours.php";
        break;
    case 67:
        $ttype='agents';
        include "tours.php";
        break;
    case 68:
		if (!testlogged()) exit;
        $ttype='operators';
        include "tours.php";
        break;
    case 292:
        include "hotels.php";
        break;
    /*case 299:
        include "pricesandtours.php";
        break;*/
    default:

		if ($tourpriceintree || $uid2==299) {
			include "pricesandtours.php";
		} else if ($hotelsincity) {
			include "hotelsincity.php";
		} else if ($hotel_info) {
			echo mailaddrreplacer(my3::repldomain($row2->html));
		} else if ($tourop_page_not_logged) {
			echo 'Информация для туроператоров';
		} else {
			// не тур, а обычная страница        
			// выводим таблицу дочерних страниц с картинками
                        if ($uid2==142 && $my3->ismobile) { // Туристам / Где приобрести наши туры в Корею
                            // показываем меню которое обычно слева
                            echo $menu99.'<br />';
                        }
			echo mailaddrreplacer(my3::repldomain($row2->html));

			echo '<br /><br />';

			$menu='';
			if (count($arr)>0) {
				$menu.='<table class="kontent bw0">';
				for ($i=0;$i<count($arr);$i++) {
					if ($arr[$i]->uid==149) { 
						// !!! убираем дублирование инфы о tripkr.com
						continue;
						}
					if ($arr[$i]->pict<>'') {
						$menu.='<tr><td width="150">';
						$img_dir=my3::basepath().'img2/';
						$img_http=my3::baseurl().'img2/';
						$noimg=($arr[$i]->pict=='' || ($szs=@getimagesize($img_dir.$arr[$i]->pict))===false || $szs[0]==0);
						if ($noimg) {
							//echo '<font color=green>Нет изображения</font>';
						} else {
                                                    if ($my3->ismobile) {
                                                        $szs[0]=$szs[0]*my3::imagefactor;
                                                        $szs[1]=$szs[1]*my3::imagefactor;
                                                    }
                                                    $szs[3]=' width="'.$szs[0].'" height="'.$szs[1].'" ';
							$menu.='<a href="'.my3::baseurl().'page/'.$arr[$i]->uid.'"><img border="0" src="'.$img_http.$arr[$i]->pict.'" '.$szs[3].'></a>';
						}
						
						$menu.='</td><td width="450"><h3><a href="'.my3::baseurl().'page/'.$arr[$i]->uid.'">'.my3::nbsh($arr[$i]->naim).
							'</a></h3></td></tr>';
					}
				}
				$menu.='</table>';
			}
			if ($uid2==41) {
				echo '<a href="http://tripkr.com"><img style="float:left;margin-right:10px;margin-bottom:15px" border="0" width="150" height="126" src="/img/654.jpg" />
				</a>
				<h3><a href="http://tripkr.com">www.tripkr.com - Ваш профессиональный онлайн гид и менеджер по Корее!!!</a></h3>
				<div style="clear:both"></div>';
			};
			echo $menu.'<br />';

			break;
	}
}

//var_dump($ar2);

include "ssi-bottom.php";
?>