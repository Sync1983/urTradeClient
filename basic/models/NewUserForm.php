<?php

namespace app\models;

use yii\base\Model;

class NewUserForm extends Model {
  
  public $uname;
  public $upass;
  public $is_admin;
  public $markup;
  public $name;
  public $phone;
  public $mail;
  
  public function rules() {    
    return [      
      ['uname',   'string', 'max'=>50],
      ['upass',   'string', 'max'=>72],
      ['is_admin','boolean' ],
      ['markup',  'integer','min'=>0, 'max'=>999],
      ['name',    'string', 'max'=>250],
      ['phone',   'string', 'max'=>11],             
      ['mail',    'string', 'max'=>250],
      ['mail',    'email'],
      [['uname','upass','is_admin','markup','name','phone','mail'],'safe'],
      [['uname','upass','is_admin','markup','name','phone','mail'],'required'],
    ];
  }

  public function attributeLabels(){
    return [
      'uname'   => 'Логин пользователя',
      'upass'   => 'Пароль',
      'is_admin'=> 'Администратор?',
      'markup'  => 'Наценка (%)',
      'name'    => 'Имя\название',
      'phone'   => 'Телефон',
      'mail'    => 'Email'
    ];
  }

}
