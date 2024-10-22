<?php
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templatePath
 * @var string $componentPath
 */

use DB\Basic;
use Main\Asset;
use Main\Settings;
use Main\Application;
use Exception;

//Подключение дефолтных стилей и скриптов если они есть
Asset::addExternalCss($templatePath . '/style.css');
Asset::addExternalJs($templatePath . '/script.js');

//Подготовка параметров
$setting = new Settings();
$cache = $setting->getCacheParams();

$arParams['CACHE_TIME'] = ($arParams['CACHE_TIME']) ? $arParams['CACHE_TIME'] : $cache['value']['config_options'];
$arParams['TABLE_NAME'] = ($arParams['TABLE_NAME']) ? $arParams['TABLE_NAME'] : null;

if(!$arParams['TABLE_NAME']) {
    throw new Exception('Нет таблицы');
}

$arParams['COUNT_ELEMENT'] = ($arParams['COUNT_ELEMENT']) ? $arParams['COUNT_ELEMENT'] : 10;
$arParams['QUERY_PARAMS'] = ($arParams['QUERY_PARAMS']) ? $arParams['QUERY_PARAMS'] :[];

//Текущая страница и стартовая позиция запроса
$offset = ($_GET['page']) ? (int)$_GET['page'] * $arParams['COUNT_ELEMENT'] + 1 - $arParams['COUNT_ELEMENT']: 1;
$currentPage = Application::getCurPage();

//кеширование
$nameCacheFile = md5($currentPage) . '.html';
$cacheFile = $cache['value']['cache_path'] . $nameCacheFile;

if(file_exists($cacheFile)) {
    if((time() - $arParams['CACHE_TIME']) < filemtime($cacheFile)) {
        echo file_get_contents($cacheFile);
        exit;
    }
}

ob_start();

$params = [];

$ob = new Basic();
$arResult = $ob->getList($arParams['TABLE_NAME'], $params);

if(file_exists($templatePath . '/result_modifer.php'))
{
    require $templatePath . '/result_modifer.php';
}
require $templatePath . '/template.php';

$handle = fopen($cacheFile,'w');
fwrite($handle, ob_get_contents());
fclose($handle);
ob_end_flush(); //выводим в браузере
