<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 12/13/17
 * Time: 1:37 PM
 */

namespace pantera\seo\validators;

use pantera\seo\behaviors\SlugBehavior;
use pantera\seo\models\SeoSlug;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\validators\Validator;

/**
 * Class SlugValidator
 * @package pantera\seo\validators
 *
 * @see SlugBehavior
 */
class SlugValidator extends Validator
{
    /* @var string Регульрное вырожение для проверки */
    public $pattern = null;
    /* @var string Сообщение об ошибки если slug не соответствует регулярному выражению */
    public $patternMessage;

    public function init()
    {
        parent::init();
        if (is_null($this->message)) {
            $this->message = Yii::t('app', 'Алиас {value} уже используется');
        }
        if (is_null($this->patternMessage)) {
            $this->patternMessage = Yii::t('app', 'Алиас {value} не допускается');
        }
    }

    public function validateAttribute($model, $attribute)
    {
        if ($this->pattern) {
            if (preg_match($this->pattern, $model->{$attribute}) === 0) {
                $this->addError($model, $attribute, $this->patternMessage);
            }
        }
        if ($this->validateUnique($model, $model->{$attribute}) === false) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /**
     * Валидация переданного slug
     * Принимаем модели для которой хотим проверить slug
     * @param ActiveRecord $model
     * @param string $value
     * @return bool
     */
    public function validateSlug(ActiveRecord $model, $value)
    {
        if ($this->pattern) {
            if (preg_match($this->pattern, $value) === 0) {
                return false;
            }
        }
        if ($this->validateUnique($model, $value) === false) {
            return false;
        }
        return true;
    }

    /**
     * Проверка slug на уникальность
     * @param Model $model
     * @param string $slug
     * @return bool
     */
    private function validateUnique($model, $slug)
    {
        $slug = trim(trim($slug, '/'));
        /* @var $model ActiveRecord */
        $query = SeoSlug::find()
            ->where(['=', 'slug', $slug]);
        if ($model->isNewRecord === false) {
            $query->andWhere([
                'OR',
                [
                    'AND',
                    ['!=', 'model', $model::className()],
                    ['=', 'model_id', $model->getPrimaryKey()],
                ],
                ['!=', 'model_id', $model->getPrimaryKey()],
            ]);
        }
        return $query->count() < 1;
    }
}