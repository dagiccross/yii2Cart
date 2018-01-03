<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Config */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('info', 'Config'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="config-view">

    <h1><?= Yii::t('info', 'Details').':'.Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('info', 'Index'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('info', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('info', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('info', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'options',
            'values:ntext',
        ],
    ]) ?>

</div>
