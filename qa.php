<?
// выводим только отвеченные вопросы

if (!isset($conf35)) die;
include_once "tools/my4.php";
$spp=10;
$title='US Korea. Вопросы-ответы';
//$row2=$db3->qobj("select * from mg_html where sid='".$db3->escape($uid)."'");
include "ssi-top.php";

echo '<h1 class="title">Вопросы-ответы</h1>';
//echo $row2->html;

$arr=my3::qlist("select b.* from et_tree a,et_ta_html b 
        where a.idtree=4 and a.uid=b.uid and b.html2<>'' order by a.ordr");

//getmonth35($arr);
echo '<h3><ol>';
for ($i=0;$i<count($arr);$i++) {
    $ar2=$arr[$i];
    echo '<li><a href="#qa'.$ar2->uid.'">'.nl2br(my4::txtesc($ar2->html)).'</a></li>';
};
echo '</ol></h3><br />';

for ($i=0;$i<count($arr);$i++) {
    $ar2=$arr[$i];
    echo '<a name="n'.$ar2->uid.'"></a>';
    echo '<div class="greydiv"><a name="qa'.$ar2->uid.'"></a>';
    echo '<strong>Вопрос:</strong><br />'.nl2br(my4::txtesc($ar2->html)).'</a><br /><br />
        <strong>Ответ:</strong><br />'.my3::repldomain($ar2->html2).'</a></div><br /><br />';
    //if ($i<count($arr)-1) echo '<hr class="qa" noshade size="1" />';
};

echo '<a name="comments"></a>';
if  (isset($_SESSION['err82'])) {
    unset($_SESSION['err82']);
    echo '<br /><div  class="error">Вы ввели ошибочные данные</div><br />';
}
if  (isset($_SESSION['savedmsg3'])) {
    unset($_SESSION['savedmsg3']);
    echo '<br /><div  class="normalmessage">Данные сохранены</div><br />';
}
if (isset($_SESSION['sv82'])) {
    $row8=$_SESSION['sv82'];
    unset($_SESSION['sv82']);
} else {
    $row8=(object)array('naim'=>'','txt'=>'','vi'=>0);
}
$ar5=array('--- выберите пожалуйста --',
	'турист',
	'представитель турагентства',
	'представитель туроператора');

	/*
echo '<form method="post" action="'.BS.'work/qa">
<input type="hidden" name="wret" value="'.urlencode(substr($_SERVER["REQUEST_URI"],0,5000)).'" />
<div style="margin-left:50px">
Вы можете задать свой вопрос:<br />    
Ваш E-mail <span class="ast">*</span> : <input type="text" name="naim" size="50" value="'.htmlspecialchars($row8->naim).'" /> 
    <br />
Вы <span class="ast">*</span> : <select name="vi">'.my3::makeselsimp($ar5,$row8->vi).'
	</select>
    <br />
Текст сообщения <span class="ast">*</span> :<br />
<textarea name="txt" cols="70" rows="5">'.htmlspecialchars($row8->txt).'</textarea>
    
<br />
<label>Введите текст указанный на рисунке <span class="ast">*</span> :</label>';
echo '<img align="absmiddle" src="'.BS.'graphics.php?r='.rand(1,1000000).'">
<input name="4l" type="text" size="4" maxlength="4">
<br />
<input type="submit" value="Отправить" />
</div>
</form>';
*/

include "ssi-bottom.php";
?>