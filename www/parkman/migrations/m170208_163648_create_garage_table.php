<?php

use yii\db\Migration;

/**
 * Handles the creation for table `garage`.
 */
class m170208_163648_create_garage_table extends Migration
{
    public function up()
    {
        $this->execute("
        CREATE TABLE `garages` (
          `id` mediumint(8) unsigned NOT NULL auto_increment,
          `owner` TEXT default NULL,
          `hourly` mediumint default NULL,
          `currency` varchar(255),
          `email` varchar(255) default NULL,
          `country` varchar(100) default NULL,
          `lat` DECIMAL(10, 8) NOT NULL,
          `lng` DECIMAL(11, 8) NOT NULL,
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=1;

            ");
    }

    public function down()
    {
        $this->dropTable('garages');
    }
}
