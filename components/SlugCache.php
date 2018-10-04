<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 9/4/18
 * Time: 3:06 PM
 */

namespace pantera\seo\components;


use pantera\seo\models\SeoSlug;
use yii\base\BaseObject;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class SlugCache extends BaseObject implements SlugCacheInterface
{
    protected $_storage = [];

    /**
     * Получить модель с алиасом
     * @param ActiveRecord $model Модель для которой пытаемся найти алиас
     * @return mixed
     */
    public function get(ActiveRecord $model)
    {
        $key = $model::className() . '-' . $model->getPrimaryKey();
        return ArrayHelper::getValue($this->_storage, $key);
    }

    /**
     * Положить модель с алиасом
     * @param ActiveRecord $model Модель к которой принадлежит алиас
     * @param SeoSlug $slug Модель с алиасом которую нужно закешировать
     * @return mixed
     */
    public function set(ActiveRecord $model, SeoSlug $slug)
    {
        $key = $model::className() . '-' . $model->getPrimaryKey();
        $this->_storage[$key] = $slug;
    }

    /**
     * Очистить хранилише
     * @return void
     */
    public function removeAll()
    {
        $this->_storage = [];
    }
}