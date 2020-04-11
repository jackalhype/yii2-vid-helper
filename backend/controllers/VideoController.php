<?php

namespace backend\controllers;

class VideoController  extends AppController
{

    public function actionNew() {
        $data = [];
        return $this->render('new', $data);
    }

    public function actionEdit($id) {
        die($id);
        $data = [];
        return $this->render('index', $data);
    }



}