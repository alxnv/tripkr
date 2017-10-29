<?php
// Copyright (C) 2010 Воробьев Александр

// редактирование одной записи БД pfx_html визуальным редактором
// вызывается a7-hedit/view/id/contacts

class A7HeditController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        my7::checkadm();
        $this->tbl=my7::getdbprefix().'html';
        $this->rewrtbl=my7::getdbprefix().'rewrite';
    }

    public function indexAction()
    {

        global $my7;

        $db=my7::db();
        $id = $this->_getParam('id', '');
        $this->view->sid=$id;
        $id2=$db->quote($id);
        //$pfx=$my7->getconfig()->config['production']['dbprefix'];
        $row=my7::qobj('select * from '.$this->tbl." where sid=$id2");
        //my7::log('k',$row);
        if ($row===false) my7::amessage('Редактирование записи: нет записи с идентификатором '.$id);

        $s=($id=='index' ? "''" : $db->quote('index/'.$id));
        $this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();
        $this->view->row=$row;
    }

    public function saveAction()
    {
        // action body
        global $my7;
        $db=my7::db();
        $sid = $this->_getParam('id', '');
        if ($sid=='') my7::amessage('Сохранение записи: нет записи с идентификатором '.$sid);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('k',$formData);
            
            my7::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my7::db()->quote($sid));
            $s=($sid=='index' ? "''" : $db->quote('index/'.$sid));
            $kw=new My_Titlekw($this->rewrtbl,$s);
            $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
            my7::amessage('Данные сохранены');
        };
    }


}





