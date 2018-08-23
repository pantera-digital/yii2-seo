<?php

namespace pantera\seo\models;

/**
 * This is the model class for table "seo_fields".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $modelName
 * @property string $h1
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $text
 * @property string $url
 */
class Seo extends \yii\db\ActiveRecord
{
    const SCENARIO_URL = 'url';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string', 'max' => 255, 'on' => self::SCENARIO_URL],
            ['url', 'trim'],
            [['url'], 'required', 'on' => self::SCENARIO_URL],
            [['item_id'], 'integer'],
            [['modelName'], 'string', 'max' => 150, 'on' => self::SCENARIO_DEFAULT],
            [['item_id', 'modelName'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['text'], 'string'],
            [['h1', 'title', 'keywords'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 522]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'modelName' => 'Model Name',
            'h1' => 'H1',
            'title' => 'Seo Title',
            'keywords' => 'Seo Keywords',
            'description' => 'Seo Description',
            'text' => 'Seo Text',
        ];
    }
}
