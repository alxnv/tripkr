<?
// uid - идентификатор записи, uid2 - признак информации для  всех, агентов или операторов
// для подтуров fpnum=3 idtree=2 (медицинский туризм = в разделе админки "Туры")
// для туров fpnum=1 idtree=1
if (!isset($conf35)) die;
if (!isset($_GET['uid']) || !isset($_GET['uid2'])) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
require_once "funct1.php";
$uid2=intval($_GET['uid']);
$ttype=mysql_escape_string(substr($_GET['uid2'],0,50));


switch  ($ttype) {
    case 'agents':
        $fld='html2';
        $kroh='<a href="'.BS.'page/10">ТурАгенствам</a> / <a href="'.BS.'page/67">Наши туры</a>';
        break;
    case 'operators':
		if (!testlogged()) {
			header('Location: '.my3::baseurl().'login');
			exit;
		}

        $fld='html3';
        $kroh='<a href="'.BS.'page/41">ТурОператорам</a> / <a href="'.BS.'page/68">Наши туры</a>';
        break;
    default:
        $fld='html';
        $ttype='tours';
        $kroh='<a href="'.BS.'page/7">Туристам</a> / <a href="'.BS.'page/64">Наши туры</a>';
        break;
    
}
$row2=$db3->qobj("select a.naim,b.$fld as html,b.flags from et_tree a left join et_ta_html b
        on a.uid=b.uid where a.uid=$uid2");
if (!$row2) die;
$t2=$row2->naim;
$title='US Korea. '.$t2;
if ($ttype=='tours' && $row2 && $row2->flags) {
    $kroh='<a href="'.BS.'page/7">Туристам</a> / <a href="'.BS.'page/65">Медицинский туризм</a>';
}

//$ar2=$db3->getkrohi('et_tree',$uid2);
include "ssi-top.php";

//echo '<h1>'.my3::nbsh($t2).'</h1>';
echo '<h1 class="title">'.$kroh.' / '.my3::nbsh($t2).'</h1>';

//echo my3::nbsh($row2->naim).'<br /><br />';
$s96=mailaddrreplacer(my3::repldomain($row2->html));
echo $s96.'<br /><br />';
//var_dump(htmlspecialchars($s96));

if ($ttype=='tours') {
	$arr=my3::qlist("select a.uid,a.naim,b.html,b.html2,b.data from et_tree a,et_ta_html b 
		where a.idtree=3 and a.topid=$uid2 and a.uid=b.uid order by a.ordr");
	if (count($arr)>0) {
		echo '<h3 style="padding-left:40px">Комментарии</h3><table class="bgwhite" width="100%" style="padding-left:40px;padding-right:40px">';
		for ($i=0;$i<count($arr);$i++) {
			$ar2=$arr[$i];
//			echo '<tr style="background-color:#EEE"><td>'.my3::nbsh($ar2->naim).
//					'<br />'.date('d.m.Y',strtotime($ar2->data)).'</td></tr>';
			echo '<tr style="background-color:#EEE"><td>'.date('d.m.Y',strtotime($ar2->data)).'</td></tr>';
			echo '<tr><td>'.nl2br(my3::nbsh($ar2->html)).'</td></tr>';
			if (trim($ar2->html2)<>'') echo '<tr><td><b>Администратор</b>:<br />'.nl2br(my3::nbsh($ar2->html2)).'</td></tr>';
			echo '<tr><td><br /></td></tr>';
		}
		echo '</table>';
	}
	echo '<a name="comments"></a>';
	if  (isset($_SESSION['err82'])) {
		unset($_SESSION['err82']);
		echo '<br /><div  class="error">Вы ввели ошибочные данные</div><br />';
	}
	if (isset($_SESSION['sv82'])) {
		$row8=$_SESSION['sv82'];
		unset($_SESSION['sv82']);
	} else {
		$row8=(object)array('naim'=>'','txt'=>'');
	}
	/*
	echo '<br /><form method="post" action="'.BS.'work/tourcomments"><input type="hidden" name="uid" value="'.$uid2.'" />
	<input type="hidden" name="wret" value="'.urlencode(substr($_SERVER["REQUEST_URI"],0,5000)).'" />
	<div style="margin-left:50px">
	<strong>Вы можете задать вопрос или оставить комментарий:</strong><br />    
	Ваш E-mail <span class="ast">*</span> : <input type="text" name="naim" size="50" value="'.htmlspecialchars($row8->naim).'" /> 
		<br />
	Текст сообщения <span class="ast">*</span> :<br />
	<textarea name="txt" cols="70" rows="5">'.htmlspecialchars($row8->txt).'</textarea>
		
	<br />
	<label>Введите текст указанный на рисунке:</label>';
	echo '<img align="absmiddle" src="'.BS.'graphics.php?r='.rand(1,1000000).'">
	<input name="4l" type="text" size="4" maxlength="4">
	<br />
	<input type="submit" value="Отправить" />
	</div>
	</form>';
	*/
};
include "ssi-bottom.php";
?>