<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel pantera\seo\models\SeoReplacementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seo Slug';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'slug',
        'model',
        'model_id',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
