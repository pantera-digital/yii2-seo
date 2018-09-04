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
use yii\base\Component;
use yii\helpers\ArrayHelper;
use function array_key_exists;
use function str_replace;

class SeoComponent extends Component
{
    private $_title;
    private $_description;
    private $_keywords;
    private $_h1;
    private $_text;
    private $_replacementsFrom = [];
    private $_replacementsTo = [];
    /* @var array Массив для всех найденых seo моделей */
    private $_seoModels = [];

    public function init()
    {
        parent::init();
        $replacements = SeoReplacement::find()
            ->all();
        $this->_replacementsFrom = ArrayHelper::getColumn($replacements, 'from');
        $this->_replacementsTo = ArrayHelper::getColumn($replacements, 'to');
    }

    /**
     * Получить модель настроект seo по переданому url адресу
     * @param string $url Url адрес для которого хотим найти модель
     * @return array|mixed|null|Seo|\yii\db\ActiveRecord
     */
    public function getSeoModel(string $url)
    {
        if (array_key_exists($url, $this->_seoModels) === false) {
            $model = Seo::find()->where(['=', 'url', $url])->one();
            $this->_seoModels[$url] = $model;
        }
        return $this->_seoModels[$url];
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $this->prepare($title);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $this->prepare($description);
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->_keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->_keywords = $this->prepare($keywords);
    }

    /**
     * @return mixed
     */
    public function getH1()
    {
        return $this->_h1;
    }

    /**
     * @param mixed $h1
     */
    public function setH1($h1)
    {
        $this->_h1 = $this->prepare($h1);
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->_text = $this->prepare($text);
    }

    private function prepare($string)
    {
        $chunks = preg_split('/\n+/', $string);
        $string = implode(" ", $chunks);
        $string = preg_replace('/\s+/', " ", $string);
        $string = trim($string);
        $string = str_replace($this->_replacementsFrom, $this->_replacementsTo, $string);
        return $string;
    }
}