<?
//<hr class="hr2" noshade="noshade" />
$show5=isset($show5);
require_once "tools/my4.php";
$arrsave=my3::qlist("select uid,naim,anons,pict,date_format(data1,'%d.%m.%Y') as df from et_news
     order by data1 desc limit 0,".($show5 ? 12 : 4));

function onenews4(&$row) {
	$s='';
	$img_dir=my3::basepath().'img2/';
	$img_http=my3::baseurl().'img2/';
	$noimg=($row->pict=='' || ($szs=@getimagesize($img_dir.$row->pict))===false || $szs[0]==0);
	if (!$noimg) {
		$s.='<a href="'.BS.'news/'.$row->uid.'"><img class="imfl" border="0" src="'.$img_http.$row->pict.'" '.$szs[3].'></a>';
	};
	$s.='<p class="newsdate">'.$row->df.'
                    </p><p class="newsnaim">
                    <a href="'.BS.'news/'.$row->uid.'"><strong>'.my4::txtesc($row->naim).'</strong></a></p>
                    <p class="newsanons"><a href="'.BS.'news/'.$row->uid.'">'.nl2br(my4::txtesc($row->anons)).'</a>
                    </p>';
	return $s;
}	 
if ($show5) {
	echo '<br /><h3>Свежие новости:</h3>';
	echo '<br /><table class="bgw4" width="100%">';
	for ($i=4;$i<count($arrsave);$i+=2) {
		$s2=($i==count($arrsave)-1 ? '' : onenews4($arrsave[$i+1]) );
		$s1=onenews4($arrsave[$i]);
		echo '<tr valign="top"><td width="50%" class="bgl4">'.$s1.'</td><td width="50%" class="bgr4">'.$s2.'</td></tr>';
	};
	echo '</table>';
	echo '<br / ><strong><a href="/allnews/0">Все новости &gt;&gt;</a></strong>';
};
?>
