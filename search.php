<?php
//if ($_SERVER['SERVER_NAME']<>'localhost') {
//    echo 'Сайт находится в разработке';
//    exit;
//}
if (!isset($conf35)) die;
$title='US Korea. Поиск';

include "ssi-top.php";

//echo '<h1>'.my3::nbsh($t2).'</h1>';
echo '<h1 class="title">Поиск</h1>';

/*
 * SELECT a.uid,a.fpnum,b.naim AS title,2 AS searchnum, a.html,a.html2,a.html3
 0 + ((LENGTH(a.html) - LENGTH(REPLACE(a.html, "коре", ""))) / LENGTH("коре")) AS rel,
  0 + ((LENGTH(a.html2) - LENGTH(REPLACE(a.html2, "коре", ""))) / LENGTH("коре")) AS rel2,
   0 + ((LENGTH(a.html3) - LENGTH(REPLACE(a.html3, "коре", ""))) / LENGTH("коре")) AS rel3 
   FROM et_ta_html a, et_tree b WHERE b.idtree=2 AND a.uid=b.uid
    AND (FALSE OR a.html LIKE "коре" OR FALSE OR a.html2 LIKE "коре" OR FALSE 
    OR a.html3 LIKE "коре")
 */

/*

Реализация поиска по статье http://www.exlab.net/dev/noindex-search.html

Скрипт модифицирован

*/


/*
Предварительная обработка строки

Сперва инициализируем переменные со строкой запроса, номером текущей
страницы и количеством записей, отображаемым на одной странице.
Последние две переменные нужны для организации постраничной выдачи
результатов с соответствующей навигацией.
*/

$request = !empty($_GET['search']) ? $_GET['search'] : false;
$p = !empty($_GET['page']) ? (int)$_GET['page'] : 0;
$spp = 10;

if (isset($_GET['page'])) $_SESSION['page483']=intval($_GET['page']);
 else $_SESSION['page483']=0;
$page=$_SESSION['page483'];

/*
Сократим поисковый запрос до 64 символов (такой длины вполне достаточно) и 
приведем его к нижнему регистру. Для этого воспользуемся функциями из упомянутого 
выше проекта PHP UTF-8, архив с которым мы распаковали в директорию utf8. Если ваш 
рабочий сервер поддерживает mbstring, можете воспользоваться аналогичными функциями оттуда.
*/

require_once 'utf8/utf8.php';
require_once 'utf8/utils/unicode.php';
require_once 'utf8/utils/specials.php';
$q = utf8_substr($request, 0, 70);
$sname35=$q;
$q = utf8_strtolower($q);


/*
Для корректной работы стеммера необходимо заменить буквы «ё» на «е».
Далее удаляем из строки все HTML сущности и специальные символы,
а идущие подряд пробелы заменяем единичными экземплярами.
Таким образом, получаем подготовленную строку из слов, разделенных пробелами.
*/

$q = strtr($q, array('ё' => 'e'));
$q = preg_replace('/&([a-zA-Z0-9]+);/', ' ', $q);
$q = utf8_strip_specials($q, ' ');
$q = trim(preg_replace('/ +/', ' ', $q));




/*
Морфологический анализатор
*/

define('CHAR_LENGTH', 2);
function stem($word){
   $a = rv($word);
   return $a[0].step4(step3(step2(step1($a[1]))));
}

function rv($word){
   $vowels = array('а','е','и','о','у','ы','э','ю','я');
   $flag = 0;
   $rv = $start='';
   for ($i=0; $i<strlen($word); $i+=CHAR_LENGTH){
      if ($flag == 1) $rv .= substr($word, $i, CHAR_LENGTH); else $start .= substr($word, $i, CHAR_LENGTH);
      if (array_search(substr($word,$i,CHAR_LENGTH), $vowels) !== FALSE) $flag = 1;
   }
   return array($start,$rv);
}

