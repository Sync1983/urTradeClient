<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_072103_change_balance_value_type extends Migration{

  public function up(){
$sql = <<<SQL
    ALTER TABLE Balance MODIFY COLUMN value FLOAT DEFAULT 0;
SQL;
   return $this->db->createCommand($sql)->execute();
  }

  public function down() {
$sql = <<<SQL
    ALTER TABLE Balance MODIFY COLUMN value INT DEFAULT 0;
SQL;
   return $this->db->createCommand($sql)->execute();

  }
}
