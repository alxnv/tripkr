<?php
echo '$_SERVER[REMOTE_ADDR]='.$_SERVER['REMOTE_ADDR'];
echo '<br />';
if (isset($_SERVER['HTTP_CLIENT_IP'])) echo '$_SERVER[HTTP_CLIENT_IP]='.$_SERVER['HTTP_CLIENT_IP'];
echo '<br />';
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) echo '$_SERVER[HTTP_X_FORWARDED_FOR]='.$_SERVER['HTTP_X_FORWARDED_FOR'];
?>