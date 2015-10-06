<?php

/* @var $this yii\web\View */
/* @var $newUserForm \app\models\NewUserForm */


use kartik\grid\GridView;
use yii\bootstrap\Html;

echo GridView::widget([
    'dataProvider'=> $clientProvider,
    //'filterModel' => $searchModel,
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true
    ],
    'export'  => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>"Пользователи",
    ],
    'toolbar'=> [
        [
          'content'=> Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>'Добавить пользователя', 'class'=>'btn btn-success','data-toggle'=>"modal", 'data-target'=>"#create-new"])
        ],
        '{export}',
        '{toggleData}',
    ],
    'columns' => [
      [ 'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
      ],
      [ 'class' => '\kartik\grid\EditableColumn',
        'label' => 'Логин',
        'attribute' => 'uname',
        'editableOptions' => [
          'asPopover' => false,          
        ]
      ],
      [ 'class' => '\kartik\grid\EditableColumn',
        'label' => 'Админ.',
        'attribute' => 'is_admin',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $column){
          return $model->is_admin?"Да":"Нет";
        },
        'editableOptions' => [
          'header'    => 'Администратор',
          'inputType' => 'widget',
          'asPopover' => false,
          'widgetClass' => kartik\editable\Editable::INPUT_SWITCH
        ]
      ],
      [ 'class' => '\kartik\grid\EditableColumn',
        'label' => 'Наценка',
        'attribute' => 'markup',
        'editableOptions' => [        
          'header'    => 'Наценка',
          'inputType' => 'widget',
          'widgetClass' => kartik\editable\Editable::INPUT_SPIN,
          'options' => [
            'pluginOptions' => [              
              'buttonup_class' => 'btn btn-primary', 
              'buttondown_class' => 'btn btn-info',               
              'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
              'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>',
              'postfix' => '%',
              'min' => 0,
              'max' => 999,
              'step' => 1,            
            ],
            
          ],          
        ]
      ],
      [ 'class' => '\kartik\grid\EditableColumn',
        'label' => 'Имя',
        'attribute' => 'name',
        'editableOptions' => [
          'asPopover' => false,
          'header'    => 'Имя',
        ]
      ],
      [ 'class' => '\kartik\grid\EditableColumn',
        'label' => 'Телефон',
        'attribute' => 'phone',
        'editableOptions' => [
          'asPopover' => false,
          'header'    => 'Телефон',
        ]
      ],
      [ 'class' => '\kartik\grid\EditableColumn',
        'label' => 'Почта',
        'attribute' => 'mail',
        'editableOptions' => [
          'asPopover' => false,
          'header'    => 'Электронная почта',
        ]
      ],
      [
        'class' => '\kartik\grid\ActionColumn',
        'header' => 'Функции',
        'template' => '{update} {delete}',
        'buttons' => [
          'update' => function ($url,$model){
            return '<form action = ' . \yii\helpers\Url::to(['client/regenerate']) . ' method="POST">' .                
              Html::hiddenInput(\yii::$app->request->csrfParam, \yii::$app->request->csrfToken).
              Html::hiddenInput('id', $model->id).
              kartik\popover\PopoverX::widget([
              'header' => 'Регенерация пароля',
              'placement' => kartik\popover\PopoverX::ALIGN_LEFT,
              'content' => 
                Html::input('text', 'new-pass', '', [
                  'placeholder' => 'Введите новый пароль'
                ]),
              'footer' => Html::submitButton('Изменить', ['class'=>'btn btn-sm btn-primary','data-confirm' => 'Вы действительно хотите изменить пароль пользователя?']),
              'toggleButton' => [
                'tag'   => 'a',
                'label' => '<span class="glyphicon glyphicon-pencil"></span>', 
                'class' => '',
                'role'  => "button",
                'title' => "Редактировать пароль", 
                'data-pjax' => '0'
              ],
              ]).
            '</form>';
          }          
        ],        
      ]
    ]
]);

?>

<div id="create-new" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="create-new-label" data-show="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Создание пользователя</h4>
      </div>
      <?php $form = \yii\bootstrap\ActiveForm::begin([
        'action'  => \yii\helpers\Url::to(['client/add-new']),
        'validateOnSubmit' => true,
        'enableAjaxValidation' => true,
        'validationUrl' => \yii\helpers\Url::to(['client/validate'])
      ]);?>
      <div class="modal-body">
        <?php
          echo $form->field($newUserForm, 'uname')  -> input('text');
          echo $form->field($newUserForm, 'upass')  -> input('text');
          echo $form->field($newUserForm, 'markup') -> input('numeric');
          echo $form->field($newUserForm, 'name')   -> input('text');
          echo $form->field($newUserForm, 'phone')  -> input('text');
          echo $form->field($newUserForm, 'mail')   -> input('text');
          echo $form->field($newUserForm, 'is_admin')->checkbox(['options'=>['style'=>['font-weight'=>'bold']]],true);
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <?= Html::submitButton("Добавить",['type'=>"button", 'class'=>"btn btn-primary"])?>
      </div>
      <?php \yii\bootstrap\ActiveForm::end()?>
    </div>
  </div>
</div>