<?php

use yii\db\Schema;
use yii\db\Migration;

class m151004_183922_init_db extends Migration
{
    public function up(){
$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS User (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      uname VARCHAR(50) NOT NULL DEFAULT 'user',
      upass VARCHAR(72) NOT NULL DEFAULT '',
      is_admin TINYINT(1) DEFAULT 0,
      markup TINYINT(3) DEFAULT 0,
      name VARCHAR(250) DEFAULT 'user-name',
      phone VARCHAR(11) DEFAULT 0,
      mail  VARCHAR(250) DEFAULT ''
    )AUTO_INCREMENT = 0,CHARACTER SET = utf8,COLLATE = utf8_general_ci;
SQL;
      $this->db->createCommand($sql)->execute();
    }

    public function down(){
      $command = $this->db->createCommand();
      $command->dropTable('User');      
      return $command->execute();
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
