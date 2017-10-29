<? 
    function getalltree7($id) {
// сохраняет в массив все дерево $idtree
//my3::log('d',"select a.*,b.name from $this->tbl a,$this->tblact b
//      where a.idtree=$this->idtree and a.actid=b.uid order by a.topid,a.ordr");
        //$ar2=my3::qlist("select a.* from $this->tbl a
          //      where a.idtree=$this->idtree order by a.topid,a.ordr");
		// выбираем все доступные разделы  
        $ar2=my3::qlist("select b.naim,a.user_id,b.uid,b.topid,c.newtour
            from et_tree b,et_zayuser a,et_ta_html c where a.user_id=$id and b.uid=a.zay_id and  
                b.idtree=12 and b.uid=c.uid order by b.topid,b.ordr");
        $aro=array();
        for ($i=0;$i<count($ar2);$i++) {
            $row=$ar2[$i];
            //$row->name=$newcont;
            if (!isset($aro[$row->topid])) {
                $aro[$row->topid]=array();
            }
            array_push($aro[$row->topid],$row);
        };

        return $aro;
    }
    function printtree5(&$aro,$topid,&$i1) {
// выводит на экран все дерево
        echo '<ol class="tree" style="line-height:30px">';
    	$newhtml='<img class="blinking" src="'.my3::baseurl().'images/newcart.gif" widht="39" height="11" /> ';
        if (isset($aro[$topid])) {
            for ($i=0,$j=count($aro[$topid]);
            $i<$j;
            $i++) {
                $row=$aro[$topid][$i];
                $n=$row->uid;
				if ($topid==299) {
					$s1='<strong>';
					$s2='</strong>';
				} else {
					$s1='';
					$s2='';
				}
				if ($row->newtour) $s1=$newhtml.$s1;
                echo '<li>'.$s1.'<a href="'.BS.'page/'.$row->uid.'">'.my3::nbsh($row->naim).'</a>'.$s2.'</li>';
                if (isset($aro[$n])) {
                    $i1++;
                    printtree5(&$aro,$n,$i1);
                    $i1--;
                }
                $i1++;
           };
        }
        echo '</ol>';

    }

$ar7=getalltree7($lgnuid3->uid);
// выводим список доступных разделов
$i1=1; // сквозной индекс (не используется)
printtree5($ar7, $uid2, $i1);

echo mailaddrreplacer($row2->html);

/*$arr4=my3::qlist("select b.uid,b.naim,c.html from et_zayuser a, et_tree b, et_ta_html c, et_zayav d where a.user_id=$lgnuid3->uid and a.user_id=d.uid and d.ismoderated=1 and b.idtree=12 and b.topid=299 and a.zay_id=b.uid and b.uid=c.uid order by b.ordr");

echo '<ol style="line-height:170%">';
for ($i=0;$i<count($arr4);$i++) {
	$row=$arr4[$i];
	echo '<li><a href="'.BS.'page/'.$row->uid.'">'.my3::nbsh($row->naim).'</a></li>';
}
echo '</ol>';*/
?>
