<?php
// Copyright (C) 2010 Воробьев Александр

// глобальный класс для работы со всем :)
// my7.php для фронтенда назодится в каталоге tools
/**
 * @static
 */
class my7 {

// ftp://u145948@u145948.ftp.masterhost.ru/id-online.ru/www/misc/tmfmosk.ru/FCKeditor/_samples/lasso/application-mozg
    public $baseurl=null;
    public $basepath=null;
    public $hostname=null;
    public $_num=1;
    public $mysqli;
    public $db;
    public $link;
	const LOG_DATA=false; // работает ли функция log()
    const SITEMAIL='master@gokoreatour.ru'; //  mail администратора сайта. на него отсылаются все оповещения

    function __construct() {
        $this->baseurl=dirname($_SERVER['SCRIPT_NAME']).'/';
        //$pu=parse_url($_SERVER['SCRIPT_NAME']);
        $this->hostname=$_SERVER['SERVER_NAME'];
        if ($this->baseurl=='//') $this->baseurl='/';
        $this->basepath=dirname($_SERVER["SCRIPT_FILENAME"]).'/';
        $this->mysqli=!function_exists('mysql_connect');
        /*if (!$this->mysqli) {
            $this->db=Zend_Db_Table::getDefaultAdapter();
            $link=$this->db->getConnection();
            }*/
    }

/**
 * Обертка для вызова php mail()
 * увеличивает счетчик отправленных почтовых сообщений
 * @param type $email
 * @param type $hdr2
 * @param type $msg
 * @param type $headers
 */    
    
 public static function mail($email,$hdr2,$msg, $headers) {
    $b=mail($email,$hdr2,$msg, $headers);
    my7::qdirect("update et_settings set moremailsent=moremailsent+1");
    return $b;
}
    
    /**
 * mysql_real_escape_string в зависимости от использования mysqli
 * @param string $s
 * @return string 
 * 
 */
 static function mysql_escape_string($s) {
     global $my7;
     //return ($my7->mysqli ? mysqli_real_escape_string($s) : mysql_real_escape_string($s));
     if ($my7->mysqli) {
        $s2=$my7->db()->quote($s);
        $s2=substr(substr($s2,0,strlen($s2)-1),1); // убираем первый и последний
            // символ (кавычку)
        return $s2;
     } else {
         $db=$my7->db(); // получаем доступ к бд?
         return mysql_real_escape_string($s);
     }
 }
 /**
 * добавить слеш для специальных символов для базы данных
 * @param char $lt
 * @return string 
 * 
 */
    static function myaddslash($lt) {
            $addslash='';
            if (stristr("\\%._",$lt)!==false) $addslash="\\";
            return $addslash;
    }
    
    
    
