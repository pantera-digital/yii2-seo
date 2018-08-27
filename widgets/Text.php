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
        $url = Yii::$app->request->pathInfo;
        $this->model = Seo::find()->where(['=', 'url', '/' . $url])->one();
        if($this->model && $this->model->text){
            Yii::$app->seo->setText($this->model->text);
        }
        $this->text = Yii::$app->seo->getText() ?: $this->text;
        if ($this->text) {
            return Html::tag('div', $this->text, [
                'class' => 'seo-block',
            ]);
        }
    }
}