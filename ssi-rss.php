<?php
//include_once "tools/my3.php";
// �������� - ���� �� ������ ���
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
$headers = array( 'Expect:','Connection: Keep-Alive','Accept-Charset: utf-8,windows-1251;q=0.7,*;q=0.7' );
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, 'http://www.ruskorinfo.ru/data/tourism/rss/'); // ����� RSS

$rss_str = curl_exec($ch);

$saveto=dirname(__FILE__).'/img2/news-rss.xml';
$s=file_get_contents($saveto);

if($rss_str) {
	if ($s<>$rss_str) {
		//file_put_contents($saveto,$text);
		// ���������� ������ � ������ � �����������
		$fp = fopen ( $saveto , "a" ); //�������� 
		flock ( $fp , LOCK_EX ); //���������� ����� 
		ftruncate ( $fp , 0 ); //������� ���������� ����� 
		fputs ( $fp , $rss_str ); //������ � ������ 
		fflush ( $fp ); //�������� ��������� ������ � ������� ���� 
		flock ( $fp , LOCK_UN ); //������ ���������� 
		fclose ( $fp ); //�������� 
	}
} else {
	$rss_str=$s;
//print "Oooops. Can't get rss stream.\n";
//exit;
}

$rss = simplexml_load_string($rss_str, 'SimpleXMLElement', LIBXML_NOCDATA);
//print_r ($rss);
foreach ($rss->channel->item as $item) {
echo '<p class="newsnaim"><a href="'.$item->link.'"><strong>'.nl2br(my3::nbsh($item->title)).'</strong></a></p>'.
	'<p class="newsanons">'.$item->pubDate.'<br />'.nl2br($item->description).'</p>';

//print $item->link." (".$item->pubDate.")\n".$item->title."\n".

//$item->description.
//"\n-----\n";
}
*/
?>