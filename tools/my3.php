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
    const SITEMAIL='mow.ustravel@gmail.com'; //  mail администратора сайта. на него отсылаются все оповещения

    function __construct() {
        $this->baseurl=dirname($_SERVER['SCRIPT_NAME']).'/';
        if ($this->baseurl=='//') $this->baseurl='/';
        $this->basepath=dirname($_SERVER["SCRIPT_FILENAME"]).'/';
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