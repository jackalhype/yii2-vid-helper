<?php


namespace backend\components;

use Yii;

class User extends \yii\web\User
{
    public function getAdmin()
    {
        if (isset(\Yii::$app->user) && isset(\Yii::$app->user->identity)) {
            return \Yii::$app->user->identity->admin;
        }
        return 0;
    }

    public function getName()
    {
        if (isset(\Yii::$app->user) && isset(\Yii::$app->user->identity)) {
            return \Yii::$app->user->identity->username;
        }
        return '';
    }

}