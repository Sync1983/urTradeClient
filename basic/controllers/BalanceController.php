<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\BalanceModel;

class BalanceController extends Controller {
    
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

  public function actionChangeValidate() {
    if ( !\yii::$app->user->getIdentity()->isAdmin() ){
      throw new \yii\web\BadRequestHttpException("Access error");
    }

    $model = new BalanceModel();
    if ( \yii::$app->request->isAjax && $model->load(\yii::$app->request->post()) ) {
      \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return \yii\bootstrap\ActiveForm::validate($model);
    }
    return false;
  }

  public function actionChange(){
    if ( !\yii::$app->user->getIdentity()->isAdmin() ){
      throw new \yii\web\BadRequestHttpException("Access error");
    }

    $uid = \yii::$app->request->post('uid',false);
    if ( !$uid ){
      throw new \yii\web\BadRequestHttpException("Parameters");
    }

    $model = new BalanceModel();
    $model->setAttribute('uid', $uid);

    if( (!$model->load(\yii::$app->request->post())) || (!$model->validate()) ) {
      throw new \yii\web\BadRequestHttpException("Validation error");
    }

    $model->save();

    $returnUrl = \yii::$app->request->referrer;    
    \yii::$app->response->getHeaders()->set('refresh',"5;url=$returnUrl");

    return $this->render('change', ['model' => $model]);
  }

  public function actionAdmin(){
    if ( !\yii::$app->user->getIdentity()->isAdmin() ){
      throw new \yii\web\BadRequestHttpException("Access error");
    }

    $query = BalanceModel::find()->indexBy('id');

    $balanceProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 50,
      ],
    ]);

    return $this->render('admin',['balanceProvider' => $balanceProvider]);
  }

  public function actionIndex(){
    $user = \yii::$app->user->getIdentity();

    $query = BalanceModel::find()
              ->where(['uid' => intval($user->getId())])
              ->indexBy('id');

    $balanceProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 50,
      ],
    ]);

    return $this->render('admin',['balanceProvider' => $balanceProvider]);
  }

}
