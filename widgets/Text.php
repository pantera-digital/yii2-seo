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

    public function run()
    {
        parent::run();
        $this->text = Yii::$app->seo->getText() ?: $this->text;
        if ($this->text) {
            return Html::tag('div', $this->text, [
                'class' => 'seo-block',
            ]);
        }
    }
}