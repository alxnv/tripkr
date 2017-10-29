<?php
/*
Copyright (C) 2010 Воробьев Александр
отображение таблицы с добавлением и удалением рядов кнопками +,-
*/

class My_plusminusstruct {
//'<select name="sl'+i+'[]">'+se.innerHTML+'</select> ,срок размещения: <select name="sz'+i+'[]" style="width:140px">'+sq.innerHTML+'</select> или до <input type="text" size="10" name="sd'+i+'[]">';
//
    function gethtml($afiles) {
        // на входе файл,имя файла,...
        $iplus='<img alt="0" width="28" height="30" src="'.my3::baseurl().'img/admin/plus.gif" />';
        $iminus='<img alt="0" width="28" height="30" src="'.my3::baseurl().'img/admin/minus.gif" />';
        $s='
<script type="text/javascript">

d=document;
isDOM=document.getElementById;
isOpera=isOpera5=window.opera && isDOM;
isMSIE=document.all && document.all.item && !isOpera;

function add35(obj,ishd) {
if (typeof(ishd)=="undefined") ishd=0;
var e=d.getElementById("tbl35");
var numtr=getnumtr5(obj);
dob35(e,numtr,ishd);
obj.scrollIntoView();
}

function del35(obj) {
var e=d.getElementById("tbl35");
//var numtr=getnumtr5(obj);
objprev=remove35(e,obj);
objprev.scrollIntoView();
}

function findparent5(tag,obj) {
var obj2=obj;
while (obj2.tagName!=tag) {
 obj2=obj2.parentNode;
 };
return obj2;
};

function getnumtr5(obj,ishd) {
var cnt=1;
var obj2=findparent5("TR",obj);
while (obj2!=null) {
 obj2=obj2.previousSibling;
 if (obj2!=null && obj2.tagName=="TR") cnt++;
 };
//if (!isMSIE && cnt!=1) cnt--;
return cnt;
}


function dob35(e,numtr,ishd) {
var arr=[\'\',\'<div style="width:100%;height:100%;text-align:center;margin:10px 0 0 0"><input type="file" name="userfile[]" /></div>\',
 \'<p align="center"><a class="nobg hand"  onClick="add35(this)">'.$iplus.'</a><a class="nobg hand" onClick="del35(this)">'.$iminus.'</a></p>\'];

arr[0]=\'<input type="hidden" name="idl[]" value="\-1" />\';
if (isMSIE) {
 row=e.insertRow(numtr);
 var td1 = d.createElement("TD");
 var td2 = d.createElement("TD");
 var td3 = d.createElement("TD");
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
 ar2[0].parentNode.insertBefore(row,ar2[numtr]);
 row.innerHTML="<td>"+arr[0]+"</td><td>"+arr[1]+"</td><td>"+arr[2]+"</td>";
 };
}

function remove35(tbl,obj) {
var tr=findparent5("TR",obj);
var tobj;
if (isMSIE) tobj=tbl.getElementsByTagName("TBODY")[0];
 else tobj=tbl;
var rob=tr;
do {
 rob=rob.previousSibling;
 } while (rob.tagName!="TR");
tr.parentNode.removeChild(tr);
return rob;
}

function uda(i,obj) {
// not used
var e=d.getElementById(\'trd\'+i);
if (obj.parentNode.parentNode.parentNode.tagName==\'TBODY\')
 e.getElementsByTagName(\'TBODY\')[0].removeChild(obj.parentNode.parentNode);
 else e.removeChild(obj.parentNode.parentNode);
};
</script>

            <div align="center"><table class="leftaligntd topaligntd" id="tbl35" style="min-width:500px" border="1" cellpadding="3" cellspacing="0">
            <tr><th>Название</th><th>Загрузка</th>
            <th><a class="nobg hand" onClick="add35(this,1)">'.$iplus.'</a>
            </th></tr>';
        $nish=count($afiles)>>1;
        for ($i=0;$i<$nish;$i++) {
            $s.='<tr><td><input type="hidden" name="idl[]" value="'.$i.'" />'.my3::nbsh($afiles[$i*2+1]).
            '</td><td><div style="width:100%;height:100%;text-align:center;margin:10px 0 0 0"><input type="file" name="userfile[]" /></div></td>
 <td><p align="center"><a class="nobg hand"  onClick="add35(this)">'.$iplus.'</a><a class="nobg hand" onClick="del35(this)">'.$iminus.'</a></p></td></tr>';
        }
        $s.='</table></div>';
        return $s;
    }
}

?>
