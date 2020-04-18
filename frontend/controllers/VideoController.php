<?php


namespace frontend\controllers;


class VideoController extends AppController
{
    public function actionShow($id) {

        $data = [];
        return $this->render('show', $data);
    }
}