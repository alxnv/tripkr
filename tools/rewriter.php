<?php
// скрипт - глобальный обработчик запросов к сайту
// !!!todo - поставить register_shutdown_function
// определение реального адреса скрипта и вызов его с переданными параметрами
//  сюда происходит переход только если не найден такой файл на диске, либо это индексный файл сайта,
//   а также если имя файла не начинается с a7
//  contacts - ищется contacts.php,htm,html
//  news/2 - вызывается news.php?uid=2
//  cat/page/2 - вызывается cat.php?uid=page&uid2=2
//   (ищется только если это корневая директория сайта)
// после вывода данных скриптов, в них заменяется title,keywords на информацию из базы данных, если она есть
// также если установлено $_SESSION['pageview483'], то добавляется в начало страницы див со ссылками
//  на редактирование title, keywords этой страницы
// страницы сайта предположительно д.б. в utf-8
// определяется константа BS - локальный урл каталога сайта
session_start();
//echo getcwd();
require_once dirname(__FILE__).'/my3.php';
$my3=new my3();

$sstr=substr($sr,strlen($spath)); // url с параметрами относительно домашнего каталога без начального слэша и без якоря
//var_dump($sstr,$sr,$spath);
$sstr=str_replace('..', '', $sstr);
define('BS',$spath);

//echo $spath.'% '.$sstr;
// проверяем, есть ли в БД такой url
$conf35=parse_ini_file(APPLICATION_PATH.'/configs/application.ini',true);
$conf35['relurl']=$sstr;
//var_dump($conf35);
include_once 'tools/database3.php';
$db3->connect();
$sth=$db3->q("select * from ".$conf35['production']['dbprefix']."rewrite where url='".$db3->escape($sstr)."'");
if (mysql_num_rows($sth)>0) $conf35['rewr']=mysql_fetch_object($sth);

$ar=parse_url($sstr); //path,query
if (!isset($ar['path'])) $ar['path']='';
//var_dump($sstr,$conf35['rewr']);

function isfl3($s) {
    if (is_file($s.'.php')) return 'php';
    if (is_file($s.'.htm')) return 'htm';
    if (is_file($s.'.html')) return 'html';
return '';
}


//$k=strpos($ar['path'],'/');
$k=strpos($ar['path'],'/');
if ($k!==false) {
    $sname=substr($ar['path'],0,$k);
    $sparm=substr($ar['path'],$k+1);
    //if (strpos($sname,'/'))
} else {
    $sname=$ar['path'];
    $sparm='';
}
//die('s '.$sname.' j '.$sparm);

if ($sname=='') $sname='index';

// временный переход для flash рекламы airtel2015 слева в банерах на сайте
if (strpos($sname.$sparm,'undefined')!==false) {
	header('Location: '.BS.'advert');
	exit;
}
// end временный переход

$bisfile=(($sfl22=isfl3('./'.$sname.'/'.$sparm))<>''); // если есть файл в подкаталоге, то переходим на  него
if ($bisfile) {
include './'.$sname.'/'.$sparm.'.'.$sfl22;
exit; // !!!  выход
}


if (($sfl=isfl3('./'.$sname))=='') {
 //header("HTTP/1.0 404 Not Found");
    echo '<br /><br /><br /><br /><br /><br /><p align="center">Страница не найдена.</p>';
 exit;
}

//var_dump($ar['query']);
@parse_str($ar['query'],$_GET); //todo commented - what it does?
//if (is_null($_GET)) $_GET=array();
if ($sparm<>'') {
    $ar858=explode('/',$sparm);
    for ($i=0;$i<count($ar858);$i++) {
        if ($i==0) $s='uid';
            else $s='uid'.($i+1);
        $_GET[$s]=$ar858[$i];    
    }
    //$_GET['uid']=$sparm;
}

unset($sr,$k,$spath,$sstr,$ar,$sparm,$row,$s,$ar858,$sfl22,$bisfile);
ob_start();
include './'.$sname.'.'.$sfl;
$cache = ob_get_contents();
ob_end_clean ();

