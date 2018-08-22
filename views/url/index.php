<?php

use pantera\seo\models\SeoSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel SeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelNames array */
$this->title = 'Seo url';
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
                A - url (вида /section)
            </small>
        </div>
        <div>
            <small>
                B - title
            </small>
        </div>
        <div>
            <small>
                C - description
            </small>
        </div>
        <div>
            <small>
                D - keywords
            </small>
        </div>
        <div>
            <small>
                E - H1
            </small>
        </div>
        <div>
            <small>
                F - Текст
            </small>
        </div>
    </div>
    <?= Html::endForm() ?>
    <p>
        <?= Html::a('Create', ['create'], [
            'class' => 'btn btn-success',
        ]) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'url',
            'title',
            'description',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
