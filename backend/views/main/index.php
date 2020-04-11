<?php
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'Admin';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Привет, <?=User::findIdentity(\Yii::$app->user->getId())->username?>!</h1>
    </div>
    <div class="body-content">
        <?php if(\Yii::$app->user->admin === 1):?>

        <? endif; ?>
    </div>
</div>