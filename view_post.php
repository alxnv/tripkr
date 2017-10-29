<? include ("blocks/bd.php");/*Соединяемся с базой*/ 
if (isset ($_GET['id'])) {$cat = $_GET['id'];}
if (!isset ($_GET['id'])) {$id = 1;}

$result = mysql_query("SELECT * FROM data WHERE id='$id'",$db);
if (!$result)
{
echo "<p>Запрос на выборку данных из базы не рошел, напишите об этом администратору.<br><strong>Код ошибки:</strong></p>";
exit (mysql_error());
}
if (mysql_num_rows($result) > 0)
{
$myrow = mysql_fetch_array($result);
}
else
{
echo "<p>Информация по запросу не может быть извлечена, в таблице нет записей</p>";
}
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><? echo "$myrow[title]"; ?></title>
<meta name="description" content="<? echo $myrow["meta_d"]; ?>">
<meta name="keywords" content="<? echo $myrow["meta_k"]; ?>">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<table id="osnova" width="100%">
<? include ("blocks/top.php"); ?>

<? include ("blocks/top_menu.php"); ?>


<tr>
<td colspan="3"><center><img src="img/kat_img.jpg" id="img_cat"></center></td>
</tr>

<tr>
<? include ("blocks/lefttd.php"); ?>
<td valign="top">
<table>
<tr>
<td>
		<h2 align="center"><? echo $myrow["title"]; ?></h2>
        <table>
        <tr>
        <td valign="top">
        <p><? 
		if ($myrow["mini_img"] == "ser.jpg") {echo "<img src='".$myrow["mini_img"]."'>";}
		else {
		echo"<img src='midi".$myrow["mini_img"]."'>";}
		
		?></p>
        <p><? 
		if ($myrow["midi_img"] == "ser.jpg") {echo "<img src='".$myrow["midi_img"]."'>";}
		else {
		echo"<img src='midi".$myrow["midi_img"]."'>";}
		
		?></p>
        <p><? 
		if ($myrow["big_img"] == "ser.jpg") {echo "<img src='".$myrow["big_img"]."'>";}
		else {
		echo"<img src='midi".$myrow["big_img"]."'>";}
		
		?></p>
</td>
<td valign="top">
		<p class="cont"><? echo $myrow["text"]; ?></p>
        </td>
        </tr>
        </table>
</td>
</tr>
</table>


</td>
<? include ("blocks/righttd.php"); ?>

</tr>

<tr>
<td colspan="3"></td>
</tr></table>



</body>
</html>
