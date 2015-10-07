<?php
/* @var $this yii\web\View */
/* @var $model app\models\SearchForm */
\app\assets\TableAsset::register($this);
?>

<div class="panel panel-default panel-full-screen">
  <div class="panel-heading">
    <h3 class="panel-title">Выберите производителя артикула <b><?= $model->articul ?></b> 
      <?= kartik\checkbox\CheckboxX::widget([
            'id'  => 'analog',
            'name'  => 'analog',
            'value' => $model->analog,
            'pluginOptions'=>[
              'threeState'=>false,
            ],
            'pluginEvents' => [
              "change"=>"function() { $('tr[data-analog]').showBy($('#analog').val())}"              
            ]            
        ]);?> <label for="analog">Аналоги</label></h3>
  </div>
  <div class="panel-body">
    <div class="selector">      
      <div class="list-group">
        <?php foreach($makers as $maker=>$key): ?>
        <a href="#" class="list-group-item" data-name="<?= $maker ?>"><?= $maker ?><span class="glyphicon glyphicon-chevron-right" style="float: right;"></span></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="viewer">
      &nbsp;
    </div>
  </div>
</div>

<div id="loader" class="hidden">
  <h4>Загружаем...</h4>
  <div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
      <span class="sr-only"></span>
    </div>
  </div>
</div>

<div id="popup-to-basket" class="hidden">
  <form class="form-inline" action="<?= yii\helpers\Url::to(['basket/place']); ?>">
    <h5>От {min} до {max} шт.</h5>
    <h5>Упаковка по {lot} шт.</h5>
    <?=  \yii\bootstrap\Html::hiddenInput(\yii::$app->request->csrfParam, \yii::$app->request->csrfToken)?>
    <div class="input-group">      
      <input type="number" min="1" max="{max}" step="{lot}" name="basket_count" value="{min}" class="form-control to-basket-count" placeholder="Количество" />
      <span class="input-group-addon" class="to-basket-header">шт.</span>
      <span class="input-group-btn">
        <button class="btn btn-default to-basket-btn" type="button">Добавить</button>
      </span>
    </div>
  </form>
</div>  
    
<?php
$url = yii\helpers\Url::to(['site/parts']);
$script = "$('a[data-name]').initPartSelect('div.viewer',"
    . "function( data ){"
    .   "return {articul:'" . $model->articul . "',maker: data.attr('data-name'),analog: $('#analog').val()}"
    . "}"
    . ",'$url');";
$this->registerJs($script);
