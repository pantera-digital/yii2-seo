<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/24/18
 * Time: 3:09 PM
 */

namespace pantera\seo\components;


use yii\base\Component;

class SeoComponent extends Component
{
    private $_title;
    private $_description;
    private $_keywords;
    private $_h1;
    private $_text;

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
        $this->_title = $title;
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
        $this->_description = $description;
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
        $this->_keywords = $keywords;
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
        $this->_h1 = $h1;
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
        $this->_text = $text;
    }
}