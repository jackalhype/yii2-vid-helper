<ul class="nav in" id="side-menu">
    <?php $menu = \Yii::$app->controller->menuData;?>
    <?php foreach ($menu as $k => $item): ?>
        <li>
            <a href="<?= !empty($item['href']) ? $item['href'] : '#'?>"><i class="fa fa-edit fa-fw"></i><?= $item['title'] ?><span class="fa arrow"></span></a>
            <?php if (!empty($item['nested'])): ?>
            <ul class="nav nav-second-level collapse">
                <?php foreach ($item['nested'] as $k2 => $item2): ?>
                    <li>
                        <a href="<?= $item2['href'] ?>"><?= $item2['title'] ?></a>
                    </li>
                <?php endforeach;?>
            </ul>
            <?php endif; ?>
        </li>

    <?php endforeach;?>
</ul>
