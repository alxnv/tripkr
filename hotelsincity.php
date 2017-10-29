<?php
// $uid2 - идентификатор страницы отеля
require_once "tools/funct.php";

$arr=my3::qlist("select a.uid,a.naim,b.uid as buid,b.naim as bnaim from et_tree a,et_tree b
 where a.topid=$uid2 and b.topid=a.uid
 order by a.ordr, b.ordr");

echo '<ul style="font-size:20px">';
$id1=-1;
$i=0;

while ($i<count($arr)) {
	$row=$arr[$i];
	if ($id1<>$row->uid) {
		if ($id1<>-1) echo '</ul></li>';
		$id1=$row->uid;
		echo '<li>'.my3::nbsh($row->naim).'<ul>';
	}
	echo '<li><a href="'.my3::baseurl().'page/'.$row->buid.'">'.my3::nbsh($row->bnaim).'</a></li>';
	$i++;
}
if ($id1<>-1) echo '</ul></li>';

echo '</ul><br /><br />';
 
echo mailaddrreplacer($row2->html);

?>
