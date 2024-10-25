<?php
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templatePath
 * @var string $componentPath
 */
 ?>

<h2>Users</h2>

<table class="users" border="1">
    <?php if(!empty($arResult['ITEMS'])):?>
        <?php foreach($arResult['ITEMS'] as $arItem):?>
            <tr>
                <td><?=$arItem['ID']?></td>
                <td><?=$arItem['LOGIN']?></td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
</table>

<?php if($arParams['SHOW_PAGER'] === 'Y'):?>
    <div class="pager">
        <?php for($i = 1; $i <= $arResult['COUNT_PAGE']; $i++):?>
            <a href="?page=<?=$i?>"><?=$i?></a>
        <?php endfor;?>
    </div>
<?php endif;?>