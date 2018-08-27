<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo_replacement`.
 */
class m180827_054028_create_seo_replacement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('seo_replacement', [
            'id' => $this->primaryKey(),
            'from' => $this->string()->notNull(),
            'to' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('seo_replacement');
    }
}
