
<table class="left_menu" style="margin-bottom:20px">
<tr><!-- class="bgwhite" -->
<td>
 <?php  

  // Получаем текущие курсы валют в rss-формате с сайта www.cbr.ru  

  $content = @get_content();  

  // Разбираем содержимое, при помощи регулярных выражений  

  $pattern = "#<Valute ID=\"([^\"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i";  

  preg_match_all($pattern, $content, $out, PREG_SET_ORDER);  

  $dollar = "";  

  $euro = "";
  
  $von = "";  
 foreach($out as $cur)  

  {  

    if($cur[2] == 840) $dollar = str_replace(",",".",$cur[4]); 

    if($cur[2] == 978) $euro   = str_replace(",",".",$cur[4]);  

    if($cur[2] == 410) $von  = str_replace(",",".",$cur[4]);  

  }  
$dollar=round((floatval($dollar)),2);  
  echo "Доллар - ".$dollar.  ' руб, ';  

$euro=round((floatval($euro)),2);  
  echo "Евро - ".$euro." руб, ";  

$von=round((floatval($von)),2);  
  echo "1000 Вон - ".$von.' руб';  

  function get_content()  

  {  

    // Формируем сегодняшнюю дату  

    $date = date("d/m/Y");  

    // Формируем ссылку  

    $link = "www.cbr.ru";
    $link2="/scripts/XML_daily.asp?date_req=$date";  

    // Загружаем HTML-страницу  
    $text="";  
    //var_dump($_SERVER["SERVER_NAME"]);
    if ($_SERVER["SERVER_NAME"]=='localhost') {
        $fd=false;
    } else {
        $fd = @fsockopen('https://'.$link, 80, $errno, $errstr, 1);
    }
    
    if (!$fd) {
        $bad=1;  
    //$fd = fopen($link, "r");  



    } else {
    @stream_set_timeout($fd, 1);
    $out = "GET $link2 HTTP/1.1\r\n";
    $out .= "Host: $link\r\n";
    $out .= "Connection: Close\r\n\r\n";

    @fwrite($fd, $out);


      // Чтение содержимого файла в переменную $text  
      $bad=0;
      while (!feof ($fd)) {
          $tx=@fgets($fd, 4096);  
          if ($tx) {
            $text .= $tx;
          } else {
              $bad=1;
              break;
          }
      }

    }  
    $saveto=dirname(__FILE__).'/img2/valuta.htm';
    $s=file_get_contents($saveto);
    if ($bad) {
        $text=$s;
    } else {
        if ($s<>$text) {
            //file_put_contents($saveto,$text);
            // безопасная работа с файлом с блокировкой
            $fp = fopen ( $saveto , "a" ); //открытие 
            flock ( $fp , LOCK_EX ); //блокировка файла 
            ftruncate ( $fp , 0 ); //УДАЛЯЕМ СОДЕРЖИМОЕ ФАЙЛА 
            fputs ( $fp , $text ); //работа с файлом 
            fflush ( $fp ); //очищение файлового буфера и записьв файл 
            flock ( $fp , LOCK_UN ); //снятие блокировки 
            fclose ( $fp ); //закрытие 
        }
    }
 // Закрыть открытый файловый дескриптор  

    if ($fd) @fclose ($fd);  

    return $text;  

  }  

?>
</td></tr></table>

