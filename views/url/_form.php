<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:49 PM
 */

use dosamigos\ckeditor\CKEditor;
use pantera\seo\models\Seo;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Seo */

$form = ActiveForm::begin();
echo $form->field($model, 'url')->textInput(['maxlength' => true]);
echo $form->field($model, 'title')->textInput(['maxlength' => true]);
echo $form->field($model, 'description')->textarea(['rows' => 6]);
echo $form->field($model, 'keywords')->textarea(['rows' => 2]);
echo $form->field($model, 'h1')->textInput(['maxlength' => true]);
echo $form->field($model, 'text')->widget(CKEditor::className(), [
    'options' => ['rows' => 6],
    'preset' => 'full'
]);
echo Html::submitButton('Save', [
    'class' => 'btn btn-success',
]);
ActiveForm::end();
