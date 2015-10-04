<?php
/* @var $view \yii\web\View */
/* @var $model app\models\SearchForm */
?>
<h4>Введите артикул или номер запчасти для поиска</h4>

<form class="form-inline" method="POST" action="<?= \yii\helpers\Url::toRoute('site/makers')?>">
  <div class="form-group">
    <label for="articul"><?= $model->getAttributeLabel('articul') ?></label>
    <input type="text" class="form-control" id="articul" name="articul" placeholder="Введите артикул детали" value="<?= $model->articul ?>">
  </div>
  <button type="submit" class="btn btn-info">Искать</button>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="analog" <?= ($model->analog?"checked": "") ?> > <?= $model->getAttributeLabel('analog') ?>
    </label>
  </div>
</form>