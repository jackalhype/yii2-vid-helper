<div class="header-menu-wrap">
    <?php $menu = \Yii::$app->controller->menuData;?>
    <ul class="menu-ul">
        <?php foreach($menu as $k => $item): ?>
        <li>
            <a href="<?= !empty($item['href']) ? $item['href'] : '#'?>" title="<?= $item['title'] ?>">
                <?= $item['title_short'] ?>
                <?php if (!empty($item['nested'])): ?><span class="fa arrow"></span><?php endif ?>
            </a>
            <?php if (!empty($item['nested'])): ?>
                <ul class="nav nav-second-level collapse">
                    <?php foreach ($item['nested'] as $k2 => $item2): ?>
                        <li>
                            <a href="<?= $item2['href'] ?>" title="<?= $item2['title'] ?>"><?= $item2['title_short'] ?></a>
                        </li>
                    <?php endforeach;?>
                </ul>
            <?php endif; ?>
        </li>
        <?php endforeach;?>
    </ul>
</div>

<style>
    .header-menu-wrap {
        height: 90px;
        background-color: #323232;
    }

    .header-menu-wrap .menu-ul {
        float: right;
        padding: 16px 50px 2px 6px;
        color: #33FE30;
        font-size: 1.5em;
        display: inline-block;
        width: 290px;

    }

    .header-menu-wrap .menu-ul a {
        color: #33FE30;
    }


    .header-menu-wrap .menu-ul > li {

    }
    .nav-second-level {
        margin-top: 6px;
        width: 290px;
        border-radius: 5px;
    }

    .nav-second-level li {
        font-size: 0.8em;
        background-color: #464646;
        border-radius: 5px;
    }

    .nav-second-level li a {
        border-radius: 5px;
    }




</style>