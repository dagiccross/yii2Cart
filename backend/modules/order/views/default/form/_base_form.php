<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?= $form->field($model, 'invoice_no')->textInput()->label(\Yii::t('app','Invoice No.')) ?>

    <?= $form->field($model, 'invoice_prefix')->textInput(['maxlength' => true])->label(\Yii::t('label','Invoice prefix')) ?>
    
    <?= $form->field($model, 'comment')->textArea(['maxlength' => true])->label(\Yii::t('label','Comment')) ?>

    <?= $form->field($model, 'total')->textInput(['maxlength' => true])->label(\Yii::t('app','Total Price')) ?>

    <?= $form->field($model, 'order_status')->dropDownList(getOrderStatus())->label(\Yii::t('label','Order Status')) ?>


    <?= $form->field($model, 'shipment_method_name')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Method Name')) ?>

    <?= $form->field($model, 'shipment_method_price')->textInput(['maxlength' => true])->label(\Yii::t('label','Shipment Price')) ?>

    <?= $form->field($model, 'payment_method_name')->textInput(['maxlength' => true])->label(\Yii::t('label','Payment Method')) ?>

</div>
