<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo '<h3>Свежие новости:</h3>';
$i8=min(count($arrsave),4);
for ($i=0;$i<$i8;$i++) {
$row=$arrsave[$i];
echo '<p class="newsdate">'.$row->df.'
                    </p><p class="newsnaim">
                    <a href="'.BS.'news/'.$row->uid.'"><strong>'.my4::txtesc($row->naim).'</strong></a></p>
                    <p class="newsanons"><a href="'.BS.'news/'.$row->uid.'">'.nl2br(my4::txtesc($row->anons)).'</a>
                    </p>';

}

echo '<br / ><strong><a href="/allnews/0" style="text-decoration:underline">Все новости &gt;&gt;</a></strong>';
include "ssi-rss.php";
?>
