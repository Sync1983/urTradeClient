<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\WebUser;

class m151004_193158_init_admin extends Migration
{
    public function up()
    {
      $user = new WebUser();
      $user->setAttributes([
        'uname'     => 'admin',
        'upass'     => password_hash('test', PASSWORD_BCRYPT),
        'is_admin'  => true,
        'markup'    => 0,
        'name'      => 'Administrator',
        'phone'     => '02',
        'mail'      => 'mail@email.ru'
      ]);
      $user->save();
    }

    public function down()
    {
        $user = WebUser::findOne(['uname'=>'admin']);
        if( $user ) {
          $user->delete();          
        }

        return true;
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
