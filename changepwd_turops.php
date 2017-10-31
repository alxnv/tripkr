<?
if (!isset($conf35)) die;
include_once "tools/my4.php";
require_once "funct1.php";
$spp=10;
$title='US Korea. Новости для туроператоров';
if (!testlogged()) {
	header('Location: '.my3::SITE);
	exit;
}
//$row2=$db3->qobj("select * from mg_html where sid='".$db3->escape($uid)."'");
include "ssi-top.php";

echo '<h1 class="title">Изменение пароля</h1>';
//echo $row2->html;
if (isset($_SESSION['err34'])) {
	echo '<p class="red" align="center">'.nl2br(my3::nbsh($_SESSION['err34'])).'</p>';
	unset($_SESSION['err34']);
}
?>
<form method="post" action="<?=BS?>work/changepwd">
<table class="logtab" style="margin:0 auto">
<tr>
<td>Новый пароль:</td>
<td><input type="password" name="pwd" style="width:150px" />
</tr>

<tr>
<td>Повторите пароль:</td>
<td><input type="password" name="pwd2" style="width:150px" />
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" value="Изменить" />
</td>
</tr>
</table>
</form>
<?
include "ssi-bottom.php";
?>