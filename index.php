<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');

?>

<? Main\Application::includeComponent('news.list', '.default', [
    'TABLE_NAME' => 'users',
    'CACHE_ACTIVE' => 'N',
    'COUNT_ELEMENT' => 2,
    'SHOW_PAGER' => 'Y'
]);?>

<? Main\Application::includeComponent('news.detail', 'exmpl', [
    'TABLE_NAME' => 'users',
    'ELEMENT_ID' => 3
]);?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/core/footer.php');?>