<?php
//if (!isset($conf35)) die;
//var_dump($_GET);
error_reporting (E_ALL);
@session_start();
require_once 'tools/my3.php';
include "funct1.php";
$my3=new my3();

if (isset($_GET['uid']) && $_GET['uid']=='logout') {
	$y2k = mktime(0,0,0,1,1,1980);
	setcookie('login', 0, $y2k, '/');
	setcookie('pwd', 0, $y2k, '/');
	header('Location: '.my3::baseurl().'page/41'); // загл страница туроператоров
	
}


if (isset($_GET['uid']) && $_GET['uid']=='changepwd' && count($_POST)>0) {
	// изменение пароля для зарегистрированного пользователя
    if (!testlogged()) { // если это не зашедший по паролю туроператор
            header('Location: '.my3::SITE);
            exit;
    }

    if (!isset($_POST['pwd']) || !isset($_POST['pwd2']))  die('Не все параметры указаны');
	$s3='';
        
	$pwd=mysql_real_escape_string(substr($_POST['pwd'],0,200));
	$pwd2=mysql_real_escape_string(substr($_POST['pwd2'],0,200));
        if ($pwd<>$pwd2) $s3.="Введенные пароли не совпадают\n";

	if ($s3<>'') {
		$_SESSION['err34']=$s3;
		header('Location: '.my3::baseurl().'changepwd_turops');
	} else {
		// записываем новый пароль в бд

            global $lgnuid3;
            $lgn=mysql_real_escape_string($lgnuid3->login);
            my3::q("update et_zayav set pwd='$pwd' where login='$lgn' and ismoderated=1");
            $month = time()+60*60*24*30; //mktime(0,0,0,1,1,2030);
            
            setcookie('pwd', MD5(substr($_POST['pwd'],0,200)), $month, '/');
            my3::gotomessage('Пароль был изменен');	
	}
	
}


if (isset($_GET['uid']) && $_GET['uid']=='retpwd' && count($_POST)>0) {
	// вход по паролю и логину
	if (!isset($_POST['login']) || !isset($_POST['keystring']))  die('Не все параметры указаны');
	$s3='';
	$login1=$db3->escape(substr($_POST['login'],0,70));
	$lgn=my3::qobj("select login,pwd,email,fio from et_zayav where login='$login1' and ismoderated=1 limit 1");
	if (!$lgn) $s3.="Неправильный логин\n";
	if (($_SERVER['SERVER_NAME']=='localhost') || (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['keystring'])){
	}else{
		$s3.="Проверочное слово введено неправильно\n";
	};
	unset($_SESSION['captcha_keystring']);

	if ($s3<>'') {
		//$_SESSION['psv3']=(object)array('login'=>$login1, 'naim'=>$naim1, 'fio'=>$fio1, 'dolgn'=>$dolgn1, 'email'=>$email1, 'phone'=>$phone1, 'rnum'=>$rnum1, 'site'=>$site1);

		$_SESSION['err34']=$s3;
		header('Location: '.my3::baseurl().'retpwd');
	} else {
		// отправляем по указанному в бд емейлу пароль

		$sitemail=$lgn->email;
		$msg='Ваш пароль для сайта www.gokoreatour.ru
		Логин :'.$lgn->login.'
		Пароль :'.$lgn->pwd;
		$headers = 'From: <'.$sitemail.">\nReply-To: ".$sitemail."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
		$hdr2=my3::encodeHeader('Ваш пароль для сайта www.gokoreatour.ru');

		@sd::mail($sitemail,$hdr2,$msg, $headers);
		
		my3::gotomessage('Пароль был отправлен на e-mail указанный при регистрации');	
	}
	
}


