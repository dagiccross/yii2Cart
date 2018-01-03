<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupon'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'coupongoods'=>$coupongoods,
        'goods'=>$goods,
    ]) ?>

</div>
