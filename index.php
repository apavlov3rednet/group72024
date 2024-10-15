<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');

use DB\Basic;
?>

    <?php
    $result = new Basic();
    
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

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/core/footer.php');?>