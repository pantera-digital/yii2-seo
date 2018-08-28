<?php

use yii\db\Migration;

/**
 * Class m180828_001701_update_table_name
 */
class m180828_001701_update_table_name extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('seo_replacement', 'seo_replacement_back');
        $this->renameTable('seo_replacement_back', '{{%seo_replacement}}');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180828_001701_update_table_name cannot be reverted.\n";
        $this->renameTable('{{%seo_replacement}}', 'seo_replacement_back');
        $this->renameTable('seo_replacement_back', 'seo_replacement');
        return true;
    }
}
