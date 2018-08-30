<?php

use pantera\seo\models\SeoRedirectSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel SeoRedirectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Seo redirect';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::beginForm(['import'], 'POST', [
        'enctype' => 'multipart/form-data',
    ]) ?>
    <div class="form-group">
        <label for="" class="control-label">Импорт через файл (xls, xlsx)</label>
        <input type="file" name="import">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Загрузить</button>
    </div>
    <div class="form-group">
        <div>
            <small>
                A - url старый (вида /section)
            </small>
        </div>
        <div>
            <small>
                B - url новый (вида /section)
            </small>
        </div>
    </div>
    <?= Html::endForm() ?>
    <p>
        <?= Html::a('Create', ['create'], [
            'class' => 'btn btn-success',
        ]) ?>
        <?= Html::a('Удалить выбранное', ['delete-group'], [
            'class' => 'btn btn-danger seo-group-delete',
        ]) ?>
        <?= Html::a('Delete all', ['delete-all'], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'POST',
                'confirm' => 'Вы уверены?',
            ],
        ]) ?>
        <?= Html::a('Export', ['export'], [
            'class' => 'btn btn-primary',
        ]) ?>
    </p>

    <?php Pjax::begin(); ?>

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
            'from',
            'to',
            'code',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
