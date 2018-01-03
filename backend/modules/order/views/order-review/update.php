<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderReview */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customers Review',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->review_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-review-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
