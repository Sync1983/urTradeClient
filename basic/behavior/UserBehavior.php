<?php

/**
 * Description of AdminBehavior
 * @author Sync<atc58.ru>
 */
namespace app\behavior;
use yii\base\Behavior;

class UserBehavior extends Behavior{

  //public vars
  //protected vars
  //private vars  
  //============================= Public =======================================
  public function events(){
    return [\yii\web\Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
  }
  /**
   * @param \yii\base\ActionEvent $event
   */
  public function beforeAction($event){
    if( !\yii::$app->user->isGuest ){
      return $event->isValid;
    }
    
    throw new \yii\web\BadRequestHttpException('Доступ запрещен',500);    
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================

}
