<?php
// Copyright (C) 2010 Воробьев Александр

/* 
визуальные эффекты на странице
 */

class My_PageEffects {
    function savemessage($s) {
        // при сохранении появляется сообщение, потом через 3 секунды исчезает
    $s2='
<script type="text/javascript">
function updatesave5() {
    document.getElementById("toh83").style.visibility="visible";
    tid38=setTimeout("afterloadsec542()",3000);
}

function afterloadsec542() {
//alert(2);
    document.getElementById("toh83").style.visibility="hidden";
};
</script>
<div id="toh83" class="hidingmess" style="visibility:hidden">'.nl2br(htmlspecialchars($s)).'
</div>
';
    return $s2;
    }
    function uploaderrormessage($s) {
        // при сохранении появляется сообщение, потом через 3 секунды исчезает
    $s2='
<script type="text/javascript">
function updateerrupload5() {
    document.getElementById("toh84").style.visibility="visible";
    tid38=setTimeout("afterloadsec543()",3000);
}

function afterloadsec543() {
    document.getElementById("toh84").style.visibility="hidden";
};
</script>
<div id="toh84" class="hidingerrormess" style="visibility:hidden">'.nl2br(htmlspecialchars($s)).'
</div>
';
    return $s2;
    }
    
    
    function hidingmessage($s) {
        // при загрузке появляется сообщение, потом через 3 секунды исчезает
    $s2='
<script type="text/javscript">
function addLoadEvent8(func) {
        var oldonload = window.onload;
        if (typeof window.onload != "function") {
                window.onload = func;
        }
        else {
                window.onload = function() {
                        oldonload();
                        func();
                }
        }
}
function loadev4() {
tid38=setTimeout("afterloadsec54()",3000);
}
function afterloadsec54() {
alert(2);
document.getElementById("toh54").style.visibility="hidden";
};
addLoadEvent8(loadev4);
</script>
<div id="toh54" class="hidingmess">'.nl2br(htmlspecialchars($s)).'
</div>
';
    return $s2;
    }
}

?>
