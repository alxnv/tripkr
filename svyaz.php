<? include ("blocks/bd.php");/*Соединяемся с базой*/ 
$result = mysql_query("SELECT * FROM settings WHERE page='svyaz'",$db);
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
		<h2 align="center"><? echo $myrow["title"]; ?></h2>
		<p class="cont"><? echo $myrow["text"]; ?></p>



</td>
<? include ("blocks/righttd.php"); ?>

</tr>

<tr>
<td colspan="3"></td>
</tr></table>



</body>
</html>
