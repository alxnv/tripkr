<?php
// редирект на редактирование SEO на сайте
if (!isset($conf35)) exit;
@session_start();
if ((isset($_SESSION['login']) && ($_SESSION['login']=='admin5'))) {
    // зарегены
    $_SESSION['viewsite583']=1;
    //my3::goUrl('');
    echo '<script type="text/javascript">
        top.location.href="'.my3::baseurl().'";
        </script>';
} else {
    //var_dump($_SESSION);
    die('Неправильные параметры (1)');
}
?>
