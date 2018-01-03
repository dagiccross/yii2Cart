<?php

use yii\helpers\Html;
use thridpart\kindeditor\KindEditor;
/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Name').'：')?>
	<?=Html::textInput('meta['.$language.'][title]',isset($model['title']) ? $model['title'] : '',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Description').'：')?>
	<?=Html::textArea('meta['.$language.'][description]',isset($model['description']) ? $model['description'] : '',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Content').'：')?>
	<?php 
	// echo KindEditor::widget(['model'=>$model,
	// 						'attribute'=>'content',
	// 						'class'=>'form-control']);
	?>
	<?php 
	echo KindEditor::widget(['name'=>'meta['.$language.'][content]',
							'id'=>$language.'content',
							'value'=>isset($model['content']) ? $model['content'] : '',
							'class'=>'form-control']);
	?>
</div>