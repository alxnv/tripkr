<?php
error_reporting (E_ALL);
@session_start();
require_once 'tools/my3.php';
$my3=new my3();

if (isset($_POST["action"]) && $_POST['action']=='send') {
	// Send the email
	//$name = isset($_POST["name"]) ? $_POST["name"] : "";
	$spammer = isset($_POST["email"]) ? $_POST["email"] : "";
	$email = isset($_POST["mail"]) ? $_POST["mail"] : "";
	$subject = isset($_POST["subject"]) ? $_POST["subject"] : "";
	$message = isset($_POST["message"]) ? $_POST["message"] : "";
	//$cc = isset($_POST["cc"]) ? $_POST["cc"] : "";
	//$token = isset($_POST["token"]) ? $_POST["token"] : "";
	$err='';
	if ($spammer<>'') $err.="Ошибка\n"; // в поле нулевой ширины и высоты "email" есть данные - значит это спам бот
	if ($email=='') $err.="Не заполенено поле 'E-mail'\n";
		else if (!my3::is_valid_email($email)) $err.="Неверный формат E-mail\n";
	if ($subject=='') $err.="Не заполенено поле 'Тема'\n";
	if ($message=='') $err.="Не введен текст сообщения\n";

    if ($err=='') {
		$sitemail=my3::SITEMAIL;
		$msg='Тема: '.substr($subject,0,400)."\n\n
Текст: 
".substr($message,0,50000);
		$headers = 'From: <'.$email.">\nReply-To: ".$email."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
		$hdr2=my3::encodeHeader('gokoreatour.ru - задан вопрос во всплывающем окне на тему: '.substr($subject,0,400));
                my3::log('sitemail',$sitemail);
                my3::log('hdr2',$hdr2);
                my3::log('msg',$msg);
                my3::log('headers',$headers);
		if (!mail($sitemail,$hdr2,$msg, $headers)) $err="Ошибка отправки сообщения\n";
	}
	$mess=($err=='' ? 'Ваш вопрос успешно отправлен' : $err);
	$arr=array('mess'=>$mess, 'success'=>($err=='' ? 1 :0));
        header('Content-Type: application/json; charset=utf-8');
	echo json_encode($arr);
}
