<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pantera\seo\models\SeoReplacement */

$this->title = 'Create Seo Replacement';
$this->params['breadcrumbs'][] = ['label' => 'Seo Replacements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-replacement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
