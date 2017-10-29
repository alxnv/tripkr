<?
// пользовательские функции для этого проекта
function testlogged() {
    global $db3, $lgnuid3;
	if (isset($_COOKIE['login']) && isset($_COOKIE['pwd'])) {
		$login2=mysql_real_escape_string(substr($_COOKIE['login'],0,100));
		$pwd2=mysql_real_escape_string(substr($_COOKIE['pwd'],0,100));
		//$uid2=intval($_COOKIE['lgnuid']);
		$row=my3::qobj("select uid,login,fio,naimfirm from et_zayav where login='$login2'
			and md5(pwd)='$pwd2' and ismoderated=1");
		if ($row) {
			$lgnuid3=$row;
			$month = time()+60*60*24*30; //mktime(0,0,0,1,1,2030);
			setcookie('login', $row->login, $month, '/');
			setcookie('pwd', $pwd2, $month, '/');
			return 1;
			}
	}
	$lgnuid=0;
	return 0;
}
?>