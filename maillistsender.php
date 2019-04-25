<?php

/* 
Отправляет сообщения почтового списка рассылки по cron раз в 10 минут
 */
$testing=1; // если 1, то тестовая отправка всех сообщений на alxnv@yandex.ru

$sendadminmailperiod=30; // минимальное время между отправками почты администратору о
    // успешном завершении отсылки списков в минутах

function updaterecord(&$obj) {
    $obj2=my3::setnulls($obj);
    my3::q("update et_tracemailsend set badbegin=from_unixtime($obj->badbegin), goodbegin=from_unixtime($obj->goodbegin),"
            . "endsend=from_unixtime($obj->endsend), cntgood=$obj->cntgood, cntbad=$obj->cntbad,"
            . "goodminutes=$obj2->goodminutes, badminutes=$obj2->badminutes,"
            . "every10=$obj->every10,mailtos=$obj->mailtos where uid=$obj->uid");
}

/**
 * закрыть запись и начать новую
 * @param object $obj
 */
function closerecord(&$obj,$every10,&$isnewrec) {
    $obj->endsend=time();
    calcdurations($obj);
    updaterecord($obj);
    $obj=(object)array('badbegin'=>0,'goodbegin'=>time(),'endsend'=>0,
            'cntgood'=>0,'cntbad'=>0,'every10'=>$every10,'mailtos'=>0);
    $isnewrec=1;

    
}

/**
 * запись в бд данных об отправленных успешно и неуспешно email, и за какое время
 * @param integer $numsuccess
 * @param integer $numerrors
 * @param integer $curtime
 * @param boolean $allsent
 * @param integer $every10
 */
function tracemailsending($numsuccess,$numerrors,$allsent,$every10,$mailtos) {
    $isnewrec=0;
    $obj=my3::qobj("select uid,UNIX_TIMESTAMP(badbegin) as badbegin,"
            . "UNIX_TIMESTAMP(goodbegin) as goodbegin,UNIX_TIMESTAMP(endsend) as endsend,"
            . "cntgood,cntbad,every10,goodminutes,badminutes,mailtos from et_tracemailsend order by uid desc limit 0,1");
    
    $tableempty=($obj===false);
    if (!$tableempty) {
        if (is_null($obj->goodbegin)) $obj->goodbegin=0;
        if (is_null($obj->badbegin)) $obj->badbegin=0;
        if (is_null($obj->endsend)) $obj->endsend=0;
    }
    
    if ($tableempty) $isnewrec=1;
    if (($numsuccess+$numerrors==0 || $every10<=0) && $mailtos<>0) {
        // ничего не отослали
        // закрываем если нужно запись
        if (!$tableempty && $obj->endsend==0) {
            $obj->endsend=time();
            updaterecord($obj);
        }
        return;
    }
    
    if (!$tableempty && ($obj->endsend<>0)) $isnewrec=1; // если закрыта запись(закончена)
    
    
    $b8=0;
    if ($obj->every10<>$every10 && !$tableempty) {
        $b8=1;
        closerecord($obj,$every10,$isnewrec);
    }
    
    if ($isnewrec) {
        $obj=(object)array('badbegin'=>0,'goodbegin'=>time(),'endsend'=>0,
            'cntgood'=>0,'cntbad'=>0,'every10'=>$every10,'mailtos'=>0);
    }

    if ($numerrors==0 && ($obj->badbegin==0)) {
        // пишем правильное
        $obj->cntgood+=$numsuccess;
        $obj->mailtos+=$mailtos;
    } else if ($numerrors<>0 && (0==$obj->badbegin)) {
        // пишем правильное и ошибки
        $obj->mailtos+=$mailtos;
        $obj->badbegin=time();
        $obj->cntgood+=$numsuccess;
        $obj->cntbad+=$numerrors;
        
    } else if (($obj->badbegin<>0) && $numsuccess==0) {
        // пишем ошибки
        $obj->mailtos+=$mailtos;
        $obj->cntbad+=$numerrors;
        
    } else if (($obj->badbegin<>0) && $numsuccess<>0) {
        
        closerecord($obj,$every10,$isnewrec);
        $obj->mailtos+=$mailtos;
        $obj->cntgood+=$numsuccess;
        if ($numerrors<>0) {
            $obj->badbegin=time();
            $obj->cntbad+=$numerrors;
        }
        
    }
    if ($allsent && !$b8) {
        closerecord($obj,$every10,$isnewrec);
    }
    
    if ($isnewrec) {
        calcdurations($obj);
        $obj2=my3::setnulls($obj);
        my3::q("insert into et_tracemailsend (badbegin,goodbegin,endsend,cntgood,cntbad,"
                . "goodminutes,badminutes,every10,mailtos) values (from_unixtime($obj2->badbegin),from_unixtime($obj2->goodbegin),from_unixtime($obj2->endsend),"
                . "$obj2->cntgood,$obj2->cntbad,$obj2->goodminutes,$obj2->badminutes,$obj2->every10,$obj2->mailtos)");
    } else {
        calcdurations($obj);
        updaterecord($obj);

    }
}

