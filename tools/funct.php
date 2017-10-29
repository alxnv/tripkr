<?php
// Copyright (C) 2010 Воробьев Александр

function _mailrepl2($mtch) {
//var_dump($mtch);
    return $mtch[1].makecode3($mtch[2]).$mtch[3].makecode3($mtch[4]).$mtch[5];
}

function _mailrepl3($mtch) {
//var_dump($mtch);
    return $mtch[1].makecode4($mtch[2]).makecode3($mtch[3]).$mtch[4];
}

function makecode3($s) {
    // &#xXX;
    $arr=str_split($s);
    $s2='';
    for ($i=0;$i<count($arr);$i++) {
        $s2.='&#x'.dechex(ord($arr[$i])).';';
    }
    return $s2;
}

function nbshsave($s) {
    // сохраняем знак &euro;
    $s=str_replace('&euro;','$euro;',$s);
    $s=my3::nbsh($s);
    $s=str_replace('$euro;','&euro;',$s);
    return $s;
}

function makecode4($s) {
    // &#xXX; with rand
    $arr=str_split($s);
    $s2='';
    for ($i=0;$i<count($arr);$i++) {
        $s2.=(rand(0,1) ? '&#x'.dechex(ord($arr[$i])).';' : $arr[$i]);
    }
    return $s2;
}
function mailaddrreplacer($html) {
    // заменяет почтовые адреса в тексте <a.+href=\"(mailto\:[^"]+)\"[^>]*\>([^<]+)\<\/a>
//return $html; 
 return preg_replace_callback('/(<a.+?href=\")(mailto)(\:[^"]+?)(\"[^>]*?\>)/ims',
            "_mailrepl3",$html);
}

?>
