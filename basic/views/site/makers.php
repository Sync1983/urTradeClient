<?php
/* @var $this yii\web\View */
/* @var $model app\models\SearchForm */
?>
<div class="panel panel-default panel-full-screen">
  <div class="panel-heading">
    <h3 class="panel-title">Выберите производителя артикула <b><?= $model->articul ?></b> 
      <?= kartik\checkbox\CheckboxX::widget([
            'id'  => 'analog',
            'name'  => 'analog',
            'value' => $model->analog,
            'pluginOptions'=>['threeState'=>false]            
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
<?php
$script = <<<SCRIPT
    var viewer = $('div.viewer');
    
    $('a[data-name]').each(function(index,item){
      $(item).click(function(event) {
        var target = event.currentTarget; 
        var name = $(target).attr('data-name');
        console.log(name,viewer);
      });
    });
SCRIPT;
$this->registerJs($script);
