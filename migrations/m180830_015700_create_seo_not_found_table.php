<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo_not_found`.
 */
class m180830_015700_create_seo_not_found_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%seo_not_found}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'referrer' => $this->string()->null(),
            'ip' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%seo_not_found}}');
    }
}
