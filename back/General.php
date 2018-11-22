<?php

class General
{

    protected static $_db;
    protected static $_smarty;

    public static function getDb()
    {
        return self::$_db;
    }

    public static function getSmarty()
    {
        return self::$_smarty;
    }

    public static function init($DB, $SMARTY)
    {
        self::$_db = $DB;
        self::$_smarty = $SMARTY;
    }

    public static function pingTest()
    {
        $handle = fopen("https://www.liveinternet.ru/stat/liveinternet.ru/index.csv?graph=csv", "rd");
        $i = -1;
        while (!feof($handle)) {
            $line = fgetcsv($handle, 1024);
            $i++;
        }
        fclose($handle);
        if (!empty($line)) {
            return 1;
            }else {
                return 0;
        }
    }

}

