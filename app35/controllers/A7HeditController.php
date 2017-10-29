<?php
// Copyright (C) 2010 Воробьев Александр

// редактирование одной записи БД pfx_html визуальным редактором
// вызывается a7-hedit/view/id/contacts

class A7HeditController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        my3::checkadm();
        $this->tbl=my3::getdbprefix().'html';
        $this->rewrtbl=my3::getdbprefix().'rewrite';
    }

    public function indexAction()
    {

        global $my3;

        $db=my3::db();
        $id = $this->_getParam('id', '');
        $this->view->sid=$id;
        $id2=$db->quote($id);
        //$pfx=$my3->getconfig()->config['production']['dbprefix'];
        $row=my3::qobj('select * from '.$this->tbl." where sid=$id2");
        //my3::log('k',$row);
        if ($row===false) my3::amessage('Редактирование записи: нет записи с идентификатором '.$id);

        $s=($id=='index' ? "''" : $db->quote('index/'.$id));
        $this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();
        $this->view->row=$row;
    }

    public function saveAction()
    {
        // action body
        global $my3;
        $db=my3::db();
        $sid = $this->_getParam('id', '');
        if ($sid=='') my3::amessage('Сохранение записи: нет записи с идентификатором '.$sid);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my3::log('k',$formData);
            
            my3::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my3::db()->quote($sid));
            $s=($sid=='index' ? "''" : $db->quote('index/'.$sid));
            $kw=new My_Titlekw($this->rewrtbl,$s);
            $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
            my3::amessage('Данные сохранены');
        };
    }


}





