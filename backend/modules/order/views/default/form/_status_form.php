<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Order;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">
   	<?php $form = ActiveForm::begin(); ?>
   	<div>
   		
   	</div>
   	<div class="form-group field-order-action-comment">
   		<?=Html::label(\Yii::t('info','Comment'),'',['class'=>'control-label'])?>
		<?=Html::textArea('Order[comment]','',['class'=>'form-control'])?>
		<div class="help-block"></div>
	</div>
	<div class="form-group field-order-order_status">
   		<?=Html::label(\Yii::t('label','Payment Status'),'',['class'=>'control-label'])?>
		<?=Html::dropDownList('Order[payment_status]',Order::getPaymentStatusKey($model->order_status,$model->payment_type,$model->order_type),Order::paymentStatus(),['class'=>'form-control'])?>
		<div class="help-block"></div>
	</div>
	<div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>
	<?php ActiveForm::end();?>
</div>
