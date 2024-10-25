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
//use \Exception;

//Подключение дефолтных стилей и скриптов если они есть
Asset::addExternalCss($templatePath . '/style.css');
Asset::addExternalJs($templatePath . '/script.js');

//Подготовка параметров
$setting = new Settings();
$cache = $setting->getCacheParams();

$arParams['CACHE_ACTIVE'] = isset($arParams['CACHE_ACTIVE']) ? $arParams['CACHE_ACTIVE'] : 'N';
$arParams['CACHE_TIME'] = isset($arParams['CACHE_TIME']) ? $arParams['CACHE_TIME'] : $cache['value']['config_options'];

$arParams['TABLE_NAME'] = isset($arParams['TABLE_NAME']) ? $arParams['TABLE_NAME'] : null;

if(!$arParams['TABLE_NAME']) {
    //throw new Exception('Нет таблицы');
}

$arParams['QUERY_PARAMS'] = isset($arParams['QUERY_PARAMS']) ? $arParams['QUERY_PARAMS'] :[];

$currentPage = Application::getCurPage();

//кеширование
$nameCacheFile = md5($currentPage) . '.html';
$directory = $cache['value']['cache_path'] . 'components/news.detail/';
$cacheFile = $directory . $nameCacheFile;

if(!is_dir($directory)) {
    mkdir($directory, 0777, true);
}

if($arParams['CACHE_ACTIVE'] == 'Y' && file_exists($cacheFile)) {
    if((time() - $arParams['CACHE_TIME']) < filemtime($cacheFile)) {
        echo file_get_contents($cacheFile);
        exit;
    }
}

ob_start();

$ob = new Basic();
$arResult = $ob->getById($arParams['TABLE_NAME'], $arParams['ELEMENT_ID'])[0];


if(file_exists($templatePath . '/result_modifer.php'))
{
    require $templatePath . '/result_modifer.php';
}

if(file_exists($templatePath . '/template.php'))
{
    require $templatePath . '/template.php';
}

if($arParams['CACHE_ACTIVE'] == 'Y') {
    $handle = fopen($cacheFile,'w');
    fwrite($handle, ob_get_contents());
    fclose($handle);
}
ob_end_flush(); //выводим в браузере