if (isset($conf35['rewr'])) {
    // заменяем в тексте информацию
    $row2=$conf35['rewr'];
    // если нет такой записи в БД, то берем пустые значения
    //print_r($row2);
    if ($row2->title<>'') {
        if (preg_match('/<title>([^<]*)<\/title>/i', $cache,$mtch,PREG_OFFSET_CAPTURE)) {
            //var_dump($mtch);
            $cache=substr($cache,0,$mtch[1][1]).htmlspecialchars($row2->title).substr($cache,$mtch[1][1]+strlen($mtch[1][0]));
        }

    }
    //echo 'descr '.$row2->description;
    if ($row2->description<>'') {
        if (preg_match('/<meta\s+name\=\"description\"\s+content=\"([^\"]*)\"/i', $cache,$mtch,PREG_OFFSET_CAPTURE)) {
            //var_dump($mtch);
            $cache=substr($cache,0,$mtch[1][1]).htmlspecialchars($row2->description).substr($cache,$mtch[1][1]+strlen($mtch[1][0]));
        }

    }
    if ($row2->keywords<>'') {
        if (preg_match('/<meta\s+name\=\"keywords\"\s+content=\"([^\"]*)\"/i', $cache,$mtch,PREG_OFFSET_CAPTURE)) {
            //var_dump($mtch);
            $cache=substr($cache,0,$mtch[1][1]).htmlspecialchars($row2->keywords).substr($cache,$mtch[1][1]+strlen($mtch[1][0]));
        }

    }
} else {
    $row2=(object)array('title'=>'','keywords'=>'','description'=>'');
}
//-
// добавляем яваскрипт редактирования title,keywords если установлено $_SESSION['viewsite583']
if ((isset($_SESSION['login']) && ($_SESSION['login']=='admin5')) && isset($_SESSION['viewsite583'])) {
    ob_start();
    my3::log('cngf',$row2);
	echo '
	<script type="text/javascript">
    function sh3810() {
        document.getElementById("jkq92").style.display="block";
    }
</script>
<div id="jkq92" style="display:none;position:absolute;top:150px;left:0;width:600px;background-color:#d5f2f9;border:3px solid blue;padding:10px;margin:10px 0 0 10px;z-index:250">
    <p align="center">Редактирование параметров страницы</p>
    <form action="'.my3::baseurl().'tools/helpers/savepageinfo" method="post">
        <input type="hidden" name="url" value="'.urlencode($conf35['relurl']).'" />
    <table border="0">
        <tr>
            <td>
                Заголовок (title):
            </td>
            <td>
                <textarea name="title" cols="50" rows="3">'.htmlspecialchars($row2->title).'</textarea>
            </td>
        </tr>
        <tr>
            <td>
                Description:
            </td>
            <td>
                <textarea name="descr" cols="50" rows="3">'.htmlspecialchars($row2->description).'</textarea>
            </td>
        </tr>
        <tr>
            <td>
                Ключевые слова (keywords):
            </td>
            <td>
                <textarea name="keywords" cols="50" rows="3">'.htmlspecialchars($row2->keywords).'</textarea>
            </td>
        </tr>
    </table>
        <p>* Для восстановленя значений по умолчанию оставьте строки пустыми</p>
       <p align="center"><input type="submit" value="Сохранить" /></p>
    </form>
</div>
<div style="position:absolute;z-index:250;width:400px;background-color:#d5f2f9;text-align:center;margin: 10px 0 0 10px;padding:10px;border:3px solid blue">
    <a href="javascript:sh3810()">Редактировать параметры страницы</a><br /><br />
    <a href="'.my3::baseurl().'a7-main">Выход</a>
</div>';
    $js2 = ob_get_contents();
    ob_end_clean ();

    if (preg_match('/<body[^>]*(\>)/i', $cache,$mtch,PREG_OFFSET_CAPTURE)) {
             // здесь не совсем чистое определение тэга body, можно усовершенствовать
        //var_dump($mtch);exit;
        $cache=substr($cache,0,$mtch[1][1]+1).$js2.substr($cache,$mtch[1][1]+1);
        
    };

};

echo $cache;

?>