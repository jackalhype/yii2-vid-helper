<?php

namespace backend\controllers;

/**
 * Class MainController - Главные страницы админки, после авториза
 */
class MainController extends AppController
{
    public function actionIndex()
    {
        $data = [];
        return $this->render('index', $data);
    }

}