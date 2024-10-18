<?php

namespace Main;

use DB\Basic as DataBase;

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
}