<?php

use yii\db\Schema;
use yii\db\Migration;

class m151013_092717_balance_table extends Migration {

  public function up(){
$sql = <<<SQL
  CREATE TABLE IF NOT EXISTS Balance(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    uid       INT NOT NULL,
    time      INT DEFAULT 0,
    value     INT DEFAULT 0,
    comment   VARCHAR(250) DEFAULT '',

    INDEX(uid),
    CONSTRAINT FOREIGN KEY (`uid`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
  )AUTO_INCREMENT = 0,CHARACTER SET = utf8,COLLATE = utf8_general_ci;
SQL;
    $this->db->createCommand($sql)->execute();
  }

  public function down(){
    $command = $this->db->createCommand();
    $command->dropTable('Balance');
    return $command->execute();
  }
}
