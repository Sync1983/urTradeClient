<?php 
/* @var $model app\models\SearchForm */
$url = yii\helpers\Url::to(['basket/place']);
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
      if( !is_numeric($part->count) ){
        $max = $part->lot_party * 10;
      } else {
        $max = intval($part->count);        
      }
      
      $lot = intval($part->lot_party);      
      if( !$lot ){
        $lot = 1;
      }
      
      if( !\yii::$app->user->isGuest ) {
        /* @var $identity \app\models\WebUser */
        $identity = \yii::$app->user->getIdentity();
        $markup = intval($identity->getAttribute('markup'))/100;
        $show_price = round($part->price + $part->price * $markup,2);
      } 
      ?>
    <tr <?= $part->is_original?"":"data-analog";?> class="<?= (!$part->is_original && !$model->analog)?"hidden ":" "?>">
      <script type="application/json">
        <?= json_encode($part); ?>
      </script>
      <td data-order="<?= $part->is_original;?>"><?= $part->producer?></td>
      <td data-order="<?= $part->is_original;?>"><?= $part->articul?></td>
      <td data-order="<?= $part->is_original;?>"><?= $part->name?></td>      
      <td class="price" original-value="<?= $show_price ?>"><?= $show_price ?></td>
      <td><?= $part->shiping?></td>
      <td><?= $part->count ?></td>
      <td><button 
            role="button" 
            class="btn btn-default btn" 
            data-to-basket 
            data-min  = "<?= $lot?>"
            data-lot  = "<?= $lot?>"
            data-max  = "<?= $max?>"
            >
          В корзину
        </button>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>