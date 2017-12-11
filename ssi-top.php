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
//$_SESSION['scrwidth']=1200; //!!!here


$s10=$_SERVER["HTTP_USER_AGENT"];
if ((strpos($s10,'+http://www.google.com/bot.html')!==false ||
        strpos($s10,'+http://yandex.com/bots')!==false)) {
    // google bot или yandex bot
    $_SESSION['scrwidth']=1280; 
} else {
    if (!isset($_SESSION['scrwidth'])) {
        if (isset($_COOKIE['scrwidth'])) {
            $_SESSION['scrwidth']=intval($_COOKIE['scrwidth']);
        } else {
            // передаем переменные с размерами
            echo "<script language='javascript'>\n"
            . "var date = new Date(new Date().getTime() + 3600 * 10000);
    document.cookie = 'scrwidth='+screen.width+'; path=/; expires=' + date.toUTCString();\n";
            echo "  location.href=location.href\n";
            echo "</script>\n";
            exit();
        }
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
<meta name="viewport" content="width=device-width, initial-scale=0.37" />
<link rel="icon" type="image/png" href="<?=BS?>images/favicon.png" />
<link rel="stylesheet" type="text/css" href="<?=BS?>style.css" />
<link rel="stylesheet" type="text/css" href="<?=BS?>css/contact.css" />
<script type="text/javascript" src="https://vk.com/js/api/share.js?11" charset="windows-1251"></script>
<? if (APPLICATION_ENV=='production') { ?>
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
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Organization",
  "name" : "US Korea",
  "url" : "https://www.gokoreatour.ru",
  "sameAs" : [
    "https://vk.com/club10332326",
    "https://www.facebook.com/USKorea"
  ]
}
</script>
<? } ?>
<script type="text/javascript">
BSJS='<?=BS?>'; // путь url до начальной папки проекта
notfpthissess=<?=$notfirstpagethissession?>;
</script>
</head>
<?
$p=($my3->ismobile ? 'pagesm' : 'pages_top');
// меню
$s8='                        <a href="'.BS.'index/about"
                         class="'.$p.'">&middot;&nbsp;О&nbsp;нас&nbsp;&middot; &nbsp;</a>
                        <a href="'.BS.'allnews/0" 
                           class="'.$p.'">&middot;&nbsp;Новости&nbsp;&middot; &nbsp;</a>
                        <a href="'.BS.'index/contacts" 
                             class="'.$p.'">&middot;&nbsp;Контакты&nbsp;&middot; &nbsp;</a>
                        <a href="'.BS.'qa" class="'.$p.'">&middot;&nbsp;Вопрос/ответ&nbsp;&middot; &nbsp;</a>
                        <a href="'.BS.'page/285" class="'.$p.'">&middot;&nbsp;Отзывы&nbsp;и&nbsp;благодарности&nbsp;&middot; &nbsp;</a>
                        <a href="'.BS.'video" class="pages_top2">&middot;&nbsp;Видео&nbsp;о&nbsp;Корее&nbsp;&middot; &nbsp;</a>
                        <a href="'.BS.'index/info" class="'.$p.'">&middot;&nbsp;Полезные&nbsp;ссылки&nbsp;&middot; &nbsp;</a>
';
if ($my3->ismobile) {
    $s8='<div  id="fon2">'.$s8.' 
                    <a href="'.BS.'page/7" class="pagesm">&middot;&nbsp;Туристам&nbsp;&middot; &nbsp;</a><a 
                            href="'.BS.'page/41" class="pagesm">&middot;&nbsp;ТурОператорам&nbsp;&middot; &nbsp;</a>
                    <a href="'.BS.'page/292" class="pagesm">&middot;&nbsp;Гостиницы&nbsp;Кореи&nbsp;&middot; &nbsp;</a>		
            </div>';
} else {
    
    $s8=                    '<center>
                        <table class="menu3">
                            <tr class="mtr1">
                                <td class="mtd11" style="background:url('.BS.'img/menu/top-left_01.gif)"></td>
                                <td class="mtd12" style="background:url('.BS.'img/menu/top-center_01.gif)"></td>
                                <td class="mtd13" style="background:url('.BS.'img/menu/top-right_01.gif)"></td>
                            </tr>
                            <tr>
                                <td class="mtd21" style="background:url('.BS.'img/menu/top-left_02.gif)"></td>
                                <td class="mtd22" align="center">'.
            $s8.

                                '</td>
                                <td class="mtd23" style="background:url('.BS.'img/menu/top-right_02.gif)"></td>
                            </tr>
                            <tr class="mtr3">
                                <td class="mtd31" style="background:url('.BS.'img/menu/top-left_03.gif)"></td>
                                <td class="mtd32" style="background:url('.BS.'img/menu/top-center_03.gif)"></td>
                                <td class="mtd33" style="background:url('.BS.'img/menu/top-right_03.gif)"></td>
                            </tr>
                        </table>
                        </center>';
};

?>

<body>
<div class="wrapper">
    <div id="header">
            <? if ($my3->ismobile) { ?>
        <div id="container7">
            <div style="margin:0 auto;text-align: center">
                <a href="<?=BS?>"><img src="<?=BS?>img/tripkr-zag.jpg" alt="" width="875" height="230" /></a>
            <? // 1300 342  ,1803 475 ?>
            </div>
            <div id="menu2">
                <? echo $s8; ?>
            </div>
                
            <? /*<div id="astrip">
                <div id="fon1" style="background:url(<?=BS?>img/menu/top_menu_fon_01.gif)"></div>
                <div id="fon2">
                    <a href='<?=BS?>page/7' class='pages'>&middot;&nbsp;Туристам&nbsp;&middot; &nbsp;</a><a 
                            href='<?=BS?>page/41' class='pages'>&middot;&nbsp;ТурОператорам&nbsp;&middot; &nbsp;</a>
                    <a href='<?=BS?>page/292' class='pages'>&middot;&nbsp;Гостиницы&nbsp;Кореи&nbsp;&middot; &nbsp;</a>		
                </div>
                <div id="fon3" style="background:url(<?=BS?>img/menu/top_menu_fon_03.gif)"></div>

            </div>*/ ?>
        </div>

    <!-- остаток -->
    </div>
                <div class="content"> 
            <? } else { ?>
            
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
                    <? echo $s8; ?>
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

    <!-- остаток -->
    </div>
    <div class="middle">

        <div class="container">
                <div class="content"> 
            <? }; // $my3>ismobile ?>
	<!--<div id="content2">-->
<?/* jkjkjk   <img src="<?=BS?>img/dog.gif" width="800" height=800" />*/?>
                    
                    

