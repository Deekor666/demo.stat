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

}