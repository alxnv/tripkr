<?php
if (defined('APP_STATE_CHANGED')) {
$x=0;
}
$ar4=array();
$s=defined('APPLICATION_ROOT') ? APPLICATION_ROOT : '';
$what=array("'<script[^>]*?>.*?</script>'si",'/[\x00-\x09|\x0B-\x0C|\x0E-\x1F]/',"/\r/","/\n/");
$repl=array('','','','');
array_push($ar4,$what);
array_push($ar4,$repl);

?>
