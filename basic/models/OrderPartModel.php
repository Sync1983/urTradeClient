<?php

/**
 * Description of OrderPartModel
 * @author Sync<atc58.ru>
 */
namespace app\models;
use app\models\BasketPartModel;

class OrderPartModel extends BasketPartModel {
  const OS_WAIT     = 0;
  const OS_IN_ORDER = 1;
  const OS_IN_STOCK = 2;
  const OS_ISSUED   = 3;
  const OS_RESTRICT = 4;  
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
      self::OS_RESTRICT => 'Отказ'
    ];
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function attributes(){    
    $attr = parent::attributes();
    return array_merge( $attr ,[
      'status'
    ]);
  }
  
  public function attributeLabels(){
    $labels = parent::attributeLabels();
    $labels['basket_count'] = 'Кол-во';
    return array_merge($labels,[
      'status' => 'Статус'
    ]);    
  }
  
  public function rules(){    
    return array_merge(parent::rules(),[
      ['status','in','range'=> self::getStatusIDs()]
    ]);
  }
  
  public static function tableName(){
    return 'Orders';
  }
  
}
