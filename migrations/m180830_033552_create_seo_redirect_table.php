<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo_redirect`.
 */
class m180830_033552_create_seo_redirect_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%seo_redirect}}', [
            'id' => $this->primaryKey(),
            'from' => $this->string()->notNull(),
            'to' => $this->string()->notNull(),
            'code' => $this->integer(3)->notNull()->defaultValue(301),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%seo_redirect}}');
    }
}
