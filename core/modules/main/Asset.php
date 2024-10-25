<?php

namespace Main;

abstract class Asset
{
    static public function addExternalCss(string $cssPath): void
    {
        if (file_exists($cssPath)) {
            echo '<link rel="stylesheet" href="' . $cssPath . '"/>';
        }
    }

    static public function addExternalJs(string $js, array $params = []) {
        if(file_exists($js)) {
            $defer = (isset($params['defer']) && $params['defer'] == true) ? 'defer' : '';
            $async = (isset($params['async']) && $params['async'] == true) ? 'async' : '';

            echo '<script src="' . $js . '" ' . $defer .' '. $async . '></script>';
        }
    }

    static public function addHeadStrinig(): void {}
}
