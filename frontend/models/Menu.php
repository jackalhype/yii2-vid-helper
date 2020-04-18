<?php

namespace frontend\models;

use common\models\database\Node;

class Menu
{
    public static function getMenu() {
        $video_root_id = 1;
//        echo '<pre>';
        $videos = Node::getFull(['id' => $video_root_id, 'depth' => 1]);
//        print_r($videos); die;
        $menu_items = [
        ];
        foreach($videos as $root_vid) {
            if (isset($root_vid['nested'])) {
                $nested = $root_vid['nested'];
                foreach($nested as $id => $vid) {
                    $title = $vid['title'];
                    $menu_items[] = [
                        'title' => $title,
                        'title_short' => mb_substr($title, 0, 80),
                        'href' => '/video/show?id='.$id,
                    ];
                }
            }
            break;
        }

        $menu = [
            [
                'title' => 'Видео',
                'title_short' => 'Видео',
                'nested' => $menu_items,
            ],
        ];
        return $menu;
    }
}