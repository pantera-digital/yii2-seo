<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo_presets`.
 */
class m180824_045922_create_seo_presets_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('seo_presets', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'comment' => $this->text()->null(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'meta_title' => $this->string()->null(),
            'meta_description' => $this->text()->null(),
            'seo_h1' => $this->string()->null(),
            'seo_text' => $this->text()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('seo_presets');
    }
}
