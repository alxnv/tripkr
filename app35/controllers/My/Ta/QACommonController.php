<?php

class MY_Ta_QACommonController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */
        my7::checkadm();
        $this->tbl=my7::getdbprefix().'ta_html';
        $this->treetbl=my7::getdbprefix().'tree';
        $this->actid=4; // идентификатор текущего контроллера в дереве
    }

    public function getNewFpnum() {
        // fpnum для новой записи этого уровня
        return 4;
    }
    
}
?>