if (isset($_GET['uid']) && $_GET['uid']=='login' && count($_POST)>0) {
	// вход по паролю и логину
	if (!isset($_POST['login']) || !isset($_POST['password']) || !isset($_POST['keystring']))  die('Не все параметры указаны');
	$s3='';
	$login1=mysql_real_escape_string(substr($_POST['login'],0,70));
	$pwd1=mysql_real_escape_string(substr($_POST['password'],0,70));
	$lgn=my3::qobj("select login,pwd,uid,fio from et_zayav where login='$login1' and pwd='$pwd1' and ismoderated=1 limit 1");
	if (!$lgn) $s3.="Неправильный логин/пароль\n";
	if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['keystring']){
	}else{
		$s3.="Проверочное слово введено неправильно\n";
	};
	unset($_SESSION['captcha_keystring']);

	if ($s3<>'') {
		//$_SESSION['psv3']=(object)array('login'=>$login1, 'naim'=>$naim1, 'fio'=>$fio1, 'dolgn'=>$dolgn1, 'email'=>$email1, 'phone'=>$phone1, 'rnum'=>$rnum1, 'site'=>$site1);

		$_SESSION['err34']=$s3;
		header('Location: '.my3::baseurl().'login');
	} else {
		$_SESSION['thanks5']=1;
		$month = time()+60*60*24*30; //mktime(0,0,0,1,1,2030);
		setcookie('login', $lgn->login, $month, '/');
		setcookie('pwd', md5($lgn->pwd), $month, '/');
		header('Location: '.my3::baseurl().'page/41'); // загл страница туроператоров
	}
	
}


if (isset($_GET['uid']) && $_GET['uid']=='register' && count($_POST)>0) {
	// регистрация туроператора
/*echo 'here2';
var_dump($_POST);
var_dump($_SESSION);*/
    if (!isset($_POST['login']) || !isset($_POST['pwd']) || !isset($_POST['keystring']) || !isset($_POST['pwd2']) ||
	!isset($_POST['naim']) || !isset($_POST['fio']) || !isset($_POST['dolgn'])
	|| !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['rnum'])
	|| !isset($_POST['site']) || !isset($_POST['city'])) die('Не все параметры указаны'); 

	my3::q("lock tables et_zayav write");
	$s3='';
	$slogin=mysql_real_escape_string(substr($_POST['login'],0,70));
	$lgn=my3::qobj("select login from et_zayav where login='$slogin' limit 1");
	if ($lgn) $s3.="Этот логин занят\n";
	if (strlen($slogin)<3) $s3.="Логин должен быть не короче 3-х букв\n";
	$login1=substr($_POST['login'],0,100);
	$login3=mysql_real_escape_string($login1);
	
	if ($_POST['pwd']<>$_POST['pwd2']) $s3.="Введенные пароли не совпадают\n";
	$pwd1=substr($_POST['pwd'],0,100);
	$pwd3=mysql_real_escape_string($pwd1);
	
	if ($_POST['pwd']=='') $s3.="Поле 'Пароль' пустое\n";
	
	$naim1=substr($_POST['naim'],0,250);
	$naim3=mysql_real_escape_string($naim1);
	if ($naim1=='') $s3.="Поле 'Наименование' пустое\n"; 
	$fio1=substr($_POST['fio'],0,250);
	$fio3=mysql_real_escape_string($fio1);
	if ($fio1=='') $s3.="Поле 'ФИО' пустое\n"; 
	$dolgn1=substr($_POST['dolgn'],0,250);
	$dolgn3=mysql_real_escape_string($dolgn1);
	if ($dolgn1=='') $s3.="Поле 'Должность' пустое\n"; 
	$email1=substr($_POST['email'],0,250);
	$email3=mysql_real_escape_string($email1);
	if ($email1=='') $s3.="Поле 'E-mail' пустое\n"; 
	if (!my3::is_valid_email($email1)) $s3.="Неверный E-mail\n";
	$phone1=substr($_POST['phone'],0,250);
	$phone3=mysql_real_escape_string($phone1);
	if ($phone1=='') $s3.="Поле 'Телефон' пустое\n"; 
	$rnum1=substr($_POST['rnum'],0,250);
	$rnum3=mysql_real_escape_string($rnum1);
	if ($rnum1=='') $s3.="Поле 'Номер в реестре' пустое\n"; 
	$site1=substr($_POST['site'],0,250);
	$site3=mysql_real_escape_string($site1);
	if ($site1=='') $s3.="Поле 'Сайт' пустое\n"; 
	$city1=substr($_POST['city'],0,250);
	$city3=mysql_real_escape_string($city1);
	if ($city1=='') $s3.="Поле 'Город' пустое\n"; 
	
	
	
	if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['keystring']){
	}else{
		$s3.="Проверочное слово введено неправильно\n";
	};
	unset($_SESSION['captcha_keystring']);

	if ($s3<>'') {
		$_SESSION['psv3']=(object)array('login'=>$login1, 'naim'=>$naim1, 'fio'=>$fio1, 'dolgn'=>$dolgn1, 'email'=>$email1, 'phone'=>$phone1, 'rnum'=>$rnum1,
		'site'=>$site1, 'city'=>$city1);

		$_SESSION['err34']=$s3;
		header('Location: '.my3::baseurl().'register');
	} else {
		// добавляем в бд
		my3::q("insert into et_zayav values (0,'$login3','$pwd3', '$naim3', now(), '$fio3', '$dolgn3', '$site3', '$email3', '$phone3', '$rnum3', 0, '', '$city3')");
		$sitemail=my3::SITEMAIL;
		$msg='На сайте www.gokoreatour.ru туроператор добавил заявку на доступ к конфиденциальной информации 
		Название фирмы :'.$naim1;
		$headers = 'From: <'.$sitemail.">\nReply-To: ".$sitemail."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
		$hdr2=my3::encodeHeader('На сайте www.gokoreatour.ru туроператор добавил заявку на доступ к конфиденциальной информации');

		if (sd::mail($sitemail,$hdr2,$msg, $headers)) {
        		my3::gotomessage('Ваш запрос отправлен');
                } else my3::gotomessage('Ошибка отправки почты');
	}
	my3::q("unlock tables");
	exit;
};

