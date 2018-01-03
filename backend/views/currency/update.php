<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app','Currency'),
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Currency'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->curr_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="currency-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
