<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

<?php
	if(isset($model['service_email'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Server Email'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['service_email']['id'].']',$model['service_email']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['smtp_server'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','SMTP Server'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['smtp_server']['id'].']',$model['smtp_server']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['smtp_user'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','SMTP User'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['smtp_user']['id'].']',$model['smtp_user']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['smtp_password'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','SMTP Password'),'',['class'=>'control-label']);
		echo Html::passwordInput('Config['.$model['smtp_password']['id'].']',$model['smtp_password']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['smtp_port'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','SMTP Port'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['smtp_port']['id'].']',$model['smtp_port']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['smtp_ssl'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','SMTP SSL'),'',['class'=>'control-label']);
		echo Html::radio('Config['.$model['smtp_ssl']['id'].']',$model['smtp_ssl']['values']==0,['value'=>'0']).'No SSL';
		echo Html::radio('Config['.$model['smtp_ssl']['id'].']',$model['smtp_ssl']['values']==1,['value'=>'1']).'SSL';
		echo Html::endTag('div');
	}
?>

<?php

	echo Html::beginTag('div',['class'=>'form-group']);
	echo Html::label(\Yii::t('label','SMTP Test'),'',['class'=>'control-label']);
	echo Html::textInput('test_email','',['class'=>'form-control']);
	echo Html::beginTag('p');
	echo Html::endTag('p');
	echo Html::textArea('test_input','',['class'=>'form-control']);
	echo Html::beginTag('p');
	echo Html::endTag('p');
	echo Html::button(\Yii::t('label','Test'),['class'=>'btn btn-success test-btn']);
	echo Html::endTag('div');

?>

<?php
	if(isset($model['currency_code'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Currency'),'',['class'=>'control-label']);
		echo Html::dropDownList('Config['.$model['currency_code']['id'].']',$model['currency_code']['values'],$list['currency'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

</div>
