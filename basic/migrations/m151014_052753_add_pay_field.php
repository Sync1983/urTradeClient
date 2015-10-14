<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_052753_add_pay_field extends Migration{

  public function up(){
$sql = <<<SQL
    ALTER TABLE Orders ADD COLUMN pay TINYINT(1) DEFAULT 0 AFTER `status`;
SQL;
   return $this->db->createCommand($sql)->execute();
  }

  public function down() {
$sql = <<<SQL
    ALTER TABLE Orders DROP COLUMN pay;
SQL;
   return $this->db->createCommand($sql)->execute();

  }

}
