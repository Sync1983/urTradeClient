<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_105939_add_was_placed extends Migration{

  public function up(){
$sql = <<<SQL
    ALTER TABLE Orders ADD COLUMN place TINYINT(1) DEFAULT 0 AFTER `pay`;
SQL;
   return $this->db->createCommand($sql)->execute();
  }

  public function down() {
$sql = <<<SQL
    ALTER TABLE Orders DROP COLUMN place;
SQL;
   return $this->db->createCommand($sql)->execute();

  }
}
