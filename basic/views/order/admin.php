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
        'group'=>true,
        //'groupedRow'=>true,
        'value' => function($model,$key,$index){
          return \app\models\OrderPartModel::getStatus()[$model->status];
        }
      ],      
      [ 'class' => '\kartik\grid\DataColumn',
        'label' => 'Пользователь',
        'attribute' => 'uid',
        'value' => function($model,$key,$index){
          $user = app\models\WebUser::findOne(['id' => $model->uid]);
          return $user->getAttribute('name');
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
        'value' => function ($model, $key, $index, $widget) {
          $user = \app\models\WebUser::findOne(['id'=>$model->uid]);
          if( !$user ){
            return 0;
          }
          
          $markup = $user->markup;
          /* @var $model app\models\BasketPartModel */
          $price = $model->getAttribute('price');

          return round($price * (1 + $markup / 100),2) . " [" . $price . "]";
        }        
      ],            
      [ 'class' => '\kartik\grid\DataColumn',        
        'attribute' => 'basket_count',
      ],
      [ 'class' => '\kartik\grid\BooleanColumn',
        'attribute' => 'pay',
      ],
      [
        'class' => '\kartik\grid\ActionColumn',
        'header' => 'Функции',
        'template' => '{update} {delete}',
        'buttons' => [
          'update' => function($url,$model){
            /* @var $model \app\models\OrderPartModel */
            $label = '<span class="glyphicon glyphicon-pencil"></span>';

            $content = Html::beginForm(\yii\helpers\Url::to(['order/change-status']), 'post');            
            $content .= Html::hiddenInput('id',$model->id);

            $content .= Html::label('Новый статус заказа');
            $content .= kartik\widgets\Select2::widget([
                          //'model'     => $model,
                          //'attribute' => 'status',
                          'name' => 'status',
                          'data' => $model->getAvaibleStatus(),
                          'options' => ['placeholder' => 'Выберите новый статус...'],
                          'pluginOptions' => [
                            'allowClear' => true
                          ],
                          'pluginEvents' => [
                            "change" => "function() {console.log(\"change\");changeStatus(this)}",
                          ]
                        ]);

            $content .= Html::endForm();
            
            return \kartik\popover\PopoverX::widget([
                      'header'    => 'Изменить статус',
                      'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
                      'content'   => $content,                      
                      'toggleButton' => [
                        'tag'   => 'a',
                        'label' => $label,
                        'size'  => \kartik\popover\PopoverX::SIZE_LARGE,
                        'class' => '',
                        'title' => 'Изменить статус',
                        'data-pjax' => '0'
                      ],
                  ]);
          },
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
<script>
  function changeStatus(button){
    var btn_parent  = $(button).parent();
    var parent      = $(button).parent().parent();
    var form        = $(parent).find('form');

    $.ajax({
      url:    $(form).attr('action'),
      method: $(form).attr('method'),
      data:   $(form).serialize()
    }).done(function(answer){
      if( answer.error ){
        $(btn_parent).append(
          '<div class="alert alert-warning" role="alert">'+
            answer.error +
          '</div>');
      };
      if( answer.status ){
        $(btn_parent).append(
          '<div class="alert alert-info" role="alert">'+
            answer.status +
          '</div>');
      }
    }).error(function(error){
      console.log(error);
    });
  }
</script>