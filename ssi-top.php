<?
require_once "tools/funct.php";
// переключение https://gokoreatour.ru/qa/ismobile/1
// https://gokoreatour.ru/qa/ismobile/0

$choosemobile=(isset($_GET['uid']) && isset($_GET['uid2']) && $_GET['uid']=='ismobile');
if ($choosemobile) {
    if (intval($_GET['uid2'])) {
    $_SESSION['scrwidth']=500;
    } else {
    $_SESSION['scrwidth']=1280;
    }
}

$_SESSION['scrwidth']=500; //!!!here


if (!isset($_SESSION['scrwidth'])) {
    if (isset($_COOKIE['scrwidth'])) {
        $_SESSION['scrwidth']=intval($_COOKIE['scrwidth']);
    } else {
        // передаем переменные с размерами
        echo "<script language='javascript'>\n"
        . "var date = new Date(new Date().getTime() + 3600 * 1000);
document.cookie = 'scrwidth='+screen.width+'; path=/; expires=' + date.toUTCString();\n";
        echo "  location.href=location.href\n";
        echo "</script>\n";
        exit();
    }
}


if (!isset($_SESSION['notfirstpagethissession'])) {
	$_SESSION['notfirstpagethissession']=1;
	$notfirstpagethissession=0;
} else {
	$notfirstpagethissession=1;
}

$my3->ismobile=(intval($_SESSION['scrwidth'])<768); // просмотр с мобильного устройства
$my3->onecolumn=$my3->ismobile; // выводим все на экран в одну колонку
//var_dump($my3->ismobile);
/*if (rand(0,99)==0) {
    include "techworks.php"; // работы по оптимизации базы данных
}*/
//echo "bs=".BS;
//exit;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=my3::nbsh($title)?></title>
<meta name="description" content="US Korea - сайт о Корее для туристов и профессионалов турбизнеса. Корея, туры" />
<meta name="keywords" content="Korea, Корея, US Korea, US Travel, туры" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="icon" type="image/png" href="<?=BS?>images/favicon.png" />
<link rel="stylesheet" type="text/css" href="<?=BS?>style.css" />
<link rel="stylesheet" type="text/css" href="<?=BS?>css/contact.css" />
<? if (APPLICATION_ENV=='production') { ?>
<?/*<script type="text/javascript" src="http://vk.com/js/api/share.js?11" charset="windows-1251"></script>*/?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30860547-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<? } ?>
<script src="<?=BS?>js/jquery-1.7.1.min.js"></script>
<? echo '<script type="text/javascript" src="'.BS.'js/popupjq.js"></script>'; ?>
<script type="text/javascript">
$(document).ready(function(){
	function runIt() {
    $(".blinking")
              .animate({"opacity": 0.1},1000)
              .animate({"opacity": 1},1000,runIt);
    }
    runIt();
});
BSJS='<?=BS?>'; // путь url до начальной папки проекта
notfpthissess=<?=$notfirstpagethissession?>;
</script>
</head>

