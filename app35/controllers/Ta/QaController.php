<?php
// Copyright (C) 2010 Воробьев Александр
// вопросы - ответы
// диспетчер отображения и данных для записей et_tree с actid=4
// 
// !!!!!!!!!!!!!!! при создании нового дерева (с новым idtree) добавить в et_tree_actnames actid 
//  его элементов
// 
// если fpnum=0 то это вызывается при добавлении новой записи верхнего уровня
// ver 2.0 - добавлено появление/скрытие кнопок удалить, добавить подраздел 
//    в зависимости от id, topid, fpnum 
// перенаправляет  для редактированиия в зависимости от topid, fpnum на Ta_C1_XXX

// редактирование одной записи БД pfx_ta_html и pfx_tree визуальным редактором
// ключом записи является integer
// вызывается как  ta_hedit/index/id/5
// выводит форму редактирования имени записи в дереве и ее перемещения
// также редактирует имя элемента из дерева в своей форме редактирования

class Ta_QaController extends My_Ta_QACommonController {

    public function getControllerNameByFpnum($fpnum) {
        $cname='';
        if ($fpnum == 0) {
            $cname = 'Ta_Qa_Newrecord';
        }
        if ($fpnum == 4) {
            $cname = 'Ta_Qa_Onehtml';
        }
        return $cname;
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
                where b.uid=$id") 
                : (object)array('fpnum'=>0);
        
        $this->view->id=$row ? $id : 0;
        $this->view->leftview=substr($leftview,0,200);
        $this->view->row=$row;
        
        if ($id) {
            $fpnum3=$row->fpnum;
        } else {
            $fpnum3=$this->getNewFpnum(); // если добавление записи, то смотрим  какой fpnum
            $this->view->row=(object)array('naim'=>'','flags'=>0,'pict'=>'','html'=>'','html2'=>'');
        }
        $cname = $this->getControllerNameByFpnum($fpnum3);
        if ($cname=='') my7::amessage('Ошибка настройки 3');
        $this->_forward('index', $cname);
        }
}





