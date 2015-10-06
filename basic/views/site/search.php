<?php
/* @var $view \yii\web\View */
/* @var $model app\models\SearchForm */
use kartik\checkbox\CheckboxX;
use yii\bootstrap\Html;
?>
<h4>Введите артикул или номер запчасти для поиска</h4>

<form class="form-inline search-row" method="POST" action="<?= \yii\helpers\Url::toRoute('site/makers')?>">
  <?= Html::hiddenInput(\yii::$app->request->csrfParam, \yii::$app->request->csrfToken); ?>
  <div class="form-group">
    <label for="articul"><?= $model->getAttributeLabel('articul') ?></label>
    <input type="text" class="form-control" id="articul" name="articul" placeholder="Введите артикул детали" value="<?= $model->articul ?>">
  </div>
  <button type="submit" class="btn btn-info">Искать</button>
  <?= CheckboxX::widget([
        'name'  => 'analog',
        'value' => $model->analog,
        'options'=>['id'=>'analog'],
        'pluginOptions' =>[
          'size'=>'xl',
          'threeState'=>false,
          'theme' => 'krajee-flatblue',
        ]
    ]);?>
  <label class="cbx-label" for="analog">
    <?= $model->getAttributeLabel('analog') ?>
  </label>
</form>