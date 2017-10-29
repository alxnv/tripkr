<?php
// Copyright (C) 2010 Воробьев Александр

// редактирование статьи (по типу новости, но без даты)
// есть поля заголовок, краткое описание, подробное описание (хтмл),
//  также есть картинка ужимаемая до 75px по ширине
// кроме того, есть кнопка "Переместить" (в таблице поля topid,ordr)
// изображения сохраняются в директорию img2

class A7Article2Controller extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=10; // кол-во записей на страницу
        $this->sess='page89';
        $this->tbl=my7::getdbprefix().'article2';
        $this->imgdir=my7::basepath().'img2/';
        $this->topid=0;
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
        $this->view->row=my7::qobj("select uid,naim,html,anons,pict from $this->tbl where uid=$id");
        if ($this->view->row===false) $this->view->row=(object)array('uid'=>0,'naim'=>'',
                            'pict'=>'','html'=>'','anons'=>'');
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };
    }

    public function saveAction() {
        // сохраняем одну новость
        //require_once APPLICATION_PATH."/userobj/funiversal.php";
        //require_once APPLICATION_PATH."/userobj/dbtree_my.php";
        $id = intval($this->_getParam('id', 0));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $db=my7::db();
            //$dt=my7::dateconv($formData['data1']);
            $s3='';

            // обрабатываем изображение
            function crthumb($n,$fn1)
            // callback-функция обработки изображения
            {
                global $edit4;
                //$path1=my7::basepath().'img2/';
                //$in15=getimgnum().'.jpg';
                //resjpegrez3(120,80,2,$fn1,$in1) &&
                my7::resamplejpeg3(75,0,$fn1,$fn1);
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
            if ($s3<>'') my7::reterror(my7::nctrl().'/edit/id/'.$id,$s3);
                    //array('html'=>stripslashes($formData['html'])));
            $arr=array('anons'=>$formData['anons'],
                'naim'=>$formData['naim'],'html'=>$formData['html'],'pict'=>$afrombd[0]);
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
            $row=my7::qobj("select topid,ordr,pict from $this->tbl where uid=$id");
            if ($row!==false) {
                @unlink($this->imgdir.$row->pict);
                //my7::db()->delete($this->tbl,"uid=$id");
                $dbt=new My_Dbtree($this->tbl);
                $dbt->delete($id,0,$row->topid,$row->ordr);
            }
        }
        my7::goUrl(my7::nctrl());
    }


}
