<?php


namespace frontend\controllers;

use frontend\models\Menu;
use yii\base\Module;
use yii\web\Controller;

class AppController extends Controller
{
    public $menuData = [];

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->menuData = Menu::getMenu();
    }


}