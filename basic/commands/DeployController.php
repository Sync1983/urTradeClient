<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

class DeployController extends Controller {

  public $session;
  public $params;
      
  public function actionIndex() {
    $this->actionDeploy();      
  }
    
  public function actionDeploy(){
    $this->actionPullUpdate();
    $this->actionDeployIndexSSH();
  }
  
  public function actionPullUpdate(){
    $this->execSSH([
      'cd ~/;',
      'pwd',
      'cd ~/urTradeClient; git pull',
      'cd ~/urTradeClient/basic; php composer.phar update'
      ]);
  }
  
  public function actionDeployIndexSSH(){
    $this->execSSH([
      'cd ~/urTradeClient/basic; ./yii deploy/deploy-index'
      ]);
    
  }
  
  public function actionDeployIndex(){
    $f = fopen(__DIR__ . "../web/index.php", "w");
    $idex_php = "<?php"
        . "require(__DIR__ . '/../vendor/autoload.php');"
        . "require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');"
        . '$config' . " = require(__DIR__ . '/../config/web.php');"
        . "(new yii\web\Application($config))->run();";
    fputs($f, $idex_php, count($idex_php));
  }

  public function init(){
    $this->params = \yii::$app->params;
    $this->initSSH();
    return parent::init();
  }

  protected function initSSH(){
    $this->session = ssh2_connect($this->params['deploy_server'],$this->params['deploy_port']);
    if( !$this->session ){
      throw new Exception("SSH Connection error");
    }
    if( !ssh2_auth_password($this->session,$this->params['deploy_user'],$this->params['deploy_pass']) ){
      throw new Exception("SSH Auth error");      
    }
    
    echo ssh2_fingerprint($this->session) . "\r\n";
  }
  
  protected function execSSH($command){
    //Если передали строку, то обернем её в массив для унификации
    if( !is_array($command) ){
      $command = [$command];
    }
    
    foreach($command as $cmd){
      echo "Execute cmd: $cmd ";
      $stream = ssh2_exec($this->session, $cmd);
      $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
      
      stream_set_blocking($errorStream, true);
      stream_set_blocking($stream, true);

      $output = stream_get_contents($stream);      
      $error  = stream_get_contents($errorStream);
      
      if( $stream && !$error ){
        echo "[OK]\r\n";
        if( $output ){
          echo $output . "\r\n";          
        }
      } else {
        echo "[FAIL]\r\n";        
        echo $error. "\r\n";
      }
      
      fclose($errorStream);
      fclose($stream);      
    }
    
  }

}
