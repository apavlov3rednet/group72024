<?php

namespace Main;

use DB\Basic as DataBase;
use Exception;

class Application {
    public function __construct() {
    }

    static public function getCurPage() {
        return $_SERVER['REQUEST_URI'];
    }

    static public function getStaticPageAttributes($page) {
        $request = new DataBase();
        $attrs = $request->getList('attributes', [
            'filter' => ['PAGE' => $page]
        ]);

        if(!empty($attrs))
            return $attrs[0];
        else return [];
    }

    static public function showHead() {
        $page = self::getCurPage();
        $attrs = self::getStaticPageAttributes($page);
    }

    /**
     * Summary of includeComponent
     * @param string $name - expl, news.list
     * @param string $templ - expl, .default
     * @param array $params = []
     * @return void
     */
    static public function includeComponent(string $name, string $templ = '.default', array $params = []): void
    {
        //Подготовка пути до вызываемого компонента
        if(!$name)
            throw new Exception('component name must be announce');

        $componentPath = $_SERVER['DOCUMENT_ROOT'] . '/core/components/' . $name;

        //Подготовка пути до шаблона компонента
        if($templ == '')
            $templ = '.default';

        $templatePath = $componentPath . '/templates/' . $templ;
        if(!is_dir($templatePath)) 
            throw new Exception('template component not available');

        //Подключаем файл с вспомогательными функциями, если он есть
        if(file_exists($componentPath . '/function.php')) {
            require ($componentPath . '/function.php');
        }

        //Подключаем параметры шаблона компонента
        if(file_exists($templatePath . '/.parameters.php')) {
            $arParams = require $templatePath . '/.parameters.php';
        }

        //Подключение entry-point компонента
        if(file_exists($componentPath . '/component.php')) {
            require ($componentPath . '/component.php');
        }
    }
}
