<?php

/**
 * Description of AdminBehavior
 * @author Sync<atc58.ru>
 */
namespace app\behavior;
use yii\base\Behavior;

class AdminBehavior extends Behavior{

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
    /* @var $identity \app\models\WebUser */
    $identity = \yii::$app->user->getIdentity();
    
    if( $identity && $identity->isAdmin() ){
      return $event->isValid;
    }
    
    throw new \yii\web\BadRequestHttpException('Доступ запрещен',500);
    
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================

}
