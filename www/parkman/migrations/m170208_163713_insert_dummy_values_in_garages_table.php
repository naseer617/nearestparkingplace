<?php

use yii\db\Migration;

class m170208_163713_insert_dummy_values_in_garages_table extends Migration
{
    public function up()
    {
        $this->execute(file_get_contents(__DIR__ . '/dummy_garages_1.sql', FILE_USE_INCLUDE_PATH));
        $this->execute(file_get_contents(__DIR__ . '/dummy_garages_2.sql', FILE_USE_INCLUDE_PATH));
        $this->execute(file_get_contents(__DIR__ . '/dummy_garages_3.sql', FILE_USE_INCLUDE_PATH));
    }

    public function down()
    {
        $this->truncateTable('garages');
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
