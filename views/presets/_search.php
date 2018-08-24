<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pantera\seo\models\SeoPresetsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-presets-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'key') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'meta_title') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'seo_h1') ?>

    <?php // echo $form->field($model, 'seo_text') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