/**
 * Проставить длительности хорошего и плохого периодов
 * @param object $obj
 */
function calcdurations(&$obj) {
    if ($obj->goodbegin==0) $obj->goodbegin=time();
    if ($obj->badbegin==0) {
        $obj->goodminutes=(($obj->endsend==0) ? null : $obj->endsend-$obj->goodbegin);
        $obj->badminutes=null;
    } else {
        $obj->goodminutes=$obj->badbegin-$obj->goodbegin;
        $obj->badminutes=($obj->endsend==0 ? null : $obj->endsend-$obj->badbegin);
        
    }
}

$ar2=my3::qobj("select * from et_settings");
//$ar2->lastsentadminmail=0;
$every10=intval($ar2->every10minutesnummails);
$moremail=intval($ar2->moremailsent);
$mailtos=$every10-$moremail;
if ($mailtos<0) $mailtos=0;
$n45=min($every10,$moremail);
if ($n45<>0) my3::q("update et_settings set moremailsent=moremailsent-$n45");

if ($every10<=0) {
    tracemailsending(0,0,0,0,$mailtos);
    exit;
}

// получаем сообщения которые будем отправлять
if ($mailtos==0) $arr=array();
    else $arr=my3::qlist("select a.uid as idlist,a.name as namelist,a.html,a.mask,"
        . " b.email,b.name,b.company,b.uid"
        . " from et_maillists a, et_dbmailexternal b"
        . " where a.tosendmail=1 and a.uid=b.idmaillist and b.tosendmail=1 and b.mailsent=0"
            . " order by b.priority,b.email"
        . " limit 0,$mailtos");

echo '<pre>';
//var_dump($arr);

$arrfromlistssent=array(); // из каких листов рассылки отправлялись сообщения

// отправляем сообщения
if (count($arr)==0) echo 'Нет сообщений для отправки<br>';
    else echo 'Отправка почтовых сообщений на следующие email: <br>';

if (count($arr)>0) echo '<table>';    
$numsuccess=0;
$numerrors=0;
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

    if ($testing) {
        $hdr2=my3::encodeHeader('Тест почтовой рассылки на gokoreatour.ru #'.rand(1,1000000000));
        $b=mail('alxnv@yandex.ru',$hdr2, htmlspecialchars($contacts), $headers);
    } else {
        $b=mail($obj->email,$hdr2,$msg, $headers);
    }
    
    if ($b) $numsuccess++;
       else $numerrors++;
       
    if ($b) {
        $error='';
    } else {
        $error = error_get_last();
        if (is_array($error)) $error=$error['message'];
        if (trim($error)=='')  {
            $error='1';
        }
        $error=$db3->escape($error);
    }
       
    echo '<tr><td>'.htmlspecialchars($obj->email).'</td><td>'.htmlspecialchars($contacts).'</td><td>'.($b ? 'Success' : 'Fail').'</td></tr>';
    //var_dump('b',$b,'email',$obj->email,'sitemail',$sitemail,'contacts',$contacts,'msg',$msg);
    $b2=($b ? "''" : "'1'");
    my3::q("update et_dbmailexternal set mailsent=1, error_sent='$error' where uid=$obj->uid");
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

    
    $b=sd::mail($sitemail,$hdr2,$msg, $headers);
    
    // записываем время когда отправили сообщение администратору
    $t=time();
    my3::q("update et_settings set lastsentadminmail=$t");
}

//$numerrors=3;
//$numsuccess=0;
//$sentall=1;
tracemailsending($numsuccess,$numerrors,$sentall,$every10,$n45);