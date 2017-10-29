<?php
class Ta_C2_QuestionsController extends My_Ta_HEdit2CommonController {

    public function editAction() {
        // редактируем одну запись
        $id = intval($this->_getParam('id', 0));
        $this->view->id=$id;
        $this->view->row=my3::qobj("select a.uid,a.html,a.html2,b.naim from $this->tbl a,$this->treetbl b
                    where a.uid=$id and b.uid=$id");
        if ($this->view->row===false) $this->view->row=(object)array('uid'=>0,'html'=>'',
                            'pict'=>'','html2'=>'','anons'=>'');
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };
    }

    public function rmoveAction() {
        $id = intval($this->_getParam('id', 0));
        $pos = intval($this->_getParam('pos', 0));
        if ($id && $pos) {
            $dbt=new My_Dbtree($this->treetbl);
            $dbt->move($id,$pos);
            //$db=my3::db();
            $row=my3::qobj("select topid,ordr from $this->treetbl where uid=$id");
            if ($row!==false) {
                my3::goUrl('ta_hedit2/index/id/'.$row->topid.'/lv/tours/acc/2');
            }
        }
        //my3::goUrl(my3::nctrl());
    }

    public function saveAction() {
        $id = intval($this->_getParam('id', 0));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $db=my3::db();
            $arr=array('html'=>$formData['html'],
                'html2'=>$formData['html2']);
            if ($id) {
                $db->update($this->tbl,
                        $arr,
                        'uid='.$id);
                $row=my3::qobj("select topid,ordr from $this->treetbl where uid=$id");
                if ($row!==false) {
                    my3::goUrl('ta_hedit2/index/id/'.$row->topid.'/lv/tours/acc/2');
                }
            /*} else {

                $dbt=new My_Dbtree($this->tbl);
                $dbt->append($this->topid,$arr);*/

            }
        }
    }
    public function rdelAction() {
        // удаляем одну новость
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            $row=my3::qobj("select topid,ordr from $this->treetbl where uid=$id");
            if ($row!==false) {
                //@unlink($this->imgdir.$row->pict);
                //my3::db()->delete($this->tbl,"uid=$id");
                $dbt=new My_Dbtree($this->treetbl);
                $dbt->delete($id,0,$row->topid,$row->ordr);
                my3::qdirect("delete from $this->tbl where uid=$id");
                my3::goUrl('ta_hedit2/index/id/'.$row->topid.'/lv/tours/acc/2');
            }
        }
        //my3::goUrl(my3::nctrl());
    }
}
?>
