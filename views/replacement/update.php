<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pantera\seo\models\SeoReplacement */

$this->title = 'Update Seo Replacement: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Seo Replacements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="seo-replacement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