function step1($word){
   $perfective1 = array('в', 'вши', 'вшись');
   foreach ($perfective1 as $suffix)
     if (substr($word, -(strlen($suffix))) == $suffix && (substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'а' || substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'я'))
         return substr($word, 0, strlen($word)-strlen($suffix));
   $perfective2 = array('ив','ивши','ившись','ывши','ывшись');
   foreach ($perfective2 as $suffix)
      if (substr($word, -(strlen($suffix))) == $suffix)
         return substr($word, 0, strlen($word) - strlen($suffix));
   $reflexive = array('ся', 'сь');
   foreach ($reflexive as $suffix)
      if (substr($word, -(strlen($suffix))) == $suffix)
         $word = substr($word, 0, strlen($word) - strlen($suffix));
   $adjective = array('ее','ие','ые','ое','ими','ыми','ей','ий','ый','ой','ем','им','ым','ом','его','ого','ему','ому','их','ых','ую','юю','ая','яя','ою','ею');
   $participle2 = array('ем','нн','вш','ющ','щ');
   $participle1 = array('ивш','ывш','ующ');
   foreach ($adjective as $suffix) if (substr($word, -(strlen($suffix))) == $suffix){
      $word = substr($word, 0, strlen($word) - strlen($suffix));
      foreach ($participle1 as $suffix)
         if (substr($word, -(strlen($suffix))) == $suffix && (substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'а' || substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'я'))
            $word = substr($word, 0, strlen($word) - strlen($suffix));
      foreach ($participle2 as $suffix)
         if (substr($word, -(strlen($suffix))) == $suffix)
            $word = substr($word, 0, strlen($word) - strlen($suffix));
      return $word;
   }
   $verb1 = array('ла','на','ете','йте','ли','й','л','ем','н','ло','но','ет','ют','ны','ть','ешь','нно');
   foreach ($verb1 as $suffix)
      if (substr($word, -(strlen($suffix))) == $suffix && (substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'а' || substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'я'))
         return substr($word, 0, strlen($word) - strlen($suffix));
   $verb2 = array('ила','ыла','ена','ейте','уйте','ите','или','ыли','ей','уй','ил','ыл','им','ым','ен','ило','ыло','ено','ят','ует','уют','ит','ыт','ены','ить','ыть','ишь','ую','ю');
   foreach ($verb2 as $suffix)
      if (substr($word, -(strlen($suffix))) == $suffix)
         return substr($word, 0, strlen($word) - strlen($suffix));
   $noun = array('а','ев','ов','ие','ье','е','иями','ями','ами','еи','ии','и','ией','ей','ой','ий','й','иям','ям','ием','ем','ам','ом','о','у','ах','иях','ях','ы','ь','ию','ью','ю','ия','ья','я');
   foreach ($noun as $suffix)
      if (substr($word, -(strlen($suffix))) == $suffix)
         return substr($word, 0, strlen($word) - strlen($suffix));
   return $word;
}

function step2($word){
   return substr($word, -CHAR_LENGTH, CHAR_LENGTH) == 'и' ? substr($word, 0, strlen($word) - CHAR_LENGTH) : $word;
}

function step3($word){
   $vowels = array('а','е','и','о','у','ы','э','ю','я');
   $flag = 0;
   $r1 = $r2 = '';
   for ($i=0; $i<strlen($word); $i+=CHAR_LENGTH){
      if ($flag==2) $r1 .= substr($word, $i, CHAR_LENGTH);
if (array_search(substr($word, $i, CHAR_LENGTH), $vowels) !== FALSE) $flag = 1;
      if ($flag = 1 && array_search(substr($word, $i, CHAR_LENGTH), $vowels) === FALSE) $flag = 2;
   }
   $flag = 0;
   for ($i=0; $i<strlen($r1); $i+=CHAR_LENGTH){
      if ($flag == 2) $r2 .= substr($r1, $i, CHAR_LENGTH);
if (array_search(substr($r1, $i, CHAR_LENGTH), $vowels) !== FALSE) $flag = 1;
if ($flag = 1 && array_search(substr($r1, $i, CHAR_LENGTH), $vowels) === FALSE) $flag = 2;
}
   $derivational = array('ост', 'ость');
   foreach ($derivational as $suffix)
      if (substr($r2, -(strlen($suffix))) == $suffix)
         $word = substr($word, 0, strlen($r2) - strlen($suffix));
   return $word;
}

function step4($word){
   if (substr($word, -CHAR_LENGTH * 2) == 'нн') $word = substr($word, 0, strlen($word) - CHAR_LENGTH);
   else {
      $superlative = array('ейш', 'ейше');
      foreach ($superlative as $suffix)
         if (substr($word, -(strlen($suffix))) == $suffix)
            $word = substr($word, 0, strlen($word) - strlen($suffix));
      if (substr($word, -CHAR_LENGTH * 2) == 'нн') $word = substr($word, 0, strlen($word) - CHAR_LENGTH);
   }
   if (substr($word, -CHAR_LENGTH, CHAR_LENGTH) == 'ь') $word = substr($word, 0, strlen($word) - CHAR_LENGTH);
   return $word;
}




