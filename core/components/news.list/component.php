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

$arParams['COUNT_ELEMENT'] = isset($arParams['COUNT_ELEMENT']) ? $arParams['COUNT_ELEMENT'] : 10;
$arParams['QUERY_PARAMS'] = isset($arParams['QUERY_PARAMS']) ? $arParams['QUERY_PARAMS'] :[];

//Текущая страница и стартовая позиция запроса
$offset = 0;
if(isset($_GET['page']) && (int)$_GET['page'] > 1) {
    $offset = (int)$_GET['page'] * (int)$arParams['COUNT_ELEMENT'] - (int)$arParams['COUNT_ELEMENT'];
}
$currentPage = Application::getCurPage();

//кеширование
$nameCacheFile = md5($currentPage) . '.html';
$directory = $cache['value']['cache_path'] . 'components/news.list/';
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

$params = [
    'limit' => [
        'rows' => $arParams['COUNT_ELEMENT'],
        'offset' => $offset
    ]
];

$ob = new Basic();
$arResult['ITEMS'] = $ob->getList($arParams['TABLE_NAME'], $params);

if($arParams['SHOW_PAGER'] === 'Y') {
    $count = $ob->getCount($arParams['TABLE_NAME']);
    $arResult['COUNT_PAGE'] = round($count / (int)$arParams['COUNT_ELEMENT']);
}

if(file_exists($templatePath . '/result_modifer.php'))
{
    require $templatePath . '/result_modifer.php';
}
require $templatePath . '/template.php';

if($arParams['CACHE_ACTIVE'] == 'Y') {
    $handle = fopen($cacheFile,'w');
    fwrite($handle, ob_get_contents());
    fclose($handle);
}
ob_end_flush(); //выводим в браузере
