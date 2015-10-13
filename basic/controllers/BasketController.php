<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;

class BasketController extends Controller {
    
  public function behaviors(){
    return [
      'user_acces' => [
        'class' => \app\behavior\UserBehavior::className()
      ]
    ];
  }

  public function actions() {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ]
    ];
  }

  public function actionIndex() {
    /* @var $user \app\models\WebUser */
    $user = \yii::$app->user->getIdentity();
    
    if( \yii::$app->request->post('hasEditable',0) == 1 ){
      $key  = \yii::$app->request->post('editableKey',null);
      $index= \yii::$app->request->post('editableIndex',null);
      $data = \yii::$app->request->post('BasketPartModel',null);
      
      if( ($key === null) || ($index === null) || !$data ){
        echo json_encode(['output'=>'','message'=>'']);
        return false;
      }
      
      $out = "";
      $part = \app\models\BasketPartModel::findOne(['id'=>  intval($key),'uid'=>$user->getId()]);
      
      foreach( $data[$index] as $key=>$value){
        $part->setAttribute($key, $value);
        $out .= $value;
      }
      
      if ( $part->save() ){
        echo json_encode(['output'=>$out,'message'=>'']);        
      }
      return;
    }
    
    
    $query = \app\models\BasketPartModel::find()->where(['uid'=>$user->getId()])->indexBy('id');
    
    $basketProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 20,
      ],
    ]);
    
    return $this->render('index',['basketProvider' => $basketProvider]);
  }
  
  public function actionDelete(){
    $id = \yii::$app->request->get('id',null);
    if( !$id ){
      return $this->redirect(['basket/index']);
    }
    $uid = \yii::$app->user->getIdentity()->getId();
    
    $part = \app\models\BasketPartModel::findOne(['id'=>intval($id),'uid'=>$uid]);
    if( !$part ){
      return $this->redirect(['basket/index']);      
    }
    
    $part->delete();
    return $this->redirect(['basket/index']);        
  }

  public function actionPlace(){    
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
  
  public function actionOrder(){
    $id = \yii::$app->request->get('id',null);
    if( !$id ){
      return $this->redirect(['basket/index']);
    }
    
    $uid = \yii::$app->user->getIdentity()->getId();
    $part = \app\models\BasketPartModel::findOne(['id'=>intval($id),'uid'=>$uid]);
    
    $order = new \app\models\OrderPartModel();
    
    $order->setAttributes($part->getAttributes());
    $order->setAttribute('status', \app\models\OrderPartModel::OS_WAIT);
    
    if( $order->save() ){
      $part->delete();
      return $this->redirect(['order/index']);
    }
    
    return $this->redirect(['basket/index']);
  }

}