if (isset($_GET['uid']) && $_GET['uid']=='tourcomments') {

	echo 'Отключено'; exit;

 /*if ((!isset($_SESSION['ttf4l'])) || (!isset($_POST['4l']))) {
  my3::gotomessage("Обновите предыдущую страницу");
  };                                              
 $_SESSION['ttfcnt']=(isset($_SESSION['ttfcnt']) ? $_SESSION['ttfcnt']+1 : 1);
 if ($_SESSION['ttfcnt']>4) unset($_SESSION['ttf4l']); // если более 5 раз подряд вводим неправильное число, то удаляем ииформацию о правильном числе
*/
 if (!isset($_POST['uid']) || !isset($_POST['wret']) || !isset($_POST['naim']) ||
         !isset($_POST['txt'])) exit;
 $uid2=intval($_POST['uid']);
 $wret2=urldecode(substr($_POST['wret'],0,5000));
 //$wret2="kihe&baарн";
 $s4='';
 $wret2 = preg_replace("/[^a-z0-9\-\.\?\/\#]/i", "", $wret2);
 $k=strpos($wret2,'#');
 if (!$k) $wret2.='#comments';
 $name1=substr($_POST['naim'],0,300);
 $mess1=substr($_POST['txt'],0,2000);

  if (!isset($_SESSION['ttf4l']) && !isset($_POST['4l']) ||
          $_SESSION['ttf4l']!=strval($_POST['4l']) ||
          $_POST['naim']=='' || $_POST['txt']=='') {
    unset($_SESSION['ttf4l']);
    $_SESSION['err82']=1;
    $_SESSION['sv82']=(object)array('naim'=>$name1,'txt'=>$mess1);
    header('Location: '.$wret2);
    exit;
      
  }

 $row=my3::qobj("select naim from et_tree where uid=$uid2 and idtree=2");
 if ($row) {
    $naim=$row->naim;

    //$edit4['sitemail']=$edit4['mail_kassa'];
    $sitemail=my3::SITEMAIL;
    $msg='Логин: '.$name1.'
    Текст: '.$mess1;
    $headers = 'From: <'.$sitemail.">\nReply-To: ".$sitemail."\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
    $hdr2=my3::encodeHeader('www.gokoreatour.ru - добавлен комментарий/вопрос к туру "'.$naim.'"');

    @sd::mail($sitemail,$hdr2,$msg, $headers);
    $name2=  mysql_escape_string($name1);
    $mess2=  mysql_escape_string($mess1);
    $row2=my3::qobj("select max(ordr) as mx from et_tree where idtree=3 and topid=$uid2");
    if ($row2===false) $row2=(object)array('mx'=>0);
    $db3->q("insert into et_tree (idtree,topid,ordr,naim) values 
            (3,$uid2,".($row2->mx+1).",'$name2')");
    $uid4=mysql_insert_id();
    $db3->q("insert into et_ta_html (uid,html) values ($uid4, '$mess2')");
    
    //var_dump(BS,$wret2);exit;
    header('Location: '.$wret2);
    exit;
    /*my3::gotomessage('Ваш запрос отправлен');
    } else {
    my3::gotomessage('Ошибка отправки почты');*/
    };
 };

 
if (isset($_GET['uid']) && $_GET['uid']=='qa') {

	echo 'Отключено'; exit;

/*if ((!isset($_SESSION['ttf4l'])) || (!isset($_POST['4l']))) {
  my3::gotomessage("Обновите предыдущую страницу");
  };                                              
 $_SESSION['ttfcnt']=(isset($_SESSION['ttfcnt']) ? $_SESSION['ttfcnt']+1 : 1);
 if ($_SESSION['ttfcnt']>4) unset($_SESSION['ttf4l']); // если более 5 раз подряд вводим неправильное число, то удаляем ииформацию о правильном числе
*/
 if (!isset($_POST['wret']) || !isset($_POST['naim']) ||
         !isset($_POST['txt']) || !isset($_POST['vi'])) exit;
$vi2=intval($_POST['vi']);

 //$uid2=intval($_POST['uid']);
 $wret2=urldecode(substr($_POST['wret'],0,5000));
 //$wret2="kihe&baарн";
 $s4='';
 $wret2 = preg_replace("/[^a-z0-9\-\.\?\/\#]/i", "", $wret2);
 $k=strpos($wret2,'#');
 if (!$k) $wret2.='#comments';
 if (substr($wret2,0,1)<>'/') $wret2='/'.$wret2;
 $name1=substr($_POST['naim'],0,300);
 $mess1=substr($_POST['txt'],0,20000);
 //var_dump($wret2);exit;

  if (!isset($_SESSION['ttf4l']) && !isset($_POST['4l']) ||
          $_SESSION['ttf4l']!=strval($_POST['4l']) ||
          $_POST['naim']=='' || $_POST['txt']=='' ||
		  $vi2<1 || $vi2>3) {
    unset($_SESSION['ttf4l']);
    $_SESSION['err82']=1;
    $_SESSION['sv82']=(object)array('naim'=>$name1,'txt'=>$mess1,'vi'=>$vi2);
    header('Location: '.$wret2);
    exit;
      
  }

 //$row=my3::qobj("select naim from et_tree where uid=$uid2 and idtree=2");
 if (1 /*$row*/) {
    //$naim=$row->naim;
	$ar5=array('--- выберите пожалуйста --',
		'турист',
		'представитель турагентства',
		'представитель туроператора');

    //$edit4['sitemail']=$edit4['mail_kassa'];
    $sitemail=my3::SITEMAIL;
    $msg='E-mail: '.$name1.'
	'.$ar5[$vi2].'
    Текст: '.$mess1;
    $headers = 'From: <'.$sitemail.">\nReply-To: ".$sitemail."\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
    $hdr2=my3::encodeHeader('www.gokoreatour.ru - задан вопрос в разделе Вопросы-ответы');

    @sd::mail($sitemail,$hdr2,$msg, $headers);
    $name2=  mysql_escape_string($name1);
    $name3=mysql_escape_string(my3::mnogot($name1,100));
    $mess2=  mysql_escape_string($mess1);
    $db3->q("lock tables et_tree  write, et_ta_html write");
    $row2=my3::qobj("select max(ordr) as mx from et_tree where idtree=4 and topid=0");
    if ($row2===false) $row2=(object)array('mx'=>0);
    $db3->q("insert into et_tree (idtree,topid,ordr,naim,actid) values 
            (4,0,".($row2->mx+1).",'$name3',4)");
    $uid4=mysql_insert_id();
    $db3->q("insert into et_ta_html (uid,html,html2,fpnum,flags) values ($uid4, '$mess2','',4,$vi2)");
    $db3->q("unlock tables");
    //var_dump(BS,$wret2);exit;
    $_SESSION['savedmsg3']=1;
    header('Location: '.$wret2);
    exit;
    /*my3::gotomessage('Ваш запрос отправлен');
    } else {
    my3::gotomessage('Ошибка отправки почты');*/
    };
 };
 
 ?>