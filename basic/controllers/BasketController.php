<?php

namespace app\controllers;

use yii\web\Controller;

class BasketController extends Controller {
    
  public function behaviors(){
    return [];
  }

  public function actions() {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ]
    ];
  }

  public function actionIndex() {
    
  }
  
  public function actionPlace(){
    if( \yii::$app->user->isGuest ){
      return "Для добавления в корзину необходима авторизация";
    }
    /* @var $user \app\models\WebUser */
    $user = \yii::$app->user->getIdentity();    
    $basketPart = new \app\models\BasketPartModel();
    
    if( !$basketPart->load(\yii::$app->request->post(),'') || !$basketPart->validate() ){      
      return implode(", ", $basketPart->getFirstErrors());      
    }
    
    $basketPart->setAttribute('uid', $user->getId());
    $basketPart->setAttribute('part_id', \yii::$app->request->post('id',''));
    
    if( $basketPart->save() ){
      return "OK";      
    }
    return "Ошибка сохранения";
  }

}
