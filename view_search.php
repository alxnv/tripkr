<? 
include ("blocks/bd.php");

if (isset($_POST['search']))
{
$search = $_POST['search'];
}
if (isset($search))
{



$search = trim($search);
$search = stripslashes($search);
$search = htmlspecialchars($search);

}

else 
{
exit("<p>Вы обратились к файлу без необходимых параметров.</p>");
}


?> 


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><? echo "Результаты поиска по запросу - $search "; ?></title>
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
<?
	$result = mysql_query("SELECT * FROM data WHERE title OR text LIKE '%$search%' AND vid='1'",$db);

if (!$result)
{
echo "<p>Запрос на выборку данных из базы не прошел. </p>";
exit(mysql_error());
}

if (mysql_num_rows($result) > 0)

{
$myrow = mysql_fetch_array($result); 

do
{
printf("<table class='kontent'>
<tr>
<td width='150'><img align='left' src='mini%s'>
</td>
<td width='450'><h3><a href='view_post.php?id=%s'>%s</a></h3><p>%s</p>
</td>
</tr>
</table><br>",$myrow["mini_img"],$myrow["id"],$myrow["title"],$myrow["description"]);
}
while ($myrow = mysql_fetch_array($result));
}

else
{
echo "<p class='cont'>Информация по Вашему запросу не найдена.</p>";

}

?>



</td>
<? include ("blocks/righttd.php"); ?>

</tr>

<tr>
<td colspan="3"></td>
</tr></table>



</body>
</html>
