<?
/*
<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
<div class="yashare-auto-init" style="margin-top:20px" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj"></div> 
*/
$uri2=htmlspecialchars(substr('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],0,500));
?>
<div style="position:relative;width:150px;overflow:hidden">
<div style="position:absolute;top:10px">
<a href="https://twitter.com/share" class="twitter-share-button" id="tweet5">Tweet</a>
<script type="text/javascript">
document.getElementById('tweet5').setAttribute( 'data-text',"<?=my3::nbsh($title).' '.$uri2?>");
document.getElementById('tweet5').setAttribute( 'data-via',"arlecchino75");
document.getElementById('tweet5').setAttribute( 'data-hashtags',"USTravel");
</script>
<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
<div style="position:absolute;top:40px">
<script type="text/javascript">
document.write(VK.Share.button(false,{type: "round", text: "ПОДЕЛИТЬСЯ"}));
</script>
</div>
<div style="position:absolute;top:70px">
<a onclick="window.open('http://www.livejournal.com/update.bml?event=<?=$uri2?>&amp;subject=<?=$title?>', 'lj', 'width=700, height=900'); return false;" rel="nofollow" href="http://www.livejournal.com/update.bml?event=<?=$uri2?>&amp;subject=<?=$title?>" title="Опубликовать в своем блоге livejournal.com"><img src="<?=BS?>img/ljLike.gif" alt="Опубликовать в своем блоге livejournal.com" width="105" height="18" /></a>
</div>

<div style="margin-top:98px">
<div id="fb-root"></div>
<div class="fb-like" id="fb7" style="margin-right:20px"></div>
</div>
<script type="text/javascript">
document.getElementById('fb7').setAttribute( 'data-href',"<?=$uri2?>");
document.getElementById('fb7').setAttribute( 'data-send',"false");
document.getElementById('fb7').setAttribute( 'data-layout',"button_count");
document.getElementById('fb7').setAttribute( 'data-width',"150");
document.getElementById('fb7').setAttribute( 'data-show-faces',"true");
document.getElementById('fb7').setAttribute( 'data-font',"verdana");
document.getElementById('fb7').setAttribute( 'data-action',"recommend");
</script>
<script type="text/javascript">(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<? // мой мир 
/*
<div style="float:left">    
<a target="_blank" class="mrc__plugin_uber_like_button" href="http://connect.mail.ru/share" data-mrc-config="{'type' : 'button', 'caption-mm' : '2', 'caption-ok' : '2', 'counter' : 'true', 'text' : 'true', 'width' : '100%'}">Нравится</a>
<script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>
</div>
 * 
 */?>
<div style="clear:both"></div>
</div>
<?
/*
<div class="title1 netLinks">
  <div class="list">

    <div class="link">
      <iframe scrolling="no" frameborder="0" allowtransparency="true"
      src="http://platform.twitter.com/widgets/tweet_button.1331069346.html#_=1331547404950&amp;count=horizontal&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=http%3A%2F%2Fecho.msk.ru%2Fprograms%2Fcode%2F&amp;size=m&amp;text=%D0%9A%D0%BE%D0%B4%20%D0%B4%D0%BE%D1%81%D1%82%D1%83%D0%BF%D0%B0%3A%20%D0%AE%D0%BB%D0%B8%D1%8F%20%D0%9B%D0%B0%D1%82%D1%8B%D0%BD%D0%B8%D0%BD%D0%B0&amp;url=http%3A%2F%2Fecho.msk.ru%2Fprograms%2Fcode%2F&amp;via=echomskru" 
      class="twitter-share-button twitter-count-horizontal" style="width: 109px; height: 20px;"
      title="Twitter Tweet Button"></iframe><script
      src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>			
    </div>
    <div class="link">
      <script charset="windows-1251" src="http://vkontakte.ru/js/api/share.js?11" type="text/javascript"></script>

      <!-- Put this script tag to the place, where the Share button will be -->
      <script type="text/javascript">&lt;!--
        document.write(VK.Share.button(false,{type: "button", text: "Поделиться"}));
        --&gt;</script><table cellspacing="0" cellpadding="0" style="position: relative; width: auto;
              border: 0px;" onmouseup="VK.Share.change(1, 0);"
              onmousedown="VK.Share.change(2, 0);" onmouseout="VK.Share.change(0, 0);"
              onmouseover="VK.Share.change(1, 0);" id="vkshare0"><tbody><tr
          style="line-height: normal;"><td></td><td style="vertical-align: middle;"><a
           style="text-decoration:none;" onclick="return VK.Share.click(0, this);" 
           onmouseup="this._btn=event.button;this.blur();"
           href="http://vkontakte.ru/share.php?url=http%3A%2F%2Fecho.msk.ru%2Fprograms%2Fcode%2F"><div 
        style="border: 1px solid #3b6798; cursor:pointer;"><div style="border: 1px solid #5c82ab;
    border-top-color: #7e9cbc; background-color: #6d8fb3; color: #fff; text-shadow: 0px 1px #45688E; 
    height: 15px; padding: 2px 4px 0px 6px; font-size: 10px;
    font-family: tahoma, arial;">Поделиться</div></div></a></td><td
style="vertical-align: middle;"><a style="text-decoration:none;" 
onclick="return VK.Share.click(0, this);" onmouseup="this._btn=event.button;this.blur();"
href="http://vkontakte.ru/share.php?url=http%3A%2F%2Fecho.msk.ru%2Fprograms%2Fcode%2F"><div
style="background: url(&quot;https://vk.com/images/btns.png&quot;) 
no-repeat scroll 0px 0px transparent; cursor: pointer; width: 29px; height: 21px;"></div></a></td><td
style="vertical-align: middle;"><a style="text-decoration:none;" onclick="return VK.Share.click(0, this);"
onmouseup="this._btn=event.button;this.blur();"
href="http://vkontakte.ru/share.php?url=http%3A%2F%2Fecho.msk.ru%2Fprograms%2Fcode%2F"><div
style="border-width: 1px 1px 1px 0px; border-style: solid solid solid none; 
border-color: rgb(162, 185, 211) rgb(162, 185, 211) rgb(162, 185, 211) -moz-use-text-color;
-moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; 
-moz-border-left-colors: none; -moz-border-image: none; cursor: pointer; background-color: rgb(222, 230, 241);
color: rgb(0, 0, 0); height: 15px; padding: 2px 6px 0px 4px; font-size: 10px; font-weight: bold; 
font-family: tahoma,arial; display: block;">81</div></a></td></tr></tbody></table>

    </div>
    <div class="link">
      <span style="position: relative; left: 0; top: 0; margin: 0; padding: 0; 
            visibility: visible;"><iframe scrolling="no" frameborder="0" style="height: 20px; 
                background-color: transparent; width: 160px; border: 0px none rgb(255, 255, 0); 
                display: block;"
 src="http://connect.mail.ru/share_button?type=button&amp;width=160&amp;show_faces=true&amp;domain=echo.msk.ru&amp;url=http%3A%2F%2Fecho.msk.ru%2Fprograms%2Fcode%2F&amp;buttonID=304956&amp;faces_count=10&amp;height=21&amp;caption=%D0%A0%D0%B5%D0%BA%D0%BE%D0%BC%D0%B5%D0%BD%D0%B4%D1%83%D1%8E&amp;wid=8445289&amp;app_id=-1&amp;host=http%3A%2F%2Fecho.msk.ru"
 allowtransparency="true" name="8445289" id="8445289"></iframe></span><a 
     data-mrc-config="{'type' : 'button', 'width' : '160', 'show_faces' : 'true'}"
     href="http://connect.mail.ru/share"
   class="mrc__plugin_like_button" target="_blank" type="like_button" style="display: none;">Рекомендую</a>
      <script charset="UTF-8" type="text/javascript" src="http://cdn.connect.mail.ru/js/loader.js"></script>
    </div>
    <div class="link"><a class="lj-button"
       href="http://www.livejournal.com/update.bml?subject=&amp;event="><img alt=""
        src="<?=BS?>img/ljLike.gif"></a></div>					
  </div>
  <div class="addInNetBlock">
    <div class="addInNetDoor">код для блога</div>
    <form id="lj-form" target="_blank" action="http://www.livejournal.com/update.bml">
      <div class="popup addInNet"> <!-- по клику раскрываем display: block; -->
        <div class="arrow">&nbsp;</div>
        <div class="inside">
          <textarea cols="1" rows="1" name="event">&lt;div style="border: 
1px solid #ed1c24; padding: 1em 1em; background: #fff; clear: left"&gt; &lt;a style="color: #000" 
href="http://echo.msk.ru/programs/code/866934-echo/"&gt; &lt;img
 src="http://echo.msk.ru/img/sys/logo_print.gif" style="float: left ; 
margin: 0px 0.5em 0.5em 0px"&gt; &lt;h4 style="margin-top: 
0px"&gt;Код доступа: Юлия Латынина&lt;/h4&gt; &lt;/a&gt; &lt;p 
style="margin-bottom: 0px"&gt; 
Очень может быть, что Путин все равно бы победил на выборах, если бы в России были выборы. 
Но, простите, это было другое мероприятие... &lt;/p&gt; &lt;hr 
style="border: 0px solid red ; clear: both; height: 1px" /&gt; &lt;/div&gt; </textarea>
          <!-- input type="submit" value="Скопировать в бубер" class="ibutton2" / -->
        </div>
      </div>
    </form>
  </div>
</div>
*/?>