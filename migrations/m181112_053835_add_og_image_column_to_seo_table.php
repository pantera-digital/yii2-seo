<?php

use yii\db\Migration;

/**
 * Handles adding og_image to table `seo`.
 */
class m181112_053835_add_og_image_column_to_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('seo', 'og_image', $this->string()->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('seo', 'og_image');
    }
}
