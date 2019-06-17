<?php
// Copyright (C) 2010 Воробьев Александр


class A7ZayavController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=50; // кол-во записей на страницу
        $this->sess='page839';
        $this->tbl=my7::getdbprefix().'zayav';
        $this->rewrtbl=my7::getdbprefix().'rewrite';
    }

    public function filterAction() {
        // фильтруем по названию фирмы список туроператоров
        $bp=my7::basePath();
        require_once $bp.'/utf8/utf8.php';
        require_once $bp.'/utf8/utils/unicode.php';
        require_once $bp.'/utf8/utils/specials.php';
        $filter = utf8_substr($this->_getParam('filter', ''), 0 ,300);
        if ($filter=='') {
            unset($_SESSION['flt596']);
        } else {
            $_SESSION['flt596']=$filter;
        }
        my7::goUrl('a7-zayav/index/page/0');
    }
    public function indexAction() {
        // выводим список туроператоров в виде таблицы
        
        //unset($_SESSION['flt596']);
        
        if (isset($_SESSION['flt596'])) {
            $this->view->flt596=$_SESSION['flt596'];
        } else {
            $this->view->flt596='';
        }
        $b = !($this->view->flt596=='');
        $flt = my7::mysql_escape_string($this->view->flt596);
        var_dump('flt',$flt);
        $pg1 = $this->_getParam('page', 0);
        $_SESSION[$this->sess]=intval($pg1);

        $page=$_SESSION[$this->sess];
        if ($b) {
            $row = my7::qobj("SELECT count(*) as cnt from $this->tbl
                where naimfirm like '%$flt%' or email like '%$flt%'");
        } else { 
            $row = my7::qobj("SELECT count(*) as cnt from $this->tbl");
        };
        $nrows=$row->cnt;
        $page++;
        do {
            $page--;
            $lm=$page*$this->cnt;
            if ($b) {
                $this->view->arr=my7::qlist("SELECT *,date_format(data1,'%d.%m.%Y') as df FROM $this->tbl
                where naimfirm like '%$flt%' or email like '%$flt%'
                order by uid desc limit $lm,$this->cnt");
            } else {
                $this->view->arr=my7::qlist("SELECT *,date_format(data1,'%d.%m.%Y') as df FROM $this->tbl
                order by uid desc limit $lm,$this->cnt");
            }
            $nrowspage=count($this->view->arr);
        } while (($nrowspage==0) && ($page>0));
        
        $this->view->psort= ($nrows-$page*$this->cnt+$nrowspage-$this->cnt); // порядковый номер от концы списка
        if ($nrowspage<$this->cnt) $this->view->psort=$nrowspage; // если последняя страница
        $this->view->lend=array($nrows,$page,$nrowspage,$this->cnt,'a7-zayav/index/page/');
        // параметры paginator
        $_SESSION[$this->sess]=$page;
        

    }
    
    /*function show($newcont,$ctrlr,$showadd=true) {
// newcont - имя контроллера для добавления новой записи
// ctrlr - номер текущего контроллера
        if ($showadd) echo '<p align="center"><a href="'.my7::baseurl().
                $newcont.'/index/id/0/idtree/'.$this->idtree.'/lv/'.$ctrlr.'"
            target="right">Добавить элемент</a></p>';
//$arr=my7::qlist("select * from $this->tbl where idtree=$this->idtree ")

        $arr=$this->getalltree($newcont);
        $s=my7::nbsh($ctrlr);
        $this->printtree(&$arr,0,$s);
    }*/

    function getalltree($id) {
// сохраняет в массив все дерево $idtree
//my7::log('d',"select a.*,b.name from $this->tbl a,$this->tblact b
//      where a.idtree=$this->idtree and a.actid=b.uid order by a.topid,a.ordr");
        //$ar2=my7::qlist("select a.* from $this->tbl a
          //      where a.idtree=$this->idtree order by a.topid,a.ordr");
        $ar2=my7::qlist("select b.naim,a.user_id,b.uid,b.topid
            from et_tree b left join et_zayuser a on a.user_id=$id and b.uid=a.zay_id where  
                b.idtree=12 order by b.topid,b.ordr");
        $aro=array();
        for ($i=0;$i<count($ar2);$i++) {
            $row=$ar2[$i];
            //$row->name=$newcont;
            if (!isset($aro[$row->topid])) {
                $aro[$row->topid]=array();
            }
            array_push($aro[$row->topid],$row);
        };

        return $aro;
    }
    

    public function editAction() {
        // редактируем одну запись новости
        $db=my7::db();
        $id = intval($this->_getParam('id', 0));
        $this->view->id=$id;
        $this->view->row=my7::qobj("select uid,login,pwd,naimfirm,
                fio,dolgn,site,email,kphone,nreestr,ismoderated,comments,city,
                date_format(data1,'%d.%m.%Y') as data1 from $this->tbl where uid=$id");
        if ($this->view->row===false) $this->view->row=(object)array('uid'=>0,'naim'=>'',
                            'data1'=>date('d.m.Y'),'html'=>'','anons'=>'');
        $this->view->zayavna=$this->getalltree($id); // вложенный массив всех элиментов с idtree=12
        //$this->view->zaystart=299;
        $s=$db->quote('zayav/'.$id);
        /*$this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();*/
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };
    }
    public function xlsAction() {
        // выдача всех заявок туроператора в формате xls
        $xls = new My_xls();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=list.xls"); 
        header("Content-Transfer-Encoding: binary");

        $xls->BOF(); //начинаем собирать файл
        /*первая строка*/
        $xls->WriteLabel(1,0,"Заявки туроператоров на сайте gokoreatour.ru от ".date("d.m.Y H:i:s",time()));
        /*вторая строка*/
        $xls->WriteLabel(2,0,"№ порядковый");
        $xls->WriteLabel(2,1,"Дата регистрации");
        $xls->WriteLabel(2,2,"Отмодерированная заявка");
        $xls->WriteLabel(2,3,"Название фирмы");
        $xls->WriteLabel(2,4,"ФИО");
        $xls->WriteLabel(2,5,"Должность");
        $xls->WriteLabel(2,6,"Сайт");
        $xls->WriteLabel(2,7,"E-mail");
        $xls->WriteLabel(2,8,"Контактный телефон");
        $xls->WriteLabel(2,9,"N в федер.реестре");
        $xls->WriteLabel(2,10,"Логин");
        $xls->WriteLabel(2,11,"Пароль");
        $xls->WriteLabel(2,12,"Город");
        $xls->WriteLabel(2,13,"Комментарий админа");
        /*третья строка*/

        $ar2=my7::qlist("select *,date_format(data1,'%d.%m.%Y') as data12 from et_zayav order by uid desc");
        $psort=count($ar2);
        $aro=array();
        $j=3;
        for ($i=0;$i<count($ar2);$i++) {
            $row=$ar2[$i];
            $xls->WriteLabel($j,0,$psort);
            $xls->WriteLabel($j,1,$row->data12);
            $xls->WriteLabel($j,2,($row->ismoderated ? "Да" : "Нет"));
            $xls->WriteLabel($j,3,$row->naimfirm);
            $xls->WriteLabel($j,4,$row->fio);
            $xls->WriteLabel($j,5,$row->dolgn);
            $xls->WriteLabel($j,6,$row->site);
            $xls->WriteLabel($j,7,$row->email);
            $xls->WriteLabel($j,8,$row->kphone);
            $xls->WriteLabel($j,9,$row->nreestr);
            $xls->WriteLabel($j,10,$row->login);
            $xls->WriteLabel($j,11,$row->pwd);
            $xls->WriteLabel($j,12,$row->city);
            $xls->WriteLabel($j,13,$row->comments);
            $j++;
            $psort--;
        };
        /*...
        $xls->WriteNumber(32,0,"30");
        $xls->WriteLabel(32,1,"Иван");
        $xls->WriteLabel(32,2,"Петров");*/

        $xls->EOF(); //заканчиваем собирать
        exit;
        
    }
/*    public function xlstmpAction() {
        // выдача всех заявок туроператора в формате xls
        $xls = new My_xls();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=list.xls"); 
        header("Content-Transfer-Encoding: binary");

        $xls->BOF(); //начинаем собирать файл
        $xls->WriteLabel(1,0,"Название");
        $xls->WriteLabel(2,0,"№п/п");
        $xls->WriteLabel(2,1,"Имя");
        $xls->WriteLabel(2,2,"Фамилия");
        $xls->WriteNumber(3,0,"1");
        $xls->WriteLabel(3,1,"Петр");
        $xls->WriteLabel(3,2,"Иванов");
        $xls->WriteNumber(32,0,"30");
        $xls->WriteLabel(32,1,"Иван");
        $xls->WriteLabel(32,2,"Петров");

        $xls->EOF(); //заканчиваем собирать
        exit;
        
    }
*/
    public function saveAction() {
        // сохраняем одну новость
        $id = intval($this->_getParam('id', 0));
        //echo $id; exit;
        //if ($sid=='') $this->_redirect('a7-message/view/id/'.urlencode('Сохранение записи: нет записи с идентификатором '.$sid),array('prependBase'=>1));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('k',$formData);
            $db=my7::db();
            $s3='';
            $dt=my7::dateconv($formData['data1']);
            if ($dt===false) $s3.='Неправильная дата '.$formData['data1']."\n";
 
            my7::qdirect('lock tables et_zayav write, et_zayuser write');
            $login1=mysql_real_escape_string($formData['login']);
            $row89=my7::qobj("select count(*) as cnt from et_zayav where login='$login1' and uid<>$id");
            if ($row89->cnt>0) $s3.="Запись с таким логином уже есть в базе данных\n";
            
            
            if ($s3<>'') {
                my7::qdirect("unlock tables");
                my7::reterror('a7-zayav/edit/id/'.$id,$s3,$formData);
            }
        /*$this->view->row=my7::qobj("select uid,login,pwd,naimfirm,
                fio,dolgn,site,email,kphone,nreestr,ismoderated,comments,
                date_format(data1,'%d.%m.%Y') as data1 from $this->tbl where uid=$id");*/
                
                    //array('html'=>stripslashes($formData['html'])));
            $ismod2=(isset($formData['ismoderated']) ? 1 :0);
            $arr=array('data1'=>$dt,'login'=>$formData['login'],
                'pwd'=>$formData['pwd'],
                'naimfirm'=>$formData['naimfirm'],
                'fio'=>$formData['fio'],
                'dolgn'=>$formData['dolgn'],
                'site'=>$formData['site'],
                'email'=>$formData['email'],
                'kphone'=>$formData['kphone'],
                'nreestr'=>$formData['nreestr'],
                'comments'=>$formData['comments'],
                'ismoderated'=>$ismod2,
                'city'=>$formData['city'],
                );
            if ($id) {
                $db->update($this->tbl,
                        $arr,
                        'uid='.$id);
                /*$s=$db->quote('news/'.$id);
                $kw=new My_Titlekw($this->rewrtbl,$s);
                $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);
                */
            } else {
                // не используется
                $db->insert($this->tbl,$arr);
                $id=$db->lastInsertId();
                $s=$db->quote('news/'.$id);
                $kw=new My_Titlekw($this->rewrtbl,$s);
                $kw->saveData($formData['title'],$formData['description'],$formData['keywords']);

            }
            // пишем страницы к которым открыт доступ в бд
            my7::qdirect("delete from et_zayuser where user_id=$id");
            $i=1;
            //var_dump($formData);exit;
            while(isset($formData['hy'.$i])) {
                if (isset($formData['hq'.$i])) {
                    my7::qdirect("insert into et_zayuser values ($id,".$formData['hy'.$i].")");
                }
                $i++;
            }
            
            my7::qdirect("unlock tables");
            my7::goUrl('a7-zayav');
        };
    }

    public function rmoveAction() {
        // не используется
    }

    public function rdelAction() {
        // удаляем одну новость
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            my7::qdirect("delete from et_zayuser where user_id=$id");
            my7::db()->delete($this->tbl,"uid=$id");
        }
        my7::goUrl('a7-zayav');
    }


}









