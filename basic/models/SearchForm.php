<?php

/**
 * Description of SearchForm
 * @author Sync<atc58.ru>
 */

namespace app\models;
use yii\base\Model;

class SearchForm extends Model{
  //public vars
  public $articul;
  public $analog;
  //protected vars
  //private vars  
  //============================= Public =======================================
  //put your code here
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function attributes(){
    return ['articul','analog'];
  }
  
  public function rules(){
    return [
      ['articul','string'],
      ['analog','boolean']
    ];
  }
  
  public function attributeLabels(){
    return [
      'articul' => 'Артикул',
      'analog' => 'Аналоги',
    ];
  }

}
