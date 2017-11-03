<?
if (!isset($conf35)) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
$title='US Korea. Войти в личный кабинет';
?>
<? include "ssi-top.php"; ?>
<h2 align="center">Восстановить пароль</h2>
<?
if (isset($_SESSION['err34'])) {
	echo '<p class="red" align="center">'.nl2br(my3::nbsh($_SESSION['err34'])).'</p>';
	unset($_SESSION['err34']);
}
?>
<form method="post" action="<?=BS?>work/retpwd">
<table class="logtab" style="margin:0 auto">
<tr>
<td>Логин:</td>
<td><input type="text" name="login" style="width:100%" />
</tr>

<tr valign="top">
<td>Число для проверки <span class="red">*</span>:</td>
<td>
<img src="<?=BS?>kcaptcha/index.php?<?php echo session_name()?>=<?php echo session_id()?>" border="0" width="400" height="200" /><br />
<input type="text" name="keystring" style="width:100%" /></td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" value="Восстановить" />
</td>
</tr>
</table>
</form>

<? include "ssi-bottom.php" ?>