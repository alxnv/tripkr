<?php
// Copyright (C) 2010 Воробьев Александр
/*
 * новости для туроператоров
 */


class A7NewstoController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=10; // кол-во записей на страницу
        $this->sess='page843';
        $this->tbl=my7::getdbprefix().'newsto';
        $this->rewrtbl=my7::getdbprefix().'rewrite';
    }

    public function indexAction() {
        // выводим список новостей в виде таблицы
        $pg1 = $this->_getParam('page', 0);
        $_SESSION[$this->sess]=intval($pg1);

        $page=$_SESSION[$this->sess];
        $row = my7::qobj("SELECT count(*) as cnt from $this->tbl");
        $nrows=$row->cnt;
        $page++;
        do {
            $page--;
            $lm=$page*$this->cnt;
            $this->view->arr=my7::qlist("SELECT *,date_format(data1,'%d.%m.%Y') as df FROM $this->tbl order by data1 desc limit $lm,$this->cnt");
            $nrowspage=count($this->view->arr);
        } while (($nrowspage==0) && ($page>0));
        
        $this->view->lend=array($nrows,$page,$nrowspage,$this->cnt,'a7-newsto/index/page/');
        // параметры paginator
        $_SESSION[$this->sess]=$page;


    }

    public function editAction() {
        // редактируем одну запись новости
        $db=my7::db();
        $id = intval($this->_getParam('id', 0));
        $this->view->id=$id;
        $this->view->row=my7::qobj("select uid,naim,html,anons,date_format(data1,'%d.%m.%Y') as data1 from $this->tbl where uid=$id");
        if ($this->view->row===false) $this->view->row=(object)array('uid'=>0,'naim'=>'',
                            'data1'=>date('d.m.Y'),'html'=>'','anons'=>'');
        $s=$db->quote('newsto/'.$id);
        $this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };
    }

    public function saveAction() {
        // сохраняем одну новость
        $id = intval($this->_getParam('id', 0));
        //if ($sid=='') $this->_redirect('a7-message/view/id/'.urlencode('Сохранение записи: нет записи с идентификатором '.$sid),array('prependBase'=>1));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('k',$formData);
            $db=my7::db();
            $dt=my7::dateconv($formData['data1']);
            $s3='';
            if ($dt===false) $s3.='Неправильная дата '.$formData['data1'];
            if ($s3<>'') my7::reterror('a7-newsto/edit/id/'.$id,$s3);
                    //array('html'=>stripslashes($formData['html'])));
            $arr=array('data1'=>$dt,'anons'=>$formData['anons'],
                'naim'=>$formData['naim'],'html'=>$formData['html']);
            if ($id) {
                $db->update($this->tbl,
                        $arr,
                        'uid='.$id);
                $s=$db->quote('newsto/'.$id);
                $kw=new My_Titlekw($this->rewrtbl,$s);
                $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
            } else {
                $db->insert($this->tbl,$arr);
                $id=$db->lastInsertId();
                $s=$db->quote('newsto/'.$id);
                $kw=new My_Titlekw($this->rewrtbl,$s);
                $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);

            }
            my7::goUrl('a7-newsto');
        };
    }

    public function rmoveAction() {
        // не используется
    }

    public function rdelAction() {
        // удаляем одну новость
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            my7::db()->delete($this->tbl,"uid=$id");
        }
        my7::goUrl('a7-newsto');
    }


}









