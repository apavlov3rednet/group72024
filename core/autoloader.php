<?php

spl_autoload_register(function($class) 
{
    $baseDir = $_SERVER['DOCUMENT_ROOT'] ."/core/modules/";

    //Получаем относительный путь к файлу класса
    $relativeClass = str_replace('\\', '/', $class);

    //Полный путь к файлу класса
    $file = $baseDir . $relativeClass . '.php';

    //Если файл существует то подключаем его
    if(file_exists($file)) {
        require $file;
    }
 });