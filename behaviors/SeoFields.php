<?php

namespace pantera\seo\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use pantera\seo\models\Seo;

class SeoFields extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateFields',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateFields',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteFields',
        ];
    }

    public function updateFields($event)
    {
        $post = Yii::$app->request->post();
        //Проверим на наличие в посте сео данных
        if(!empty($post['Seo'])) {
            //Найдем существующую модель
            if (($model = Seo::findOne(['item_id' => $this->owner->id, 'modelName' => $this->owner->className() ])) === null) {
                //Создадим новую модель
                $model = new Seo([
                    'item_id' => $this->owner->id,
                    'modelName' => $this->owner->className(),
                ]);
            }

            $model->load($post);
            $model->save();

            //Почистим пост от наших данных
            unset($post['Seo']);
            Yii::$app->request->setBodyParams($post);
        }
    }
    
    public function deleteFields($event)
    {
        if($this->owner->seo) {
            $this->owner->seo->delete();
        }
        
        return true;
    }
    
    public function getSeo()
    {
        if($model = Seo::find()->where(['item_id' => $this->owner->id, 'modelName' => $this->owner->className()])->one()) {
            return $model;
        } else {
            return new Seo;
        }
    }
}
