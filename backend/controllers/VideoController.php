<?php

namespace backend\controllers;

use yii\filters\VerbFilter;
use common\models\database\{Node};

class VideoController  extends AppController
{
    public function behaviors() {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [      // we luv REST so much :3
                    'new' => ['GET'],
                    'edit' => ['GET'],
                    'save' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    // new page
    public function actionNew() {
        $data = [];
        return $this->render('new', $data);
    }

    // edit page
    public function actionEdit() {
        $id = \Yii::$app->request->get('id');
//        echo '<pre>';
        $video = Node::getFull([
            'id' => $id,
//            'debug' => true,
        ]);
        $video_node = current($video);
        $video_html = Node::makeAdminHtmlTree($video);
        $data = [
            'video_node' => $video_node,
            'video_html' => $video_html,
        ];
        return $this->render('edit', $data);
    }

    // do save
    public function actionSave() {
        $post = \Yii::$app->request->post();
        $node_id = isset($post['node_id']) ? $post['node_id'] : null;
        $tree = isset($post['tree']) ? $post['tree'] : null;

        if (!$node_id) {
            $node = new Node;
        } else {
            $node = Node::findOne(['id' => $node_id]);
        }
        if (!$node) {
            return $this->asJson([ 'succes' => false, 'error' => "node problems"]);
        }

        $res = $node->saveHtmlTree([ 'html_tree' => $tree]);
        return $this->asJson($res);
    }

    // do delete
    public function actionDelete($id) {
        if (!$id) {
            return $this->asJson([ 'success' => false, 'error' => 'id not set']);
        }

        $node = Node::findOne(['id' => $id]);
        if (!$node) {
            return $this->asJson([ 'success' => false, 'error' => "node (id={$id}) not found"]);
        }
        $n = $node->delete();
        if (false === $n) {
            return $this->asJson([ 'success' => false, 'error' => "deletion failed"]);
        }

        return $this->asJson([ 'success' => true ]);
    }


}