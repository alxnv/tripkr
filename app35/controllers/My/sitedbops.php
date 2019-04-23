<?php

// Copyright (C) 2019 Воробьев Александр
// это my_itdbtr - следующая версия с доработанным отображением методов

/**
 * Операции с базой данных для этого сайта
 */
class my_sitedbops {
    public $tblprefix='et_';

    function __construct() {
    }

    /**
     * получить содержимое файла $pfx.'settings'
     */
    public function getsettings() {
        return my7::qobj("select * from ".$this->tblprefix."settings");
    }
}
