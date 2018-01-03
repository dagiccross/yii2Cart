<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

<?php
	if(isset($model['company_name'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Company Name'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['company_name']['id'].']',$model['company_name']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['company_tel'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Company Tel'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['company_tel']['id'].']',$model['company_tel']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['server_mail'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Server E-mail (Receive Orders)'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['server_mail']['id'].']',$model['server_mail']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['address'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Address'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['address']['id'].']',$model['address']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['postcode'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Postcode'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['postcode']['id'].']',$model['postcode']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['city'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','City'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['city']['id'].']',$model['city']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

</div>
