<?php
/* Basket Index */
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\bootstrap\Html;
$markup = 0;
/* @var $user app\models\WebUser */
$user = \yii::$app->user->getIdentity();

if( !\yii::$app->user->isGuest ){
  $markup = $user->getAttribute('markup');
}

echo GridView::widget([
    'dataProvider'=> $basketProvider,
    //'filterModel' => $searchModel,
    'showPageSummary' => true,
    'id'  => 'basket-list',
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true
    ],
    'export'  => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>"Кррзина",
    ],
    'toolbar'=> [
        [
          'content'=> Html::button('<i class="glyphicon glyphicon-shopping-cart"></i>', [
            'type'  => 'button', 
            'title' => 'Добавить позиции в заказ', 
            'class' => 'btn btn-success to-order',            
          ])
        ],
        '{export}',
        '{toggleData}',
    ],
    'columns' => [
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'producer',
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'articul',
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
      ],
      [
        'class' => '\kartik\grid\FormulaColumn',
        'attribute' => 'price',
        'pageSummary' => true,
        'value' => function ($model, $key, $index, $widget) use ($markup) {
          /* @var $model app\models\BasketPartModel */
          $price = $model->getAttribute('price');
          return round($price + $price * $markup / 100,2);
        }        
      ],      
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'shiping',
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'count',
      ],
      [ 'class' => '\kartik\grid\EditableColumn',        
        'attribute' => 'basket_count',        
        'pageSummary' => true,
        'editableOptions' => function ($model, $key, $index) {
          /* @var $model app\models\BasketPartModel */
          return [
            'header'    => 'Количество',
            'inputType' => 'widget',
            'widgetClass' => kartik\editable\Editable::INPUT_SPIN,
            'options' => [
              'pluginOptions' => [              
              'buttonup_class' => 'btn btn-primary', 
              'buttondown_class' => 'btn btn-info',               
              'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
              'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>',
              'postfix' => 'шт.',
              'min' => $model->getAttribute('lot_party'),
              'max' => $model->getAttribute('count'),
              'step' => $model->getAttribute('lot_party'),
              ]
            ]            
          ];
        }
      ],
      [
        'class' => '\kartik\grid\CheckboxColumn'
      ],
      [
        'class' => '\kartik\grid\ActionColumn',
        'header' => 'Функции',
        'template' => '{order}&nbsp;&nbsp;{delete}',
        'buttons' => [
          'order' => function ($url,$model){
            return Html::a('<span class="glyphicon glyphicon-shopping-cart"></span>', $url,[
              'title'         => 'В заказ',
              'data-confirm'  => 'Разместить позицию (' . $model->getAttribute('basket_count') . ' шт.) в заказ',
              'data-method'   => "POST",
              'data-pjax'     => 0
            ]);
          }
        ]
      ]
    ]
]);

$this->registerJs('$("button.to-order").toOrderList("' . yii\helpers\Url::to(['order/place']) . '")');
?>