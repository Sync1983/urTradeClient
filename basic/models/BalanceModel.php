<?php

/**
 * Description of OrderPartModel
 * @author Sync<atc58.ru>
 */
namespace app\models;
use yii\db\ActiveRecord;

class BalanceModel extends ActiveRecord {
  //public vars
  //protected vars
  //private vars  
  //============================= Public =======================================
  public static function getBalance($uid){
    $user = WebUser::findOne(['id'=>$uid]);
    if( !$user ){
      return ['full' => 0, 'credit' => 0];
    }
    $credit = $user->getAttribute('credit') * 1;
    return ['full' => self::find()
                        -> where(['uid'=>$uid])
                        -> sum('value')
                        + $credit,
            'credit' => $credit
    ];
  }
  
  public function getUserBalance(){
    return self::getBalance( intval($this->getAttribute('uid')) );
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function beforeSave($insert) {
    $this->setAttribute('time', time());
    return parent::beforeSave($insert);
  }

  public function attributes(){        
    return [
      'id',
      'uid',
      'time',
      'value',
      'comment'
    ];
  }
  
  public function attributeLabels(){
    return [
      'time'   => 'Время операции',
      'value'   => 'Сумма',
      'comment' => 'Описание'
    ];
  }

  public function attributeHints() {
    return [
      'value' => 'Положительное значение увеличивает общий баланс пользователя, отрицательное - уменьшает',
      'comment' => 'Комментарий будет виден пользователю. Рекомендуется указывать причину изменения баланса.'
    ];
  }
  
  public function rules(){    
    return [
      [['id','uid','time'],'integer','min'=>0],
      [['value'],'integer'],
      [['comment'], 'string']
    ];
  }
  
  public static function tableName(){
    return 'Balance';
  }
  
}
