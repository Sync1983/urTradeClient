<?php

/* @var $this yii\web\View */
/* @var $model app\models\WebUser */

use yii\bootstrap\Html;
use kartik\editable\Editable;
use app\models\BalanceModel;
use yii\bootstrap\ActiveForm;

$qkey = $model->getAttribute('id');
$balance_model = new BalanceModel();
$balance = BalanceModel::getBalance($qkey);
?>
<div class="container" style="width:90%">
<h3>Общий баланс пользователя <b><u><?= round($balance['full']-$balance['credit'],2) ?></u></b> руб. и начальный кредит <b>
      <?= Editable::widget([
        'name'            => "WebUser[$qkey][credit]",
        //'model'               => $model,
        //'attribute'           => "credit",
        'value'               => $model->getAttribute('credit'),
        'valueIfNull'         => 0,
        'asPopover'           => true,
        'size'                => 'md',
        'formOptions' => [
          'action' => yii\helpers\Url::to(['client/index']),
        ],
        'beforeInput' => function($form,$widget) use ($qkey) {
          return  Html::hiddenInput('editableKey', $qkey).
                  Html::hiddenInput('editableIndex' , $qkey);
        },
        'options' => [
          'class'=>'form-control',
          'pluginOptions' => [
            'postfix' => 'руб.',
            'min' => 0,
            'max' => 1000000,
          ]
        ],
        'inputType' => Editable::INPUT_WIDGET,
        'widgetClass' => Editable::INPUT_SPIN,
      ]);
  ?></b>&nbsp;руб.</h3>
<div class="panel panel-warning">
  <div class="panel-heading">
    <h3 class="panel-title">Изменение баланса</h3>
  </div>
  <div class="panel-body">
  <?php $form = ActiveForm::begin([
          'layout' => 'horizontal',
          'enableAjaxValidation' => true,
          'action' => yii\helpers\Url::to(['balance/change']),
          'validationUrl' => yii\helpers\Url::to(['balance/change-validate']),
          'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-4',
                'error' => '',
                'hint' => '',
            ],
          ],
        ]);
    echo Html::hiddenInput('uid',$qkey);
    echo $form->field($balance_model, 'value')->widget(kartik\widgets\TouchSpin::classname(),[
      'pluginOptions' => [
        'postfix' => 'руб.',
        'min' => -10000000,
        'max' =>  10000000,
      ]
    ]);
    echo $form->field($balance_model, 'comment')->input('text');
    echo Html::submitButton('Изменить',['class'=>'btn btn-info col-sm-offset-2 col-sm-2']);
    ActiveForm::end();
  ?>
  </div>
</div>

</div>