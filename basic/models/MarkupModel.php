<?php

/**
 * Description of OrderPartModel
 * @author Sync<atc58.ru>
 */
namespace app\models;
use yii\db\ActiveRecord;

class MarkupModel extends ActiveRecord {
  //public vars
  //protected vars
  //private vars  
  //============================= Public =======================================
  public static function getList(){
    $uid = \yii::$app->user->getId();    
    $items = MarkupModel::find()->where(['uid' => $uid])->orderBy('value')->asArray()->all();
    $list = [];
    foreach($items as $item){
      $list[] = ['id'=>$item['value'],'text'=>$item['name']];
    }
    return $list;
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function beforeSave($insert) {
    if (parent::beforeSave($insert)){
      $this->setAttribute('uid', \yii::$app->user->getId());
      return true;
    }
    return false;
  }

  public function attributes(){        
    return [
      'id',
      'uid',
      'name',
      'value'      
    ];
  }
  
  public function rules(){    
    return [
      [['id','uid'],'integer','min'=>0],
      [['value'],'number','min'=>0,'max'=>1000],
      [['name'], 'string', 'max'=>10]
    ];
  }
  
  public static function tableName(){
    return 'Markup';
  }
  
}
