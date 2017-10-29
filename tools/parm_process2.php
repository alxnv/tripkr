<?php
if (APPLICATION_ENV=='production') $path593=realpath(dirname(__FILE__).'/../../../');
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', APPLICATION_ENV=='production' ? $path593.'/application' : 'c:\Users\User\1-tripkr\application');
?>
