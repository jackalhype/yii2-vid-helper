<?php
/**
 * Created by PhpStorm.
 * User: ivan-it
 * Date: 14.05.2019
 * Time: 14:14
 */

namespace backend\controllers;
use backend\components\behaviors\BehaviorToUser;
use yii\web\Controller;
use yii\base\Module;
use yii\filters\AccessControl;

/**
 * Class AppController - Класс контроллера нашего приложения
 * @package backend\controllers
 */
class AppController extends Controller
{
    public $menuData = [];

    public function __construct($id, Module $module, array $config = [])
    {

    }

    /**
     * Правила доступа, если юзер не авторизирован
     * то он попадает на контроллер site/login
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        //'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'class' => BehaviorToUser::class,
        ];
    }

    /**
     * Superhook, on before EVERY Controller
     */
    public function init() {
        parent::init();

    }

}