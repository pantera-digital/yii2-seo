<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/24/18
 * Time: 3:09 PM
 */

namespace pantera\seo\components;

use pantera\seo\models\Seo;
use pantera\seo\models\SeoReplacement;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use function array_key_exists;
use function str_replace;

class SeoComponent extends Component
{
    private $title;
    private $description;
    private $keywords;
    private $h1;
    private $text;
    private $replacementsFrom = [];
    private $replacementsTo = [];
    /* @var array Массив для всех найденых seo моделей */
    private $seoModels = [];

    public function init()
    {
        parent::init();
        $replacements = SeoReplacement::find()
            ->all();
        $this->replacementsFrom = ArrayHelper::getColumn($replacements, 'from');
        $this->replacementsTo = ArrayHelper::getColumn($replacements, 'to');
    }

    /**
     * Получить модель настроект seo по переданому url адресу
     * @param string $url Url адрес для которого хотим найти модель
     * @return array|mixed|null|Seo|\yii\db\ActiveRecord
     */
    public function getSeoModel(string $url)
    {
        if (array_key_exists($url, $this->seoModels) === false) {
            $model = Seo::find()->where(['=', 'url', $url])->one();
            $this->seoModels[$url] = $model;
        }
        return $this->seoModels[$url];
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $this->prepare($title);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $this->prepare($description);
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $this->prepare($keywords);
    }

    /**
     * @return mixed
     */
    public function getH1()
    {
        return $this->h1;
    }

    /**
     * @param mixed $h1
     */
    public function setH1($h1)
    {
        $this->h1 = $this->prepare($h1);
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $this->prepare($text);
    }

    private function prepare($string)
    {
        $chunks = preg_split('/\n+/', $string);
        $string = implode(" ", $chunks);
        $string = preg_replace('/\s+/', " ", $string);
        $string = trim($string);
        $string = str_replace($this->replacementsFrom, $this->replacementsTo, $string);
        return $string;
    }

    /**
     * Зарегистрировать
     * @param Seo $model
     */
    public function register(Seo $model)
    {
        if ($model->title) {
            Yii::$app->seo->setTitle($model->title);
        }
        if ($model->h1) {
            Yii::$app->seo->setH1($model->h1);
        }
        if ($model->description) {
            Yii::$app->seo->setDescription($model->description);
        }
        if ($model->keywords) {
            Yii::$app->seo->setKeywords($model->keywords);
        }
        if ($model->text) {
            Yii::$app->seo->setText($model->text);
        }
    }
}
