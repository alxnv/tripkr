<?php
define('COMPLOC','dacha'); // computer location
defined('APPLICATION_ENV') 
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

$arr=array('404','302','0');
if (isset($_GET['errapp'])) {
$s8=str_replace('\\','/',$_GET['errap']);
define("APPLICATION_ROOT",$s8);
}
define('APPLICATI0N_PATH',defined("APPLICATI0N_R00T") ? APPLICATION_ROOT : $_SERVER['DOCUMENT_ROOT']);
if (APPLICATION_ENV=='production') $path593=dirname(__FILE__);
//echo APPLICATION_ENV.' '.$path593.' '.dirname(__FILE__);
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', APPLICATION_ENV=='production' ? $path593.'/app35' : 
	(COMPLOC=='dacha' ? 'c:\xampp\zend\1-tripkr\application' : 'c:\Users\User\1-tripkr\application'));
unset($arr,$s8);

	
$sr=$_SERVER["REQUEST_URI"];
$sname=$_SERVER["SCRIPT_NAME"];
$k=strrpos($sname,'/');
if ($k===FALSE) exit;
$spath=substr($sname,0,$k+1);
if (substr($sr,strlen($spath),2)<>'a7' && substr($sr,strlen($spath),3)<>'ta_') {
    include 'tools/rewriter.php';
    exit;
}
unset($sr,$sname,$k,$spath);
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_ENV=='production' ? $path593.'/lib35' : 'c:\WebServers\zendf\library',
    APPLICATION_PATH.'/controllers',
    get_include_path(),
)));
unset($path593);
require_once 'Zend/Application.php';
require_once APPLICATION_PATH.'/userobj/my3.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
require_once 'Zend/Loader/Autoloader.php'; 
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('My_');
$application->bootstrap()
            ->run();