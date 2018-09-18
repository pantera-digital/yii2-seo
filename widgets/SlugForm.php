<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 9/18/18
 * Time: 11:21 AM
 */

namespace pantera\seo\widgets;


use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

class SlugForm extends Widget
{
    /* @var ActiveForm */
    public $form = null;
    /* @var ActiveRecord */
    public $model = null;
    /* @var string Название атрибута в модели которое хранит в себе алиас */
    public $attribute = 'slug';

    public function run()
    {
        parent::run();
        return $this->form->field($this->model, $this->attribute);
    }
}