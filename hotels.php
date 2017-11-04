<?php
require_once "tools/funct.php";

$arr=my3::qlist("select uid,naim from et_tree where idtree=11 and topid=292 order by ordr");
echo '<div style="width:250px;padding-top:8px;float:left"><ul>';
for ($i=0;$i<count($arr);$i++) {
	$ar48=$arr[$i];
	echo '<li><h3><a href="'.my3::baseurl().'page/'.$arr[$i]->uid.'">'.my3::nbsh($arr[$i]->naim).
                '</a></h3></li>';
}

echo '</ul></div><div style="float:left">';
echo mailaddrreplacer(my3::repldomain($row2->html));
echo '</div><div style="clear:both"></div>';

?>