/*
Список стоп слов
*/

$stopWords = array(
   'что', 'как', 'все', 'она', 'так', 'его', 'только', 'мне', 'было', 'вот',
   'меня', 'еще', 'нет', 'ему', 'теперь', 'когда', 'даже', 'вдруг', 'если',
   'уже', 'или', 'быть', 'был', 'него', 'вас', 'нибудь', 'опять', 'вам', 'ведь',
   'там', 'потом', 'себя', 'может', 'они', 'тут', 'где', 'есть', 'надо', 'ней',
   'для', 'тебя', 'чем', 'была', 'сам', 'чтоб', 'без', 'будто', 'чего', 'раз',
   'тоже', 'себе', 'под', 'будет', 'тогда', 'кто', 'этот', 'того', 'потому',
   'этого', 'какой', 'ним', 'этом', 'один', 'почти', 'мой', 'тем', 'чтобы',
   'нее', 'были', 'куда', 'зачем', 'всех', 'можно', 'при', 'два', 'другой',
   'хоть', 'после', 'над', 'больше', 'тот', 'через', 'эти', 'нас', 'про', 'них',
   'какая', 'много', 'разве', 'три', 'эту', 'моя', 'свою', 'этой', 'перед',
   'чуть', 'том', 'такой', 'более', 'всю'
);



/*
if(!isset($_GET['metod']))$_GET['metod'] = 'full'; // Если не задан метод поиска, то по умолчанию ищется "любое из слов"


if($_GET['metod'] == 'dif'){

	$where = '';
	$relevance = '0';
	$count = 0;
	$words = explode(' ', $q);
	foreach($words as $w){
	   if (utf8_strlen($w) < 3 || in_array($w, $stopWords)) continue;
	   if ($count++ > 4) break;
	   $w = stem($w);
	   $where .= ' OR `content` LIKE "%'.$w.'%"';
	   $relevance .= ' + ((LENGTH(`content`) -
	      LENGTH(REPLACE(`content`, "'.$w.'", ""))) / LENGTH("'.$w.'"))';
	}
	$where .= ' OR ';
	
	foreach($words as $w){
	   if (utf8_strlen($w) < 3 || in_array($w, $stopWords)) continue;
	   if ($count++ > 4) break;
	   $w = stem($w);
	   $where .= '`title` LIKE "%'.$w.'%" OR ';	   
	}	
	
	$relevance .= ' as `relevance`';
	$where = substr($where, 4);
	$where = substr($where, 0,-4);
	
	
}
else {
*/

/*
Построение SQL-запроса
*/

