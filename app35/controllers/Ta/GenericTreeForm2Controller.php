<?php
/*
Copyright (C) 2010 Александр Воробьев
 * обрабатывает запросы на перемещение, удаление записей, создание подразделов
 * может быть ситуация что в дереве есть элемент, а в соотв. таблице контента эл-та нет
 *  такая ситуация может создаться при создании подразделов
 * 
 * !!!!!!!! для каждого проекта нужно настроить getControllerByActid()
 */
class Ta_genericTreeForm2Controller extends Zend_Controller_Action {

    public function getControllerByActid($actid) {
        $ct = '';
        switch ($actid) {
            case 1:
                $ct = 'Ta_Hedit1Controller';
        }
        return $ct;
        
    }
    public function init() {
        /* Initialize action controller here */
        my3::checkadm();
        $this->tbl=my3::getdbprefix().'tree';
        $this->tbl2=my3::getdbprefix().'ta_html';
    }

    public function moveAction() {
        // перемещает запись в дереве
        $itd=new My_itdbtree($this->tbl);
        $id = intval($this->_getParam('id', 0));
        $pos = intval($this->_getParam('pos', 0));
        $leftview = $this->_getParam('leftview', 0);
        if (!$id || !$leftview) my3::amessage('Не все параметры указаны');
        $itd->move($id,$pos);
        my3::goUrl('a7-left/'.$leftview);
    }

    public function makesubAction() {
        // создать подраздел
        $itd=new My_itdbtree($this->tbl);
        $id = intval($this->_getParam('id', 0));
        $actid = 0; // intval($this->_getParam('actid', 0));
        $leftview = $this->_getParam('leftview', 0);
        $zgl = $this->_getParam('zgl', 0);
        if (!$id || !$leftview || !$zgl) my3::amessage('Не все параметры указаны');
        
        /*// получаем fpnum создаваемого элемента
        $ct = $this->getControllerByActid($actid);
        if ($ct == '')  my3::amessage('Ошибка - настройте администраторский модуль');
        include str_replace('_','/',$ct).".php";
        $obj = new $ct($this->getRequest(), $this->getResponse());
        $row=my3::qobj("select fpnum from $obj->tbl where uid=$id");
        if (!$row || $row->fpnum==0)  my3::amessage('Ошибка структуры данных');
        $fpnum=$row->fpnum;
        $sf = $obj->getControllerNameByFpnum($fpnum);
        if ($sf=='')  my3::amessage('Ошибка структуры данных 4');
        $sf2=$sf.'Controller';
        include str_replace('_','/',$sf2).'.php';
        $objc = new $sf2($this->getRequest(), $this->getResponse());
        $cfpnum = $objc->getChildFpnum($id);
        if ($cfpnum==0)  my3::amessage('Ошибка структуры данных 2');
        */
        my3::qdirect("lock tables $this->tbl write, $this->tbl2 write");
        $row=my3::qobj("select idtree from $this->tbl where uid=$id");
        if ($row) {
            $itd->idtree=$row->idtree;
            $tp=new My_textop();
            $arr=array('naim'=>$tp->myunescape($zgl),'actid'=>$actid);
            $idnew=$itd->append($id,$arr);
            my3::qdirect("insert into $this->tbl2 (uid) values ($idnew)");
        };
        my3::qdirect('unlock tables');
        my3::goUrl('a7-left/'.$leftview);

    }
    /*public function indexAction() {

        // если надо, отображаем форму
        /*$id = intval($this->_getParam('id', 0));
        $row=$id ? my3::qobj("select a.*,b.naim from $this->tbl a, $this->treetbl b
                where a.uid=$id and a.uid=b.uid") : false;
        $this->view->id=$row ? $id : 0;
        $this->view->naim=$row ? $row->naim : '';
        $this->renderScript('ta/generic-tree-form/treeform.phtml');
        if ($row!==false) {
            $this->view->sid=$id;
            $this->view->row=$row;
            $this->render('index');
        }
    }*/

    /*public function saveAction() {
        // action body
        /*$id = intval($this->_getParam('id', 0));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($id) {
                
            } else {

            }
            my3::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my3::db()->quote($sid));
            my3::amessage('Данные сохранены');
        };
    }*/


}
?>
