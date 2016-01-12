<?php

use yii\db\Schema;
use yii\db\Migration;

class m160112_083331_tbl_markup extends Migration {

  public function up() {
    $sql = <<<SQL
      CREATE TABLE IF NOT EXISTS Markup(
        id        INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        uid       INT NOT NULL,
        name      VARCHAR(10) DEFAULT 'name',
        value     SMALLINT DEFAULT 0,
        CONSTRAINT FOREIGN KEY (`uid`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    )AUTO_INCREMENT = 0,CHARACTER SET = utf8,COLLATE = utf8_general_ci;
SQL;
    $this->db->createCommand($sql)->execute();
  }

  public function down() {
    $command = $this->db->createCommand();
    $command->dropTable('Markup');
    return $command->execute();
  }
}
