<?php

use yii\db\Migration;

/**
 * Handles adding url to table `seo`.
 */
class m180821_053047_add_url_column_to_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('seo', 'url', $this->string()->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('seo', 'url');
    }
}
