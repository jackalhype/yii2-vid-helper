<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use common\models\User;

class RbacController extends Controller
{
    /**
     * php yii rbac/init
     * @throws \Exception
     */
    public function actionInit()
    {
        // Создание ролей.
        // Роль администратора.
        $authManager = \Yii::$app->authManager;
        $role = Yii::$app->authManager->createRole('admin');
        $role->description = 'Администратор';
        Yii::$app->authManager->add($role);
        // Роль контенщика.
        $role = Yii::$app->authManager->createRole('content');
        $role->description = 'Контент менеджер';
        Yii::$app->authManager->add($role);
        // Роль тестового пользователя.
        $role = Yii::$app->authManager->createRole('testuser');
        $role->description = 'Тестовый пользователь';
        Yii::$app->authManager->add($role);

        $users = User::find()->where(['admin' => 0])->all();

        // Назначение ролей пользователям.
        // Роль контент менеджера.
        foreach ($users as $key => $item){
            $userRole = $userRole = Yii::$app->authManager->getRole('content');
            Yii::$app->authManager->assign($userRole, $item->id);
        }
    }

    /**
     * php yii rbac/create-admin
     */
    public function actionCreateAdmin() {
        $user = new User();
        $user->username = 'admin';
        $user->setPassword('1234');
        $user->generateAuthKey();
        $user->email = 'admin@video.glob';
        $user->admin = 1;
        $user->status = 10;
        $user->save();

        $user = User::findOne(['username' => 'admin']);
        if ($user) {
            $userRole = \Yii::$app->authManager->getRole('admin');
            \Yii::$app->authManager->assign($userRole, $user->id);
        }
    }
}