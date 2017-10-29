<?php
// Copyright (C) 2010 Воробьев Александр

class My_Dbtree {

    // работа с таблицей дерева со структурой uid,topid,ordr,naim
    //  возможно появится еще поле istopic, но вряд ли

    public $tbl=null;

    function __construct($tbl,$istopic=false) {
        $this->tbl=$tbl;
        $this->istopic=$istopic;
        return $this;
    }

    function delete($id,$depth,$topid=null,$ordr=null,$fcallback=null) {
        // удаляем запись и корректируем ordr у других записей если нужно
        // если не передано $topid, то оно запрашивается из БД
        // $depth определяет сколько уровней вложенности удалять вместе с этой записью
        //  если==0, то одну эту запись
        my3::qdirect("lock tables $this->tbl write");
        if (is_null($topid)) {
            $row=my3::qobj("select topid,ordr from $this->tbl where uid=$id");
            if ($row===null) return;
            $topid=$row->topid;
            $ordr=$row->ordr;
        }
        my3::db()->delete($this->tbl,"uid=$id");
        $arr = my3::qlist("select uid from $this->tbl where topid=$topid and ordr>$ordr order by ordr");
        //my3::qdirect("update $this->tbl set ordr=ordr-1 where");
        $b=0;
        $s='';
        for ($i=0;$i<count($arr);$i++) {
            if ($b) $s.=',';
            $s.=$arr[$i]->uid;
            $b=1;
        }
        if ($s<>'') my3::qdirect("update $this->tbl set ordr=ordr-1 where uid in ($s)");
        my3::qdirect('unlock tables');


    }

    function append($topid,$arr) {
        // добавляет запись в конец списка дочерних объектов $topid
        // вычисляет ordr
        $db=my3::db();
        my3::qdirect("lock tables $this->tbl write");
        $row=my3::qobj("select max(ordr) as mo from $this->tbl where topid=$topid");
        if ($row===false) $row=(object)array('mo'=>0);
        $arr['topid']=$topid;
        $arr['ordr']=$row->mo+1;
        $db->insert($this->tbl,$arr);
        my3::qdirect("unlock tables");
    }

    function move($id,$pos2) {
        // перемещает запись внутри topid
        $row=my3::qobj("select topid,ordr from $this->tbl where uid=$id");
        if ($row!==false) {
            if ($pos2<1) $pos2=1;
            $db=my3::db();
            $id1=$row->ordr;
            my3::qdirect("lock tables $this->tbl write");
            $row2=my3::qobj("select max(ordr) as mo from $this->tbl where topid=$row->topid");
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
            $arr = my3::qlist("select uid from $this->tbl where topid=$row->topid and ordr>=$mn1 and ordr<=$mx1 order by ordr".$dir1);

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
            my3::qdirect('unlock tables');
        }
    }
}
?>
