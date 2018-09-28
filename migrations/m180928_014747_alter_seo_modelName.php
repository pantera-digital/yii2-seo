<?php

use yii\db\Migration;

/**
 * Class m180928_014747_alter_seo_modelName
 */
class m180928_014747_alter_seo_modelName extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%seo}}', 'modelName', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180928_014747_alter_seo_modelName cannot be reverted.\n";
        $this->alterColumn('{{%seo}}', 'modelName', $this->string()->notNull());
        return true;
    }
}
