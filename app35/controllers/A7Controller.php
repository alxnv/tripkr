<?php
// Copyright (C) 2010 Воробьев Александр


class A7Controller extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexPhpAction() {
        // для редиректа с дебаггера
        $this->_redirect('a7');

        }
    public function indexAction()
    {
        // action body
        //~ my7::gotoamessage($this,'месаг один34');
        //$this->_redirect('a7-message/view/id/'.urlencode('ололд пг гшш 78'),array('prependBase'=>1));
        @session_start();
        $this->view->title='Администраторский модуль - введите пароль';
        //$this->_redirect('tools/helpers/noframe.htm',array('prependBase'=>1));
    }

public function loginAction()
         {
             // Получение параметра пришедшего от пользователя
             //$articleId = $this->_getParam('articleId');
        if ($this->getRequest()->isPost()) {
            @session_start();
            if ($_SERVER['SERVER_NAME']=='localhost') {
                $_SESSION['login']='admin5';
                $this->_helper->redirector('index','a7-main');
            }
            $pwd = $this->getRequest()->getPost('pwd');
            $l4 = $this->getRequest()->getPost('keystring');
            if (isset($_SESSION['captcha_keystring']) && !is_null($pwd) && !is_null($l4)) {
            //$this->view->kr=$del;
                //$pwdsite=Zend_Registry::get('config');
                //var_dump($pwdsite); exit;
                $obj3=my7::qobjS("select * from et_cpusers where login='$1'", array('admin'));
                $pwdadm=$obj3->pwd;
                if ($l4==$_SESSION['captcha_keystring'] && $pwd==$pwdadm) {
                    $_SESSION['login']='admin5';
                    //gopage4('a7-main');
                    $this->_helper->redirector('index','a7-main');
                }
            }
            unset($_SESSION['captcha_keystring']);
        }
         $this->_helper->redirector('index');
         }
}

