<?php
/* Balance/Admin */
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\bootstrap\Html;
$blnc = app\models\BalanceModel::getUserBalance();
$crd  = $blnc['credit'];
$full = $blnc['full'];
?>
<h4> Общий баланс: <u><?= round($full-$crd, 2)?> руб.</u> Кредит: <u><?= round($crd, 2)?> руб.</u></h4>
<h5> Заказать еще можно на сумму: <u <?= ($full<0)?"style=\"color:red\"":""?>><?= round($full, 2)?> руб.</u></h5>
<?php
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