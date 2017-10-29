<?php
// функции для работы с БД
// уровень записи для getkrohi возвращается в db3->prcnt, топ uid корня в db3->toplid

class database3 {
    function connect() {
        global $conf35;
        if (!isset($this->handle)) {
            $this->handle = mysql_connect ($conf35['production']['resources.db.params.host'],
                    $conf35['production']['resources.db.params.username'],
                    $conf35['production']['resources.db.params.password'])
                    or die (sprintf ("Не могу открыть соединение с MySQL [%s]: %s", mysql_errno (), mysql_error ()));

            @mysql_select_db ($conf35['production']['resources.db.params.dbname'])
                    or die (sprintf ("Не могу выбрать базу данных [%s]: %s", mysql_errno (), mysql_error ()));
        };


        $sql='resources.db.params.driver_options.1002';
        $this->q($conf35['production'][$sql]);

    }

    function escape($s) {
        return mysql_real_escape_string($s);
    }

    function q($s) {
        $sth = @mysql_query ($s, $this->handle)
                or die (sprintf ("Не могу выполнить запрос [%s]: %s\nИсходная строка: %s", mysql_errno (), mysql_error (),$s));
        return $sth;
    }

    function qobj($s) {
        $sth=$this->q($s);
        return mysql_fetch_object($sth);
    }

    function getkrohi($tab,$uid2) {
        $sth=$this->q("select a.uid as uid1,a.ordr as ord1,a.naim as naim1,a.topid as top1,
        b.uid as uid2,b.ordr as ord2,b.naim as naim2,b.topid as top2,
        c.uid as uid3,c.ordr as ord3,c.naim as naim3,c.topid as top3,
        d.uid as uid4,d.ordr as ord4,d.naim as naim4,d.topid as top4,
        e.uid as uid5,e.ordr as ord5,e.naim as naim5,e.topid as top5
        from $tab a left join $tab b on a.topid=b.uid left join $tab c
        on b.topid=c.uid left join $tab d on c.topid=d.uid left join $tab e
        on d.topid=e.uid where a.uid=$uid2");
        $arr=mysql_fetch_array($sth);
        for ($i=1;$i<6;$i++) {
            if (is_null($arr['uid'.$i])) break;
        };
        $this->prcnt=$i-1; // уровень записи
        $this->toplid=$arr['uid'.$this->prcnt];
        return $arr;
    }
	
	function hasintreefrom($pos,$val,$ar) {
		// проверка $val содержится ли в массиве $ar начиная с $ar['uid'.$pos] 
        for ($i=$pos;$i<6;$i++) {
            if ($ar['uid'.$i]==$val) return true;
        };
		return false;
	}

	function kroh_delone($ar,$n) {
		// удалить n-й элемент в массиве крох (например uid2,ordr2,naim2,top2) со сдвигом следующих значений на это место
		$ar2=$ar;
		//var_dump($ar);
		for ($i=1;$i<=$this->prcnt;$i++) {
			if ($i>=$n) {
				$ar2['uid'.$i]=$ar2['uid'.($i+1)];
				$ar2['ord'.$i]=$ar2['ord'.($i+1)];
				$ar2['naim'.$i]=$ar2['naim'.($i+1)];
				$ar2['top'.$i]=$ar2['top'.($i+1)];
			}
		}
		
		return $ar2;
	}
	
    function printkrohi($ar,$sn,$linkfirst) {
        // linkfirst - показывать ли первую ссылку
        $b=0;
        //my3::log('t',$ar);
        $s='';
        for ($i=$this->prcnt;$i>0;$i--) {
            if ($b) $s.=' / ';
            if (($linkfirst || $b) && $i>1) $s.='<a href="'.$sn.$ar['uid'.$i].'">';
            $s.=my3::nbsh($ar['naim'.$i]);
            if (($linkfirst || $b) && $i>1) $s.='</a>';
            $b=1;
        }
        return $s;
    }
	

} // enc class

$db3=new database3();
?>