<body>
<div id="app">
  <div id="appContainer">
    <div id="header">
        <div id="container4">
            <div id="logo">
            <a href="<?=BS?>"><img src="<?=BS?>img/top_logo.png"
                    width="253" height="43" align="left" id="logo" alt="" /></a>
               <div class="nadp">Ведущий корейский туроператор:<br />
               офисы в Корее, США и России
               </div>
               <div style="margin: 0 0 0 55px">
               <img src="<?=BS?>images/since.gif" width="127" height="47" />
               </div>
            </div>
            <div id="container3">
                <div id="valuta">
                    <div id="valuta2">
                        <? if (APPLICATION_ENV=='production')  { 
                            include "valuta_cb.php";
                        } else { ?> Доллар - 58.16 руб, Евро - 67.71 руб, 1000 Вон - 52.19 руб
                        <? } ?>
                    </div>
                </div>
                <div id="menu2">
                    <center>
                        <table class="menu3">
                            <tr class="mtr1">
                                <td class="mtd11" style="background:url(<?=BS?>img/menu/top-left_01.gif)"></td>
                                <td class="mtd12" style="background:url(<?=BS?>img/menu/top-center_01.gif)"></td>
                                <td class="mtd13" style="background:url(<?=BS?>img/menu/top-right_01.gif)"></td>
                            </tr>
                            <tr>
                                <td class="mtd21" style="background:url(<?=BS?>img/menu/top-left_02.gif)"></td>
                                <td class="mtd22" align="center">
                        <a href='<?=BS?>index/about'
                         class='pages_top'>&middot;&nbsp;О&nbsp;нас&nbsp;&middot; &nbsp;</a>
                        <a href='<?=BS?>allnews/0' 
                           class='pages_top'>&middot;&nbsp;Новости&nbsp;&middot; &nbsp;</a>
                        <a href='<?=BS?>index/contacts' 
                             class='pages_top'>&middot;&nbsp;Контакты&nbsp;&middot; &nbsp;</a>
                        <a href='<?=BS?>qa' class='pages_top'>&middot;&nbsp;Вопрос/ответ&nbsp;&middot; &nbsp;</a>
                        <a href='<?=BS?>page/285' class='pages_top'>&middot;&nbsp;Отзывы&nbsp;и&nbsp;благодарности&nbsp;&middot; &nbsp;</a>
                        <a href='<?=BS?>video' class='pages_top2'>&middot;&nbsp;Видео&nbsp;о&nbsp;Корее&nbsp;&middot; &nbsp;</a>
                        <a href='<?=BS?>index/info' class='pages_top'>&middot;&nbsp;Полезные&nbsp;ссылки&nbsp;&middot; &nbsp;</a>

                                </td>
                                <td class="mtd23" style="background:url(<?=BS?>img/menu/top-right_02.gif)"></td>
                            </tr>
                            <tr class="mtr3">
                                <td class="mtd31" style="background:url(<?=BS?>img/menu/top-left_03.gif)"></td>
                                <td class="mtd32" style="background:url(<?=BS?>img/menu/top-center_03.gif)"></td>
                                <td class="mtd33" style="background:url(<?=BS?>img/menu/top-right_03.gif)"></td>
                            </tr>
                        </table>
                        </center>
                </div>
            </div>
            <div id="weather">
                <? if (APPLICATION_ENV=='production') { ?><a target="_blank" href="http://clck.yandex.ru/redir/dtype=stred/pid=7/cid=1228/*http://pogoda.yandex.ru/seoul"><img
                        src="https://info.weather.yandex.net/seoul/2_white.ru.png" border="0" alt=""/><img width="1" height="1" src="https://clck.yandex.ru/click/dtype=stred/pid=7/cid=1227/*http://img.yandex.ru/i/pix.gif" alt="" border="0"/></a>
                <? } else {
                    ?><img src="<?=BS?>images/dummy_weather.png" width="200" height="100" border=0" />
                <? } ?>            
            </div>
        </div>
        <div id="astrip">
            <div id="fon1" style="background:url(<?=BS?>img/menu/top_menu_fon_01.gif)"></div>
            <div id="fon2">
                <a href='<?=BS?>page/7' class='pages'>&middot;&nbsp;Туристам&nbsp;&middot; &nbsp;</a><a 
                        href='<?=BS?>page/41' class='pages'>&middot;&nbsp;ТурОператорам&nbsp;&middot; &nbsp;</a>
                <a href='<?=BS?>page/292' class='pages'>&middot;&nbsp;Гостиницы&nbsp;Кореи&nbsp;&middot; &nbsp;</a>		
            </div>
            <div id="fon3" style="background:url(<?=BS?>img/menu/top_menu_fon_03.gif)"></div>
            
        </div>
        <div id="container5">
            <div id="clocks">
                <div style="width:100%;position:relative"><? if (true || APPLICATION_ENV=='production') include "ssi-clocks.php";?></div>                
            </div>
            <div id="logo2">
                <div style="margin:0 auto;width:601px"><img src="<?=BS?>img/tripkr-zag.jpg" alt="" id="img_cat" width="601" height="125" /></div>                
            </div>
            <div id=social">
                <? if (true || APPLICATION_ENV=='production') include "ssi-social.php"; ?>                
            </div>
        </div>
    </div>
    <div id="contentContainer">

	<div id="content2">
            <div id="leftbl">
                <div id="menu" style="margin-top:10px"> <?//здесь сдвиг от верхнего желтого банера?>
                <?
                if (isset($menu)) {
                    echo $menu;
                }
                ?>
                </div>
                <? if (true || APPLICATION_ENV=='production') include "ssi-banners-left-tripkrcom.php" ?>	
                
            </div>
		<div id="content">
    <img src="<?=BS?>img/dog.gif" width="800" height=800" />
                    
                    

