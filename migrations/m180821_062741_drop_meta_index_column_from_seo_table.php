<?php

use yii\db\Migration;

/**
 * Handles dropping meta_index from table `seo`.
 */
class m180821_062741_drop_meta_index_column_from_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('seo', 'meta_index');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('seo', 'meta_index', $this->string()->null());
    }
}
