<?php

use yii\db\Migration;

/**
 * Class m200825_114211_alter_keywords_column_to_text_in_seo_tbl
 */
class m200825_114211_alter_keywords_column_to_text_in_seo_tbl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%seo}}', 'keywords', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%seo}}', 'keywords', $this->string(255));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200825_114211_alter_keywords_column_to_text_in_seo_tbl cannot be reverted.\n";

        return false;
    }
    */
}
