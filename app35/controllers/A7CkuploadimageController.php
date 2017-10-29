<?php


class A7CkuploadimageController extends Zend_Controller_Action
{

function ckuploadAction()
{
//echo getcwd();!!
$allowed_exts=array('zip','arj','gif','png','jpeg','jpg','xls','xlsx','doc','docx','rtf','pdf','txt','rar','eps'); // допустимые расширения файлов для загрузки
//require_once 'tools/my7.php';
//$my7=new my7();

session_start();
//my7::log('allw',$_SESSION['login']);
if ($_SESSION['login']!=='admin5') exit; // вызов не из админки
$callback = $_GET['CKEditorFuncNum'];
$file_name = $_FILES['upload']['name'];
$file_name_tmp = $_FILES['upload']['tmp_name'];
$file_new_name = my7::basePath().'images/upload/'; // серверный адрес - папка для сохранения файлов. (нужны права на запись)
$error = '';
//$error.=$filename;
$full_path = $file_new_name.my7::alphatrans($file_name);
$ext=my7::getExtension($full_path);
while (is_file($full_path)) {
    $fn=my7::withoutExt($full_path);
    $ext=my7::getExtension($full_path);
    $full_path=$fn.'1.'.$ext;
//    $error.='jj '.$full_path;
}
$http_path = my7::baseUrl().'images/upload/'.basename($full_path); // адрес изображения для обращения через http
//my7::log('ss',$file_name.'@1@'.$ext.'@2@'.in_array(strtolower($ext),$allowed_exts).'@3@'.$file_name_tmp.'@4@'.$full_path);
if(in_array(strtolower($ext),$allowed_exts) && move_uploaded_file($file_name_tmp, $full_path) )
{
// можно добавить код при успешном выполнение загрузки файла
//$error.=' yes ';    
    // если ширина файла  больше 700px то ужимаем
    $fn1=$full_path;
    @chmod($fn1,0666);
    $s=my7::getExtension($fn1);
    if (strtolower($s)=='jpg' || strtolower($s)=='jpeg') my7::resamplejpeg3(700,0,$fn1,$fn1);
    
} else
{
$error = 'Ошибка, повторите попытку позже'; // эта ошибка появится в браузере если скрипт не смог загрузить файл
$http_path = '';
}
echo "<script type='text/javascript'>// <![CDATA[
window.parent.CKEDITOR.tools.callFunction(".$callback.",  \"".$http_path."\", \"".$error."\" );
// ]]></script>";
exit;
}

}