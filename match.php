<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class my_htmlparser {

// парсер html из строки по указанным тегам
// regex должен быть вида '/(\<(img)([^>]+)\>)/mi' где img - тэг для распарски
// также можно указать (img|p|table) и т.д.
    
    public $afrom=null; // массив распарсенных тэгов
    public $ato=null; // массив замены для распарсенных тэгов(то же что и $afrom, но
      // можно заменить значения атрибутов
    public $regex=null;
    public $str1=null; // исходная строка которая парсится
    public $count=null; // количество распарсенных тэгов

    function __construct($tags,$s) {
        $this->regex='/(\<('.$tags.')([^>]+)\>)/mi';
        $this->str1=$s;
        $this->parsestr($this->afrom,$this->ato);
        //var_dump($this->afrom,$this->ato);
        $this->count=count($this->afrom);
    }
    
    /**
 * добавить к обработке атрибут (обычно style), с разделетилем (обычно ";")
     * (чтобы парсить атрибут style или другие)
     * пока расчитано только на один атрибут
 * @param string $attrs
 * @param string $delimeter
 * @return string 
 * 
 */
    function add2level($attrs,$delimeter) {
        for ($i=0;$i<$this->count;$i++) {
            if ($this->hasAttr($i, $attrs)) {
                $jindex=$this->afrom[$i][4][$attrs];
                $s=$this->afrom[$i][2][$jindex][2][0]; // for example - width:40px;height:10px
                $mtch=array();
                preg_match_all('/([^\\'.$delimeter.']+)/mi',$s,$mtch,PREG_SET_ORDER+PREG_OFFSET_CAPTURE);
                for ($j=0;$j<count($mtch);$j++) {
                    $mtch2=array();
                    if (preg_match('/^\s*([^\:\s]+)\s*\:\s*([^\:\s]+)\s*$/mi',$mtch[$j][1][0],$mtch2,PREG_OFFSET_CAPTURE)) {
                        $mtch[$j][2]=$mtch2;
                    } else {
                        $mtch[$j][2]=false;
                    };
                }
                $this->afrom[$i][5]=$mtch;
            }
        }
    }

    /**
 * получить указанный атрибут $i тэга
 * @param integer $i
 * @param string $attrname
 * @return string 
 * 
 */
    function getValue($i,$attrname) {
        $jindex=$this->afrom[$i][4][$attrname];
        return $this->afrom[$i][2][$jindex][2][0];
    }
    
    /**
 * записать указанный атрибут $i тэга
 * @param integer $i
 * @param string $attrname
 * @param string $value
 * 
 */
    function setValue($i,$attrname,$value) {
        $jindex=$this->afrom[$i][4][$attrname];
        $this->ato[$i][2][$jindex][2][0]=$value;
    }
 
   /**
 * проверить, имеется ли указанный атрибут $i тэга
 * @param integer $i
 * @param string $attrname
 * @param string $value
 * 
 */
    function hasAttr($i,$attrname) {
        return isset($this->afrom[$i][4][$attrname]);
    }
     
/**
     * заменяет в строке $this->str1 один подмассив вхождений с координатами $afrom
 *  на другой ($ato)
     */

    function repltextarray() {
        $s=$this->str1;
        $arr1=$this->afrom;
        $arr2=$this->ato;
        $last=strlen($s);
        $s2='';
        $modifiedindex1=-1;
        $modifiedindex2=-1;
        for ($j=count($arr1)-1;$j>=0;$j--) {
            for ($i=count($arr1[$j][2])-1;$i>=0;$i--) {
                if ($arr1[$j][2][$i][2][0]!=$arr2[$j][2][$i][2][0]) {
                    $joffset=$arr1[$j][3][1]; // смещение строки тэга в основной строке
                    $ps2=$joffset+$arr1[$j][2][$i][2][1]+strlen($arr1[$j][2][$i][2][0]);
                    $s2=$arr2[$j][2][$i][2][0].substr($s,$ps2,$last-$ps2).$s2;
                    $last=$joffset+$arr1[$j][2][$i][2][1];
                    $modifiedindex2=$i;
                    $modifiedindex1=$j;
                }
            }
        }
        if ($modifiedindex1==-1) {
            $s2=$s;
        } else {
            $s2=substr($s,0,$last).$s2;
        }
        
        /*$this->str1=$s2;
        $this->afrom=$arr1;
        $this->ato=$arr2;*/
        return $s2;
    }
    
    function parsestr(&$mtch2,&$mtchto) {
        $mtch=array();
        $mtch2=array();
        preg_match_all($this->regex,$this->str1,$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);
        //var_dump('mtch=',$mtch);

        for ($i=0;$i<count($mtch);$i++) {
        //    $reg2='/(\w+)\s.\=\s.[\"|\\'."'".']([\d|\w]+)[\"|\\'."'".']/mi';
            $reg2='/(\w+)\s*\=\s*[\"|\\'."'".']([^\"\\'."'".']*)[\"|\\'."'".']/mi';
            preg_match_all($reg2,$mtch[$i][3][0],$mtch3, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);
        //    var_dump('mtch_'.$i.'=',$reg2,'s=',$mtch[$i][3][0],$mtch3);

            $mtch2[]=array($mtch[$i][1][0],$mtch[$i][1][1],$mtch3,$mtch[$i][3],false);
        }
        $mtchto=$mtch2;
        // проставляем ассоциативнй массив для быстрого поиска атрибутов
        for ($i=0;$i<count($mtch2);$i++) {
            $mtch2[$i][4]=array();
            for ($j=0;$j<count($mtch2[$i][2]);$j++) {
                //var_dump('aa',$i,$j,$mtch2[$i][2][$j]);
                $mtch2[$i][4][strtolower($mtch2[$i][2][$j][1][0])]=$j;
            }
        }
}
} //end class

