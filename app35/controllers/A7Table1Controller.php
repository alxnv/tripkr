<?php
// Copyright (C) 2010 Воробьев Александр

// редактирование списка записей по n текстовых полей без валидации, кнопка "Переместить"
//  все текстовые поля сериализуются в одно поле
// есть поля заголовок, краткое описание, подробное описание (хтмл),
//  также есть картинка ужимаемая до 75px по ширине
// кроме того, есть кнопка "Переместить" (в таблице поля topid,ordr)
// изображения сохраняются в директорию img2

class A7Table1Controller extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=10; // кол-во записей на страницу
        $this->sess='page879';
        $this->tbl=my7::getdbprefix().'tabl';
        //$this->imgdir=my7::basepath().'img2/';
        $this->topid=0;
        $this->nflds=7;
    }

    public function indexAction() {
        // выводим список новостей в виде таблицы
        $pg1 = $this->_getParam('page', 0);
        $_SESSION[$this->sess]=intval($pg1);

        $page=$_SESSION[$this->sess];
        $row = my7::qobj("SELECT count(*) as cnt from $this->tbl where topid=$this->topid");
        $nrows=$row->cnt;
        $page++;
        do {
            $page--;
            $lm=$page*$this->cnt;
            $this->view->arr=my7::qlist("SELECT * FROM $this->tbl where topid=$this->topid order by ordr limit $lm,$this->cnt");
            $nrowspage=count($this->view->arr);
        } while (($nrowspage==0) && ($page>0));

        $this->view->lend=array($nrows,$page,$nrowspage,$this->cnt,my7::nctrl().'/index/page/');
        // параметры paginator
        $_SESSION[$this->sess]=$page;


    }

    public function editAction() {
        // редактируем одну запись новости
        $id = intval($this->_getParam('id', 0));
        $this->view->id=$id;
        $this->view->row=my7::qobj("select * from $this->tbl where uid=$id");
        if ($this->view->row===false) {
            $this->view->row=(object)array('uid'=>0,'naim'=>'',
                            'pict'=>'','html'=>'','anons'=>'');
            $this->view->items=array();
            $ob23=new My_arrop();
            $ob23->expandarr('s', $this->nflds, $this->view->items);
        } else {
        $this->view->items=unserialize($this->view->row->s_srz);
        }
        $this->view->items=(object)$this->view->items;

        // not exec in this controller
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };
        // end not exec in this controller
    }

    public function saveAction() {
        // сохраняем одну новость
        $id = intval($this->_getParam('id', 0));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $db=my7::db();
            $s3='';
            $ao=new My_arrop();
            $ar3=$ao->nwithpfx('s',$this->nflds);
            $arr=array('s_srz'=>serialize($ao->getvalues($formData,$ar3)));

            if ($id) {
                $db->update($this->tbl,
                        $arr,
                        'uid='.$id);
            } else {

                $dbt=new My_Dbtree($this->tbl);
                $dbt->append($this->topid,$arr);

            }
            my7::goUrl(my7::nctrl());
        };
    }

    public function rmoveAction() {
        // не используется
        $id = intval($this->_getParam('id', 0));
        $pos = intval($this->_getParam('pos', 0));
        if ($id && $pos) {
            $dbt=new My_Dbtree($this->tbl);
            $dbt->move($id,$pos);
        }
        my7::goUrl(my7::nctrl());
    }

    public function rdelAction() {
        // удаляем одну новость
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            $row=my7::qobj("select topid,ordr from $this->tbl where uid=$id");
            if ($row!==false) {
                //@unlink($this->imgdir.$row->pict);
                //my7::db()->delete($this->tbl,"uid=$id");
                $dbt=new My_Dbtree($this->tbl);
                $dbt->delete($id,0,$row->topid,$row->ordr);
            }
        }
        my7::goUrl(my7::nctrl());
    }


}
