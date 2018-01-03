<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Language */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('info','Language'),
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->language_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="language-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
