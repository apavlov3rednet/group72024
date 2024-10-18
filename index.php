<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');

?>

    <?php
    $result = new DB\Basic();
    
    $arResult = $result->getList('users', [
        'select' => ['LOGIN'],
        'limit' => [
            'rows' => 2,
            'offset' => 1
        ]
    ]);

    //$result->add('users', ['LOGIN' => 'tester', 'PASSWORD' => "777777"]);
    ?>

    <pre><?print_r($arResult);?></pre>

    <?
    Application::initComponent('news.list', [
        'table' => 'news',
        'count' => 10
    ]);
    
    ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/core/footer.php');?>