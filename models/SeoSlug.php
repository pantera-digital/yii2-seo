<?php

namespace pantera\seo\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "slug".
 *
 * @property integer $id
 * @property string $slug
 * @property string $model
 * @property integer $model_id
 */
class SeoSlug extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_slug}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug', 'model', 'model_id'], 'required'],
            [['model_id'], 'integer'],
            [['slug', 'model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'model' => 'Model',
            'model_id' => 'Model ID',
        ];
    }
}
