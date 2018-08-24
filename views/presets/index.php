<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel pantera\seo\models\SeoPresetsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seo Presets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-presets-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Seo Presets', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'key',
            'comment:ntext',
            'status:boolean',
            'meta_title',
            'meta_description:ntext',
            'seo_h1',
            'seo_text:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
