<?php
// без actid!!!!!!!!!!!!!!!
// Copyright (C) 2010 Воробьев Александр
// диспетчер отображения и данных для записей et_tree с actid=14
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

class Ta_HeditvideoController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */
        my3::checkadm();
        $this->tbl=my3::getdbprefix().'ta_html';
        $this->treetbl=my3::getdbprefix().'tree';
        $this->actid=1; // идентификатор текущего контроллера в дереве
        $this->imgdir=my3::basepath().'img2/';
        $this->rewrtbl=my3::getdbprefix().'rewrite';

        }

    public function getChildFpnum($uid = 0) {
        $fpnum=1;
        return $fpnum;
    }
    public function getCanDelete($uid = 0) {
        $candelete = 1;
        //if (in_array($uid,array(41,64,65,67,299))) $candelete=0;
        return $candelete;
    }
    public function getCanMakeChild($uid = 0) {
        $can = 0;
        //if (in_array($uid,array(64,65,67,68))) $can=0;
        return $can;
    }
    public function getCanRename($uid = 0) {
        $can = 1;
        return $can;
    }
    
    /*public function getControllerNameByFpnum($fpnum) {
        $cname='';
        if ($fpnum == 0) {
            $cname = 'Ta_Turop_Newrecord';
        }
        if ($fpnum == 1) {
            $cname = 'Ta_Tourop_Onehtml';
        }
        return $cname;
    }*/
    public function indexAction() {

        // если надо, отображаем форму
        $id = intval($this->_getParam('id', 0));
        $this->view->idtree=intval($this->_getParam('idtree', 0));
        $leftview = $this->_getParam('lv', '');
        /*my3::log('r',$leftview);
        my3::log('r2',$id);
        my3::log('r3',$this->view->idtree);*/
        if (!$id && (!$this->view->idtree || $leftview=='')) my3::amessage('Не все параметры указаны');
        $row=$id ? my3::qobj("select b.topid,b.naim,b.idtree,b.actid,a.* from $this->treetbl b
                left join $this->tbl a on b.uid=a.uid
                where b.uid=$id") : false;
        $this->view->id=$row ? $id : 0;
        $this->view->leftview=substr($leftview,0,200);
        $this->view->row=$row;
        
        /*$cname = $this->getControllerNameByFpnum($row->fpnum);
        if ($cname=='') my3::amessage('Ошибка настройки 3');
        $this->_forward('index', $cname);*/
        $db=my3::db();
        if ($this->view->id) {
            $id=$this->view->id;
            $this->view->candelete = $this->getCanDelete($this->view->id);
            $this->view->canrename = $this->getCanRename($this->view->id);
            $this->view->canmakechild = $this->getCanMakeChild($this->view->id);
            $this->renderScript('ta/generic-tree-form/treeform2.phtml');
        } else {
            $id=0;
        };
            //$this->view->sid=$id;
        $s=$db->quote('page/'.$id);
        //$this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        //$this->view->kw->getData();
        $this->render('index');
        
        }

    public function deleteAction() {
        $id = intval($this->_getParam('id', 0));
        $itd=new My_itdbtr($this->treetbl);
        my3::qdirect("lock tables $this->tbl write, $this->treetbl write");
        // удаляем элемент и все подэлементы в обоих таблицах
        $itd->deletein2tbl($id,$this->tbl);
        //$itd->delete($id,0);
        //my3::db()->delete($this->tbl,"uid=$id");
        my3::qdirect('unlock tables');

        my3::amessage('Запись удалена',1);
    }
    
    public function saveAction() {
        // action body
        global $my3;
        $id = intval($this->_getParam('id', 0));
        //if ($id=='') my3::amessage('Сохранение записи: нет записи с идентификатором '.$sid);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $db=my3::db();
            //$dt=my3::dateconv($formData['data1']);
            $s3='';
            $leftview = $this->_getParam('leftview', 0);

            // обрабатываем изображение
            function crthumb($n,$fn1)
            // callback-функция обработки изображения
            {
                global $edit4;
                //$path1=my3::basepath().'img2/';
                //$in15=getimgnum().'.jpg';
                //resjpegrez3(120,80,2,$fn1,$in1) &&
                my3::resamplejpeg3(150,0,$fn1,$fn1);
                //@unlink($fn1);
                //@rename($path1.$in15,$fn1);
            };

            $aimnums=array();
            $adel=array();
            for ($i=0;$i<1;$i++) {
                array_push($aimnums,my3::siteuniqid());
                array_push($adel,(isset($_POST['img_del'.($i+1)]) ? 1 : 0));
            };
            $aval2=array('jpg');
            $arfinfo=array(
                    array(1,$aval2,0,0,0,0)
            );
            if ($s3=='') {
                if ($id) {
                    $row=my3::qobj("select pict from $this->tbl where uid=$id");
                    $afrombd=array($row->pict);
                } else {
                    $afrombd=array('');
                };
                $achanged=array();
                $f3=new My_upload();
                $s3=$f3->move_upl(1,$this->imgdir,'',&$afrombd,$arfinfo,$adel,
                        $aimnums,&$achanged,'crthumb',0,0);
                //if ($in15=='' && $isedit1) $in15=$row->bigpict;
            };


            //if ($dt===false) $s3.='Неправильная дата '.$formData['data1'];
            if ($s3<>'') {
                my3::goUrl('a7-left/'.$leftview.'/updaterightsave/2');

                    //array('html'=>stripslashes($formData['html'])));
            //$arr=array('anons'=>$formData['anons'],
              //  'naim'=>$formData['naim'],'html'=>$formData['html'],'pict'=>$afrombd[0]);
            
                } else {
            
            $newtour=(isset($formData['newtour']) ? 1 : 0);        
            if ($id) {
                //$itd=new My_itdbtree($this->treetbl);
                //$idtree = intval($this->_getParam('idtree', 0));
                if (!$leftview) my3::amessage('Не все параметры указаны');
                //$itd->idtree=$idtree;
                my3::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$formData['naim'],'actid'=>$this->actid);
                my3::db()->update($this->treetbl,$arr,"uid=$id");
                $arr=array('html'=>$formData['html'],'html2'=>$formData['html2'],'pict'=>$afrombd[0]);
                my3::dbselreplace($id,$this->tbl,$arr);
                my3::qdirect('unlock tables');
                $s=$db->quote('page/'.$id);
                //$kw=new My_Titlekw($this->rewrtbl,$s);
                //$kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
                
                
                my3::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            } else {
                $itd=new My_itdbtr($this->treetbl);
                $idtree = intval($this->_getParam('idtree', 0));
                if (!$idtree || !$leftview) my3::amessage('Не все параметры указаны');
                $itd->idtree=$idtree;
                my3::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$formData['naim'],'actid'=>$this->actid);
                $uid2=$itd->append(0,$arr);
                $arr=array('html'=>$formData['html'],'html2'=>$formData['html2'],'pict'=>$afrombd[0]);
                my3::dbselreplace($uid2,$this->tbl,$arr);
                my3::qdirect('unlock tables');
                $s=$db->quote('page/'.$uid2);
                //$kw=new My_Titlekw($this->rewrtbl,$s);
                //$kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
                my3::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            }
                }
            //my3::log('k',$formData);
            //my3::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my3::db()->quote($sid));
            //my3::amessage('Данные сохранены');
        };
    }


        
}





