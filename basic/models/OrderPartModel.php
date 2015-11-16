<?php

/**
 * Description of OrderPartModel
 * @author Sync<atc58.ru>
 */
namespace app\models;
use app\models\BasketPartModel;
use app\models\BalanceModel;

class OrderPartModel extends BasketPartModel {
  /*
   * Индексы должны идти последовательно с действиями
   * Все действия с индексом меньше текущего отображаться не будут,
   * либо должны быть специально обработаны в getAvaibleStatus
   */
  const OS_WAIT     = 0;
  const OS_IN_ORDER = 1;
  const OS_REJECT   = 2;
  const OS_IN_STOCK = 3;
  const OS_ISSUED   = 4;
  //public vars
  //protected vars
  //private vars  
  //============================= Public =======================================
  /**
   * Возвращает массив статусов заказа
   * @return array Список идентификаторов статуса
   */
  public static function getStatusIDs(){
    return array_keys(self::getStatus());
  }
  /**
   * Возвращает список возможных статусов заказа
   * @return array Массив статусов
   */
  public static function getStatus(){
    return [
      self::OS_WAIT     => 'Ожидает заказа',
      self::OS_IN_ORDER => 'В заказе',
      self::OS_IN_STOCK => 'На складе',
      self::OS_ISSUED   => 'Выдано',
      self::OS_REJECT   => 'Отказ'
    ];
  }
  /**
   * Возвращает текущий статус детали
   * @return string Текущий статус детали
   */
  public function getCurrentStatus(){
    $status = self::getStatus();
    return isset($status[$this->status])?$status[$this->status]:"Неопределено";
  }
  /**
   * Возвращает массив доступных статусов
   */
  public function getAvaibleStatus(){
    $status = self::getStatus();
    //Если деталь уже оплачена, то оставляем только 2 варианта
    if( $this->pay ){
      return [
        self::OS_IN_STOCK => $status[self::OS_IN_STOCK],
        self::OS_ISSUED   => $status[self::OS_ISSUED],
      ];
    }
    //Убираем из статусов все с индексом меньше, чем текущий
    foreach ($status as $key=>$value){
      if( $key < $this->status ){
        unset($status[$key]);
      }
    }
    return $status;
  }
  /**
   * Изменяет статус детали
   * @param integer $new_status Новый статус
   */
  public function changeStatus($new_status){
    $status_keys = array_keys( $this->getAvaibleStatus() );
    if( !in_array($new_status, $status_keys) ){
      return false;
    }

    $this->status = intval($new_status);
    if( !$this->save() ){
      return false;
    }

    if ( $new_status == self::OS_IN_STOCK ){
      
      $user = WebUser::findOne(['id'=>$this->uid]);
      if( !$user ){
        return false;
      }

      $markup  = floatval($user->getAttribute('markup'));
      $comment = "Оплата за деталь [" . $this->articul . " - " .  $this->producer ."] количеством " . $this->basket_count . "шт.";
      $price = $this->price * $this->basket_count;
      $price *= 1 + $markup/100;
      $price_value = round($price,2);

      $balance = new BalanceModel();
      $balance->setAttribute('uid', $this->uid);
      $balance->setAttribute('comment', $comment);
      $balance->setAttribute('value', -$price_value);

      $this->pay = 1;

      if( $balance->save() && $this->save() ){
        return true;
      } else {
        \yii::trace($balance->getFirstErrors());
        \yii::trace($this->getFirstErrors());
      }

      return false;
      
    }

    return true;
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function attributes(){    
    $attr = parent::attributes();
    return array_merge( $attr ,[
      'status',
      'pay',
      'place'
    ]);
  }
  
  public function attributeLabels(){
    $labels = parent::attributeLabels();
    $labels['basket_count'] = 'Кол-во';
    return array_merge($labels,[
      'status'  => 'Статус',
      'pay'     => 'Оплачено',
      'place'   => 'Размещен'
    ]);    
  }
  
  public function rules(){    
    return array_merge(parent::rules(),[
      ['status','in','range'=> self::getStatusIDs()],
      [['pay','place'],'boolean']
    ]);
  }
  
  public static function tableName(){
    return 'Orders';
  }
  
}
