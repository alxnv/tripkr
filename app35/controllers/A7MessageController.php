<?php
// Copyright (C) 2010 Воробьев Александр

//вызывается как a7-message/view/id/hkqp
// может также присутствовать параметр updleft==1 - обновить левый фрейм

class A7MessageController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        if (my7::notadmin()) $this->_redirect('tools/helpers/noframe.htm',array('prependBase'=>1));
    }

    public function indexAction()
    {
        // action body
    }

    public function viewAction()
    {
        // action body
        $msg = $this->_getParam('id', '');
        $this->view->updleft = $this->_getParam('updleft', '');
        $this->view->alignleft = $this->_getParam('alignleft', 0);
        $this->view->msg=$msg;
        //$this->view->pr=$this->_getAllParams();
    }


}