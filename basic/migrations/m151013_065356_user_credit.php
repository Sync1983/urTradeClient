<?php

use yii\db\Schema;
use yii\db\Migration;

class m151013_065356_user_credit extends Migration {

  public function up(){
$sql = <<<SQL
    ALTER TABLE User ADD COLUMN credit INT DEFAULT 0 AFTER `markup`;
SQL;
   return $this->db->createCommand($sql)->execute();
  }

  public function down() {
$sql = <<<SQL
    ALTER TABLE User DROP COLUMN credit;
SQL;
   return $this->db->createCommand($sql)->execute();
        
  }
    
}
