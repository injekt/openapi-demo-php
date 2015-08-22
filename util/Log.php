<?php

class Log
{
    public static function i($msg)
    {
        self::write('I', $msg);
    }
    
    public static function e($msg)
    {
        self::write('E', $msg);
    }
    
    private static function write($level, $msg)
    {
        $logFile = fopen("log/test.log", "aw");
        fwrite($logFile, $level . "/" . date(" Y-m-d h:i:s") . "  " . $msg . "\n");
        fclose($logFile);    
    }
}
