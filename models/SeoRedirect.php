<?php

namespace pantera\seo\models;

/**
 * This is the model class for table "seo_redirect".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property int $code
 */
class SeoRedirect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_redirect';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            [['code'], 'default', 'value' => 301],
            [['code'], 'integer'],
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
            'code' => 'Code',
        ];
    }
}
