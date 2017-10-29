<?php
/* 
сохранить информацию о title,keywords
  */
if (!isset($conf35)) exit;
@session_start();
if ((isset($_SESSION['login']) && ($_SESSION['login']=='admin5')) && isset($_SESSION['viewsite583'])) {
    // зарегены
    $url1=urldecode($_POST['url']);
    $ti=mysql_real_escape_string($_POST['title']);
    $descr=mysql_real_escape_string($_POST['descr']);
    $keyw=mysql_real_escape_string($_POST['keywords']);
    $db3->q('lock tables '.$conf35['production']['dbprefix']."rewrite write");
    $db3->q('delete from '.$conf35['production']['dbprefix']."rewrite where url='".$db3->escape($url1)."'");
    if ($ti=='' && $keyw=='') {
    } else {
        $db3->q('insert into '.$conf35['production']['dbprefix']."rewrite (url,title,description,keywords) values ('".$db3->escape($url1)."','$ti','$descr','$keyw')");
    }
    $db3->q('unlock tables');
    my3::goUrl($url1);
} else {
    die('Неправильные параметры (2)');
};
?>
