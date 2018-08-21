<?php

use yii\db\Migration;

/**
 * Handles dropping redirect_301 from table `seo`.
 */
class m180821_062806_drop_redirect_301_column_from_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('seo', 'redirect_301');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('seo', 'redirect_301', $this->string()->null());
    }
}
