<?php

namespace pantera\seo\widgets;

use dosamigos\ckeditor\CKEditor;
use pantera\seo\models\Seo;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

class SeoForm extends \yii\base\Widget
{
    /* @var ActiveRecord */
    public $model = null;
    public $modelName = null;
    /* @var ActiveForm */
    public $form = null;
    public $title = 'SEO';

    public function init()
    {
        if (empty($this->modelName)) {
            $this->modelName = $this->model->className();
        }
        parent::init();
    }

    public function run()
    {
        $content = [];
        $content[] = $this->form->field($this->model->seo, 'title')->textInput(['maxlength' => true]);
        $content[] = $this->form->field($this->model->seo, 'description')->textarea([
            'rows' => 5,
            'maxlength' => true,
        ]);
        $content[] = $this->form->field($this->model->seo, 'keywords')->textInput(['maxlength' => true]);
        $content[] = $this->form->field($this->model->seo, 'h1')->textInput(['maxlength' => true]);
        $content[] = $this->form->field($this->model->seo, 'text')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'full'
        ]);
        return implode('', $content);
    }
}
