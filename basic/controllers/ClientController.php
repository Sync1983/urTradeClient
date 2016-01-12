<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;

class ClientController extends Controller {
    
  public function behaviors(){
    return [
      'access' => [
        'class' => \app\behavior\AdminBehavior::className()
      ],      
    ];
  }

  public function actions() {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ]
    ];
  }

  public function actionIndex($new_user=null) {
    /* hasEditable request - change field value */
    if( \yii::$app->request->post('hasEditable',0) == 1 ){
      $key  = \yii::$app->request->post('editableKey',null);
      $index= \yii::$app->request->post('editableIndex',null);
      $data = \yii::$app->request->post('WebUser',null);
      
      if( ($key === null) || ($index === null) || !$data ){
        echo json_encode(['output'=>'','message'=>'']);
        return false;
      }
      
      $out = "";
      $user = \app\models\WebUser::findOne(['id'=>  intval($key)]);
      
      foreach( $data[$index] as $key=>$value){
        $user->setAttribute($key, $value);
        $out .= $value;
      }
      
      $user->save();
      echo json_encode(['output'=>$out,'message'=>'']);
      return;
    }
    
    $query = \app\models\WebUser::find()->indexBy('id');    
    $clientProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 20,
      ],
    ]);
    
    
    if( !$new_user ){
      $new_user = new \app\models\NewUserForm();      
    } 
    
    return $this->render('index',[
      'clientProvider' => $clientProvider,
      'newUserForm' => $new_user      
    ]);
  }
  
  public function actionValidate(){
    $model = new \app\models\NewUserForm();
    if (\yii::$app->request->isAjax && $model->load(\yii::$app->request->post())) {
      \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return \yii\widgets\ActiveForm::validate($model);
    }
    return false;
  }
  
  public function actionAddNew(){
    $model = new \app\models\NewUserForm();
    
    if( $model->load(\yii::$app->request->post()) && $model->validate() ){
      $user = new \app\models\WebUser();
      $user->setAttributes($model->getAttributes());
      $user->save();
      return $this->redirect(['client/index']);
    }
     
    return $this->actionIndex($model);    
  }
  
  public function actionRegenerate(){
    $uid = \yii::$app->request->post('id',null);
    $pass = \yii::$app->request->post('new-pass',null);
    
    if( !$uid || !$pass ){
      return $this->render('error',['name'=>"Ошибка", 'message' => 'Неверно переданные данные']);
    }
    
    $user = \app\models\WebUser::findOne(['id' => intval($uid)]);
    if( !$user ){
      return $this->render('error',['name'=>"Ошибка", 'message' => 'Данный пользователь не найден']);      
    }
    
    $user->setAttribute('upass', password_hash($pass, PASSWORD_BCRYPT));
    if ( !$user->save() ){
      return $this->render('error',['name'=>"Ошибка", 'message' => 'Ошибка сохранения']);      
    }
    
    return $this->render('change',['user'=>$user, 'message' => 'Пароль изменен']);
  }
  
  public function actionDelete(){
    $uid = \yii::$app->request->get('id',null);
    if( !$uid ){
      return $this->render('error',['name'=>"Ошибка", 'message' => 'Неверно переданные данные']);
    }
    /* @var $user WebUser */
    $user = \app\models\WebUser::findOne(['id' => intval($uid)]);    
    if( !$user ){
      return $this->render('error',['name'=>"Ошибка", 'message' => 'Данный пользователь не найден']);      
    }
    
    if( !$user->delete() ){
      return $this->render('error',['name'=>"Ошибка", 'message' => 'Ошибка удаления']);      
    }
    
    return $this->render('change',['user'=>$user, 'message' => 'Пользователь удален']);    
  }

}
