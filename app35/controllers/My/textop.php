<?php
// Copyright (C) 2010 Воробьев Александр

/* 
операции с текстом
*/
class My_textop {
    function myunescape($s) {
        return strtr($s,'¦¤v·','?&#=');
    }
    
    function mnogot($s,$n) {
        $bp=my7::basePath();
        require_once $bp.'/utf8/utf8.php';
        require_once $bp.'/utf8/utils/unicode.php';
        require_once $bp.'/utf8/utils/specials.php';

        if (utf8_strlen($s)>$n) {
            $s2=utf8_substr($s,0,$n).'...';
        } else {
            $s2=$s;
        }
        return $s2;
    }

}
?>
