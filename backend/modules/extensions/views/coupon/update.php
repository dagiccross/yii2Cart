<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => \Yii::t('app','Coupon'),
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupon'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => '#'];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="coupon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'coupongoods'=>$coupongoods,
        'goods'=>$goods,
    ]) ?>

</div>
