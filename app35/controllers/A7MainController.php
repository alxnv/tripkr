<?php
// Copyright (C) 2010 Воробьев Александр


class A7MainController extends Zend_Controller_Action
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
public function preDispatch() {
        $this->_helper->layout->setLayout('mainframe');
    }

}

