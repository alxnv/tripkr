<?php
//include "ssi-advert.php";

$row93=my3::qobj("select spec,hrefspec from et_settings");
if (trim($row93->spec)<>'' && trim($row93->hrefspec)<>'') {
	echo '<div style="width:245px;height:245px;background-image:url('.BS.'img/office-sticky-note.png);">
<div style="padding:0px 20px 30px 20px"><br />
	<a href="'.htmlspecialchars($row93->hrefspec).'" class="ahref">'.nl2br(htmlspecialchars($row93->spec)).'</a>
</div></div>';
//	echo '<div style="margin-left:5px; border: #ffe500 solid 2px; padding:2px; background-color:#ffffaa">
//<a href="'.htmlspecialchars($row93->hrefspec).'" class="ahref">'.nl2br(htmlspecialchars($row93->spec)).'</a>
//</div>';

}

if (isset($is_first_page)) {
	/*echo '<br /><br /><br /><div style="width:205px;background-color:#ffe376;margin:0 20px 0 8px;padding:10px 10px"><a
	class="ahref"  href="http://www.gokoreatour.ru/page/142">Туристам: где приобрести
	наши туры?</div>';*/
	echo '<br /><br /><br />
	<a href="http://www.gokoreatour.ru/page/142"><img border="0" 
	style="margin-left:0px" src="http://www.gokoreatour.ru/img/tripkr2.gif" width="205" height="117" /></a>';
};
?>
<br />
<br />
<br />
	<a href="http://korshop.ru/"><img border="0" 
	style="margin-left:0px" src="http://www.gokoreatour.ru/img2/banner1.jpg" width="205" height="117" /></a>
<br />
<br />
<br />
<a href="http://www.kartaturov.ru/advertise/" target="_blank"><img border="0" style="margin-left:10px" src="http://www.gokoreatour.ru/img/kartaturov.jpg" width="168" height="61" /></a>

<div style="margin-top:50px; width:180px;margin-left:5px">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
    <tbody>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ???? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot(1).gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a href="http://tripkr.com/home/aen/default.html#"><img border="0" alt="" src="<?=BS?>img/airport.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot(1).gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ????? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a target="new" href="http://www.smrt.co.kr/Train/Subwaymap/Eng/Subwaymap.jsp"><img border="0" alt="" src="<?=BS?>img/seoul_subway.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ???????? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a target="new" href="http://www.smrt.co.kr/Train/Subwaymap/Eng/Subwaymap.jsp"><img border="0" alt="" src="<?=BS?>img/citytourbus.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ???? ?????? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a href="http://tripkr.com/home/aen/default.html#"><img border="0" alt="" src="<?=BS?>img/kto_freebus.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ???? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a href="http://tripkr.com/home/aen/default.html#"><img border="0" alt="" src="<?=BS?>img/koreanair.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ???? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a href="http://tripkr.com/home/aen/default.html#"><img border="0" alt="" src="<?=BS?>img/kto.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ???? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a target="new" href="http://english.seoul.go.kr/"><img border="0" alt="" src="<?=BS?>img/hiseoul.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
        <tr>
            <td><!---- ?????? --->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/1(1).gif"></td>
                        <td background="<?=BS?>img/bg1(1).gif" width="99%"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/2(1).gif"></td>
                    </tr>
                    <tr>
                        <td background="<?=BS?>img/bg2(1).gif"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
                        <td width="99%"><a href="http://tripkr.com/home/aen/default.html#"><img border="0" alt="" src="<?=BS?>img/kto_food.gif"></a></td>
                        <td background="<?=BS?>img/bg3(1).gif" width="3"><img alt="" width="3" height="1" src="<?=BS?>img/dot.gif"></td>
                    </tr>
                    <tr>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/3(1).gif"></td>
                        <td background="<?=BS?>img/bg4(1).gif"><img alt="" width="3" height="3" src="<?=BS?>img/dot.gif"></td>
                        <td height="3" width="3"><img alt="" width="3" height="3" src="<?=BS?>img/4(1).gif"></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
        <tr>
            <td height="3"><img alt="" width="1" height="1" src="<?=BS?>img/dot.gif"></td>
        </tr>
    </tbody>
</table>
</div>

<br />
