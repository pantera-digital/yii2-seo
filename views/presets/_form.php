<?php

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pantera\seo\models\SeoPresets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-presets-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'key')->textarea(['rows' => 6, 'maxlength' => true]) ?>

            <?= $form->field($model, 'meta_title')->textarea(['rows' => 6, 'maxlength' => true]) ?>

            <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'seo_h1')->textarea(['rows' => 6, 'maxlength' => true]) ?>

            <?= $form->field($model, 'seo_text')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
            <?php
            echo Html::label($model->getAttributeLabel('comment'));
            if ($model->comment) {
                echo Html::tag('div', nl2br($model->comment));
                echo Html::tag('br');
            }
            echo Collapse::widget([
                'options' => [
                    'class' => 'collapse--without-style',
                ],
                'items' => [
                    [
                        'label' => 'Изменить комментарий',
                        'content' => $form->field($model, 'comment')->textarea(['rows' => 10])->label(false),
                    ]
                ],
            ]);
            ?>
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Применить', [
            'class' => 'btn btn-success',
            'name' => 'action',
            'value' => 'apply',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
