<?php
// Copyright (C) 2010 Воробьев Александр
// левый фрейм

class A7LeftController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        if (my7::notadmin()) $this->_redirect('tools/helpers/noframe.htm',array('prependBase'=>1));
        unset($_SESSION['viewsite583']);
        $this->view->bodyclass='bodyleft';

    }

    public function logoutAction() {
        // action body
        unset($_SESSION['login']);
        $this->_helper->redirector('index');
    }
    public function indexAction() {
        // action body
        $this->view->updateright = intval($this->_getParam('updright', 0));

    }
    public function mainmenuAction() {
        // action body
    }
    public function treedopmenuAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function videoAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function touropmenuAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function gostcorAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function confAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function heditconfAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function qaAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function toursAction() {
        // action body
/*
 *если в командной строке указано updaterightsave/1 
 *  то вызываем в правом фрейме функцию js updatesave5
 */

        $this->view->updaterightsave = intval($this->_getParam('updaterightsave', 0));
    }
    public function treepubAction() {
        
    }
    public function pagesetupAction() {
        // action body
    }


}

