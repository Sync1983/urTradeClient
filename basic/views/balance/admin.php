<?php
/* Balance/Admin */
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\bootstrap\Html;

echo GridView::widget([
    'dataProvider'=> $balanceProvider,    
    'pjax'=>false,
    'pjaxSettings'=>[
        'neverTimeout'=>true
    ],
    'export'  => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>"Общий список операций",
    ],
    'toolbar'=> [       
        '{toggleData}',
    ],
    'columns' => [
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
        'width'   => '5%'
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'time',
        'format'  => 'DateTime',
        'width'   => '15%'
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'uid',
        'label'  => 'Пользователь',
        'format'  => 'Text',
          'value'  => function ($model,$row, $widget){
            return \app\models\WebUser::findOne(['id' => intval($model->getAttribute('uid'))])->getAttribute('uname');
          },
        'group'=>true,
        'groupedRow'=>true,
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'value',
        'width'   => '30%'
      ],
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'comment',
      ]
   ]
]);