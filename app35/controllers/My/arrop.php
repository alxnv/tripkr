<?php
// Copyright (C) 2010 Воробьев Александр

/* 
операции с массивами
*/

class My_arrop {

    function expandarr($pfx,$num,&$arr) {
        // если в массиве не хватает значений c данным префиксом, то добавляем пустые
        for ($i=1;$i<=$num;$i++) {
            if (!isset($arr[$pfx.$i])) $arr[$pfx.$i]='';
        }

    }

    function nwithpfx($pfx,$n) {
        // возвращает массив 's1','s2',...
        $arr=array();
        for($i=1;$i<=$n;$i++) array_push($arr,$pfx.$i);
        return $arr;
    }

    function getvalues($ai,$ak) {
        // возвращает только те элементы из $ai ключи которых совпадают с элементами $ak
        $arr=array();
        for ($i=0;$i<count($ak);$i++) {
            if (isset($ai[$ak[$i]])) {
                $arr[$ak[$i]]=$ai[$ak[$i]];
            }
        }
        return $arr;
    }
} // end class

?>
