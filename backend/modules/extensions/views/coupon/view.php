<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->coup_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->coup_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coup_id',
            'coup_no',
            'name',
            'type',
            'coup_value',
            'coup_quanity',
            'total_quanity',
            'start_date',
            'end_date',
            'status',
            'flat_date',
            'flat_coup',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'taturday',
            'sunday',
        ],
    ]) ?>

</div>
