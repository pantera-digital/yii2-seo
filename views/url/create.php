<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:47 PM
 */

use pantera\seo\models\Seo;
use yii\web\View;

/* @var $this View */
/* @var $model Seo */
$this->title = 'Create seo url';
$this->params['breadcrumbs'][] = ['label' => 'Seo url', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>
    <?= $this->title ?>
</h1>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
