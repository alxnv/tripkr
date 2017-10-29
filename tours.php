<?php
//если установлена $ismed мед.туры
require_once "tools/funct.php";


switch  ($ttype) {
    case 'agents':
        $fld='html2';
        break;
    case 'operators':
        $fld='html3';
        break;
    default:
        $fld='html';
        $ttype='tours';
        break;
    
}
if ($ttype<>'tours') {
    $wh2='';
}
else if (isset($ismed)) {
    $wh2=' and b.flags<>0';
} else {
    $wh2=' and b.flags=0';
}
echo mailaddrreplacer($row2->html);
echo '<br />';
$arr=my3::qlist("select a.uid,b.pict,a.naim,b.fpnum,b.newtour,b.newtourta  from et_tree a,et_ta_html b
    where a.idtree=2 and a.uid=b.uid $wh2 and b.".$fld."<>'' order by a.ordr desc");
$menu='';
if (count($arr)>0) {
    $menu.='<table class="kontent bw0" style="margin-left:16px">';
    for ($i=0;$i<count($arr);$i++) {
            $menu.='<tr><td width="150">';
            $img_dir=my3::basepath().'img2/';
            $img_http=my3::baseurl().'img2/';
            $noimg=($arr[$i]->pict=='' || ($szs=@getimagesize($img_dir.$arr[$i]->pict))===false || $szs[0]==0);
            if ($noimg) {
                //echo '<font color=green>Нет изображения</font>';
                $menu.='<a href="'.my3::baseurl().'tour/'.$arr[$i]->uid.'/'.$ttype.'"><img border="0"  src="'.BS.'img/stub.jpg" width="150" height="105" alt="" /></a>';
            } else {
                $menu.='<a href="'.my3::baseurl().'tour/'.$arr[$i]->uid.'/'.$ttype.'"><img border="0" src="'.$img_http.$arr[$i]->pict.'" '.$szs[3].'></a>';
            }
            
			$isnew=($ttype=='tours' && $arr[$i]->newtour) || ($ttype=='agents' && $arr[$i]->newtourta);
			$newhtml='<img class="blinking" src="'.my3::baseurl().'images/newcart.gif" widht="39" height="11" />';
            $menu.='</td><td width="470"><h3>'.($isnew ? $newhtml : '').' <a href="'.my3::baseurl().'tour/'.$arr[$i]->uid.'/'.$ttype.'">'.
			my3::nbsh($arr[$i]->naim).
                '</a></h3></td></tr>';
    }
    $menu.='</table>';
}
echo $menu.'<br />';

?>
