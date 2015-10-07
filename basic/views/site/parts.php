<?php 
/* @var $model app\models\SearchForm */
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
      <td data-order="<?= $part->is_original;?>"><?= $part->name?></td>      
      <td><?= $show_price ?></td>
      <td><?= $part->shiping?></td>
      <td><?= $part->count ?></td>
      <td>asd</td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>