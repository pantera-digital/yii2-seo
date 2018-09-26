<?php

use pantera\seo\models\SeoSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel SeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelNames array */
$this->title = 'Seo models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'modelName',
                'filter' => Html::activeDropDownList($searchModel, 'modelName', $modelNames, [
                    'class' => 'form-control',
                    'prompt' => '---',
                ]),
            ],
            'item_id',
            'title',
            'h1',
            'description',
            'keywords',
            [
                'attribute' => 'text',
                'format' => 'html',
                'contentOptions' => [
                    'style' => 'white-space: initial;',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
