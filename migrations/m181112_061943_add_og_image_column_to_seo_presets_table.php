<?php

use yii\db\Migration;

/**
 * Handles adding og_image to table `seo_presets`.
 */
class m181112_061943_add_og_image_column_to_seo_presets_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('seo_presets', 'og_image', $this->string()->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('seo_presets', 'og_image');
    }
}
