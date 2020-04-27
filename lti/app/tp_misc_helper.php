<?php

/* 
 * Dev: Waqar Muneer
 */
//**** TP = Tool Proiver *******
class TPMiscHelper
{
    
    static function getRandomString($length = 8)
    {

        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $value = '';
        $charsLength = strlen($chars) - 1;

        for ($i = 1 ; $i <= $length; $i++) {
            $value .= $chars[rand(0, $charsLength)];
        }

        return $value;

    }        
}