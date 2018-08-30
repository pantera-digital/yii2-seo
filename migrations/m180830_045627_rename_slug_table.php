<?php

use yii\db\Migration;

/**
 * Class m180830_045627_rename_slug_table
 */
class m180830_045627_rename_slug_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('{{%slug}}', '{{%seo_slug}}');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180828_001701_update_table_name cannot be reverted.\n";
        $this->renameTable('{{%seo_slug}}', 'slug');
        return true;
    }
}