$where = 'false';
$relevance = '0';
$count = 0;
$words = explode(' ', $q);
$newwords=array();
foreach($words as $w){
   if (utf8_strlen($w) < 3 || in_array($w, $stopWords)) continue;
   if ($count++ > 4) break;
   $w = stem($w);
   array_push($newwords,$w);
   $where .= ' OR #sword# LIKE "%'.$w.'%"';
   $relevance .= ' + ((LENGTH(#sword#) -
      LENGTH(REPLACE(LOWER(#sword#), "'.$w.'", ""))) / LENGTH("'.$w.'"))'; 
    //#sword# потом заменяем на имя поля 
}
//$relevance .= ' as relevance';


/* ищем по всем  нужным таблицам */
$arsc=array(); //сюда записываем все найденные значения

function hasitnotags($html,$words) {
    $h=utf8_strtolower(strip_tags($html));
    $b=0;
    for ($i=0;$i<count($words);$i++) {
        if (utf8_strpos($h,$words[$i])!==FALSE) {
            $b=1;
            break;
        }
    }
    return $b;
}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! сам поиск
// 
// дополнительное меню
$where1=str_replace('#sword#','a.html',$where);
$rel1=str_replace('#sword#','a.html',$relevance);
$where2=str_replace('#sword#','a.html2',$where);
$where3=str_replace('#sword#','a.html3',$where);
$where4=str_replace('#sword#','concat(a.html,a.html2)',$where);
$rel2=str_replace('#sword#','a.html2',$relevance);
$rel3=str_replace('#sword#','a.html3',$relevance);

$s="select a.uid,a.fpnum,b.naim as title,a.html,1 as searchnum,$rel1 as rel
	from et_ta_html a, et_tree b where b.idtree=1 and a.uid=b.uid and ($where1)";
$sth=$db3->q($s);
while ($row=mysql_fetch_object($sth)) {
	$row->link='page/'.$row->uid;
        if (hasitnotags($row->html,$newwords)) {
            $row2=(object)array('uid'=>$row->uid, 'searchnum'=>$row->searchnum,'title'=>$row->title,
                'fpnum'=>$row->fpnum, 'rel'=>$row->rel,'link'=>$row->link);
            array_push($arsc,$row2);
        }
}

// вопрос-ответ
$s="select a.uid,a.fpnum,b.naim as title,concat(a.html,a.html2)  as html,5 as searchnum,$rel1 as rel
	from et_ta_html a, et_tree b where b.idtree=4 and a.uid=b.uid and ($where4)";
$sth=$db3->q($s);
while ($row=mysql_fetch_object($sth)) {
	$row->link='qa#n'.$row->uid;
        if (hasitnotags($row->html,$newwords)) {
            $row2=(object)array('uid'=>$row->uid, 'searchnum'=>$row->searchnum,'title'=>'Вопрос-ответ: '.$row->title,
                'fpnum'=>$row->fpnum, 'rel'=>$row->rel,'link'=>$row->link);
            array_push($arsc,$row2);
        }
}

// одиночные хтмл-страницы
$s="select a.sid,a.html,a.naim as title,$rel1 as rel
	from et_html a where a.sid in ('index','about','contacts','info') and ($where1)";
$sth=$db3->q($s);
while ($row=mysql_fetch_object($sth)) {
	$row->link='index/'.$row->sid;
        if (hasitnotags($row->html,$newwords)) {
            $row2=(object)array('sid'=>$row->sid, 'searchnum'=>3,'title'=>$row->title,
                'fpnum'=>0, 'rel'=>$row->rel,'link'=>$row->link);
            array_push($arsc,$row2);
        }
}

// новости
$s="select a.uid,a.html,a.naim as title,$rel1 as rel
	from et_news a where ($where1)";
$sth=$db3->q($s);
while ($row=mysql_fetch_object($sth)) {
	$row->link='news/'.$row->uid;
        if (hasitnotags($row->html,$newwords)) {
            $row2=(object)array('uid'=>$row->uid, 'searchnum'=>4,'title'=>'Новость: '.$row->title,
                'fpnum'=>0, 'rel'=>$row->rel,'link'=>$row->link);
            array_push($arsc,$row2);
        }
}


//туры
$s="select a.uid,a.fpnum,b.naim as title,2 as searchnum,
        a.html,a.html2,a.html3,
        ($rel1) as rel, ($rel2) as rel2, ($rel3) as rel3
	from et_ta_html a, et_tree b where b.idtree=2 and a.uid=b.uid and 
        ($where1 or $where2)";
$sth=$db3->q($s);
while ($row=mysql_fetch_object($sth)) {
	$row->link='tour/'.$row->uid;
        if (hasitnotags($row->html,$newwords)) {
            $row2=(object)array('uid'=>$row->uid, 'searchnum'=>$row->searchnum,'title'=>'Туры: '.$row->title,
                'fpnum'=>$row->fpnum, 'rel'=>$row->rel,'link'=>$row->link.'/tours');
            array_push($arsc,$row2);
        }
        if (hasitnotags($row->html2,$newwords)) {
            $row2=(object)array('uid'=>$row->uid, 'searchnum'=>$row->searchnum,'title'=>'Туры, информация для ТурАгенств: '.$row->title,
                'fpnum'=>$row->fpnum, 'rel'=>$row->rel2,'link'=>$row->link.'/agents');
            array_push($arsc,$row2);
        }
        /*if (hasitnotags($row->html3,$newwords)) {
            $row2=(object)array('uid'=>$row->uid, 'searchnum'=>$row->searchnum,'title'=>'Туры, информация для Туроператоров: '.$row->title,
                'fpnum'=>$row->fpnum, 'rel'=>$row->rel3,'link'=>$row->link.'/operators');
            array_push($arsc,$row2);
        }*/
}



// сортируем все по убыванию relevance
function cmp49($a,$b) {
	$a1=$a->rel;
	$b1=$b->rel;
    if ($a1 == $b1) {
        return 0;
    }
    return ($a1 < $b1) ? 1 : -1;
	
}

usort($arsc,'cmp49');

/*}	*/





/*
Поскольку мы намереваемся получать результаты постранично,
нам понадобятся два SQL-запроса. Из первого мы узнаем общее количество искомых страниц,
а с помощью второго выберем необходимые для отображения (в количестве, указанном в $rpp),
отсортировав их по убыванию релевантности.
*/
/*
if($where == ')')$where = 'false';
//die($where);
$tq = mysql_query('SELECT count(*) AS `n` FROM `search` WHERE '.$where.' LIMIT 1');
$tr = mysql_fetch_array($tq);
$total = $tr[0];

$rows = mysql_query('SELECT
      `title` as `title`,      
      `url` as `url`,
	  `category` as `category`,
      LOWER(`content`) as `content`,
      '.$relevance.'
   FROM `search` WHERE '.$where.' ORDER BY `relevance` DESC
   LIMIT '.($p * $rpp).', '.$rpp);

   
   
  */ 
/*
Вывод
*/   
/*
echo '<form action="">';

if($_GET['metod'] == 'full'){
	echo '<div style="text-align:left; width:380px; font-size:11px; color:#808040;"><input
	type="radio" name="metod" value="full" checked>&nbsp;Все выражение целиком&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input type="radio" name="metod" value="dif">&nbsp;Любое из слов<br></div>';
}
else {
	echo '<div style="text-align:left; width:380px; font-size:11px; color:#808040;"><input
	type="radio" name="metod" value="full">&nbsp;Все выражение целиком&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input type="radio" name="metod" value="dif" checked>&nbsp;Любое из слов<br></div>';
}





echo '<input style="width:300px;" type="search" name="search" value="'.stripslashes($request).'" />';
echo '<input type="submit" value="Искать" />';
echo '</form>';
echo '<h2>'.($total ? 'Найдено документов: '.$total : 'Ничего не найдено').'</h2>';
echo '<section>';
while($r = mysql_fetch_array($rows)){
   echo '<div style="text-align:left; margin-bottom:20px;">';
   echo '<div><a href="'.$r['url'].'"><b>'.stripslashes($r['title']).'</b></a></div>';   
   echo '<div style="font-size:11px; color:#3a9457;"><b>url:</b> '.$r['url'].'</div>'; 
   echo '<div style="font-size:11px; color:#698161;"><b>Раздел:</b> '.$r['category'].'</div>';   
   echo '</div>';
}
echo '</section>';






/*
И наконец, добавим ссылки для перемещения между страницами результатов:
*/
// выводим результат

$total=count($arsc);
$nrows=$total;
if ($page*$spp>=$total) {
    $nrowspage=0;
    $page=0;
} else {
    $nrowspage=$spp;
    if ($total-$page*$spp<=$spp) {
        // последняя страница
        $nrowspage=$total-$page*$spp;
    }
}
$arsc=array_slice($arsc,$page*$spp,$nrowspage);

echo '<strong>'.($total ? 'Найдено документов: '.$total : 'Ничего не найдено').'</strong><br /><br />';
for ($i=0;$i<count($arsc);$i++) {
	$rw = $arsc[$i];
	echo '<a href="'.BS.$rw->link.'">'.nl2br(my3::nbsh($rw->title)).'</a><br /><br />';
		
}
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
   echo '<a href="'.BS.'search/?page='.$i.'&search='.urlencode($request).'">['.($i+1).']</a> ';
   };
  };
 };
?>
</p>
<?


//var_dump($arsc);
/*
if ($total){
   $url = '?search='.$request.'&metod=full';
   $radius = 5;
   $current = $p;
   $offset = $current * $rpp;
   $lastPage = ceil($total / $rpp) - 1;
   if ($lastPage){
      echo '<nav>';
      if ($current > $radius) echo '<a href="'.$url.'">1</a>&nbsp;';
      for($i = max(0, $current - $radius);
         $i <= min($lastPage, $current + $radius); $i++)
         if ($current == $i) echo '&nbsp;<b>'.($i+1).'</b>&nbsp;';
         else {
            echo '&nbsp;<a href="'.$url.($i ? '&amp;p='.$i : '').'">';
            if (($i == $current - $radius && $i > 0) ||
               ($i == $current + $radius && $i < $lastPage)) echo '&hellip;';
               else echo $i+1;
            echo '</a>&nbsp;';
         }
      if ($current < $lastPage - $radius)
         echo '&nbsp;<a href="'.$url.'&amp;p='.$i.'/">'.($lastPage+1).'</a>';
      echo '</nav>';
   }
}
*/
include "ssi-bottom.php";
?>
