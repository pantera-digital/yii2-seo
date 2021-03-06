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
 * @property string $og_image
 */
class Seo extends \yii\db\ActiveRecord
{
    const SCENARIO_URL = 'url';
    /**
     * @var null Сюда подставляем название модели от которой зависит Seo модель
     * это нужно для формирования правильного название поля в форме
     */
    public $relationFormName = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo}}';
    }

    public function formName()
    {
        return $this->relationFormName ? $this->relationFormName . '[Seo]' : 'Seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string', 'max' => 255, 'on' => self::SCENARIO_URL],
            [['url'], 'trim', 'on' => self::SCENARIO_URL],
            [['url'], 'required', 'on' => self::SCENARIO_URL],
            [['item_id'], 'integer'],
            [['modelName'], 'string', 'max' => 150, 'on' => self::SCENARIO_DEFAULT],
            [['item_id', 'modelName'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['text'], 'string'],
            [['h1', 'title', 'og_image'], 'string', 'max' => 255],
            [['keywords'], 'string'],
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
            'og_image' => 'OG Image',
        ];
    }
}
