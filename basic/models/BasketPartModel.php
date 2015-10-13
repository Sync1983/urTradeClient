<?php

/**
 * Description of BasketPartModel
 * @author Sync<atc58.ru>
 */

namespace app\models;
use yii\db\ActiveRecord;

class BasketPartModel extends ActiveRecord {

  //public vars  
  //protected vars
  //private vars  
  //============================= Public =======================================
  //put your code here
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function attributes(){
    return[
      'id',
      'uid',
      'part_id',
      'provider',

      'count',
      'articul',
      'producer',
      'maker_id',
      'name',
      'price',
      'shiping',

      'stock',      
      'is_original',
      'lot_party',
      'basket_count' 
    ];
  }
  
  public function attributeLabels(){
    return[      
      'producer'  => 'Производитель',
      'articul'   => 'Артикул',
      'name'      => 'Название',
      'count'     => 'На складе',      
      'price'     => 'Цена',
      'shiping'   => 'Доставка',
      'basket_count' => 'В корзине' 
    ];
  }

  public function rules(){
    return[
      [ ['uid', 'provider', 'maker_id','shiping'],'integer' ],
      [ ['count','articul','producer'], 'string', 'max' => 50],
      [ ['name', 'stock' ], 'string', 'max' => 100],
      [ ['part_id'], 'string', 'max' => 150],
      [ ['price'], 'number'],
      [ ['is_original'],'validateOriginal'],
      [ ['is_original'],'boolean'],
      [ ['lot_party','basket_count'], 'integer'],
      [ ['part_id','uid', 'provider', 'maker_id','shiping','count','articul','producer','name', 'stock','price','is_original','lot_party','basket_count'],'safe'],
      [ ['basket_count'], 'validateCount']
    ];
  }
  
  public function validateOriginal($attr,$param){
    $this->is_original = intval($this->is_original);
    return true;
  }
  
  public function validateCount($attr,$param){
    $max = intval($this->count);
    $lot = intval($this->lot_party);
    if (!$max){
      $max = 10 * $lot;
    }
    
    if( ( $this->basket_count < 1) ||
        ( $this->basket_count > $max) ){
      $this->addError('basket_count','Неправильное значение количества');
      return false;
    }
    
    if ( ($this->basket_count % $lot) !== 0 ){
      $this->addError('basket_count','Количество должно быть кратно партии');
      return false;      
    }
    
    return true;
  }
  
  public static function tableName(){
    return 'Basket';
  }

}
