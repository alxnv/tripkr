<?php

/* 
Отправляет сообщения почтового списка рассылки по cron раз в 10 минут
 */


$sendadminmailperiod=30; // минимальное время между отправками почты администратору о
    // успешном завершении отсылки списков в минутах

$ar2=my3::qobj("select * from et_settings");
//$ar2->lastsentadminmail=0;
$every10=intval($ar2->every10minutesnummails);
if ($every10<=0) exit;

// получаем сообщения которые будем отправлять
$arr=my3::qlist("select a.uid as idlist,a.name as namelist,a.html,a.mask,"
        . " b.email,b.name,b.company,b.uid"
        . " from et_maillists a, et_dbmailexternal b"
        . " where a.tosendmail=1 and a.uid=b.idmaillist and b.tosendmail=1 and b.mailsent=0"
        . " limit 0,$every10");

echo '<pre>';
//var_dump($arr);

$arrfromlistssent=array(); // из каких листов рассылки отправлялись сообщения

// отправляем сообщения
if (count($arr)==0) echo 'Нет сообщений для отправки<br>';
    else echo 'Отправка почтовых сообщений на следующие email: <br>';

if (count($arr)>0) echo '<table>';    
for ($i=0;$i<count($arr);$i++) {
    $obj=$arr[$i];
    $idlist=intval($obj->idlist);
    $contacts=str_replace('%n', $obj->name, $obj->mask);
    $contacts=str_replace('%c', $obj->company, $contacts);
    //echo '<hr>';

    $sitemail=$ar2->siteemail;
    $msg=$obj->html;
    $headers = 'From: <'.$sitemail.">\nReply-To: ".$sitemail."\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
    $hdr2=my3::encodeHeader($contacts);

    
    $b=mail($obj->email,$hdr2,$msg, $headers);
    echo '<tr><td>'.htmlspecialchars($obj->email).'</td><td>'.htmlspecialchars($contacts).'</td></tr>';
    //var_dump('b',$b,'email',$obj->email,'sitemail',$sitemail,'contacts',$contacts,'msg',$msg);
    $b2=($b ? "''" : "'1'");
    my3::q("update et_dbmailexternal set mailsent=1, error_sent=$b2 where uid=$obj->uid");
    $arrfromlistssent[$idlist]=$obj->namelist;
}
if (count($arr)>0) echo '</table>';

// отправляем сообщение администратору сайта если завершена отправка всего почтового списка, 
//   и если можем отправить почтовое сообщение
//var_dump('time',time(),'lasts',$ar2->lastsentadminmail+$sendadminmailperiod*60);

    //var_dump(1111);
$ark=array_keys($arrfromlistssent);
$ar6=array();
$b4=1;
for ($i=0;$i<count($ark);$i++) {
    $id3=intval($ark[$i]);
    //var_dump('id3',$id3);
    $ar8=my3::qlist("select a.uid as idlist,a.name"
        . " from et_maillists a, et_dbmailexternal b"
        . " where a.tosendmail=1 and a.uid=$id3 and a.uid=b.idmaillist and b.tosendmail=1 and b.mailsent=0"
        . " limit 0,1");
    //var_dump('ar8',$ar8);
    if (count($ar8)==0) {
        // отправили все по данному списку
        if ($b4) echo '<br><br>Отправлены следующие списки почтовых сообщений:<br>';
        $b4=0;
        array_push($ar6,$arrfromlistssent[$id3]);
        echo htmlspecialchars($arrfromlistssent[$id3]).'<br>';
    }
}
//var_dump('ar6',$ar6);

$sentall=0;

if (count($ar6)>0) { // завершили отправку какого то количества списков рассылки
    $ar8=my3::qlist("select a.uid as idlist,a.name"
        . " from et_maillists a, et_dbmailexternal b"
        . " where a.tosendmail=1 and a.uid=b.idmaillist and b.tosendmail=1 and b.mailsent=0"
        . " limit 0,1");
    if (count($ar8)==0) {
        // отправили все списки
        $sentall=1; 
        echo '<br><br>Отправлены все списки почтовых сообщений';
    }

}
    
if (count($ar6)>0 && time()>=$ar2->lastsentadminmail+$sendadminmailperiod*60) {
    //var_dump('sentall',$sentall);
    echo '<br><br>Отправляем сообщение об окончании рассылки на почту администратору сайта';
    $sitemail=$ar2->siteemail;
    $msg="Отправлены все сообщения из списков рассылки:\n";
    for ($i=0;$i<count($ar6);$i++) $msg.="     ".$ar6[$i];
    $headers = 'From: <'.$sitemail.">\nReply-To: ".$sitemail."\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
    $s45=($sentall ? 'На сайте gokoreatour.ru была завершена отправка почтовых сообщений из всех списков рассылки' 
            : 'На сайте gokoreatour.ru была завершена отправка почтовых сообщений из списков рассылки');
    $hdr2=my3::encodeHeader($s45);

    
    $b=mail($sitemail,$hdr2,$msg, $headers);
    
    // записываем время когда отправили сообщение администратору
    $t=time();
    my3::q("update et_settings set lastsentadminmail=$t");
}