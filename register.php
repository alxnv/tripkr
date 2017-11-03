<?
error_reporting (E_ALL);

if (!isset($conf35)) die;
include_once "tools/my4.php";
require_once "tools/funct.php";
$title='US Korea. Регистрация для туроператоров';
?>
<? include "ssi-top.php"; ?>
<h2 align="center">Регистрация для туроператоров</h2>
<?
if (isset($_SESSION['err34'])) {
	echo '<p class="red">'.nl2br(my3::nbsh($_SESSION['err34'])).'</p>';
	unset($_SESSION['err34']);
}
if (isset($_SESSION['psv3'])) {
	$v=(object)$_SESSION['psv3'];
	unset($_SESSION['psv3']);
} else {
	$v=(object)array('login'=>'','pwd'=>'','naim'=>'','fio'=>'','dolgn'=>'', 'email'=>'', 'phone'=>'', 'rnum'=>'',
	'site'=>'', 'city'=>'');
}

?>
<form onsubmit="ga('send', 'event', 'Form', 'Registration', 'New TO', 1);" method="post" action="<?=BS?>work/register">
<table class="logtab" style="margin:0 auto">
<tr>
<td>Логин <span class="red">*</span>:</td>
<td><input type="text" name="login" style="width:100%" value="<?=my3::nbsh($v->login)?>" /></td>
</tr>
<tr>
<td>Пароль <span class="red">*</span>:</td>
<td><input type="password" name="pwd" style="width:100%" /></td>
</tr>

<tr>
<td>Повторите пароль <span class="red">*</span>:</td>
<td><input type="password" name="pwd2" style="width:100%" /></td>
</tr>

<tr>
<td>Название компании <span class="red">*</span>:</td>
<td><input type="text" name="naim" style="width:100%"  value="<?=my3::nbsh($v->naim)?>" /></td>
</tr>

<tr>
<td>Имя (ФИО) <span class="red">*</span>:</td>
<td><input type="text" name="fio" style="width:100%"  value="<?=my3::nbsh($v->fio)?>" /></td>
</tr>

<tr>
<td>Должность <span class="red">*</span>:</td>
<td><input type="text" name="dolgn" style="width:100%" value="<?=my3::nbsh($v->dolgn)?>"  /></td>
</tr>

<tr>
<td>E-mail <span class="red">*</span>:</td>
<td><input type="text" name="email" style="width:100%"  value="<?=my3::nbsh($v->email)?>" /></td>
</tr>

<tr>
<td>Контактный телефон <span class="red">*</span>:</td>
<td><input type="text" name="phone" style="width:100%"  value="<?=my3::nbsh($v->phone)?>" /></td>
</tr>

<tr>
<td>Номер в &quot;Едином Федеральном Реестре Туроператоров&quot; 
 <a target="_blank" href="http://reestr.russiatourism.ru/">http://reestr.russiatourism.ru/</a> <span class="red">*</span><br />
 (для нероссийских ТО укажите номер лицензии или прочий идентификационный номер) :</td>
<td><input type="text" name="rnum" style="width:100%"  value="<?=my3::nbsh($v->rnum)?>" /></td>
</tr>

<tr>
<td>Сайт компании <span class="red">*</span>:</td>
<td><input type="text" name="site" style="width:100%"  value="<?=my3::nbsh($v->site)?>" /></td>
</tr>

<tr>
<td>Город <span class="red">*</span>:</td>
<td><input type="text" name="city" style="width:100%"  value="<?=my3::nbsh($v->city)?>" /></td>
</tr>

<tr valign="top">
<td>Число для проверки <span class="red">*</span>:</td>
<td>
<img src="<?=BS?>kcaptcha/index.php?<?php echo session_name()?>=<?php echo session_id()?>" border="0" width="400" height="200" /><br />
<input type="text" name="keystring" style="width:100%" /></td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" value="Зарегистрироваться" />
</td>
</tr>
</table>
</form>
<p align="center">* Ваш личный кабинет будет доступен Вам после модерации<br />
<span class="red">*</span> отмечены поля обязательные для заполнения
<br /><br />
* Самим фактом заполнения данной анкеты Туроператор дает согласение на сбор и обработку конфиденциальной информации. 
Указанный в анкете электронный адрес будет использован для рассылок актуальных цен, туров и прочей информации.
</p>

<? include "ssi-bottom.php" ?>