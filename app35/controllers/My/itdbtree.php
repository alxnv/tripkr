<?php
// Copyright (C) 2010 Воробьев Александр
// my_itdbtr - следующая версия с доработанным отображением методов


class my_itdbtree {

// работа с таблицей дерева со структурой uid,idtree,topid,ordr,naim
//  есть также метод отображения дерева в левом фрейме
// !адрес view для редактирования сохраняется в базе данных
//  (то есть его номер который нужно также добавить в таблицу et_tree_actnames)
    
    public $tbl=null;
    public $tblact=null;
    public $idtree=null;
    public $nolock=1;

    function __construct($tbl) {
        $this->tbl=$tbl;
        $this->tblact=$this->tbl.'_actnames';
    }

    function show($newcont,$ctrlr,$showadd=true) {
// newcont - имя контроллера для добавления новой записи
// ctrlr - номер текущего контроллера
        if ($showadd) echo '<p align="center"><a href="'.my3::baseurl().
                $newcont.'/index/id/0/idtree/'.$this->idtree.'/lv/'.$ctrlr.'"
            target="right">Добавить элемент</a></p>';
//$arr=my3::qlist("select * from $this->tbl where idtree=$this->idtree ")

        $arr=$this->getalltree();
        $s=my3::nbsh($ctrlr);
        $this->printtree($arr,0,$s);
    }

    function printtree(&$aro,$topid,$ct) {
// выводит на экран все дерево
        echo '<ol class="tree">';
        if (isset($aro[$topid])) {
        for ($i=0,$j=count($aro[$topid]);
        $i<$j;
        $i++) {
            $arr=$aro[$topid][$i];
            $n=$arr->uid;
            echo '<li><a href="'.my3::baseurl().$arr->name.'/index/id/'.$n.'/lv/'.$ct.'" target="right">'.
                    my3::nbsh(ltrim($arr->naim=='' ? '---' : $arr->naim)).
                    '</a></li>';
            if (isset($aro[$n])) $this->printtree($aro,$n,$ct);
        };
        }
        echo '</ol>';

    }

    function deletein2tbl($id,$tbl2) {
    // удаляет записи в $this->tbl, $tbl2 с идентификатором $id, а также все их подразделы
        $arr=$this->allchildren($id);
        //my3::log('a',$arr); return;

        // удаляем сам элемент и сдвигаем соседние элементы
        $this->delete($id,0);

        array_push($arr,$id);
        $s=join(',',$arr);
        my3::db()->delete($this->tbl,"uid in ($s)");
        my3::db()->delete($tbl2,"uid in ($s)");
    }

    function allchildren($id) {
        // получаем всех детей элемента, со вложенностью, в виде массива uid
        $arg=array();
        $ar4=array($id);
        do {
            $s=join(',',$ar4);
            $ar2=my3::qlist("select uid from $this->tbl where topid in ($s)");
            $ar4=array();
            for ($i=0;$i<count($ar2);$i++) {
                array_push($arg,$ar2[$i]->uid);
                array_push($ar4,$ar2[$i]->uid);
            }
        } while (count($ar2)>0);
        return $arg;
    }

