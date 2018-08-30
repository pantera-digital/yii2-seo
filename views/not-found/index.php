<?php

use pantera\seo\models\SeoNotFound;
use pantera\seo\models\SeoNotFoundSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel SeoNotFoundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Not Found';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="not-found-logger-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Удалить выбранное', ['delete-group'], [
            'class' => 'btn btn-danger seo-group-delete',
        ]) ?>
        <?= Html::a('Удалить все', ['delete-all'], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'POST',
                'confirm' => 'Вы уверены?',
            ],
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'cssClass' => 'selection',
                'options' => [
                    'style' => 'width: 20px !important;'
                ],
                'contentOptions' => [
                    'style' => 'width: 20px !important;'
                ],
            ],
            'id',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function (SeoNotFound $model) {
                    return Html::a($model->url, $model->url, [
                        'target' => '_blank',
                        'data' => [
                            'pjax' => 0,
                        ],
                    ]);
                },
            ],
            [
                'attribute' => 'referrer',
                'format' => 'raw',
                'value' => function (SeoNotFound $model) {
                    return Html::a($model->referrer, $model->referrer, [
                        'target' => '_blank',
                        'data' => [
                            'pjax' => 0,
                        ],
                    ]);
                },
            ],
            'ip',
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
