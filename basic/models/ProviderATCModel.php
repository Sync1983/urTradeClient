<?php

/**
 * Description of ProviderATCModel
 * @author Sync<atc58.ru>
 */

namespace app\models;
use yii\base\Object;

class ProviderATCModel extends Object{

  //public vars
  //protected vars
  //private vars  
  
  //============================= Public =======================================
  public function getMakers($part_id, $analog){
    $makers = $this->request('rest/makers',['part_id' => $part_id, 'analog' => $analog ]);
    
    return $makers;
  }
  
  public function getParts($part_id, $analog, $maker){
    $parts = $this->request('rest/parts',['part_id' => $part_id, 'analog' => $analog, 'maker'=>$maker ]);
    if( !is_array($parts) ){
      \yii::trace($parts);
      return [];
    }
    
    $result = [];
    foreach( $parts as $part){
      $part_model = new PartModel();
      $part_model->setAttributes($part,false);
      $result[]  = $part_model;      
    }
    
    return $result;
  }
  
  //============================= Protected ====================================
  protected function request($action,$get){
    $user = 'diman.tarasov';
    $pass = '2e073bf51668b37819a6d95dd18c3e8e';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://atc58.ru/index.php?r=$action");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($get));
    $site_answer = curl_exec($ch);    
    curl_close($ch);
    
    $decode = json_decode($site_answer,true);
    
    return $decode;
  }
  //============================= Private ======================================
  //============================= Constructor - Destructor =====================

}
