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

    public function init()
    {
        parent::init();
        $url = Yii::$app->request->pathInfo;
        $this->model = Seo::find()->where(['=', 'url', $url])->one();
        if (!empty($this->model->text)) {
            $this->text = $this->model->text;
        }
    }

    public function run()
    {
        parent::run();
        if ($this->text) {
            return Html::tag('div', $this->text, [
                'class' => 'seo-block',
            ]);
        }
    }
}