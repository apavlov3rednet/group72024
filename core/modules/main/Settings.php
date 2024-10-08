<?php

namespace Core\Main;

class Settings {
    protected $arSettings;

    public function __construct()
    {
        if(file_exists('../../.settings.php')) {
            self::$arSettings = require_once('../../.settings.php');
        }
        //include, inlcude_once, require, require_once
    }

    static public function getDbParams(string $dbname = 'default') : mixed
    {
        return self::$arSettings['connections']['value'][$dbname] || false;
    }

    static public function getSessionParams() : mixed
    {
        return self::$arSettings['session'] || [];
    }

}
