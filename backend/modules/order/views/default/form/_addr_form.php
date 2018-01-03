<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?= $form->field($model, 'member_id')->textInput()->label(\Yii::t('label','Member ID')) ?>

    <?= $form->field($model, 'shipment_name')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Name')) ?>

    <?= $form->field($model, 'shipment_phone')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Phone')) ?>

    <?= $form->field($model, 'shipment_city')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment City')) ?>

    <?= $form->field($model, 'shipment_addr')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Address')) ?>

    <?= $form->field($model, 'shipment_add2')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Address 2')) ?>

    <?= $form->field($model, 'shipment_postcode')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Postcode')) ?>


</div>
