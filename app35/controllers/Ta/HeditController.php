<?php
// Copyright (C) 2010 Воробьев Александр

// редактирование одной записи БД pfx_ta_html визуальным редактором
// ключом записи является integer
// вызывается как  ta_hedit/index/id/5
// выводит форму редактирования имени записи в дереве и ее перемещения
// также редактирует имя элемента из дерева в своей форме редактирования

class Ta_HeditController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->tbl=my7::getdbprefix().'ta_html';
        $this->treetbl=my7::getdbprefix().'tree';
        $this->actid=1; // идентификатор текущего контроллера в дереве
    }

    public function indexAction() {

        // если надо, отображаем форму
        $id = intval($this->_getParam('id', 0));
        $this->view->idtree=intval($this->_getParam('idtree', 0));
        $leftview = $this->_getParam('lv', '');
        /*my7::log('r',$leftview);
        my7::log('r2',$id);
        my7::log('r3',$this->view->idtree);*/
        if (!$id && (!$this->view->idtree || $leftview=='')) my7::amessage('Не все параметры указаны');
        $row=$id ? my7::qobj("select b.naim,b.idtree,b.actid,a.* from $this->treetbl b
                left join $this->tbl a on b.uid=a.uid
                where b.uid=$id") : false;
        $this->view->id=$row ? $id : 0;
        $this->view->leftview=substr($leftview,0,200);
        $this->view->row=$row;
        if ($this->view->id) $this->renderScript('ta/generic-tree-form/treeform.phtml');
        //$this->view->sid=$id;
        $this->render('index');
    }

    public function deleteAction() {
        $id = intval($this->_getParam('id', 0));
        $itd=new My_itdbtree($this->treetbl);
        my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
        // удаляем элемент и все подэлементы в обоих таблицах
        $itd->deletein2tbl($id,$this->tbl);
        //$itd->delete($id,0);
        //my7::db()->delete($this->tbl,"uid=$id");
        my7::qdirect('unlock tables');

        my7::amessage('Запись удалена',1);
    }
    public function saveAction() {
        // action body
        global $my7;
        $id = intval($this->_getParam('id', 0));
        //if ($id=='') my7::amessage('Сохранение записи: нет записи с идентификатором '.$sid);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($id) {
                //$itd=new My_itdbtree($this->treetbl);
                //$idtree = intval($this->_getParam('idtree', 0));
                $leftview = $this->_getParam('leftview', 0);
                if (!$leftview) my7::amessage('Не все параметры указаны');
                //$itd->idtree=$idtree;
                my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$formData['naim'],'actid'=>$this->actid);
                my7::db()->update($this->treetbl,$arr,"uid=$id");
                $arr=array('uid'=>$id,'html'=>$formData['html']);
                my7::dbreplace($this->tbl,$arr);
                my7::qdirect('unlock tables');
                my7::goUrl('a7-left/'.$leftview);
            } else {
                $itd=new My_itdbtree($this->treetbl);
                $idtree = intval($this->_getParam('idtree', 0));
                $leftview = $this->_getParam('leftview', 0);
                if (!$idtree || !$leftview) my7::amessage('Не все параметры указаны');
                $itd->idtree=$idtree;
                my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$formData['naim'],'actid'=>$this->actid);
                $uid2=$itd->append(0,$arr);
                $arr=array('uid'=>$uid2,'html'=>$formData['html']);
                my7::dbreplace($this->tbl,$arr);
                my7::qdirect('unlock tables');
                my7::goUrl('a7-left/'.$leftview);
            }
            //my7::log('k',$formData);
            //my7::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my7::db()->quote($sid));
            //my7::amessage('Данные сохранены');
        };
    }


}





