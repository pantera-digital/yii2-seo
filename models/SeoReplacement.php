<?php

namespace pantera\seo\models;

use Yii;

/**
 * This is the model class for table "seo_replacement".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 */
class SeoReplacement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_replacement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            [['from', 'to'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
        ];
    }
}
