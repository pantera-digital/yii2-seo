<?php

namespace pantera\seo\models;

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
        if ($model) {
            $title = $model->meta_title ? self::prepare($model->meta_title, $params) : $title;
            Yii::$app->seo->setTitle($title);
            $description = $model->meta_description ? self::prepare($model->meta_description, $params) : $description;
            Yii::$app->seo->setDescription($description);
            $h1 = $model->seo_h1 ? self::prepare($model->seo_h1, $params) : $h1;
            Yii::$app->seo->setH1($h1);
            $text = $model->seo_text ? self::prepare($model->seo_text, $params) : $text;
            Yii::$app->seo->setText($text);
        }
    }

    private static function prepare($str, $params = [])
    {
        $twig = new \Twig_Environment(new \Twig_Loader_String());
        return $twig->render($str, $params);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_presets';
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
            [['key'], 'string', 'max' => 255],
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
