<?php

namespace pantera\seo\behaviors;

use pantera\seo\models\Seo;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use function is_null;

class SeoFields extends Behavior
{
    /* @var ActiveRecord */
    public $owner;
    /* @var Seo|null */
    private $_model;
    /* @var array|null */
    //TODO: Не нравится название атрибута, потому что оно начинается с большой буквы и перекликается с getSeo - Саня
    public $Seo;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateFields',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateFields',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteFields',
        ];
    }

    public function attach($owner)
    {
        parent::attach($owner);
        //Добавляем правила для лоада Seo данныъ
        $this->owner->validators->append( yii\validators\Validator::createValidator('safe', $this->owner, ['Seo']));
    }

    public function updateFields($event)
    {
        if ($this->Seo) {
            //Найдем существующую модель
            if (($model = Seo::findOne(['item_id' => $this->owner->id, 'modelName' => $this->owner->className()])) === null) {
                //Создадим новую модель
                $model = new Seo([
                    'item_id' => $this->owner->id,
                    'modelName' => $this->owner->className(),
                ]);
            }
            $model->load(['Seo' => $this->Seo]);
            $model->save();
        }
    }

    public function deleteFields($event)
    {
        if ($this->owner->seo) {
            $this->owner->seo->delete();
        }

        return true;
    }

    public function getSeo()
    {
        if (is_null($this->_model)) {
            $this->_model = Seo::find()->where([
                'item_id' => $this->owner->getPrimaryKey(),
                'modelName' => $this->owner::className(),
            ])->one();
            $this->_model = $this->_model ?: new Seo;
        }
        $this->_model->relationFormName = $this->owner->formName();
        return $this->_model;
    }
}
