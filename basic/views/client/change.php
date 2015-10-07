<?php

/* @var $this yii\web\View */
/* @var $user app\models\WebUser */

use yii\bootstrap\Html;
?>

<div class="alert alert-warning" role="alert">
  <p><span class="glyphicon glyphicon-user"></span> Пользователь <b><?= $user->getAttribute('uname')?></b> </p>
  <p><?= $message ?></p>
</div>

<small><?= Html::a("Назад",  \yii\helpers\Url::to(['client/index']))?></small>
