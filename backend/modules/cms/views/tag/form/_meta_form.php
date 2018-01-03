<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(\Yii::t('app','Tag Name').'：')?>
	<?=Html::textInput('meta['.$language.'][name]',isset($model['name']) ? $model['name'] : '',['class'=>'form-control']);?>
</div>
