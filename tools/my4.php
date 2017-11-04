<?php
// Copyright (C) 2010 Воробьев Александр
// 
// основные функци для клиентской части сайта
class my4 {

public static function txtesc($s) {
    return htmlspecialchars($s);
}

/*public static function cutlinebreak($s) {
    return str_replace("\n",$s);
}*/


public static function printpages($arr) {
// печатает на экран список номеров страниц гиперссылками (paginator)
$nrows=$arr[0];
$spp=$arr[3];
$nrowspage=$arr[2];
$page=$arr[1];
$url=$arr[4];
echo '<p align="left">';
$nrs=max(0,ceil($nrows/$spp)-1);
if ($nrowspage<$nrows) {
 echo 'Страницы: ';
 for($i=0;$i<=$nrs;$i++) {
  if (($i!=0) && (floor($i/20)==$i/20)) {
   echo '<br>';
   };
  if ($i==$page) {
   echo '<b> ['.($i+1).'] </b>';
   } else {
   echo '<a href="'.my3::baseurl().$url.$i.'">['.($i+1).']</a> ';
   };
  };
 };
echo '</p>';
}

}

$my4=new my4();
?>
