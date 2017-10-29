<?php
/* 
отображение таблицы с добавлением и удалением рядов кнопками +,-
*/

class My_plusminusstruct {
//'<select name="sl'+i+'[]">'+se.innerHTML+'</select> ,срок размещения: <select name="sz'+i+'[]" style="width:140px">'+sq.innerHTML+'</select> или до <input type="text" size="10" name="sd'+i+'[]">';
//
    function gethtml() {
        $iplus='<img alt="0" width="28" height="30" src="'.my7::baseurl().'img/admin/plus.gif" />';
        $iminus='<img alt="0" width="28" height="30" src="'.my7::baseurl().'img/admin/minus.gif" />';
        $s='<script type="text/javascript">
            function add35(obj) {
            e=d.getElementById("tbl35");
            dob35(e);
            }

d=document;
isDOM=document.getElementById;
isOpera=isOpera5=window.opera && isDOM;
isMSIE=document.all && document.all.item && !isOpera;

function dob35(e) {
var arr=[\'qw\',\'aty\',\'<p align="center"><a class="nobg" href="javascript:add35(this)">'.$iplus.'</a><a class="nobg" href="javascript:del35(this)">'.$iminus.'</a></p>\'];

if (isMSIE) {
 row=e.insertRow(1);
 var td1 = d.createElement("TD");
 var td2 = d.createElement("TD");
 var td3 = d.createElement("TD");
 var td4 = d.createElement("TD");
 row.appendChild(td1);
 row.appendChild(td2);
 row.appendChild(td3);
 td1.innerHTML=arr[0];
 td2.innerHTML=arr[1];
 td3.innerHTML=arr[2];
 } else {
 var row = d.createElement("TR");
 //e.appendChild(row);
 ar2=e.getElementsByTagName("TR");
 e.insertBefore(row,ar2[1]);
 row.innerHTML="<td>"+arr[0]+"</td><td>"+arr[1]+"</td><td>"+arr[2]+"</td>";
 };
}
function uda(i,obj) {
var e=d.getElementById(\'trd\'+i);
if (obj.parentNode.parentNode.parentNode.tagName==\'TBODY\')
 e.getElementsByTagName(\'TBODY\')[0].removeChild(obj.parentNode.parentNode);
 else e.removeChild(obj.parentNode.parentNode);
};
</script>

            <div align="center"><table id="tbl35" style="min-width:500px" border="1" cellpadding="3" cellspacing="0">
            <tr><th>Название</th><th>Загрузка</th>
            <th><a class="nobg" href="javascript:add35(this)">'.$iplus.'</a>
            </th></tr>';
        $s.='</table></div>';
        return $s;
    }
}

?>
