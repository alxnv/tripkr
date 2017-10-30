<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class A7HashController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        /*$arr=array();
        for ($i=0;$i<100;$i++) $arr[]=my7::generatetexthash (32);
        $this->view->hashes=$arr;*/
        //$this->_redirect('tools/helpers/noframe.htm',array('prependBase'=>1));
    }
    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('k',$formData);
            $pwd=$formData['pwd'];
            if ($pwd=='') {
                // берем пароль админа
                $q1=my7::qobj("select pwd from et_cpusers where login='admin'");
                $pwd=$q1->pwd;
            }
            $arr=my7::qobjarrayS("select * from et_zayav where pwd='$1' and ismoderated=1 order
                by email", array($pwd));
            $errs=array();
            $numerrs=0;
            //var_dump($arr);
            //exit;
            if ($arr===false) $arr=array();
            for ($i=0;$i<count($arr);$i++) {
                
                // генерируем запись с доступом на страничку смены парполя в базе данных
                $repeat=false;
                do {
                    $hash=my7::generatetexthash(32);
                    try {
                        my7::qobjS("insert into et_hashlogin (hash,login,date_generated,date_expired)
                            values ('$1','$2',now(),now()+$3 days)",
                                array($hash,$arr[$i]->login,intval($formData['days'])));
                    } catch(Exception $e){
                        $repeat=true;

                    }
                } while ($repeat);
                
                // отсылаем письмо
                $email=$formData['email'];
                $err='';
		$sitemail=my7::SITEMAIL;
		$msg=$formData['frm'];
                $msg=  str_replace('@fio@', $arr[$i]->fio, $msg);
                $msg=  str_replace('@firm@', $arr[$i]->firm, $msg);
                $lnk1=my7::baseUrl().'/secret_change_pwd.php?hash='.$hash;
                $msg=  str_replace('@link@', '<a href="'.$lnk1.'">'.$lnk1.'</a>', $msg);
                $headers = 'From: <'.$email.">\nReply-To: ".$email."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
		$hdr2=my7::encodeHeader('Письмо с сайта gokoreatour.ru с просьбой о смене пароля');
                /*my3::log('sitemail',$sitemail);
                my3::log('hdr2',$hdr2);
                my3::log('msg',$msg);
                my3::log('headers',$headers);*/
		if (!mail($sitemail,$hdr2,$msg, $headers)) {
                    $err=$formData['login']."<br>";
                    $errs=$errs.$err;
                    $numerrs++;
                }
                
            }
            
            my7::amessageleft('Email отправлены и доступ к личным страничкам по смене пароля предоставлен
                <br>Всего '.count($arr)-$numerrs.' сообщений отправлено'.
                    ($numerrs>0 ? '<br>Не отправлены сообщения следующим пользователям:<br>'.$errs : ''));
        };
        
    }
};
 ?>
