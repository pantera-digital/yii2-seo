<?php

namespace pantera\seo\models;

use pantera\seo\Module;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "seo_presets".
 *
 * @property int $id
 * @property string $key
 * @property string $comment
 * @property int $status
 * @property string $meta_title
 * @property string $meta_description
 * @property string $seo_h1
 * @property string $seo_text
 * @property string $og_image
 */
class SeoPresets extends \yii\db\ActiveRecord
{
    /* @var int Активный статус */
    const STATUS_ACTIVE = 1;
    /* @var int Не активный статус */
    const STATUS_NOT_ACTIVE = 0;

    public static function apply($key, $params = [], $default = [])
    {
        /* @var $model self */
        $model = self::find()
            ->isActive()
            ->andWhere(['=', self::tableName() . '.key', $key])
            ->one();
        $title = ArrayHelper::getValue($default, 'title', null);
        $description = ArrayHelper::getValue($default, 'description', null);
        $h1 = ArrayHelper::getValue($default, 'h1', null);
        $text = ArrayHelper::getValue($default, 'text', null);
        $ogImage = ArrayHelper::getValue($default, 'ogImage', null);
        $title = $model && $model->meta_title ? Module::twigCompile($model->meta_title, $params) : $title;
        Yii::$app->seo->setTitle($title);
        if ($model && $model->meta_description) {
            $description = Module::twigCompile($model->meta_description, $params);
        }
        Yii::$app->seo->setDescription($description);
        $h1 = $model && $model->seo_h1 ? Module::twigCompile($model->seo_h1, $params) : $h1;
        Yii::$app->seo->setH1($h1);
        $text = $model && $model->seo_text ? Module::twigCompile($model->seo_text, $params) : $text;
        Yii::$app->seo->setText($text);
        $ogImage = $model && $model->og_image ? $model->og_image : $ogImage;
        Yii::$app->seo->setOgImage($ogImage);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_presets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['status'], 'default', 'value' => 1],
            [['comment', 'meta_description', 'seo_text', 'meta_title', 'seo_h1'], 'string'],
            [['status'], 'integer'],
            [['key', 'og_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'comment' => 'Comment',
            'status' => 'Status',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'seo_h1' => 'Seo H1',
            'seo_text' => 'Seo Text',
            'og_image' => 'OG Image',
        ];
    }

    /**
     * @inheritdoc
     * @return SeoPresetsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeoPresetsQuery(get_called_class());
    }
}
