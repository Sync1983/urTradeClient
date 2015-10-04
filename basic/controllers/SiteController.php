<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller {
    
  public function behaviors(){
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['logout'],
        'rules' => [
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['post'],
        ],
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

  public function actionIndex() {
    return $this->render('index');
  }

  public function actionLogin()
  {
      if (!\Yii::$app->user->isGuest) {
          return $this->goHome();
      }

      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
          \yii::error(\yii::$app->getUser()->getReturnUrl());
          return $this->goBack();
      }
      return $this->render('login', [
          'model' => $model,
      ]);
  }

  public function actionLogout() {
    Yii::$app->user->logout();
    return $this->goHome();
  }

  public function actionContact() {      
    return $this->render('contact');
  }
  
  public function actionSearch() {      
    $model = new \app\models\SearchForm();
    return $this->render('search',['model'=>$model]);
  }
    
}
