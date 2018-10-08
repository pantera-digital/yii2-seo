<?php

use pantera\seo\models\Seo;
use pantera\seo\models\SeoPresets;
use yii\db\Migration;

/**
 * Class m181008_052306_add_support_unicode
 */
class m181008_052306_add_support_unicode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE ' . Seo::tableName() . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;');
        $this->execute('ALTER TABLE ' . SeoPresets::tableName() . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181008_052306_add_support_unicode cannot be reverted.\n";
        $this->execute('ALTER TABLE ' . Seo::tableName() . ' CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;');
        $this->execute('ALTER TABLE ' . SeoPresets::tableName() . ' CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;');
        return true;
    }
}
