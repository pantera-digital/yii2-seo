<?php

namespace pantera\seo\models;

/**
 * This is the ActiveQuery class for [[SeoPresets]].
 *
 * @see SeoPresets
 */
class SeoPresetsQuery extends \yii\db\ActiveQuery
{
    /**
     * Только активные
     * @return self
     */
    public function isActive(): self
    {
        return $this->andWhere(['=', SeoPresets::tableName() . '.status', SeoPresets::STATUS_ACTIVE]);
    }
}
