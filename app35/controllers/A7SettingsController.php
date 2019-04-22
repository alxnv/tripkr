<?php
// Copyright (C) 2010 Воробьев Александр

// страничка с настройками отображения сайта, и ее сохранение

class A7SettingsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        my7::checkadm();
        $this->tbl=my7::getdbprefix().'settings';
    }

    public function indexAction()
    {
        // action body
        $this->view->row = my7::qobj("SELECT * from $this->tbl");
    }

    public function saveAction()
    {
        // action body
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $formData['every10minutesnummails']=intval($formData['every10minutesnummails']);
            $db=my7::db();
            $db->update($this->tbl,
                        $formData,
                        '');
//            echo 1; exit;
            my7::amessage('Данные сохранены');
        }
    }


}



