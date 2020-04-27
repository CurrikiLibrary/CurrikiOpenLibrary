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
    static function cleanSpecialCharacters($string) 
    {
        $str = "qwertyuioplkjhgfdsazxcvbnm";
        $randomChar = $str[rand(0, strlen($str)-1)];        
        $string = str_replace(' ', $randomChar, $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}