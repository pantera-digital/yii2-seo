<?php

namespace pantera\seo\widgets;

use pantera\seo\models\Seo;
use Yii;
use yii\helpers\Html;

class Meta extends \yii\base\Widget
{
    /* @var Seo|null */
    public $model;
    public $title;
    public $description;

    public function init()
    {
        parent::init();
        $url = Yii::$app->request->pathInfo;
        $this->model = Seo::find()->where(['=', 'url', $url])->one();
        if (!empty($this->model->description)) {
            $this->description = $this->model->description;
        }
        if (!empty($this->model->title)) {
            $this->title = $this->model->title;
        }
    }

    public function run()
    {
        parent::run();
        echo "<title>" . Html::encode($this->title) . "</title>\n";
        echo "<meta name=\"description\" content=\"" . Html::encode($this->description) . "\"/>\n";
    }
}