    function getalltree() {
// сохраняет в массив все дерево $idtree
//my3::log('d',"select a.*,b.name from $this->tbl a,$this->tblact b
//      where a.idtree=$this->idtree and a.actid=b.uid order by a.topid,a.ordr");
        $ar2=my3::qlist("select a.*,b.name from $this->tbl a,$this->tblact b
                where a.idtree=$this->idtree and a.actid=b.uid order by a.topid,a.ordr");
        $aro=array();
        for ($i=0;
        $i<count($ar2);
        $i++) {
            $row=$ar2[$i];
            if (!isset($aro[$row->topid])) {
                $aro[$row->topid]=array();
            }
            array_push($aro[$row->topid],$row);
        };

        return $aro;
    }

    function delete($id,$depth,$idtree=null,$topid=null,$ordr=null,$fcallback=null) {
// удаляем запись и корректируем ordr у других записей если нужно
// если не передано $topid, то оно запрашивается из БД
// $depth определяет сколько уровней вложенности удалять вместе с этой записью
//  если==0, то одну эту запись
        if (!$this->nolock) my3::qdirect("lock tables $this->tbl write");
        if (is_null($idtree)) {
            $row=my3::qobj("select idtree,topid,ordr from $this->tbl where uid=$id");
            if ($row===false) return;
            $idtree=$row->idtree;
            $topid=$row->topid;
            $ordr=$row->ordr;
        }
        my3::db()->delete($this->tbl,"uid=$id");
        $arr = my3::qlist("select uid from $this->tbl where idtree=$idtree
                and topid=$topid and ordr>$ordr order by ordr");
//my3::qdirect("update $this->tbl set ordr=ordr-1 where");
        $b=0;
        $s='';
        for ($i=0;
        $i<count($arr);
        $i++) {
            if ($b) $s.=',';
            $s.=$arr[$i]->uid;
            $b=1;
        }
        if ($s<>'') my3::qdirect("update $this->tbl set ordr=ordr-1 where uid in ($s)");
        if (!$this->nolock) my3::qdirect('unlock tables');


    }

    /*function appendbyid($id) {

    }*/

    function append($topid,$arr) {
// добавляет запись в конец списка дочерних объектов $topid
// вычисляет ordr
// возвращает идентификатор добавленной записи
        $db=my3::db();
        if (!$this->nolock) my3::qdirect("lock tables $this->tbl write");
        $row=my3::qobj("select max(ordr) as mo from $this->tbl
                where idtree=$this->idtree and topid=$topid");
        if ($row===false) $row=(object)array('mo'=>0);
        $arr['idtree']=$this->idtree;
        $arr['topid']=$topid;
        $arr['ordr']=$row->mo+1;
        $db->insert($this->tbl,$arr);
        $uid4=$db->lastInsertId();
        if (!$this->nolock) my3::qdirect("unlock tables");
        return $uid4;
    }

    function move($id,$pos2) {
// перемещает запись внутри topid
        my3::qdirect("lock tables $this->tbl write");
        $row=my3::qobj("select idtree,topid,ordr from $this->tbl where uid=$id");
        if ($row!==false) {
            if ($pos2<1) $pos2=1;
            $db=my3::db();
            $id1=$row->ordr;
            $row2=my3::qobj("select max(ordr) as mo from $this->tbl where
                    idtree=$row->idtree and topid=$row->topid");
            $mxx1=$row2->mo;

            if ($mxx1===false) {
                $mxx1=0;
            };
            if ($pos2>$mxx1)
                $pos2=$mxx1;

            $mini=min($id1,$pos2);
            $maxi=max($id1,$pos2);
            if ($id1>$pos2) {
                $mn1=$pos2;
                $mx1=$id1-1;
                $dir1=' desc';
                $sg1='+1';
            }
            else {
                $mn1=$id1+1;
                $mx1=$pos2;
                $dir1='';
                $sg1='-1';
            };

//$arr2=array();
            $arr = my3::qlist("select uid from $this->tbl
                    where idtree=$row->idtree and topid=$row->topid
                    and ordr>=$mn1 and ordr<=$mx1 order by ordr".$dir1);

            $b=0;
            $s='';
            for ($i=0;$i<count($arr);$i++) {
                if ($b) $s.=',';
                $s.=$arr[$i]->uid;
                $b=1;
            }
            if ($s<>'') my3::qdirect("update $this->tbl set ordr=ordr $sg1 where uid in ($s)");

            $db->update($this->tbl,array('ordr'=>$pos2),"uid=$id");
//update $ob4->tbl set ordr=$pos2 where uid=$uid2");
        }
        my3::qdirect('unlock tables');
    }
}
?>