 /**
 * заменяет в строке вхождения распарсенные parsestrall
 * @param string $str1 - строка в которой заменять
 * @param array $afrom - массив исходных данных для замены
 * @param array $ato - массив конечных данных для замены
 * @return string 
 * 
 */
    function repltextarray3($str1,$afrom,$ato) {
        $s=$str1;
        $arr1=$afrom;
        $arr2=$ato;
        $last=strlen($s);
        $s2='';
        $modifiedindex1=-1;
        for ($j=count($arr1)-1;$j>=0;$j--) {
            if ($arr1[$j][0][0]!=$arr2[$j][0][0]) {
                $joffset=$arr1[$j][0][1]; // смещение строки тэга в основной строке
                $ps2=$joffset+strlen($arr1[$j][0][0]);
                $s2=$arr2[$j][0][0].substr($s,$ps2,$last-$ps2).$s2;
                $last=$joffset; //-$arr1[$j][0][1];
                $modifiedindex1=$j;
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
    
function parsestrall(&$mtch,&$mtchto,$regex,$str1) {
        $mtch=array();
        preg_match_all($regex,$str1,$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);
        $mtchto=$mtch;
}
    
 /**
 * мое urlencode
 * @param string $s
 * @return string 
 * 
 */
    static function myurlencodedollar($s) {
        $s2='';
        for ($i=0;$i<strlen($s);$i++) {
            $toescape=0;
            $lt=substr($s,$i,1);
            if (stristr("\\%._",$lt)!==false) $toescape=1;
            $s2.=($toescape ? '$'.dechex(ord($lt)) : $lt);
        }
        return $s2;
    }
 /**
 * мое urldecode
 * @param string $s
 * @return string 
 * 
 */
    static function myurldecodedollar($s) {
        $afrom=array();
        $ato=array();
        my7::parsestrall($afrom,$ato,'/\$[\da-f]{2}/',$s);
        //echo '<pre>';
        //var_dump($ato);
        for ($i=0;$i<count($ato);$i++) {
            $ato[$i][0][0]=chr(hexdec(substr($ato[$i][0][0],1,2)));
        }
        $s2=my7::repltextarray3($s,$afrom,$ato);
        return $s2;
    }
 /**
 * генерировать случайный хэщ из заданного количества цифр и строчных и заглавных букв
 * @param integer $len
 * @return string 
 * 
 */
    static function generatetexthash($len) {
        $s='';
        for ($i=0;$i<$len;$i++) {
            $n=rand(1,10+26+25);
            if ($n<10) $c=chr(ord('0')+$n-1);
                else if ($n<10+26) $c=chr(ord('a')+$n-10);
                    else $c=chr(ord('A')+$n-10-26);
            $s=$s.$c;
            //var_dump($s);
        }
        return $s;
    }
    
 /**
 * получить имя текущего контроллера
 * @return <type> 
 * 
 */
    static public function nctrl() {
        return Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }

    static public function lnctrl() {
        return strtolower(my7::nctrl());
    }
   /**
 * Перейти к Url
 * @param <type>
 */
    static public function goUrl($s) {
        $response=Zend_Controller_Front::getInstance()->getResponse();
        $response->setRedirect(my7::baseurl().$s);
        $response->sendHeaders();
        exit;
        //header('Location: '.my7::baseurl().$s);
    }

    /**
     * Получить url директории сайта
     * @global <type> $my7
     * @return <type> 
     */
    static public function baseUrl() {
        global $my7;
        return $my7->baseurl;
    }

    
  function withoutExt($fn) {
      return substr($fn,0,strrpos($fn, "."));
  }
  function getExtension($filename) {
    return end(explode(".", $filename));
  }
/**
 * Вывести страницу с сообщением об ошибке
 * @param <type> $s
 * @param <type> $updleft 
 */
    static public function amessage($s,$updleft=0) {
        $_SESSION['message8']=$s;
        my7::goUrl('a7-message/view/'.($updleft ? 'updleft/1/' : ''));
    }

/**
 * Вывести страницу с сообщением c выравниванием по левому краю
 * сообщение передается в $_SESSION['message8']
 * @param <type> $s
 * @param <type> $updleft 
 */
    static public function amessageleft($updleft=0) {
        my7::goUrl('a7-message/view/alignleft/1/'.($updleft ? 'updleft/1/' : '').'insession/1');
    }
    /**
     * перейти в браузере к странице ввода пароля
     */
    static public function gotoaindex() {
        my7::goUrl('tools/helpers/noframe.htm');
    }

    /**
     * если текущий пользователь не ввел пароль, перейти к странице ввода пароля
     */
    static public function checkadm() {
        if (my7::notadmin()) my7::gotoaindex();
    }


    /*function gopage4(&$controller,$s) {
        //$controller->_helper->redirector->gotoURL($this->baseURL.'/'.$s);
}*/

    /**
     * возвращает путь к директории сайта
     * @global <type> $my7
     * @return <type> 
     */
    static public function basePath() {
        global $my7;
        return $my7->basepath;
    }

    /**
     * возвращает имя сайта
     * @global <type> $my7
     * @return <type> 
     */
    static public function hostName() {
        global $my7;
        return $my7->hostname;
    }

    /*function gofrombaseurl($helper) {
        // ? не работает ?
        // переход на url относительно базового урла проекта
        // вызывается из контроллера, передается $this->_helper
        $helper->redirector->gotoURL($this->baseURL.'/'.$s);
    }*/

    /*function gotoamessage($obj,$s) {
    $obj->_redirect('a7-message/view/id/'.urlencode($s),array('prependBase'=>1));
}*/

    /**
     * проверка что пользователь не ввел пароль
     * @return <type>
     */
    static public function notadmin() {
        @session_start();
        if (!(isset($_SESSION['login']) && ($_SESSION['login']=='admin5'))) {
            //header("Location: a7");
            //exit;
            //gopage4($controller,'a7');
            return 1;
        };
        return 0;
    }

    /**
     * записать отладочное сообщение в лог-файл
     * !!! если my7::LOG_DATA===false то записи лога не происходит!
     * @param <type> $s
     * @param <type> $s2
     * @param <type> $stp
     */
    static public function log($s,$s2='',$stp=0) {
// $stp==1, то записать также тип переменной
	//$APPLICATION_ENV=getenv('APPLICATION_ENV');
	//$x8=(APPLICATION_ENV<>'production');
	//echo '55'.(my7::LOG_DATA);
		//echo '555'.print_r($my7->log_always).'|'.print_r($APPLICATION_ENV);
        if (my7::LOG_DATA) { // || APPLICATION_ENV<>'production') 
			//echo '44';
            $writer = new Zend_Log_Writer_Stream($my7->basepath.'logs/main.log');
            $logger = new Zend_Log($writer);
            //gettype()
            if ($s2==='') $logger->info($s);
            else {
                if (is_array($s2)) $s3=print_r($s2,true);
                else if (is_object($s2)) $s3=print_r($s2,true);
                else if (is_bool($s2)) $s3=($s2 ? 'True':'False');
                else if (is_null($s2)) $s3='NULL';
                else if (is_int($s2)) $s3=$s2.($stp ? ' (int)' : '');
                else if (is_string($s2)) $s3=$s2.($stp ? ' (str)' : '');
                else $s3=$s2;
                $logger->info($s.' = '.$s3);
            }
            $logger = null;
        };
    }

    /**
     * возвращает конфигурационный файл сайта application.ini считанный в массив
     * @return <type>
     */
    static public function getconfig() {
        // возвращает конфигурацию приложения в виде массива
        global $my7;
        if (!isset($my7->config)) {
            $my7->config=parse_ini_file(APPLICATION_PATH.'/configs/application.ini',true);
        }

        return $my7;
    }

    /**
     * возвращает префикс имени таблиц БД для текущего проекта
     * @global <type> $my7
     * @return <type>
     */
    static public function getdbprefix() {
        global $my7;
        return $my7->getconfig()->config['production']['dbprefix'];
    }

    /**
     * возвращает глобальный объект "соединение с базой данных"
     * @return <type>
     */
    static public function db() {
        // получение адаптера приложения
        return Zend_Db_Table::getDefaultAdapter();
    }

    public static  function encodeheader($input, $charset = 'UTF-8')
{
	$s='';
	for ($i=0;$i<strlen($input);$i++)
		{
		$s.='='.strtoupper(dechex(ord(substr($input,$i,1))));
		};
	return '=?'.$charset.'?Q?'.$s.'?=';
}

    /**
     * выполняет команду БД select и возвращает массив объектов
     * @param <type> $s
     * @return <type>
     */
    static public function qlist($s) {
        $db=my7::db();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        return $db->fetchAll($s);
    }

    /**
     * выполняет команду БД select и возвращает массив массивов
     * @param <type> $s
     * @return <type>
     */
    static public function qarray($s) {
        $db=my7::db();
        $db->setFetchMode(Zend_Db::FETCH_ASSOC);
        return $db->fetchAll($s);
    }
    /**
     * выполняет команду БД select и возвращает 1 объект
     * @param <type> $s
     * @return <type>
     */
    static public function qobj($s) {
        $db=my7::db();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        return $db->fetchRow($s);
    }

    function makelist($arr,$selectednum,$default='') {
        // сформировать текст выпадающего списка
        // $arr - массив, каждая строка которого состоит из двух элементов - значения для строки
        //      и текста
        //      или если размерность массива x на 1 (1 элемент в каждой строке) то нумеруется все
        //      по порядку с 1
        // $selectednum - номер выбранного элемента(если 0, то первый элемент)
        // если $default установлен, то это будет первый элемент строки с индексом 0
        $s='';
        //var_dump($arr);exit;
        if (count($arr)==0) return '';
        $is1dim=!is_array($arr[0]);
        if (!$is1dim) $ark=array_keys($arr[0]);

        $cnt=1;
        if ($default<>'') $s.='<option value="0">'.my7::nbsh($default);
        for ($i=0;$i<count($arr);$i++) {
            if ($is1dim) {
                $s.='<option value="'.$cnt.'"'.($cnt==$selectednum ? ' selected' : '').
                        '>'.my7::nbsh($arr[$i]);
                
                $cnt++;
            } else {
                $s.='<option value="'.$arr[$i][$ark[0]].'"'.((string)$arr[$i][$ark[0]]==$selectednum ? ' selected' : '').
                        '>'.my7::nbsh($arr[$i][$ark[1]]);
            }
        }
        return $s;
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

    static function repltextarray($s,$arr1,$arr2) {

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


    
    /**
     * выполняет команду БД select и возвращает 1 объект
     * экранирует строки в аргументах
     * параметры в строке запроса $s - вида $1, $2, ...
     * @param string $s
     * @param array $arr
     * @return <type>
     */
    static public function qobjS($s, $arr) {
        $arr2=array();
        for ($i=0;$i<count($arr);$i++) {
            if (is_string($arr[$i])) {
                $arr2[] =  mysql_real_escape_string($arr[$i]);
            } else {
                $arr2[] = $arr[$i];
            }
        }
        $mtch=array();
        preg_match_all('/(\$\d+)/m',$s,$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);

        // убераем лишнюю информацию
        $mtch2=array();
        for ($i=0;$i<count($mtch);$i++) {
            $mtch2[]=$mtch[$i][1];
        }

        // проставляем соответствия '$n'
        $zam2=array();
        for ($i=0;$i<count($mtch2);$i++) {
            $zam2[]=strval($arr[intval(substr($mtch2[$i][0],1))-1]);
        }

        $s3=  my7::repltextarray($s, $mtch2, $zam2);
        $db=my7::db();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        return $db->fetchRow($s3);
    }

    /**
     * выполняет команду БД select и возвращает массив объектов
     * экранирует строки в аргументах
     * параметры в строке запроса $s - вида $1, $2, ...
     * @param string $s
     * @param array $arr
     * @return <type>
     */
    static public function qobjarrayS($s, $arr) {
        $arr2=array();
        for ($i=0;$i<count($arr);$i++) {
            if (is_string($arr[$i])) {
                $arr2[] =  mysql_real_escape_string($arr[$i]);
            } else {
                $arr2[] = $arr[$i];
            }
        }
        $mtch=array();
        preg_match_all('/(\$\d+)/m',$s,$mtch, PREG_SET_ORDER+PREG_OFFSET_CAPTURE);

        // убераем лишнюю информацию
        $mtch2=array();
        for ($i=0;$i<count($mtch);$i++) {
            $mtch2[]=$mtch[$i][1];
        }

        // проставляем соответствия '$n'
        $zam2=array();
        for ($i=0;$i<count($mtch2);$i++) {
            $zam2[]=strval($arr[intval(substr($mtch2[$i][0],1))-1]);
        }

        $s3=  my7::repltextarray($s, $mtch2, $zam2);
        $db=my7::db();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        return $db->fetchAll($s3);
    }

    /**
     * Печатает на экран paginator
     * @param <type> $arr
     */
    static public function printpages($arr) {
// печатает на экран список номеров страниц гиперссылками (paginator)
        $nrows=$arr[0];
        $spp=$arr[3];
        $nrowspage=$arr[2];
        $page=$arr[1];
        $url=$arr[4];
        $resh=(isset($arr[5]) ? $arr[5] : '');
        echo '<p align="left">';
        $nrs=max(0,ceil($nrows/$spp)-1);
        if ($nrowspage<$nrows) {
            echo 'Страницы: ';
            for($i=0;$i<=$nrs;$i++) {
                if (($i!=0) && (floor($i/20)==$i/20)) {
                    echo '<br>';
                };
                if ($i==$page) {
                    echo '<b> ['.($i+1).'] </b>';
                } else {
                    echo '<a href="'.my7::baseurl().$url.$i.$resh.'">['.($i+1).']</a> ';
                };
            };
        };
        echo '</p>';
    }

    /**
     * преобразует строковую дату в формате dd.mm.YYYY в вид для записи в БД
     * @param <type> $date
     * @return <type>
     */
    static public function dateconv($date) {
        $data1=substr($date,0,20);
        $tomatch='/^\s*(\d{1,2})\.(\d{1,2})\.(\d{4})\s*$/s';
        $b4=preg_match($tomatch,$data1,$mtch);
        if ($b4) $data1=preg_replace($tomatch,'$3-$2-$1',$mtch[0]);
        if (!$b4 || (trim($data1)=='')||(strtotime($data1)==-1)) {
            //$s3="Неверная дата ".$data1;
            return false;
        };
        return $data1;
    }

    /**
     * проставляет значения чекбоксов из вида 'on' в вид 1,0
     * @param array $arnames
     * @param array $arpost
     */
    static public function setcheckboxes($arnames,&$arpost) {
        for($i=0;$i<count($arnames);$i++) {
            if (isset($arpost[$arnames[$i]])) $arpost[$arnames[$i]]=1;
                else $arpost[$arnames[$i]]=0;
        }
    }

    /**
     * запоминает сообщение об ошибке и $_POST в сессию, затем переходит на заданную страницу
     * @param <type> $url
     * @param <type> $s
     * @param <type> $corr 
     */
    static public function reterror($url,$s,$corr=null) {
        $_SESSION['postsv3']=$_POST;
        if (!is_null($corr)) {
            // заносим корректировки в массив
            foreach ($corr as $k=>$v) {
                $_SESSION['postsv3'][$k]=$v;
            }
        }
        $_SESSION['err3']=$s;
        my7::goUrl($url);
    }

    /**
     * отображает на странице переданное через сессии сообщение об ошибке
     */
    static public function showerror() {
        if (isset($_SESSION['err3'])) {
            echo '<font color=red>'.nl2br(htmlspecialchars($_SESSION['err3'])).'</font><br /><br />';
            unset($_SESSION['err3']);
        };

    }

    /**
     * возвращает транслитерацию строки с русского на английский
     * @param <type> $s
     * @return <type>
     */
    static public function alphatrans($s) {
        $bp=my7::basePath();
        require_once $bp.'/utf8/utf8.php';
        require_once $bp.'/utf8/utils/unicode.php';
        require_once $bp.'/utf8/utils/specials.php';
$alphas=array(
    "а" => "a",
    "б" => "b",
    "в" => "v",
    "г" => "g",
    "д" => "d",
    "е" => "e",
    "э" => "e",
    "ё" => "yo",
    "ж" => "zh",
    "з" => "z",
    "и" => "i",
    "й" => "j",
    "к" => "k",
    "л" => "l",
    "м" => "m",
    "н" => "n",
    "о" => "o",
    "п" => "p",
    "р" => "r",
    "с" => "s",
    "т" => "t",
    "у" => "u",
    "ф" => "f",
    "х" => "h",
    "ц" => "ts",
    "ч" => "ch",
    "ш" => "sh",
    "щ" => "sch",
    "ь" => "",
    "ъ" => "",
    "ы" => "y",
    "ю" => "yu",
    "я" => "ya"
    );
        
//        $s2=utf8_strtr(strtolower($s),'абвгдеёжзийклмнопрстуфхцчшщьыъэюя','abvgdeeжziyklmnoprstufhcчшщ_i_euя');
//        $s2=utf8_str_replace('ж','zh',$s2);
//        $s2=str_replace('ч','ch',$s2);
//        $s2=str_replace('ш','sh',$s2);
//        $s2=str_replace('щ','sch',$s2);
//        $s2=str_replace('я','ya',$s2);
//        if (strtolower($s)==$s2) $s2=$s;
    $s2=strtr(utf8_strtolower($s),$alphas);
        return $s2;
    }

/**
 * создать каталог если его нет и сделать доступным для записи (777)
 * @param <type> $catalog 
 */
    static public function makeavailcat($catalog) {
        if (!is_dir($catalog)) {
            @mkdir($catalog);
        };
        @chmod($catalog,0777);
    }

    /**
     *
     * возвращает уникальный для сайта идентификатор (integer)
     * обычно испльзуется для генерации названий изображений;
     * сейчас число берется из таблицы БД
     */
    static public function siteuniqid() {
        $tbl=my7::getdbprefix().'counter';
        $db=my7::db();
        $db->insert($tbl,array('uid'=>0));
        $r=$db->lastInsertId();
        $db->delete($tbl,"uid<($r-9)");
        return $r;
    }

 /**
  * генерирует уменьшенное изображение jpeg, сжимая его пропорционально по одной из осей, либо по обоим осям
  * @param <type> $cx1
  * @param <type> $cy1
  * @param <type> $inf1
  * @param <type> $outf1
  * @return <type> 
  */
    static public function resamplejpeg3($cx1,$cy1,$inf1,$outf1) {
        // !!! работа не проверена
        // создает из одного файла jpeg другой уменьшенный, если он уже не меньше
        // $cx1,$cy1 - требуемые размерности (могут быть 0,если эту размерность нужно пересчитать
        if ($cx1==0 && $cy1==0) return 1;
        $im = @imagecreatefromjpeg($inf1);
        if (!$im) return 0;
        $sx2=imagesx($im);
        $sy2=imagesy($im);

        if (($cx1<>0 && $cy1<>0) || $sx2<=$cx1 || $sy2<=$cy1) {
            @copy($inf1,$outf1);
            return 1;
        }
        else {
            if ($cy1/$cx1>$sy2/$sx2) $cx1=intval($sx2*$cy1/$sy2);
            else $cy1=intval($sy2*$cx1/$sx2);
        };

        if ($cy1==0) $cy1=intval($sy2*$cx1/$sx2);
        if ($cx1==0) $cx1=intval($sx2*$cy1/$sy2);
        $im2=imagecreatetruecolor($cx1,$cy1);

        if (!imagecopyresampled($im2,$im,0,0,0,0,$cx1,$cy1,$sx2,$sy2)) return 0;
        imagejpeg($im2,$outf1,90);
    }

  /**
   * выполняет прямое обращение к драйверу БД;
   * вызываю для команд lock tables,unlock tables
   * @param <type> $s 
   */
    static public function qdirect($s) {
        $db=my7::db();
        $db->getConnection()->exec($s);
    }

    /**
     * инициализирует визуальный html-редактор
     */
    static public function inithtmleditor() {
        //var_dump(my7::basepath()."FCKeditor/fckeditor.php");exit;
        require_once(my7::basepath()."ckeditor/ckeditor.php");
    }

/**
 * создает поле редактирования визуального редактора
 * @param <type> $name
 * @param <type> $html
 * @param <type> $width
 * @param <type> $height 
 */
    static public function editorfield($name,&$html,$width,$height) {
// Create a class instance.
$CKEditor = new CKEditor();

// Do not print the code directly to the browser, return it instead.
$CKEditor->returnOutput = false;

// Path to the CKEditor directory, ideally use an absolute path instead of a relative dir.
//   $CKEditor->basePath = '/ckeditor/'
// If not set, CKEditor will try to detect the correct path.
$CKEditor->basePath = my7::baseurl().'ckeditor/';

// Set global configuration (will be used by all instances of CKEditor).
$CKEditor->config['width'] = '100%';
$CKEditor->config['height'] = $height;
//$ckeditor->config['filebrowserUploadUrl'] = my7::baseUrl().'ckeditor/uploadimage.php';
//echo $ckeditor->config['filebrowserUploadUrl'];exit;
// Change default textarea attributes.
$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);

// The initial value to be displayed in the editor.
$initialValue = '<p>This is some <strong>sample text</strong>. You are using <a href="http://ckeditor.com/">CKEditor</a>.</p>';

// Create the first instance.
$CKEditor->editor($name, $html);
/*        $oFCKeditor = new CKEditor($name);
        $sBasePath=my7::baseurl().'ckeditor/';
        $oFCKeditor->Height = $height;
        $oFCKeditor->BasePath	= $sBasePath;
        $oFCKeditor->editor($name, $html);*/
        //$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/silver/'; - иначе если не комментировать не передаются данные
        //$oFCKeditor->Value = $html;
        //$oFCKeditor->Create();
    }

/**
 * возвращает следующий уникальный для этого контроллера номер
 * @global <type> $my7
 * @return <type> 
 */
    static public function nextnum() {
        global $my7;
        return $my7->_num++;
    }

  /**
   * возвращает следующий уникальный для этой страницы идентификатор
   * @return <type> 
   */
    static public function nextid() {
        return 'idx'.my7::nextnum;

    }

/**
 * экранирует для вывода на экран, просто htmlspecialchars
 * @param <type> $s
 * @return <type> 
 */
    static public function nbsh($s) {
        return htmlspecialchars($s);
    }

/**
 * выполняет функцию mysql replace
 * @param <type> $tbl
 * @param <type> $arr 
 */
    static public function dbreplace($tbl,$arr) {
        $db=my7::db();
        $ar2=array_keys($arr);
        $arnames=array();
        for ($i=0;$i<count($ar2);$i++) {
            if (is_string($arr[$ar2[$i]])) {
                $arr[$ar2[$i]]=$db->quote($arr[$ar2[$i]]);
            }
            array_push($arnames,$ar2[$i]);
        }
        $s2=join(',',$arr);
        $sn=join(',',$arnames);
        //my7::log('k',"replace into $tbl ($sn) values ($s2)");
        my7::qdirect("replace into $tbl ($sn) values ($s2)");
    }
/**
 * выполняет функцию mysql replace
 * тестирует если запись 
 * @param <type> $tbl
 * @param <type> $arr 
 */
    static public function dbselreplace($uid,$tbl,$arr) {
        $row = my7::qobj("select 1 from $tbl where uid=$uid");
        if ($row) {
            $db=my7::db();
            $ar2=array_keys($arr);
            $arnames=array();
            for ($i=0;$i<count($ar2);$i++) {
                array_push($arnames, $ar2[$i]."=".$db->quote($arr[$ar2[$i]]));
            }
            $s=join(', ',$arnames);
            my7::qdirect("update $tbl set $s where uid=$uid");
        } else {
            $db=my7::db();
            $arr['uid']=$uid;
            $ar2=array_keys($arr);
            $arnames=array();
            for ($i=0;$i<count($ar2);$i++) {
                if (is_string($arr[$ar2[$i]])) {
                    $arr[$ar2[$i]]=$db->quote($arr[$ar2[$i]]);
                }
                array_push($arnames,$ar2[$i]);
            }
            $s2=join(',',$arr);
            $sn=join(',',$arnames);
            //my7::log('k',"replace into $tbl ($sn) values ($s2)");

            my7::qdirect("insert into $tbl ($sn) values ($s2)");
        }
    }

 /**
  * должен возвращать имя текущего view в формате tree-dop-menu
  * @return <type> 
  */
    static public function curaction() {
        return Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }

} // end class

$my7=new my7();
?>