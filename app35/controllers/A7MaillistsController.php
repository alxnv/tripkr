<?php
// Copyright (C) 2019 Воробьев Александр


class A7MaillistsController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=20; // кол-во записей на страницу
        $this->sess='page8139';
        $this->tbl=my7::getdbprefix().'maillists';
        $this->tbldata=my7::getdbprefix().'dbmailexternal';
        //$this->rewrtbl=my7::getdbprefix().'rewrite';
    }

    public function preparecounters() { // сделать запросы к базе данных по количеству 
              // различных данных в бд
        $arr=my7::qlist("select idmaillist,count(*) as cnt from $this->tbldata group by idmaillist");
        $aarr=array();
        for ($i=0;$i<count($arr);$i++) {
            $aarr[intval($arr[$i]->idmaillist)]=intval($arr[$i]->cnt);
        }
        $ar2=my7::qlist("select idmaillist,count(*) as cnt from $this->tbldata where tosendmail=1 and mailsent=0 group by idmaillist");
        $aar2=array();
        for ($i=0;$i<count($ar2);$i++) {
            $aar2[intval($ar2[$i]->idmaillist)]=intval($ar2[$i]->cnt);
        }
        $ar3=my7::qlist("select idmaillist,count(*) as cnt from $this->tbldata where error_sent<>'' group by idmaillist");
        $aar3=array();
        for ($i=0;$i<count($ar3);$i++) {
            $aar3[intval($ar3[$i]->idmaillist)]=intval($ar3[$i]->cnt);
        }
        $ar4=my7::qlist("select idmaillist,count(*) as cnt from $this->tbldata where mailsent=1 group by idmaillist");
        $aar4=array();
        for ($i=0;$i<count($ar4);$i++) {
            $aar4[intval($ar4[$i]->idmaillist)]=intval($ar4[$i]->cnt);
        }
        return array('all'=>$aarr, 'tosend'=>$aar2,'witherror'=>$aar3,'sent'=>$aar4);
    }
    
    public function whencompletemailsend($pr,$every10minutes) { 
        // выполняется ли сейчас отправка почты и когда она завершится
        if (intval($every10minutes)<=0) {
            $num=0;
        } else {
            $arr=my7::qlist("select uid from $this->tbl where tosendmail=1");
            // определяем количество почты которую остается отправить
            $num=0;
            for ($i=0;$i<count($arr);$i++) {
                if (isset($pr['tosend'][intval($arr[$i]->uid)])) 
                    $num+=$pr['tosend'][intval($arr[$i]->uid)];
            }
        }
        return $num;
    }
    public function indexAction() {
        // выводим список туроператоров в виде таблицы
        $pg1 = $this->_getParam('page', 0);
        $_SESSION[$this->sess]=intval($pg1);

        // получаем настройки сайта
        $so=new My_sitedbops();
        $this->view->settings=$so->getsettings();
        
        $this->view->pr=$this->preparecounters(); // сделать запросы к базе данных по количеству 
              // различных данных в бд
        $this->view->compl=$this->whencompletemailsend($this->view->pr,$this->view->settings->every10minutesnummails); 
          // выполняется ли сейчас отправка почты и когда она завершится
        //var_dump($pr);
        $page=$_SESSION[$this->sess];
        $row = my7::qobj("SELECT count(*) as cnt from $this->tbl");
        $nrows=$row->cnt;
        $page++;
        do {
            $page--;
            $lm=$page*$this->cnt;
            $this->view->arr=my7::qlist("SELECT * FROM $this->tbl
                order by ordr limit $lm,$this->cnt");
            $nrowspage=count($this->view->arr);
        } while (($nrowspage==0) && ($page>0));
        
        $this->view->psort= ($nrows-$page*$this->cnt+$nrowspage-$this->cnt); // порядковый номер от концы списка
        if ($nrowspage<$this->cnt) $this->view->psort=$nrowspage; // если последняя страница
        $this->view->lend=array($nrows,$page,$nrowspage,$this->cnt,'a7-zayav/index/page/');
        // параметры paginator
        $_SESSION[$this->sess]=$page;
        

    }

    public function moveAction() {
        // перемещает запись в дереве
        $itd=new My_Dbtree($this->tbl);
        $id = intval($this->_getParam('id', 0));
        $pos = intval($this->_getParam('pos', 0));
        //$leftview = $this->_getParam('leftview', 0);
        if (!$id) my7::amessage('Не все параметры указаны');
        $itd->move_no_topid($id,$pos);
        my7::goUrl('a7-maillists');
    }
    
     public function editAction() {
        // редактируем одну запись
        $db=my7::db();
        $id = intval($this->_getParam('id', 0));
        $this->view->id=$id;
        $this->view->maillists=my7::qarray("select uid,name from $this->tbl where uid<>$id order by name");
        $this->view->row=my7::qobj("select * from $this->tbl where uid=$id");
        if ($this->view->row===false) $this->view->row=(object)array('uid'=>0,'name'=>'',
                            'html'=>'', 'mask'=>'%n', 'tosendmail'=>0);
        /*$s=$db->quote('news/'.$id);
        $this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };*/
    }
    
    /**
     * читает xls или xlsx файл в список рассылки с указанным номером
     *  читает $_FILE['xlfile'] если он загружен
     * @param integer $idlist - номер списка рассылки
     * 
     */
    protected function excelLoad($idlist) {
    /** Include path **/

        var_dump($_FILES['xlfile']['tmp_name']);
        //exit;
        if ($_FILES['xlfile']['tmp_name']<>'') {
            $db=my7::db();
            set_include_path(get_include_path() . PATH_SEPARATOR . my7::basePath().'PHPExcel-1.8/Classes/');

            /** PHPExcel_IOFactory */
            include 'PHPExcel/IOFactory.php';


            $inputFileName = $_FILES['xlfile']['tmp_name'];
            //echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


            //echo '<hr />';

            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //var_dump($sheetData);

            $i=1;
            $arr=array();
            while (isset($sheetData[$i])) {
                $ar2=$sheetData[$i];
                $email=(isset($ar2['A']) ? $ar2['A'] : '');
                if (is_null($email)) $email='';
                $contacts=(isset($ar2['B']) ? $ar2['B'] : '');
                if (is_null($contacts)) $contacts='';
                $firm=(isset($ar2['C']) ? $ar2['C'] : '');
                if (is_null($firm)) $firm='';
                if ($email<>'' && $contacts<>'') {
                    $email=$db->quote($email);
                    $contacts=$db->quote($contacts);
                    $firm=$db->quote($firm);
                    array_push($arr, "($idlist,$email,1,$contacts,$firm,0,'')");
                }
                $i++;
            }
            $s=join(', ',$arr);
            $s2="replace into $this->tbldata (idmaillist,email,tosendmail,name,"
                    . "company,mailsent,error_sent) values $s";
            //var_dump($s2);
            my7::qdirect($s2);
            //exit;
        }
    }
 
    /**
     * Импорт данных из одного списка рассылки в другой
     * @param integer $lfrom - идентификатор списка рассылки из которого импортировать
     * @param integer $lto - идентификатор списка рассылки в который импортировать
     */
    public function importfrommaillist($lfrom,$lto) {
        $arr=my7::qlist("select * from $this->tbldata where idmaillist=$lfrom");
        $ar2=array();
        $db=my7::db();
        for ($i=0;$i<count($arr);$i++) {
            $obj=$arr[$i];
            $email=$db->quote($obj->email);
            $name=$db->quote($obj->name);
            $company=$db->quote($obj->company);
            array_push($ar2,"($lto,$email,$obj->tosendmail,$name,$company,$obj->mailsent,"
                    . "'$obj->error_sent')");
        }
        $s=join(', ',$ar2);
        my7::qdirect("replace into $this->tbldata (idmaillist,email,tosendmail,"
                . "name,company,mailsent,error_sent) values $s");
    }

    /**
     * Проставляем новую отправку сообщений которые были отправлены с ошибкаами
     */
    public function tosendwitherrors($id) {
        my7::qdirect("update $this->tbldata set mailsent=0,error_sent='' where idmaillist=$id"
                . " and tosendmail=1 and error_sent<>''");
    }
    
    // добавляем к списку рассылки данные пользователей с сайта
    public function toaddfromsite($id) {
        $arr=my7::qlist("select email,fio,naimfirm from et_zayav where ismoderated=1");
        $ar2=array();
        $db=my7::db();
        for ($i=0;$i<count($arr);$i++) {
            $obj=$arr[$i];
            $email=$db->quote($obj->email);
            $name=$db->quote($obj->fio);
            $company=$db->quote($obj->naimfirm);
            array_push($ar2,"($id,$email,1,$name,$company,0,"
                    . "'')");
        }
        $s=join(', ',$ar2);
        my7::qdirect("replace into $this->tbldata (idmaillist,email,tosendmail,"
                . "name,company,mailsent,error_sent) values $s");
        
    }

    public function saveAction() {
        // сохраняем одну новость
        $id = intval($this->_getParam('id', 0));
        //if ($sid=='') $this->_redirect('a7-message/view/id/'.urlencode('Сохранение записи: нет записи с идентификатором '.$sid),array('prependBase'=>1));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('k',$formData);
            $db=my7::db();
            //$dt=my7::dateconv($formData['data1']);
            $s3='';
            
            // обрабатываем изображение
            /*function crthumb($n, $fn1) {
                // callback-функция обработки изображения
                global $edit4;
                //$path1=my7::basepath().'img2/';
                //$in15=getimgnum().'.jpg';
                //resjpegrez3(120,80,2,$fn1,$in1) &&
                my7::resamplejpeg3(150, 0, $fn1, $fn1);
                //@unlink($fn1);
                //@rename($path1.$in15,$fn1);
            };*/
            $arr=array(
                'name'=>$formData['name'],
                'html'=>$formData['html'],
                'mask'=>$formData['mask'],
                'tosendmail'=>(isset($formData['tosend']) ? 1 :0)
                );
            if (isset($formData['addfromsite'])) $s79=' ,et_zayav read';
                else $s79='';
            my7::qdirect("lock tables $this->tbl write, $this->tbldata write $s79");
            if ($id) {
                $db->update($this->tbl,
                        $arr,
                        'uid='.$id);
                if (isset($formData['deldata'])) 
                    my7::qdirect("delete from $this->tbldata where idmaillist=$id");
            } else {
                $q1=my7::qobj("select max(ordr) as mo from ".$this->tbl);
                $arr['ordr']=intval($q1->mo)+1;
                $db->insert($this->tbl,$arr);
                $id=$db->lastInsertId();
            }
            //$n4=my7::qobj("select count(*) as cnt from $this->tbldata where idmaillist=$id");
            $this->excelLoad($id); // загружаем данные из файла excel если он загружен
            //$n4=my7::qobj("select count(*) as cnt from $this->tbldata where idmaillist=$id");
            if (intval($formData['importfrom'])<>0) $this->importfrommaillist(intval($formData['importfrom']),$id);
            //$n4=my7::qobj("select count(*) as cnt from $this->tbldata where idmaillist=$id");
            if (isset($formData['sendwitherrors'])) $this->tosendwitherrors($id); // проставляем новую отправку сообщений которые были отправлены с ошибкаами
            //$n4=my7::qobj("select count(*) as cnt from $this->tbldata where idmaillist=$id");
            if (isset($formData['addfromsite'])) $this->toaddfromsite($id); // добавляем к списку рассылки данные пользователей с сайта
            //$n4=my7::qobj("select count(*) as cnt from $this->tbldata where idmaillist=$id");
            my7::qdirect('unlock tables');
            

            /*$aimnums = array();
            $adel = array();
            for ($i = 0; $i < 1; $i++) {
                array_push($aimnums, my7::siteuniqid());
                array_push($adel, (isset($_POST['img_del' . ($i + 1)]) ? 1 : 0));
            };
            $aval2 = array('jpg');
            $arfinfo = array(
                array(1, $aval2, 0, 0, 0, 0)
            );
            if ($s3 == '') {
                if ($id) {
                    $row = my7::qobj("select pict from $this->tbl where uid=$id");
                    $afrombd = array($row->pict);
                } else {
                    $afrombd = array('');
                };
                $achanged = array();
                $f3 = new My_upload();
                $s3 = $f3->move_upl(1, $this->imgdir, '', $afrombd, $arfinfo, $adel, $aimnums, $achanged, 'crthumb', 0, 0);
                //if ($in15=='' && $isedit1) $in15=$row->bigpict;
            };*/

            
            if ($s3<>'') my7::reterror('a7-maillists/edit/id/'.$id,$s3);
                    //array('html'=>stripslashes($formData['html'])));
            my7::goUrl('a7-maillists');
        };
    }

    public function checkAction() {
        // нажали чекбокс, переключили отправку сообщений
        $id = intval($this->_getParam('id', 0));
        my7::qdirect("update $this->tbl set tosendmail=if(tosendmail,0,1) where uid=$id");
        my7::goUrl('a7-maillists');
        
    }
     public function rdelAction() {
        // удаляем один списко рассылки
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            $q2=my7::qobj("select ordr from $this->tbl where uid=$id");
            my7::qdirect("delete from $this->tbldata where idmaillist=$id");
            my7::db()->delete($this->tbl,"uid=$id");
            if ($q2) { // если есть такая запись
                my7::qdirect("update $this->tbl set ordr=ordr-1 where ordr>$q2->ordr");
            }
        }
        my7::goUrl('a7-maillists');
    }
}