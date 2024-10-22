<?php

namespace Main;

class Settings {
    private $arSettings;

    public function __construct()
    {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php')) {
            $this->arSettings = require($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
        }
        //include, inlcude_once, require, require_once
    }

    public function getDbParams(string $dbname = 'default') : mixed
    {
        return $this->arSettings['connections']['value'][$dbname];
    }

    public function getSessionParams() : mixed
    {
        return $this->arSettings['session'];
    }

    public function getCacheParams():mixed
    {
        return $this->arSettings['cache_flags'];
    }
}
