<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">
    <?=Html::button(\Yii::t('label','Add Goods'),['class'=>'btn btn-primary'])?>

<table class="table table-bordered">
    <th>#</th>
    <th><?=\Yii::t('info','Name')?></th>
    <th><?=\Yii::t('app','Price')?></th>
    <th><?=\Yii::t('app','Quanity')?></th>
    <th><?=\Yii::t('app','Options')?></th>
</table>

</div>
