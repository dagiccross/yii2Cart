<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

<?php
	if(isset($model['Collection_Time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Collection Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Collection_Time']['id'].']',$model['Collection_Time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['Delivery_Time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Delivery Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Delivery_Time']['id'].']',$model['Delivery_Time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>



<?php
	if(isset($model['Open_time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Open Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Open_time']['id'].']',$model['Open_time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>
<?php
	if(isset($model['Close_time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Close Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Close_time']['id'].']',$model['Close_time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
if(isset($model['delivery_flat'])){
	echo Html::beginTag('div',['class'=>'form-group']);
	echo Html::label(\Yii::t('label','Delivery Options'),'',['class'=>'control-label']);
	echo Html::dropDownList('Config['.$model['delivery_flat']['id'].']',$model['delivery_flat']['values'],['0'=>'We Deliver','1'=>'We DO NOT deliver at the moment'],['class'=>'form-control']);
	echo Html::endTag('div');
}
?>
<!-- <?php
	if(isset($model['Minimum'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Minimum Consumption'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Minimum']['id'].']',$model['Minimum']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?> -->


</div>
