<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;

class OrderController extends Controller {
    
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
    
    $query = \app\models\OrderPartModel::find()->where(['uid'=>$user->getId()])->indexBy('id');
    
    $orderProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 20,
      ],
    ]);
    
    return $this->render('index',['orderProvider' => $orderProvider]);
  }
  
  public function actionDelete(){
    $id = \yii::$app->request->get('id',null);
    if( !$id ){
      return $this->redirect(['basket/index']);
    }
    $uid = \yii::$app->user->getIdentity()->getId();
    
    $part = \app\models\OrderPartModel::findOne(['id'=>intval($id),'uid'=>$uid]);
    if( !$part ){
      return $this->redirect(['order/index']);      
    }
    
    if( $part->getAttribute('status') === \app\models\OrderPartModel::OS_WAIT ){
      $part->delete();      
    }
    
    return $this->redirect(['order/index']);        
  }

  public function actionPlace(){    
    /* @var $user \app\models\WebUser */
    $user = \yii::$app->user->getIdentity();    
    
    $ids = array_map(function($item){
      return intval($item);
    }, \yii::$app->request->post('ids'));
    
    $parts = \app\models\BasketPartModel::find() 
              ->where(['uid'=>$user->getId()])
              ->andWhere(['in', 'id', $ids]) 
              ->all();
    
    foreach( $parts as $part) {
      
      $order = new \app\models\OrderPartModel();
    
      $order->setAttributes($part->getAttributes());
      $order->setAttribute('status', \app\models\OrderPartModel::OS_WAIT);
    
      if( $order->save() ){
        $part->delete();      
      }
      
    }
    
    return \yii\helpers\Url::to(['order/index']);
  }  
  
  public function actionAdmin(){
    
    /* @var $user \app\models\WebUser */
    $user = \yii::$app->user->getIdentity();
    
    if( !$user->isAdmin() ){
      return $this->redirect(['order/index']);
    }
    
    if( \yii::$app->request->post('hasEditable',0) == 1 ){
      $key  = \yii::$app->request->post('editableKey',null);
      $index= \yii::$app->request->post('editableIndex',null);
      $data = \yii::$app->request->post('BasketPartModel',null);
      
      if( ($key === null) || ($index === null) || !$data ){
        echo json_encode(['output'=>'','message'=>'']);
        return false;
      }
      
      $out = "";
      $part = \app\models\OrderPartModel::findOne(['id'=>  intval($key)]);
      
      foreach( $data[$index] as $key=>$value){
        $part->setAttribute($key, $value);
        $out .= $value;
      }
      
      if ( $part->save() ){
        echo json_encode(['output'=>$out,'message'=>'']);        
      }
      return;
    }
    
    
    $query = \app\models\OrderPartModel::find()->indexBy('id');
    
    $orderProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 20,
      ],
    ]);
    
    return $this->render('admin',['orderProvider' => $orderProvider]);
  }

  public function actionChangeStatus(){
    
    /* @var $user \app\models\WebUser */
    $user = \yii::$app->user->getIdentity();

    if( !$user->isAdmin() ){
      return $this->redirect(['order/index']);
    }

    $id     = \yii::$app->request->post('id',null);    
    $status = \yii::$app->request->post('status',null);

    \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    if( !$id ){
      return ['error' => 'ID Error'];
    }

    $order = \app\models\OrderPartModel::findOne(['id' => $id]);
    if( !$order ){
      return ['error' => 'Заказ не найден'];
    }

    if( !$order->changeStatus($status) ){
      return ['error' => 'Ошибка сохранения. Проверьте баланс пользователя'];
    }

    return ['status'=>"Статус изменен. Новый статус [" . $order->getCurrentStatus() . "]"];
  }

  public function actionPlaceProvider(){

    /* @var $user \app\models\WebUser */
    $user = \yii::$app->user->getIdentity();

    if( !$user->isAdmin() ){
      return $this->redirect(['order/index']);
    }

    \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    $id     = \yii::$app->request->post('id',null);
    
    if( !$id ){
      return ['error' => 'ID Error'];
    }

    $order = \app\models\OrderPartModel::findOne(['id' => $id]);
    if( !$order ){
      return ['error' => 'Заказ не найден'];
    }

    $provider = new \app\models\ProviderATCModel();
    if ( !$provider->sendToOrder($order) ){
      return ['error' => 'Заказ не размещен'];
    }

    $order->setAttribute('place', 1);
    if( $order->status == \app\models\OrderPartModel::OS_WAIT ){
      $order->setAttribute('status', \app\models\OrderPartModel::OS_IN_ORDER);
    }

    $order->save();

    return ['status'=>"OK"];
  }

}
