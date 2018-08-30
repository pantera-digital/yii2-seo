<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/18/18
 * Time: 1:22 PM
 */

namespace pantera\seo\traits;

use pantera\seo\models\SeoSlug;
use Yii;

/**
 * Class SlugActiveQueryTrait
 * @package pantera\seo\traits
 */
trait SlugActiveQueryTrait
{
    private $_aliasIsJoined = false;

    /**
     * Приджойнить последний алиас для модели
     * @param string $modelName Название класса модели
     * @param string $tableName Название таблицы с которой джойнимся
     * @return $this
     */
    public function joinSlug($modelName, $tableName)
    {
        if ($this->_aliasIsJoined === false) {
            $subSql = 'SELECT ' . SeoSlug::tableName() . '.*
    	FROM ' . SeoSlug::tableName() . '
      	JOIN (
			SELECT ' . SeoSlug::tableName() . '.model_id, MAX(id) AS max_id
           	FROM ' . SeoSlug::tableName() . '
           	WHERE ' . SeoSlug::tableName() . '.model = ' . Yii::$app->db->quoteValue($modelName) . '
            GROUP BY ' . SeoSlug::tableName() . '.model_id
		) as sel ON ' . SeoSlug::tableName() . '.model_id = sel.model_id AND ' . SeoSlug::tableName() . '.id = sel.max_id';
            $sql = '(' . $subSql . ') as ' . SeoSlug::tableName() . '';
            $this->leftJoin($sql, $tableName . '.id = ' . SeoSlug::tableName() . '.model_id');
            $this->_aliasIsJoined = true;
        }
        return $this;
    }
}