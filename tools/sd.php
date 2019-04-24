<?php
/* 
 Copyright (C) 2019 Воробьев Александр
 класс с сайто-зависимыми функциями, статический
 
 
*/
class sd {
    /**
     * Обертка для вызова php mail()
     * увеличивает счетчик отправленных почтовых сообщений
     * @param type $email
     * @param type $hdr2
     * @param type $msg
     * @param type $headers
     */    

     public static function mail($email,$hdr2,$msg, $headers) {
        $b=mail($email,$hdr2,$msg, $headers);
        my3::q("update et_settings set moremailsent=moremailsent+1");
        return $b;
    }
    
}