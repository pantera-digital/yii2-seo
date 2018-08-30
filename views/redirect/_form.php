<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:49 PM
 */

use pantera\seo\models\SeoRedirect;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SeoRedirect */

$form = ActiveForm::begin();
echo $form->field($model, 'from')->textInput(['maxlength' => true]);
echo $form->field($model, 'to')->textInput(['maxlength' => true]);
echo $form->field($model, 'code')->textInput(['rows' => 6, 'maxlength' => true]);
echo Html::submitButton('Save', [
    'class' => 'btn btn-success',
]);
ActiveForm::end();
