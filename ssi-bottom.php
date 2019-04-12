        <? if (!$my3->ismobile) { ?>
                            <? include "ssi-bottomnews.php"; ?>
            </div>
        </div>
            <div id="leftbl">
                <div id="menu" style="margin-top:10px"> <?//здесь сдвиг от верхнего желтого банера?>
                <?
                if (isset($menu99)) {
                    echo $menu99;
                }
                ?>
                </div>
                <? if (true || APPLICATION_ENV=='production') include "ssi-banners-left-tripkrcom.php" ?>	
                
            </div>
            <div id="rightbl">
                <div id="search">
                    <form action="<?=BS?>search/" method="get">
                    <p class="poisk">ПОИСК<br />
                    Введите название<br />
                    <input  type="hidden" name="page" value="0" />
                    <input name="search" type="text" size="25" style="width:180px" maxlength="70" value="<?=(isset($sname35) ? 
                            htmlspecialchars($sname35) : '')?>" /><br />
                    <input type="submit" value="Искать" />
                    </p>
                    </form>
                    <br />
                    <? include "ssi-rightcol.php"; ?>

                </div>
            </div>
        <? } // !$my3->ismobile ?>
      <!--</div>-->
	</div><!-- .middle or content for mobile-->

</div><!-- .wrapper -->
      <div id="<?=($my3->ismobile ? 'footer2' : 'footer')?>">
          <hr class="hr2" noshade="noshade" />
            <p id="svyaz" style="font-size:90%">Тел.: (495) 728-88-95 |

            E-mail: <?=mailaddrreplacer('<a href="mailto:master@gokoreatour.ru">master@gokoreatour.ru</a>')?><br />
            &copy; Воробьёв А.Б., 2012-<?php echo date('Y') ?>
            </p>

    </div>
<? if (!$my3->ismobile) include "ssi-contact-popup.php"; ?>
<?
if (!$my3->ismobile) {
?>
<script src="<?=BS?>js/jquery-1.7.1.min.js"></script>
<? echo '<script type="text/javascript" src="'.BS.'js/popupjq.js"></script>'; ?>
<script type="text/javascript">
$(document).ready(function(){
	function runIt() {
    $(".blinking")
              .animate({"opacity": 0.1},1000)
              .animate({"opacity": 1},1000,runIt);
    }
    runIt();
});
</script>
<? } // !$my3->ismobile?>
</body>
</html>