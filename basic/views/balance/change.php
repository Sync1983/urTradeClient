<?php
use app\models\BalanceModel;
use app\models\WebUser;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model BalanceModel */
/* @var $user WebUser */
$user = \app\models\WebUser::findOne(['id' => intval($model->getAttribute('uid'))]);
if ( !$user ){
  return;
}
$balance = $model->getUserBalance();
?>
<div class="container">
  <h3>Баланс пользователя <?= $user->getAttribute('uname')?> изменен на величину <?= $model->getAttribute('value') ?> </h3>
  <p>Комментарий к записи: <code><?=$model->getAttribute('comment')?></code></p>
  <p>Итоговый баланс пользователя <b><u><?= $balance['full'] ?></u></b> руб. в том числе кредит <b><u><?=$balance['credit']?></u></b> руб.</p>

  <small>Вы вернётесь на предыдущую страницу через 5 секунд.</small>
</div>

</div>