<?php
/* 
 Copyright (C) 2010 Воробьев Александр

 * основной класс для клиентской части (суперглобальный), с теми же названиями фунций что и в zend/my3.php
 * должен заменить my4 и использоваться вместо него
 
 my3.php для бэкэнда находится в каталоге application/userobj
*/
class my3 {
    public $baseurl=null;
    public $basepath=null;
    public $ismobile=null;
    public $onecolumn=null;
    const SITEMAIL='mow.ustravel@gmail.com'; //  mail администратора сайта. на него отсылаются все оповещения
    const SITE='https://www.gokoreatour.ru'; //  сайт

    function __construct() {
        $this->baseurl=dirname($_SERVER['SCRIPT_NAME']).'/';
        if ($this->baseurl=='//') $this->baseurl='/';
        $this->basepath=dirname($_SERVER["SCRIPT_FILENAME"]).'/';
    }

    /**
     * делает редирект, если текущий сайт не my3::SITE с www или без
     */

    function redirectifnotdomain() {
        $ar=parse_url(my3::SITE);
        $s1=$ar['scheme'];
        $shost=$ar['host'];
        if (substr($shost,0,4)=='www.') {
            $sh2=substr($shost,4);
        } else {
            $sh2='www.'.$shost;
        }
        
        $hostname=$_SERVER['SERVER_NAME'];
        $scheme=$_SERVER["REQUEST_SCHEME"];
        
        if ($hostname<>'localhost' && !($s1==$scheme && ($hostname==$shost || $hostname==$sh2))) {

            $s2=parse_url(substr($_SERVER["REQUEST_URI"],0,10000)); //$_SERVER['SCRIPT_NAME']
            $s=my3::SITE.$s2['path'];
            if (isset($s2['query'])) $s.='?'.$s2['query'];
            if (isset($s2['fragment'])) $s.='#'.$s2['fragment'];

            //var_dump('si',$s);
            header("HTTP/1.1 301 Moved Permanently"); 
            header("Location: ".$s); 
            exit();
        }
        

    }
    /**
     * парсит html и заменяет если текущий сайт домен на https://www.gokoreatour.ru
     * @param string $s исходная строка
     * @return string
     */
public static function repldomain($s) {
    include_once dirname(__FILE__)."/../app35/controllers/My/htmlparser.php";
    $pr=new my_htmlparser('/(\<(img)([^>]+)\>)/mi',$s);
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

    
// Validate email address 
public static function is_valid_email($email) {
    // проверяет также на русские домены
	$at = strrpos($email, "@");

	// Make sure the at (@) sybmol exists and  
	// it is not the first or last character
	if ($at && ($at < 1 || ($at + 1) == strlen($email)))
		return false;

	// Make sure there aren't multiple periods together
	if (preg_match("/(\.{2,})/", $email))
		return false;

	// Break up the local and domain portions
	$local = substr($email, 0, $at);
	$domain = substr($email, $at + 1);


	// Check lengths
	$locLen = strlen($local);
	$domLen = strlen($domain);
	if ($locLen < 1 || $locLen > 100 || $domLen < 2 || $domLen > 255)
		return false;

	// Make sure local and domain don't start with or end with a period
	if (preg_match("/(^\.|\.$)/", $local) || preg_match("/(^\.|\.$)/", $domain))
		return false;

        // проверка на email injection
	if (preg_match('/\n|\r|content\-type\:|to\:|from\:|cc\:/', $local)) return false;
	if (preg_match('/\n|\r|content\-type\:|to\:|from\:|cc\:/', $domain)) return false;

        // Check for quoted-string addresses
	// Since almost anything is allowed in a quoted-string address,
	// we're just going to let them go through
	if (!preg_match('/^"(.+)"$/', $local)) 
		// It's a dot-string address...check for valid characters
		if (!preg_match('/^[a-zA-Zа-яА-ЯёЁ0-9!#$%*\/?|^{}`~&\'+=_\.\-]*$/u', $local)) 
			return false;
	

	// Make sure domain contains only valid characters and at least one period
	if (!preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9\.\-]*$/u", $domain) || !strpos($domain, "."))
		return false;	

	return true;
}
    
public static function makeselsimp(&$arr,$n=0) {
$s='';
for ($i=0,$j=count($arr);$i<$j;$i++) {
 $s.='<option '.($i==$n ? 'selected ' : '').'value='.$i.'>'.$arr[$i];
 };
return $s;
}
    public static  function log($s,$s2='',$stp=0) {
// $stp==1, то записать также тип переменной
// почему-то не работает на сервере
			//echo dirname($_SERVER["SCRIPT_FILENAME"]).'/'."main.log";
			//exit;
        if ($_SERVER['SERVER_NAME']=='localhost') {
            if ($s2==='') $s3=$s;
            else {
                if (is_array($s2)) $s3=print_r($s2,true);
                else if (is_object($s2)) $s3=print_r($s2,true);
                else if (is_bool($s2)) $s3=($s2 ? 'True':'False');
                else if (is_null($s2)) $s3='NULL';
                else if (is_int($s2)) $s3=$s2.($stp ? ' (int)' : '');
                else if (is_string($s2)) $s3=$s2.($stp ? ' (str)' : '');
                else $s3=$s2;
                $s3=$s.' = '.$s3;
            }
            $handle = fopen (my3::basepath()."logs/main.log", "a");
            @fwrite ($handle, $s3."\n");
            fclose ($handle);
        };

    }
/*    public static  function logfromck($pathtoroot,$s,$s2='',$stp=0) {
// $stp==1, то записать также тип переменной
        if (true || $_SERVER['SERVER_NAME']=='localhost') {
            if ($s2==='') $s3=$s;
            else {
                if (is_array($s2)) $s3=print_r($s2,true);
                else if (is_object($s2)) $s3=print_r($s2,true);
                else if (is_bool($s2)) $s3=($s2 ? 'True':'False');
                else if (is_null($s2)) $s3='NULL';
                else if (is_int($s2)) $s3=$s2.($stp ? ' (int)' : '');
                else if (is_string($s2)) $s3=$s2.($stp ? ' (str)' : '');
                else $s3=$s2;
                $s3=$s.' = '.$s3;
            }
            $handle = fopen (my3::basepath().$pathtoroot."main.log", "a");
            @fwrite ($handle, $s3."\n");
            fclose ($handle);
        };

    }
*/	
   public static  function qlist($s) {
        global $db3;
        $sth=$db3->q($s);
        $arr=array();
        while($row=mysql_fetch_object($sth)) {
            array_push($arr,$row);
        }
        return $arr;
    }

    public static  function nbsh($s) {
        // экранирует для вывода на экран
        return htmlspecialchars($s);
    }

    public static  function basePath() {
        global $my3;
        return $my3->basepath;
    }

    public static  function baseUrl() {
        global $my3;
        return $my3->baseurl;
    }

    public static  function goUrl($s) {
        header('Location: '.my3::baseurl().$s);
        exit;
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
        global $db3;
        return $db3->qobj($s);
    }

    
    public static  function qobj($s) {
        global $db3;
        return $db3->qobj($s);
    }
    public static  function q($s) {
        global $db3;
        return $db3->q($s);
    }
public static  function gotomessage($e,$sh2=0)
{
	header('Location: '.my3::baseurl().'message/'.urlencode($e));
	exit;
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

    function mnogot($s,$n) {
        if (strlen($s)>$n) {
            return substr($s,0,$n).'...';
        } else {
            return $s;
        }
    }

} // end class
?>