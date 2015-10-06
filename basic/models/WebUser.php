<?php

/**
 * Description of WebUser
 * @author Sync<atc58.ru>
 */

namespace app\models;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class WebUser extends ActiveRecord implements IdentityInterface{

  //public vars
  public $authKey = "pub_client";
  //protected vars
  //private vars  
  //
  //============================= Public =======================================
  /*
   * Проверяет, является ли пользователь администратором 
   */
  public function isAdmin(){    
    return boolval( $this->getAttribute('is_admin') === 1 );
  }
  //============================= Protected ====================================
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================
  public function attributes(){
    return [
      'id',
      'uname',
      'upass',
      'is_admin',
      'markup',
      'name',
      'phone',
      'mail'
    ];
  }
  
  public function rules(){
    return [
      ['id',      'integer' ],
      ['uname',   'string', 'max'=>50],
      ['upass',   'string', 'max'=>72],
      ['is_admin','boolean' ],
      ['markup',  'integer','min'=>0, 'max'=>999],
      ['name',    'string', 'max'=>250],
      ['phone',   'string', 'max'=>11],             
      ['mail',    'string', 'max'=>250],
      [['uname','upass','is_admin','markup','name','phone','mail'],'safe'],
    ];
  }
  
  public function getAuthKey(){
    return $this->authKey;
  }

  public function getId(){
    return $this->getAttribute('id');
  }

  public function validateAuthKey($authKey){    
    return $this->authKey===$authKey;
  }
  
  public static function tableName(){
    return 'User';
  }

  public static function findIdentity($id){
    return self::findOne(['id'=>  intval($id)]);
  }

  public static function findIdentityByAccessToken($token, $type = null){
    return false;
  }

}
