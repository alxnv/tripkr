<?
// на странице туроператоров - выводим гиперссылки логин и регистрация

require_once "funct1.php";
//var_dump($_COOKIE);
if (isset($_SESSION['thanks5'])) {
	unset($_SESSION['thanks5']);
	echo '<p align="center" style="font-weight:bold;font-size:120%">Спасибо, вот вы и в специальном разделе для Туроператоров</p>';
}
?>
<div class="plashka" style="font-weight:bold;font-size:120%;line-height:200%">
<?
if (testlogged()) {
?>
<p align="center">
<?
echo my3::nbsh($lgnuid3->login).' ';
?>
<a href="<?=BS?>work/logout"  style="margin-left:30px" onClick="return window.confirm('Вы уверены что хотите выйти из личного кабинета?')">Выйти</a>
<a href="<?=BS?>register" style="margin-left:30px">Регистрация</a>
<a href="<?=BS?>retpwd" style="margin-left:30px">Восстановить пароль</a>
<a href="<?=BS?>allnewsto/0" style="margin-left:30px">Новости для туроператоров</a>
</p>

<?
} else {
?>
<p align="center">
<a href="<?=BS?>login">Войти</a>
<a href="<?=BS?>register" style="margin-left:30px">Регистрация</a>
<a href="<?=BS?>retpwd" style="margin-left:30px">Восстановить пароль</a>
</p>
<?
}
?>
</div><br />