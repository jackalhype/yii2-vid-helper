<?php


namespace backend\components\behaviors;

use common\models\database\MenuCategory;
use Yii;
use yii\base\Behavior;
use yii\web\NotFoundHttpException;

class BehaviorToUser extends Behavior
{
    /*
     * Поведение-правило для backend\controllers\SettingsController, для отключения actionUser,
     * @string
     */
    public function checkAccess()
    {
        if(Yii::$app->user->admin !== 1){
            return Yii::$app->controller->redirect(['main/index']);
        }
    }


    public function accessUser()
    {
        return true;
    }

}