<?php

use pantera\seo\models\SeoSlug;
use yii\db\Migration;

/**
 * Handles the creation of table `index_for_slug`.
 */
class m180904_060731_create_index_for_slug_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createIndex('modelAndModel_id', SeoSlug::tableName(), [
            'model',
            'model_id',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('modelAndModel_id', SeoSlug::tableName());
    }
}
