<?php
/*
 * Контроллер редактирования для fpnum=1
 */
class Ta_C1_OneHtmlController extends My_Ta_HEdit1CommonController {

    public function getChildFpnum($uid = 0) {
        $fpnum=1;
        return $fpnum;
    }
    public function getCanDelete($uid = 0) {
        $candelete = 1;
        if (in_array($uid,array(41,64,65,67,68,299))) $candelete=0;
        return $candelete;
    }
    public function getCanMakeChild($uid = 0) {
        $can = 1;
        if (in_array($uid,array(64,65,67,68))) $can=0;
        return $can;
    }
    public function getCanRename($uid = 0) {
        $can = 1;
        return $can;
    }
    public function init() {
        parent::init();
        $this->imgdir=my7::basepath().'img2/';
        $this->rewrtbl=my7::getdbprefix().'rewrite';
        
    }

    public function indexAction() {

        $db=my7::db();
        if ($this->view->id) {
            $id=$this->view->id;
            $this->view->candelete = $this->getCanDelete($this->view->id);
            $this->view->canrename = $this->getCanRename($this->view->id);
            $this->view->canmakechild = $this->getCanMakeChild($this->view->id);
            $this->renderScript('ta/generic-tree-form/treeform.phtml');
        } else {
            $id=0;
        };
            //$this->view->sid=$id;
        $s=$db->quote('page/'.$id);
        $this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();
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
            $db=my7::db();
            //$dt=my7::dateconv($formData['data1']);
            $s3='';
            $leftview = $this->_getParam('leftview', 0);

            // обрабатываем изображение
            function crthumb($n,$fn1)
            // callback-функция обработки изображения
            {
                global $edit4;
                //$path1=my7::basepath().'img2/';
                //$in15=getimgnum().'.jpg';
                //resjpegrez3(120,80,2,$fn1,$in1) &&
                my7::resamplejpeg3(150,0,$fn1,$fn1);
                //@unlink($fn1);
                //@rename($path1.$in15,$fn1);
            };

            $aimnums=array();
            $adel=array();
            for ($i=0;$i<1;$i++) {
                array_push($aimnums,my7::siteuniqid());
                array_push($adel,(isset($_POST['img_del'.($i+1)]) ? 1 : 0));
            };
            $aval2=array('jpg');
            $arfinfo=array(
                    array(1,$aval2,0,0,0,0)
            );
            if ($s3=='') {
                if ($id) {
                    $row=my7::qobj("select pict from $this->tbl where uid=$id");
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
                my7::goUrl('a7-left/'.$leftview.'/updaterightsave/2');

                    //array('html'=>stripslashes($formData['html'])));
            //$arr=array('anons'=>$formData['anons'],
              //  'naim'=>$formData['naim'],'html'=>$formData['html'],'pict'=>$afrombd[0]);
            
                } else {
            
            if ($id) {
                //$itd=new My_itdbtree($this->treetbl);
                //$idtree = intval($this->_getParam('idtree', 0));
                if (!$leftview) my7::amessage('Не все параметры указаны');
                //$itd->idtree=$idtree;
                my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$formData['naim'],'actid'=>$this->actid);
                my7::db()->update($this->treetbl,$arr,"uid=$id");
                $arr=array('html'=>$formData['html'],'pict'=>$afrombd[0]);
                my7::dbselreplace($id,$this->tbl,$arr);
                my7::qdirect('unlock tables');
                $s=$db->quote('page/'.$id);
                $kw=new My_Titlekw($this->rewrtbl,$s);
                $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
                my7::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            } else {
                $itd=new My_itdbtree($this->treetbl);
                $idtree = intval($this->_getParam('idtree', 0));
                if (!$idtree || !$leftview) my7::amessage('Не все параметры указаны');
                $itd->idtree=$idtree;
                my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$formData['naim'],'actid'=>$this->actid);
                $uid2=$itd->append(0,$arr);
                $arr=array('html'=>$formData['html'],'pict'=>$afrombd[0]);
                my7::dbselreplace($uid2,$this->tbl,$arr);
                my7::qdirect('unlock tables');
                $s=$db->quote('page/'.$uid2);
                $kw=new My_Titlekw($this->rewrtbl,$s);
                $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
                my7::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            }
                }
            //my7::log('k',$formData);
            //my7::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my7::db()->quote($sid));
            //my7::amessage('Данные сохранены');
        };
    }


}





?>
