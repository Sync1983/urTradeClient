<?php
/* Order Index */
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
    'dataProvider'=> $orderProvider,
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
        'heading'=>"Заказы",
    ],
    'toolbar'=> [        
        '{toggleData}',
    ],
    'columns' => [
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'format'  => 'raw',
        'group'=>true,
        //'groupedRow'=>true,
        'value' => function($model,$key,$index){
          return \app\models\OrderPartModel::getStatus()[$model->status];
        }
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
        'attribute' => 'basket_count',
      ],      
      [
        'class' => '\kartik\grid\ActionColumn',
        'header' => 'Функции',
        'template' => '{delete}',
        'buttons' => [
          'delete' => function ($url, $model){
            
            if( $model->getAttribute('status') != \app\models\OrderPartModel::OS_WAIT ){
              return '';
            }

            return Html::a('<span class="glyphicon glyphicon-trash"></span>',$url,[
              'title' => 'Удалить заказ?',
              'data-confirm' => 'Вы уверены, что хотите удалить этот заказ?',
              'data-method' => 'post',
              'data-pjax' => '0'
            ]);
          }
        ]        
      ]
    ]
]);

$this->registerJs('$("button.to-order").toOrderList("' . yii\helpers\Url::to(['order/place']) . '")');
?>