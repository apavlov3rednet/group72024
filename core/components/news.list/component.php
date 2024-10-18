<?php

use DB\Basic;

class Component {
    public $arParams;
    public $arResult;


    public function __construct() {
        
    }

    public function getResult() {
        $request = new Basic();
        $arResult = $request->getList($arParams['table'], $arParams['params']);
    }

    public function renderTemplate() {
        render($arResult);
    }
}