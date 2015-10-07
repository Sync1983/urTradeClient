<?php 
/* @var $model app\models\SearchForm */

$csrf = \yii\bootstrap\Html::hiddenInput(\yii::$app->request->csrfParam, \yii::$app->request->csrfToken);
$input = \yii\bootstrap\Html::input('text','count',false,['placeholder'=>'Количество', 'style'=>'width:100%','class'=>'form-control']);
$submit = \yii\bootstrap\Html::submitButton('Разместить',['class'=>'btn btn-danger']);
$url = yii\helpers\Url::to(['basket/place']);

$popup = <<<POPUP
  <div class="panel-body">    
    <form action = $url method="POST">    
      $csrf
      <input type="hidden" name="id" value = '{id}' />
    <div class="row form-group">
      $input
    </div>
    <div class="row form-group">
      $submit
    </div>
    </form>
  </div>
POPUP;
?>
    
<table id="parts" class="stripe hover">
  <thead>
  <tr class='part-table-head'>    
    <th style="width: 20%;">Производитель</th>					
    <th style="width: 10%;">Артикул</th>
    <th>Наименование</th>
    <th style="width: 12%;">Цена,руб.</th>	
    <th style="width: 8%;">Срок,дн.</th>
    <th style="width: 8%;">Наличие,шт.</th>	
    <th style="width: 9%;">В корзину</th>	
  </tr>
  </thead>
  <tbody>
    <?php foreach($parts as $id=>$part): 
      /* @var $part \app\models\PartModel */ 
      $show_price = "";
      if( !\yii::$app->user->isGuest ) {
        /* @var $identity \app\models\WebUser */
        $identity = \yii::$app->user->getIdentity();
        $markup = intval($identity->getAttribute('markup'))/100;
        $show_price = round($part->price + $part->price * $markup,2);
      } 
      ?>
    <tr <?= $part->is_original?"":"data-analog";?> class="<?= (!$part->is_original && !$model->analog)?"hidden ":" "?>">
      <td data-order="<?= $part->is_original;?>"><?= $part->producer ?></td>
      <td data-order="<?= $part->is_original;?>"><?= $part->articul?></td>
      <td data-order="<?= $part->is_original;?>"><?= $part->name . $part->id?></td>      
      <td><?= $show_price ?></td>
      <td><?= $part->shiping?></td>
      <td><?= $part->count ?></td>
      <td><button 
            type="button" 
            class="btn btn-default" 
            data-container="body" 
            data-toggle="popover" 
            data-placement="left"
            data-html="true"
            data-title="Разместить в корзине"
            data-content = "<?= str_replace('"', "'", 
                str_replace("{id}", 
                    "{ articul: '".$model->articul."',"
                    . "maker  : '".$part->maker_id."',"
                    . "provider:'".$part->provider."',"
                    . "id     : '".$part->id      ."'}",
                    $popup));?>">
              В корзину
        </button>          
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>