<? /*<body>
<table class="bgwhite" id="osnova" width="100%">
<tr class="bw0" id="tr_verh">
<td valign="top" width="260">
</td>

<td align="center" valign="bottom">

    

<table class="bgwhite" cellpadding="0" cellspacing="0" style="height:80px">
<tr class="bw0">
<td id="top_tabl1" width="29">&nbsp;</td><td id="top_tabl2" width="650"><center>
<a href='<?=BS?>index/about'
 class='pages_top'>&middot;&nbsp;О&nbsp;нас&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>allnews/0' 
   class='pages_top'>&middot;&nbsp;Новости&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>index/contacts' 
     class='pages_top'>&middot;&nbsp;Контакты&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>qa' class='pages_top'>&middot;&nbsp;Вопрос/ответ&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>page/285' class='pages_top'>&middot;&nbsp;Отзывы&nbsp;и&nbsp;благодарности&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>video' class='pages_top2'>&middot;&nbsp;Видео&nbsp;о&nbsp;Корее&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>index/info' class='pages_top'>&middot;&nbsp;Полезные&nbsp;ссылки&nbsp;&middot; &nbsp;</a>
		</center>

</td><td id="top_tabl3" width="29">&nbsp;</td></tr></table>
</td>
<td id="valcell" style="height:110px" align="right" width="260">
<? if (APPLICATION_ENV=='production') { ?><a target="_blank" href="http://clck.yandex.ru/redir/dtype=stred/pid=7/cid=1228/*http://pogoda.yandex.ru/seoul"><img
        src="https://info.weather.yandex.net/seoul/2_white.ru.png" border="0" alt=""/><img width="1" height="1" src="https://clck.yandex.ru/click/dtype=stred/pid=7/cid=1227/*http://img.yandex.ru/i/pix.gif" alt="" border="0"/></a>
<? } ?></td>

</tr>
<tr class="bw0 pr1">
<td colspan="3" id="top_menu" valign="middle" align="center"> 
<a href='<?=BS?>page/7' class='pages'>&middot;&nbsp;Туристам&nbsp;&middot; &nbsp;</a><a 
        href='<?=BS?>page/41' class='pages'>&middot;&nbsp;ТурОператорам&nbsp;&middot; &nbsp;</a>
<a href='<?=BS?>page/292' class='pages'>&middot;&nbsp;Гостиницы&nbsp;Кореи&nbsp;&middot; &nbsp;</a>		
		</td>
</tr>*/?>

<?/*<tr class="bw0 pr1">
<td><div style="width:100%;position:relative"><? if (APPLICATION_ENV=='production') include "ssi-clocks.php";?></div></td>
<td><div style="margin:0 auto;width:601px"><img src="<?=BS?>img/tripkr-zag.jpg" alt="" id="img_cat" width="601" height="125" /></div></td>
<td valign="top" align="right"><? if (true || APPLICATION_ENV=='production') include "ssi-social.php"; ?></td>
</tr>

<tr>
<? if (!$my3->onecolumn) { ?><td width="200" valign="top">
    <?/*<img src="<?=BS?>img/ex.gif" width="240">
    <br />
<div id="menu" style="margin-top:10px"> <?//здесь сдвиг от верхнего желтого банера?>
<?
if (isset($menu)) {
    echo $menu;
}
?>

      
	       
    </div>
<? if (true || APPLICATION_ENV=='production') include "ssi-banners-left-tripkrcom.php" ?>	
</td><? } //(!$my3->onecolumn) ?>
    <td valign="top" <?=($my3->onecolumn ? ' colspan="3" ' : '')?> class="content">
<div class="content2">*/ ?>