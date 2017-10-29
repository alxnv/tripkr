<? include ("blocks/bd.php");/*Соединяемся с базой*/ 
if (isset ($_GET['rod_cat'])) {$rod_cat = $_GET['rod_cat'];}
if (!isset ($_GET['rod_cat'])) {$rod_cat = 0;}

if (isset ($_GET['cat'])) {$cat = $_GET['cat'];}
if (!isset ($_GET['cat'])) {$cat = 1;}

$result = mysql_query("SELECT * FROM categories WHERE id='$cat'",$db);
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
		<p align="center"><? echo $myrow["text"]; ?></p>
        <?
		$result77 = mysql_query("SELECT str FROM options", $db);
$myrow77 = mysql_fetch_array($result77);
$num = $myrow77["str"];
// Извлекаем из URL текущую страницу
@$page = $_GET['page'];
// Определяем общее число сообщений в базе данных
$result00 = mysql_query("SELECT COUNT(*) FROM data WHERE cat='$cat' AND vid='1'");
$temp = mysql_fetch_array($result00);
$posts = $temp[0];
// Находим общее число страниц
$total = (($posts - 1) / $num) + 1;
$total =  intval($total);
// Определяем начало сообщений для текущей страницы
$page = intval($page);
// Если значение $page меньше единицы или отрицательно
// переходим на первую страницу
// А если слишком большое, то переходим на последнюю
if(empty($page) or $page < 0) $page = 1;
  if($page > $total) $page = $total;
// Вычисляем начиная с какого номера
// следует выводить сообщения
$start = $page * $num - $num;
// Выбираем $num сообщений начиная с номера $start
		
		
$result = mysql_query("SELECT * FROM data WHERE cat='$cat' AND vid='1' LIMIT $start, $num",$db);
if (!$result){
echo "<p class='cont'>Данная категория пуста</p>";
} else
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

// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href=view_cat.php?cat='.$cat.'&page=1>Первая</a> | <a href=view_cat.php?cat='.$cat.'&page='. ($page - 1) .'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' | <a href=view_cat.php?cat='.$cat.'&page='. ($page + 1) .'>Следующая</a> | <a href=view_cat.php?cat='.$cat.'&page=' .$total. '>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 5 > 0) $page5left = ' <a href=view_cat.php?cat='.$cat.'&page='. ($page - 5) .'>'. ($page - 5) .'</a> | ';
if($page - 4 > 0) $page4left = ' <a href=view_cat.php?cat='.$cat.'&page='. ($page - 4) .'>'. ($page - 4) .'</a> | ';
if($page - 3 > 0) $page3left = ' <a href=view_cat.php?cat='.$cat.'&page='. ($page - 3) .'>'. ($page - 3) .'</a> | ';
if($page - 2 > 0) $page2left = ' <a href=view_cat.php?cat='.$cat.'&page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href=view_cat.php?cat='.$cat.'&page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';

if($page + 5 <= $total) $page5right = ' | <a href=view_cat.php?cat='.$cat.'&page='. ($page + 5) .'>'. ($page + 5) .'</a>';
if($page + 4 <= $total) $page4right = ' | <a href=view_cat.php?cat='.$cat.'&page='. ($page + 4) .'>'. ($page + 4) .'</a>';
if($page + 3 <= $total) $page3right = ' | <a href=view_cat.php?cat='.$cat.'&page='. ($page + 3) .'>'. ($page + 3) .'</a>';
if($page + 2 <= $total) $page2right = ' | <a href=view_cat.php?cat='.$cat.'&page='. ($page + 2) .'>'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href=view_cat.php?cat='.$cat.'&page='. ($page + 1) .'>'. ($page + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
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
