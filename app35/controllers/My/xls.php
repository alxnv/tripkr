<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class My_xls {


public function BOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0); 
return;
}

public function EOF() {
echo pack("ss", 0x0A, 0x00);
return;
}

public function WriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

public function WriteLabel($Row, $Col, $Value ) {
    $Value2 = $this->win1251($Value);
$L = strlen($Value2);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value2;
return;
}

public function win1251($text) {
    $text = iconv('utf-8', 'windows-1251', $text);
    $text = substr($text,0,254);
    return $text;
}

}
?>