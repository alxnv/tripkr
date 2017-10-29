<?php

class MY_Ta_QACommonController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */
        my3::checkadm();
        $this->tbl=my3::getdbprefix().'ta_html';
        $this->treetbl=my3::getdbprefix().'tree';
        $this->actid=4; // идентификатор текущего контроллера в дереве
    }

    public function getNewFpnum() {
        // fpnum для новой записи этого уровня
        return 4;
    }
    
}
?>
