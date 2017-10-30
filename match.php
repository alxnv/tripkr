<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
     * заменяет в строке один подмассив вхождений с координатами на другой
     * параметры в строке запроса $s - вида $1, $2, ...
     * @param string $s строка в которой заменяется
     * @param array $arr1 массив в каждой строке которого массив из двух
        * элементов (строка,координаты в строке $s)
     * @param array $arr2 массив заменяющих значений
     * @return string
     */

function repltextarray($s,$arr1,$arr2) {
    
    $last=strlen($s);
    $s2='';
    $modifiedindex=-1;
    for ($i=count($arr1)-1;$i>=0;$i--) {
        if ($arr1[$i][0]!=$arr2[$i]) {
            $ps2=$arr1[$i][1]+strlen($arr1[$i][0]);
            $s2=$arr2[$i].substr($s,$ps2,$last-$ps2).$s2;
            $last=$arr1[$i][1];
            $modifiedindex=$i;
        }
    }
    if ($modifiedindex==-1) {
        $s2=$s;
    } else {
        $s2=substr($s,0,$arr1[$modifiedindex][1]).$s2;
    }
    return $s2;
}

$s="select *,$3 from jkjlk
    where login='$1' and num=$3 order by $2";
$mtch=array();
$zam=array('xjx','id', 4);
preg_match_all('/(\$\d+)/m',$s,&$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);

// убераем лишнюю информацию
$mtch2=array();
for ($i=0;$i<count($mtch);$i++) {
    $mtch2[]=$mtch[$i][1];
}

// проставляем соответствия '$n'
$zam2=array();
for ($i=0;$i<count($mtch2);$i++) {
    $zam2[]=strval($zam[intval(substr($mtch2[$i][0],1))-1]);
}

echo '<pre>';
var_dump('$s=',$s);
var_dump('$zam=',$zam);
var_dump('$zam2=',$zam2);

var_dump($mtch2);
$s3=  repltextarray($s, $mtch2, $zam2);
var_dump('$3=',$s3);
?>
