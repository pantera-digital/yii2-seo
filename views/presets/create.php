<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pantera\seo\models\SeoPresets */

$this->title = 'Create Seo Presets';
$this->params['breadcrumbs'][] = ['label' => 'Seo Presets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-presets-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
