<?php
// Copyright (C) 2010 Воробьев Александр

// редактирование одной записи БД pfx_html визуальным редактором
// вызывается a7-hedit/view/id/contacts

class A7HeditFilesController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->tbl=my7::getdbprefix().'withfiles';
        $this->imgdir=my7::basepath().'docs/';
    }

    public function indexAction() {

        global $my7;

        $db=my7::db();
        $id = intval($this->_getParam('id', ''));
        $this->view->id=$id;
        //$id2=$db->quote($id);
        $row=my7::qobj('select * from '.$this->tbl." where uid=$id");
        //my7::log('k',$row);
        if ($row===false) my7::amessage('Редактирование записи: нет записи с идентификатором '.$id);

        $this->view->row=$row;
    }

    public function saveAction() {
        // action body
        $id = intval($this->_getParam('id', ''));
        if ($id==0) my7::amessage('Сохранение записи: нет записи с идентификатором '.$id);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('r',$formData);
            //my7::log('k',$formData);
            $f3=new My_upload();
            $aimnums=array();
            $adel=array();
            $afrombd=array();
            /*for ($i=0;$i<1;$i++) {
                array_push($aimnums,my7::siteuniqid());
                array_push($adel,(isset($_POST['img_del'.($i+1)]) ? 1 : 0));
            };
            $aval2=array('jpg');
            $arfinfo=array(
                    array(1,$aval2,0,0,0,0)
            );*/
            if ($s3=='') {
                if ($id) {
                    $row=my7::qobj("select files from $this->tbl where uid=$id");
                    $rf2=$row->files;
                    //$afrombd=array($row->pict);
                } else {
                    $rf2='';
                };
                $sfl=$f3->preprocess1($formData,'userfile','idl',$rf2,
                        &$aimnums,&$adel,&$afrombd);
                my7::log('e',$sfl);
                my7::log('afr',$afrombd);
                $aval2=array('doc','docx','xls','rar','zip');
                $arfinfo=array_fill(0,$f3->nfiles,
                        array(0,$aval2,0,0,0,0));

                $achanged=array();
                $s3=$f3->move_upl($f3->nfiles,$this->imgdir,'',&$afrombd,$arfinfo,$adel,
                        $aimnums,&$achanged,'',0,0);
                //if ($in15=='' && $isedit1) $in15=$row->bigpict;
            };


            //if ($dt===false) $s3.='Неправильная дата '.$formData['data1'];
            if ($s3<>'') my7::reterror(my7::nctrl().'/index/id/'.$id,$s3);
            my7::db()->update($this->tbl,array('html'=>$formData['html'],'files'=>$sfl),
                    'uid='.$id);
            my7::amessage('Данные сохранены');
        };
    }


}





