<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$email='th@jkljj.ru';
$headers = 'From: <'.$email.">\nReply-To: ".$email."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
		$hdr2='letter';
                //my3::log('sitemail',$sitemail);
                //my3::log('hdr2',$hdr2);
                //my3::log('msg',$msg);
                //my3::log('headers',$headers);
                $msg='hgfh';
		if (!mail('alxnv@yandex.ru',$hdr2,$msg, $headers))  echo "Ошибка отправки сообщения\n";
			else echo "Данные отправлены";

?>
