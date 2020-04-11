<?php

namespace backend\models;

use yii\helpers\ArrayHelper;
use common\models\database\Node;

class Menu
{
    public static function getMenu() {
        $menu = [
            [
                'title' => 'Видео',
                'nested' => [
                    [
                        'title' => 'Новое',
                        'href' => '/admin/video/new',
                    ],
                    [
                        'title' => 'Первое',
                        'href' => '/admin/video/edit/1',
                    ],
                    [
                        'title' => 'Второе',
                        'href' => '/admin/video/edit/2',
                    ],
                ]
            ],
            [
                'title' => 'Настройки',
                'href' => '/admin/settings'
            ],
        ];
        return $menu;
    }

}