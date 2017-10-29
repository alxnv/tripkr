<?php

/*
 * работа с таблицей seo et_rewrite из которой  подставляются в страницы значения title и тд
 */
class My_Titlekw {
    function __construct($tbl,$rec) {
        $this->tbl=$tbl;
        $this->rec=$rec;
    }
    function getData() {
        $this->data=my7::qobj("select * from $this->tbl where url=$this->rec");
        if (!$this->data) {
            $this->data=(object)array('title'=>'','description'=>'','keywords'=>'');
        }
        return $this->data;
    }
    function  saveData($title,$descr,$keywords) {
        $db=my7::db();
        if (trim($title)=='' && trim($descr)=='' && trim($keywords)=='') {
            my7::qdirect("delete from $this->tbl where url=$this->rec");
        } else {
            $obj=my7::qobj("select 1 from $this->tbl where url=$this->rec");
            if ($obj) {
            my7::qdirect("update $this->tbl set title=".$db->quote($title).", 
                description=".$db->quote($descr).", keywords=".$db->quote($keywords)."
                    where url=$this->rec");
            } else {
            my7::qdirect("insert into $this->tbl (url,title,description,keywords) 
                    values ($this->rec,".$db->quote($title).", ".$db->quote($descr).
                    ", ".$db->quote($keywords).")");
            }
        }
    }
}
?>
