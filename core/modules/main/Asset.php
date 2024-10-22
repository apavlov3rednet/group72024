<?php

namespace Main;

abstract class Asset
{
    static public function addExternalCss(string $cssPath): void
    {
        if (file_exists($cssPath)) {
            echo '<link rel="stylsheet" href="' . $cssPath . '/>';
        }
    }

    static public function addExternalJs(string $jsPath, array $options = []): void
    {
        if (file_exists($jsPath)) {
            $defer = $options['defer'] == true ? 'defer' : '';
            $async = $options['async'] == true ? 'async' : '';
            $type = (!empty($options['type'])) ? $options['type'] : null;

            $string = '<script src="' . $jsPath . '" ' . $defer . ' ' . $async;

            if ($type) {
                $string .= ' type="' . $type . '"';
            }

            $string .= '></script>';
            //<script src='path' async defer type='module'></script>
            echo $string;
        }
    }

    static public function addHeadStrinig(): void {}
}
