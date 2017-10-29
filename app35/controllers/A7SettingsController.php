<?php
// Copyright (C) 2010 Воробьев Александр

// страничка с настройками отображения сайта, и ее сохранение

class A7SettingsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        my3::checkadm();
        $this->tbl=my3::getdbprefix().'settings';
    }

    public function indexAction()
    {
        // action body
        $this->view->row = my3::qobj("SELECT * from $this->tbl");
    }

    public function saveAction()
    {
        // action body
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $db=my3::db();
            $db->update($this->tbl,
                        $formData,
                        '');
            my3::amessage('Данные сохранены');
        }
    }


}



