<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(\Yii::t('app','Name').'：')?>
    <?=Html::textInput('meta['.$language.'][name]',isset($model['name']) ? $model['name'] : '',['class'=>'form-control']);?>
</div>

<div class="tag-form" style="padding:10px 0px;">
    <?=Html::hiddenInput('meta['.$language.'][options]',isset($model['options']) ? $model['options'] : '',['class'=>'form-control']);?>
</div>