<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_165850_add_order extends Migration {
  
  public function up() {
    $sql = <<<SQL
      CREATE TABLE IF NOT EXISTS Orders (
        id        INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        uid       INT NOT NULL,
        status    TINYINT(1) DEFAULT 0,

        maker_id      INT NOT NULL,
        provider      SMALLINT NOT NULL,
        shiping       SMALLINT DEFAULT 0,
        is_original   TINYINT(1) DEFAULT 0,
        lot_party     SMALLINT DEFAULT 1,
        basket_count  SMALLINT DEFAULT 1,

        part_id   VARCHAR(150) DEFAULT '',
        count     VARCHAR(50) DEFAULT '1',
        articul   VARCHAR(50) DEFAULT '',
        producer  VARCHAR(50) DEFAULT '',
        name      VARCHAR(100) DEFAULT '',
        stock     VARCHAR(100) DEFAULT '',       

        price     FLOAT(8,2) NOT NULL,
        INDEX(uid),
        INDEX(status),
        INDEX(uid,status),
        CONSTRAINT FOREIGN KEY (`uid`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    )AUTO_INCREMENT = 0,CHARACTER SET = utf8,COLLATE = utf8_general_ci;
SQL;
    $this->db->createCommand($sql)->execute();
  }

  public function down() {
    $command = $this->db->createCommand();
    $command->dropTable('Order');      
    return $command->execute();
  }
    
}
