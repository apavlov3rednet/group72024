<?php

$arResult['HASH_PASSWORD'] = md5(time() . $arResult['PASSWORD']);
$arResult['TEST'] = 'test';