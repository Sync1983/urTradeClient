<?php
/* @var $this yii\web\View */
/* @var $model app\models\SearchForm */
\app\assets\TableAsset::register($this);
?>

<script>
  var markupData = [
    <?php  foreach (\app\models\MarkupModel::getList() as $item):?>
        {
          id: <?=$item['id']?>,
          text: '<?=$item['text']?>'
        },
    <?php endforeach;?>
  ];
  
  function onMClose(){    
    var popup = $("div.markup-add");
    var form = popup.find('form');
    form.trigger('reset');
    popup.hide(100);
  }

  function onMChange(item){
    var ctrl = $(item);
    var type = ctrl.attr('type');
    var val  = ctrl.val();

    ctrl.removeClass('error');

    if( type === "text"){

      if (String(val).length > 10 ){
        ctrl.addClass('error');
      }
    } else if( type === "number" ){
    
      if( (val<0) || (val > 1000) ){
        ctrl.addClass('error');
      }
    }    
  }

  function onMSave(){
    var popup = $("div.markup-add");
    var form  = popup.find('form');
    var formData = form.serialize();    

    $.ajax({
      method: "GET",
      url: '<?= yii\helpers\Url::to(['order/markup-add']);?>',
      data: formData
    }).done( function (data){
      $('.markup-selector').html('');
      $('.markup-selector').select2("destroy");
      $('.markup-selector').markupInit(data);
      onMClose();
    });
  }

  function markupDelete(value,name){
    console.log("Delete",name,value);
    $.ajax({
      method: "GET",
      url: '<?= yii\helpers\Url::to(['order/markup-delete']);?>',
      data: {name:name,value:value}
    }).done( function (data){
      $('.markup-selector').html('');
      $('.markup-selector').select2("destroy");
      $('.markup-selector').markupInit(data);
    });
  }
</script>

<div class="panel panel-default panel-full-screen">
  <div class="panel-heading">
    <div class="panel-title title-bar"><span class="text">Выберите производителя артикула</span><b><?= $model->articul ?></b>
      <?php if( !\yii::$app->user->isGuest): ?>
        <select class="markup-selector"></select>
      <?php endif;?>

      <label for="analog" class="analog-label">Аналоги</label>
      <?= kartik\checkbox\CheckboxX::widget([            
            'id'    => 'analog',
            'name'  => 'analog',
            'value' => $model->analog,            
            'pluginOptions'=>[
              'threeState'=>false,
              'theme' => 'analog',
            ],
            'pluginEvents' => [
              "change"=>"function() { $('tr[data-analog]').showBy($('#analog').val())}"              
            ]            
        ]);?> 
        </div>
  </div>
  <div class="panel-body">
    <div class="selector">      
      <div class="list-group">
	<?php if( !$makers ){
		  $makers = [];
		};
        foreach($makers as $maker=>$key): ?>
        <a href="#" class="list-group-item" data-name="<?= $maker ?>"><?= $maker ?><span class="glyphicon glyphicon-chevron-right" style="float: right;"></span></a>
        <?php endforeach; ?>
      </div>
    </div>
      <?php if( !\yii::$app->user->isGuest): ?>
      <div class="markup-add panel panel-warning">
        <div class="panel-heading">
          <h3 class="panel-title">Добавить вариант наценки</h3>
        </div>
        <div class="panel-body">
          <form>
            <label>Описание</label>               <input type="text"  name="name"                      onchange="onMChange(this);" onkeydown="onMChange(this);" />
            <label>Значение наценки (в %)</label> <input type="number" name="value" min="0" max="1000" onchange="onMChange(this);" onkeydown="onMChange(this);" />
          </form>
        </div>
        <div class="panel-footer">
          <button class="btn btn-danger" onclick="onMClose();">Закрыть</button>
          <button class="btn btn-info"   onclick="onMSave();" >Сохранить</button>
        </div>
      </div>
      <?php endif;?>
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
$script = "$('.markup-selector').markupInit(markupData);";
if( !\yii::$app->user->isGuest){
  $this->registerJs($script);
}
