<?php

namespace pantera\seo\models;

/**
 * @property int $id
 * @property string $url
 * @property string $referrer
 * @property string $ip
 * @property string $created_at
 */
class SeoNotFound extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_not_found}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'ip'], 'required'],
            [['url', 'referrer'], 'string'],
            [['created_at'], 'safe'],
            [['ip'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'referrer' => 'Referrer',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }
}
