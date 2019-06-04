<?php
/**
 * Created by PhpStorm.
 * User: e.korolev
 * Date: 04.06.2019
 * Time: 9:45
 */

class Logger
{
// use STATIC rendering
    private static $fplog;    // file handler for logging

// open logging file for writing
    public static function start($flogname = 'log.txt')
    {
        self::$fplog = fopen($flogname, 'ab');
    }

    public static function stop()
    {
        fclose(self::$fplog);
    }

    public static function write($s, $usedate = true)
    {
// пишем в лог-файл строку $s,
// $usedate - вставлять ли в лог дату/время текущие
        if ($usedate)
            $tim = '[' . date('Y-m-d H:i:s') . '] ';
        else
            $tim = '';
        fwrite(self::$fplog, $tim . $s . "\n");
    }
}