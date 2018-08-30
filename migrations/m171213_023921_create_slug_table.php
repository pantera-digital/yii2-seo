<?php

use yii\db\Migration;

/**
 * Handles the creation of table `slug`.
 */
class m171213_023921_create_slug_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema('{{%slug}}', true) === null) {
            $this->createTable('{{%slug}}', [
                'id' => $this->primaryKey()->unsigned(),
                'slug' => $this->string()->notNull(),
                'model' => $this->string()->notNull(),
                'model_id' => $this->integer()->notNull(),
            ]);
            $this->createIndex('slug-slug', '{{%slug}}', 'slug', true);
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        if ($this->db->schema->getTableSchema('{{%slug}}', true)) {
            $this->dropTable('{{%slug}}');
        }
    }
}
