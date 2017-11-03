        <? if (!$my3->ismobile) { ?>
                            <? include "ssi-bottomnews.php"; ?>
            </div>
        </div>
            <div id="leftbl">
                <div id="menu" style="margin-top:10px"> <?//здесь сдвиг от верхнего желтого банера?>
                <?
                if (isset($menu)) {
                    echo $menu;
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

        <? if (!$my3->ismobile) { ?>
      <div id="footer">
          <hr class="hr2" noshade="noshade" />
            <p id="svyaz">Тел.: (495) 728-88-95 |

            E-mail: <?=mailaddrreplacer('<a href="mailto:mow.ustravel@gmail.com">mow.ustravel@gmail.com</a>')?><br />
            &copy; Воробьёв А.Б., 2012-<?php echo date('Y') ?>
            </p>

    </div>
        <? } // !$my3->ismobile ?>
<? if (!$my3->ismobile) include "ssi-contact-popup.php"; ?>
</body>
</html>

<? /*                        </div>
<? include "ssi-bottomnews.php"; ?>
</td>
<? if (!$my3->onecolumn) { ?>
<td valign="top">
        <?/*<img src="<?=BS?>img/ex.gif" width="240">
    <br />

    <p align="right">
<table class="bgwhite" align="right">
<tr>
<td align="right" class="left_menu">
<form action="<?=BS?>search/" method="get">
<p class="poisk">ПОИСК<br />
Введите название<br />
<input  type="hidden" name="page" value="0" />
<input name="search" type="text" size="25" style="width:180px" maxlength="70" value="<?=(isset($sname35) ? 
        htmlspecialchars($sname35) : '')?>" /><br />
<input type="submit" value="Искать" />
</p>
</form>
</td>
</tr>
<tr><td align="left" class="bwhite">
<div id="rightcol" style="width:210px;margin-left: 40px;">
<? include "ssi-rightcol.php"; ?>
</div>
    </td></tr>
</table>
</p>
 </td><? } // (!$my3->onecolumn)?>

</tr>
<tr><td></td><td></td><td></td></tr>

<tr id="bottom_tr">
<td colspan="3" valign="bottom">
<hr class="hr2" noshade="noshade" />
<p id="svyaz">Тел.: (495) 728-88-95 |

E-mail: <?=mailaddrreplacer('<a href="mailto:mow.ustravel@gmail.com">mow.ustravel@gmail.com</a>')?><br />
&copy; Воробьёв А.Б., 2012-<?php echo date('Y') ?>
</p>

</td>
</tr></table>


<? include "ssi-contact-popup.php"; ?>
</body>
</html>*/?>