<?php

class Log {

    private static $path = __DIR__ . '/../var/log.txt';

    public static function initialize() {
        if (!file_exists(self::$path)):
            $myfile = fopen(self::$path, "w") or die("Unable to open file!");
            fclose($myfile);
        endif;
    }

    public static function message($msg) {
        self::initialize();
        $msg .=PHP_EOL;
        $myfile = fopen(self::$path, "a");
        fwrite($myfile, $msg);
        fclose($myfile);
    }

}
