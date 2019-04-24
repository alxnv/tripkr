<?php
// Copyright (C) 2019 Воробьев Александр

// страничка с настройками отображения сайта, и ее сохранение

class A7TracemailController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=10; // кол-во записей на страницу
        $this->sess='page8125';
        $this->tbl=my7::getdbprefix().'tracemailsend';
    }

    public function indexAction()
    {
        // action body
        $pg1 = $this->_getParam('page', 0);
        $_SESSION[$this->sess]=intval($pg1);

        $page=$_SESSION[$this->sess];
        $row = my7::qobj("SELECT count(*) as cnt from $this->tbl");
        $nrows=$row->cnt;
        $page++;
        do {
            $page--;
            $lm=$page*$this->cnt;
            $this->view->arr=my7::qlist("SELECT * FROM $this->tbl
                order by uid desc limit $lm,$this->cnt");
            $nrowspage=count($this->view->arr);
        } while (($nrowspage==0) && ($page>0));
        
        $this->view->psort= ($nrows-$page*$this->cnt+$nrowspage-$this->cnt); // порядковый номер от концы списка
        if ($nrowspage<$this->cnt) $this->view->psort=$nrowspage; // если последняя страница
        $this->view->lend=array($nrows,$page,$nrowspage,$this->cnt,'a7-tracemail/index/page/');
        // параметры paginator
        $_SESSION[$this->sess]=$page;
        
    }

    
         public function rdelAction() {
        // удаляем один trace mail
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            my7::db()->delete($this->tbl,"uid=$id");
            }
        my7::goUrl('a7-tracemail');
    }
}