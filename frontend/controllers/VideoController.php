<?php

namespace frontend\controllers;

use common\models\database\Node;
use frontend\models\Video;

class VideoController extends AppController
{
    public function actionShow($id) {
        $video = Node::getFull([
            'id' => $id,
//            'debug' => true,
        ]);
        if (count($video) === 0) {
            throw new \yii\web\NotFoundHttpException(404);
        }
        $video_node = current($video);
        $video_html = Video::makeHtmlTree($video);
        $data = [
            'video_node' => $video_node,
            'video_html' => $video_html,
        ];
        $data = [
            'video_node' => $video_node,
            'video_html' => $video_html,
        ];
        return $this->render('show', $data);
    }
}