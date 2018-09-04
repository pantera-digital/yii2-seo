<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 9/4/18
 * Time: 3:06 PM
 */

namespace pantera\seo\components;


use pantera\seo\models\SeoSlug;
use yii\db\ActiveRecord;

interface SlugCacheInterface
{
    /**
     * Получить модель с алиасом
     * @param ActiveRecord $model Модель для которой пытаемся найти алиас
     * @return mixed
     */
    public function get(ActiveRecord $model);

    /**
     * Положить модель с алиасом
     * @param ActiveRecord $model Модель к которой принадлежит алиас
     * @param SeoSlug $slug Модель с алиасом которую нужно закешировать
     * @return mixed
     */
    public function set(ActiveRecord $model, SeoSlug $slug);
}