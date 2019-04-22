<?php
// Copyright (C) 2019 Воробьев Александр


class A7ExtmaillistController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->cnt=2; // кол-во записей на страницу для данного $letter1
        $this->cntletters=3; // количество записей на страницу для данного набора
        $this->sess='page8099';
        $this->sess2='page7099';
        $this->tbl=my7::getdbprefix().'dbmailexternal';
        $this->mailtbl=my7::getdbprefix().'maillists';
        //$this->rewrtbl=my7::getdbprefix().'rewrite';
    }

    public function values2($letter1) {
        // задать фильтр по email
        $s='';
        $arr=array();
        //var_dump('lt1',$letter1);
        for ($i=0;$i<strlen($letter1);$i++) {
            $lt=substr($letter1,$i,1);
            $addslash=my7::myaddslash($lt);
            //var_dump('ads',$addslash);
            //$s9=$lt; //my7::db()->quote($lt);
            //var_dump('s9',$s9);
            array_push($arr, ' (email like "'.$lt.'%")');
        }
        $s=join(' or ',$arr);
        if ($s<>'') $s=' and ('.$s.')';
        return $s;
    }
    
    public function indexAction() {
        // выводим список туроператоров в виде таблицы
        $id = $this->_getParam('id', 0);
        $pg1 = $this->_getParam('page', 0);
        $letter1 = my7::myurldecodedollar($this->_getParam('lt', ''));
        $_SESSION[$this->sess]=$letter1;
        $_SESSION[$this->sess2]=intval($pg1);

        $this->view->id=$id;
        $arq=my7::qlist("select substring(email,1,1) as firstletter, count(*) as cnt from $this->tbl where idmaillist=$id and email<>'' group by firstletter");
        $this->view->arq=$arq;
        $this->view->cntletters=$this->cntletters;
        if ($letter1=='' and count($arq)<>0) {
            $obj=$arq[0];
            $n=$obj->cnt;
            $fl=$obj->firstletter;
            $ll=$fl;
            for ($i=1;$i<count($arq);$i++) {
                if ($n>=$this->cntletters) break;
                $n+=$arq[$i]->cnt;
                $ll=$arq[$i]->firstletter;
            }
        } else if (count($arq)<>0) {
            $fl=substr($letter1,0,1);
            $ll=substr($letter1,strlen($letter1)-1,1);
        }
        $ar3=array();
        for ($i=0;$i<count($arq);$i++) {
            $ar3[$arq[$i]->firstletter]=1;
        }
        $top=new My_textop();
        $letter1=(count($arq)>0 ? $top->lettersfromtobyarray($fl, $ll, $ar3) : '');
        //var_dump('arq',$arq);
        $this->view->letter1=$letter1;
        $this->view->href=my7::baseUrl().'a7-extmaillist/index/id/'.$id.'/lt/';
        $this->view->maillist = my7::qobj("SELECT * from $this->mailtbl where uid=$id");
        $s8=$this->values2($letter1); // задать фильтр по началу email
        $page=$_SESSION[$this->sess2];
        $row = my7::qobj("SELECT count(*) as cnt from $this->tbl where idmaillist=$id "
                . $s8);
        //var_dump("SELECT count(*) as cnt from $this->tbl where idmaillist=$id "
         //       . $s8);
 //       $row = my7::qobj("SELECT count(*) as cnt from $this->tbl where idmaillist=$id "
   //             . "and email<>'' and substring(email,1,1)>='$fl' and substring(email,1,1)<='$ll'");
        $nrows=$row->cnt;
        $page++;
        do {
            $page--;
            $lm=$page*$this->cnt;
            $this->view->arr=my7::qlist("SELECT * FROM $this->tbl where idmaillist=$id
                $s8
                order by email limit $lm,$this->cnt");
           // var_dump("SELECT * FROM $this->tbl where idmaillist=$id
             //   $s8
             //  order by email limit $lm,$this->cnt");
/*            $this->view->arr=my7::qlist("SELECT * FROM $this->tbl where idmaillist=$id
                and substring(email,1,1)>='$fl' and substring(email,1,1)<='$ll'
                order by email limit $lm,$this->cnt");*/
            $nrowspage=count($this->view->arr);
        } while (($nrowspage==0) && ($page>0));
        
        $this->view->psort= ($nrows-$page*$this->cnt+$nrowspage-$this->cnt); // порядковый номер от концы списка
        if ($nrowspage<$this->cnt) $this->view->psort=$nrowspage; // если последняя страница
        $this->view->lend=array($nrows,$page,$nrowspage,$this->cnt,'a7-extmaillist/index/id/'.$id.'/lt/'.my7::myurlencodedollar($letter1).'/page/');
        // параметры paginator
        $_SESSION[$this->sess2]=$page;
        

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
        $this->view->maillist = intval($this->_getParam('ml', 0));
        $id = intval($this->_getParam('id', 0));
        $this->view->id=$id;
        /*if ($id) {
            $this->view->maillists=my7::qarray("select uid,name from $this->tbl where uid<>$id order by name");
        }*/
        $this->view->row=my7::qobj("select * from $this->tbl where uid=$id");
        if ($this->view->row===false) $this->view->row=(object)array('uid'=>0,'email'=>'',
                            'name'=>'', 'company'=>'', 'mailsent'=>0, 'error_sent'=>'',
                            'tosendmail'=>0);
        /*$s=$db->quote('news/'.$id);
        $this->view->kw=new My_Titlekw($this->rewrtbl,$s);
        $this->view->kw->getData();
        if (isset($_SESSION['postsv3'])) {
            $this->view->row=(object)$_SESSION['postsv3'];
            unset($_SESSION['postsv3']);
        };*/
    }
 
    public function saveAction() {
        // сохраняем одну новость
        $id = intval($this->_getParam('id', 0));
        $maillist = intval($this->_getParam('ml', 0));
        //if ($sid=='') $this->_redirect('a7-message/view/id/'.urlencode('Сохранение записи: нет записи с идентификатором '.$sid),array('prependBase'=>1));
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //my7::log('k',$formData);
            $db=my7::db();
            //$dt=my7::dateconv($formData['data1']);
            $s3='';
            $top=new My_textop();
            $arr=array(
                'name'=>$formData['name'],
                'idmaillist'=>$maillist,
                'email'=>$top->filteremailforsite($formData['email']),
                'company'=>$formData['company'],
                'tosendmail'=>(isset($formData['tosend']) ? 1 :0),
                'mailsent'=>(isset($formData['mailsent']) ? 1 :0),
                'error_sent'=>(isset($formData['error_sent']) ? '' : '1'),
                );
            if ($id) {
                $db->update($this->tbl,
                        $arr,
                        'uid='.$id);
            } else {
                //$q1=my7::qobj("select max(ordr) as mo from ".$this->tbl);
                //$arr['ordr']=intval($q1->mo)+1;
            //var_dump($id,$maillist);exit;
                $db->insert($this->tbl,$arr);
                //$id=$db->lastInsertId();
            }
            

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

            
            if ($s3<>'') my7::reterror('a7-extmaillist/edit/ml/'.$maillist.'/id/'.$id,$s3);
                    //array('html'=>stripslashes($formData['html'])));
            my7::goUrl('a7-extmaillist/index/id/'.$maillist);
        };
    }

    public function checkAction() {
        // нажали чекбокс, переключили отправку сообщений
        $id = intval($this->_getParam('id', 0));
        my7::qdirect("update $this->tbl set tosendmail=if(tosendmail,0,1) where uid=$id");
        my7::goUrl('a7-maillists');
        
    }
     public function rdelAction() {
        // удаляем одну новость
        $id = intval($this->_getParam('id', 0));
        if ($id) {
            //$q2=my7::qobj("select ordr from $this->tbl where uid=$id");
            my7::db()->delete($this->tbl,"uid=$id");
            //if ($q2) { // если есть такая запись
            //    my7::qdirect("update $this->tbl set ordr=ordr-1 where ordr>$q2->ordr");
            //}
        }
        my7::goUrl('a7-extmaillist');
    }
}