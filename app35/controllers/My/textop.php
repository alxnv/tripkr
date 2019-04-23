<?php
// Copyright (C) 2010 Воробьев Александр

/* 
операции с текстом
*/
class My_textop {
    function myunescape($s) {
        return strtr($s,'¦¤v·','?&#=');
    }
    
    /**
     * !!! не используется !!! удаляем из начала названия неправильные символы
     * !!! всегда выполнять эту функцию для вставляемых email
     * @param type $email
     */
    function filteremailforsite($email) {
        $lt=substr($email,0,1);
        if (stristr("\\%._",$lt)!==false) return substr($email,1);
         else return $email;
    }
    
    function lettersfromto($firstletter,$lastletter) {
        // формирует строку симоволов по порядку от $firstletter до $lastletter
        $s='';
        
        for ($l=$firstletter;$l<=$lastletter;$l=chr(ord($l)+1)) $s.=$l;
        return $s;
    }

    function lettersfromtobyarray($firstletter,$lastletter,$arr) {
        // формирует строку симоволов по порядку от $firstletter до $lastletter,
        // включая только символы из $arr
        $s='';
        
        for ($l=$firstletter;$l<=$lastletter;$l=chr(ord($l)+1)) if (isset($arr[$l])) $s.=$l;
        return $s;
    }

    function lettersofpages($arq,$letter1,$cnt,$href) {
        // формирует строку для отображения пажинации в формате abc de fgh
        // $arq - массив объектов object(firstletter,cnt)
        // $letter1 - текущая страница
        // $cnt - кол-во элементов минимальное на набор страниц
        // $href - начало адреса строки для перехода (потом к ней добавляется $letter1 для данной строки
        if (count($arq)==0) return '';
        $s='';
        $ar3=array();
        for ($i=0;$i<count($arq);$i++) {
            $ar3[$arq[$i]->firstletter]=1;
        }
        $j=0;
        //var_dump('cnt',$cnt,'letter1',$letter1);
        while ($j<count($arq)) {
            $lts=$arq[$j]->firstletter;
            $n=$arq[$j]->cnt;
            //var_dump('fl',$fl,'ll',$ll,'n',$n);
            //echo '<br>';
            for ($i=$j+1;$i<count($arq);$i++) {
                if ($n>=$cnt) {
                    break;
                } else {
                    $n+=$arq[$i]->cnt;
                    $lts.=$arq[$i]->firstletter;
                }
            }
            $j=$i;
            //var_dump('fl2',$fl,'ll2',$ll);echo '<br>';
            //$lts=$this->lettersfromtobyarray($fl,$ll,$ar3);
            //var_dump('fl',$fl,'ll',$ll,'ar3',$ar3,'lts',$lts);exit;
            if ($s<>'') $s.=' ';
            $s.=($lts<>$letter1 ? '<a href="'.$href.my7::myurlencodedollar($lts).'">' : '<b>').'['.$lts.']'
                    . ($lts<>$letter1 ? '</a>' : '</b>');
        }
        return $s;
    }
    function mnogot($s,$n) {
        $bp=my7::basePath();
        require_once $bp.'/utf8/utf8.php';
        require_once $bp.'/utf8/utils/unicode.php';
        require_once $bp.'/utf8/utils/specials.php';

        if (utf8_strlen($s)>$n) {
            $s2=utf8_substr($s,0,$n).'...';
        } else {
            $s2=$s;
        }
        return $s2;
    }
    
    function month($n) {
        $arr=array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября',
                'октября','ноября','декабря');
        return $arr[intval($n)-1];
    }
 /**
 * вывод текста и единиц измерения числа в падеже 'через'
 * @param integer $num - число
 * @param integer $whichunit - какие единицы измерения (цифра) 
 * @return string 
 * 
 */
    function numplusunit($num,$whichunit) {
        return ($num==0 ? '' : ' '.$num.' '.$this->unitstext($num,$whichunit));
    }
    
 /**
 * вывод текста единиц измерения числа в падеже 'через'
 * @param integer $num - число
 * @param integer $whichunit - какие единицы измерения (цифра) 
 * @return string 
 * 
 */
    function unitstext($num, $whichunit) {
        
        $ar2=array(
            2=> array('минут','минуту','минуты'),
            3=> array('часов','час','часа'),
            105=> array('сообщений','сообщение','сообщения'),
            106=> array('почтовых','почтовое','почтовых'),
        );
                
        $n2=$num % 100;
        if ($n2>=0 && $n2<5) {
            $arr=array(0,1,2,2,2);
            $s=$ar2[$whichunit][$arr[$n2]];
        } else if ($n2>=5 && $n2<20) {
            $s=$ar2[$whichunit][0];
        } else if ($n2>=20 && $n2<100) {
            $n2 = $n2 % 10;
            $arr=array(0,1,2,2,2,0,0,0,0,0);
            $s=$ar2[$whichunit][$arr[$n2]];
        } else {
            $s='';
        }
        return $s;
    }
}
?>
