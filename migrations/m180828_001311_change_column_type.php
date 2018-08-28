<?php

use yii\db\Migration;

/**
 * Class m180828_001311_change_column_type
 */
class m180828_001311_change_column_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%seo_presets}}', 'meta_title', $this->text()->null());
        $this->alterColumn('{{%seo_presets}}', 'seo_h1', $this->text()->null());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180828_001311_change_column_type cannot be reverted.\n";
        $this->alterColumn('{{%seo_presets}}', 'meta_title', $this->string()->null());
        $this->alterColumn('{{%seo_presets}}', 'seo_h1', $this->string()->null());
        return true;
    }
}
