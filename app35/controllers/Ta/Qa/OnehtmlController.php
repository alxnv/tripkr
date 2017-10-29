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
        $this->imgdir=my7::basepath().'img2/';
        
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

            $to=new My_textop();
            $naim2=$to->mnogot(strip_tags($formData['html']),100);
            if ($id) {
                //$itd=new My_itdbtree($this->treetbl);
                //$idtree = intval($this->_getParam('idtree', 0));
                if (!$leftview) my7::amessage('Не все параметры указаны');
                //$itd->idtree=$idtree;
                my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$naim2,'actid'=>$this->actid);
                my7::db()->update($this->treetbl,$arr,"uid=$id");
                $arr=array('html'=>$formData['html'],'html2'=>$formData['html2']);
                my7::dbselreplace($id,$this->tbl,$arr);
                my7::qdirect('unlock tables');
                my7::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            } else {
                $itd=new My_itdbtree($this->treetbl);
                $idtree = intval($this->_getParam('idtree', 0));
                if (!$idtree || !$leftview) my7::amessage('Не все параметры указаны');
                $itd->idtree=$idtree;
                my7::qdirect("lock tables $this->tbl write, $this->treetbl write");
                $arr=array('naim'=>$naim2,'actid'=>$this->actid);
                $uid2=$itd->append(0,$arr);
                $fpnum2=$this->getNewFpnum();
                $arr=array('fpnum'=>$fpnum2,'html'=>$formData['html'],'html2'=>$formData['html2']);
                my7::dbselreplace($uid2,$this->tbl,$arr);
                my7::qdirect('unlock tables');
                my7::goUrl('a7-left/'.$leftview.'/updaterightsave/1');
            }
                }
            //my7::log('k',$formData);
            //my7::db()->update($this->tbl,array('html'=>$formData['html']),'sid='.my7::db()->quote($sid));
            //my7::amessage('Данные сохранены');
    }


}





?>
