<?php

namespace pantera\seo\widgets;

use pantera\seo\models\Seo;
use Yii;
use yii\helpers\Html;

class Text extends \yii\base\Widget
{
    /* @var Seo|null */
    public $model;
    public $text;
    /* @var array Набор опций для контейнера */
    public $options = [];

    public function run()
    {
        parent::run();
        $url = Yii::$app->request->pathInfo;
        $model = Yii::$app->seo->getSeoModel('/' . $url);
        if ($model && $model->text) {
            Yii::$app->seo->setText($model->text);
        }
        $this->text = Yii::$app->seo->getText() ?: $this->text;
        if ($this->text) {
            return Html::tag('div', $this->text, $this->options);
        }
    }
}