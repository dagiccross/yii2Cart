<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app','Page'),
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meta' => $meta,
    ]) ?>

</div>
