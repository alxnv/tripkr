<?php
/*
 * Контроллер редактирования для fpnum=1
 * flags(1-3)-турист,турагент,туроператор
 */
class Ta_QA_OneHtmlController extends My_Ta_QACommonController {

    public function getChildFpnum($uid = 0) {
        $fpnum=4;
        return $fpnum;
    }
    public function getCanDelete($uid = 0) {
        $candelete = 1;
        //if (in_array($uid,array(64,65,67,68))) $candelete=0;
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
    public function init() {
        parent::init();
        $this->imgdir=my3::basepath().'img2/';
        
    }

    public function indexAction() {

        if ($this->view->id) {
            $this->view->candelete = $this->getCanDelete($this->view->id);
            $this->view->canrename = $this->getCanRename($this->view->id);
            $this->view->canmakechild = $this->getCanMakeChild($this->view->id);
            $this->renderScript('ta/generic-tree-form/treeform.phtml');
        };
        //$this->view->sid=$id;
        $this->render('index');
    }

    public function deleteAction() {
        $id = intval($this->_getParam('id', 0));
        $itd=new My_itdbtree($this->treetbl);
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

            $to=new My_textop();
            $naim2=$to->mnogot(strip_tags($formData['html']),100);
            if ($id) {
                //$itd=new My_itdbtree($this->treetbl);
                //$idtree = intval($this->_getParam('idtree', 0));
                if (!$leftview) my3::amessage('Не все параметры указаны');
                //$itd->idtree=$idtree;
                my3::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$naim2,'actid'=>$this->actid);
                my3::db()->update($this->treetbl,$arr,"uid=$id");
                $arr=array('html'=>$formData['html'],'html2'=>$formData['html2']);
                my3::dbselreplace($id,$this->tbl,$arr);
                my3::qdirect('unlock tables');
                my3::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            } else {
                $itd=new My_itdbtree($this->treetbl);
                $idtree = intval($this->_getParam('idtree', 0));
                if (!$idtree || !$leftview) my3::amessage('Не все параметры указаны');
                $itd->idtree=$idtree;
                my3::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$naim2,'actid'=>$this->actid);
                $uid2=$itd->append(0,$arr);
                $fpnum2=$this->getNewFpnum();
                $arr=array('fpnum'=>$fpnum2,'html'=>$formData['html'],'html2'=>$formData['html2']);
                my3::dbselreplace($uid2,$this->tbl,$arr);
                my3::qdirect('unlock tables');
                my3::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            }
                }
            //my3::log('k',$formData);
            //my3::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my3::db()->quote($sid));
            //my3::amessage('Данные сохранены');
    }


}





?>