/**
     * парсит html и заменяет если текущий сайт домен на https://www.gokoreatour.ru
     * @param string $s исходная строка
     * @return string
     */
function repldomain($s) {
    $pr=new my_htmlparser('img',$s);
    for ($i=0;$i<$pr->count;$i++) {
        if ($pr->hasAttr($i, 'src')) {
            $addr=$pr->getValue($i,'src');
            $s2=parse_url($addr);
            if (isset($s2['host'])) {
                $sl=strtolower($s2['host']);
                $hashost=1;
            } else {
                $hashost=0;
            };
            $s3=$addr;
            if ($hashost && in_array($sl,
                    array('gokoreatour.ru','гокореатур.рф','tripkr.ru',
                        'www.gokoreatour.ru','www.гокореатур.рф','www.tripkr.ru'))) {
                if (!($s2['scheme']=='https' && $sl=='www.gokoreatour.ru')) {
                    // заменяем на https://www.gokoreatour.ru
                    $s3='https://www.gokoreatour.ru'.$s2['path'];
                    if (isset($s2['query'])) $s3.='?'.$s2['query'];
                }
            if ($s3<>$addr) $pr->setValue($i,'src',$s3);    
            }
        }
    }
    return $pr->repltextarray();
    
}

/**
     * заменяет в строке один подмассив вхождений с координатами на другой
     * параметры в строке запроса $s - вида $1, $2, ...
     * @param string $s строка в которой заменяется
     * @param array $arr1 массив в каждой строке которого массив из двух
        * элементов (строка,координаты в строке $s)
     * @param array $arr2 массив заменяющих значений
     * @return string
     */

function repltextarray($s,$arr1,$arr2) {
    
    $last=strlen($s);
    $s2='';
    $modifiedindex=-1;
    for ($i=count($arr1)-1;$i>=0;$i--) {
        if ($arr1[$i][0]!=$arr2[$i]) {
            $ps2=$arr1[$i][1]+strlen($arr1[$i][0]);
            $s2=$arr2[$i].substr($s,$ps2,$last-$ps2).$s2;
            $last=$arr1[$i][1];
            $modifiedindex=$i;
        }
    }
    if ($modifiedindex==-1) {
        $s2=$s;
    } else {
        $s2=substr($s,0,$arr1[$modifiedindex][1]).$s2;
    }
    return $s2;
}

/*$s='<p align="center">
        <img width="40" 
        src="go.php" />
        <b>go</b> <img name="hgfdh">
            ';*/

$s='<p align="center">
        <img width="40" 
        src="https://www.гокореатур.рф/jjkjk/" style="width:10px;hjhk; height: 30px " />
        <b>go</b> <img name="hgfdh">
            ';

$mtch2=array();
$zam=array('xjx','id', 4);
//preg_match_all('/(\<(img)(!\>)+\>)/mi',$s,$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);
$regex='/(\<(img)([^>]+)\>)/mi';
/*preg_match_all($regex,$s,$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);

for ($i=0;$i<count($mtch);$i++) {
//    $reg2='/(\w+)\s.\=\s.[\"|\\'."'".']([\d|\w]+)[\"|\\'."'".']/mi';
    $reg2='/(\w+)\s*\=\s*[\"|\\'."'".']([^\"\\'."'".']*)[\"|\\'."'".']/mi';
    preg_match_all($reg2,$mtch[$i][3][0],$mtch3, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);
//    var_dump('mtch_'.$i.'=',$reg2,'s=',$mtch[$i][3][0],$mtch3);

    $mtch2[]=array($mtch[$i][1][0],$mtch[$i][1][1],$mtch3);
}*/
echo '<pre>';
//var_dump($s,repldomain($s));
$pr=new my_htmlparser('img',$s);
$pr->add2level('style', ';');
//var_dump($pr->afrom,$pr->ato);
//var_dump($pr->hasAttr(1,'name'));
//$pr->setValue(1,'name','vasya');
//$pr->setValue(0,'width','1000');
//var_dump($pr->repltextarray());
//var_dump('$regex=',$regex);
//var_dump('$s=',$s);
//var_dump('$zam=',$zam);
//var_dump('$zam2=',$zam2);

//var_dump($mtch2);
//$s3=  repltextarray($s, $mtch2, $zam2);
//var_dump('$3=',$s3);

// проверка разборки style="xxxxxxx"
include_once dirname(__FILE__).'/tools/my3.php';
$s=' width : 40px ;jklj;height: 30px ';
$sp=new styleparser($s);
var_dump($sp->hasAttr('jkjk'));
$sp->setValue('width','50px');
var_dump($sp->write());
var_dump(my3::px_parse(' 17 px ',$n));
var_dump($n);
?>
