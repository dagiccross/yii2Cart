<?php

use yii\helpers\Html;
use thridpart\kindeditor\KindEditor;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
	$root = categoryChild(0,0);
	$category = $goodstocategory;
	// var_dump($goodstocategory);
	foreach ($root as $k => $v) {
		$isCheck = is_array($category) ? (in_array($v['id'], $category) ? true : false) : false;
		echo '<ul class="list-inline">';
		echo '<li>'.Html::checkbox('category[]',$isCheck,['value'=>$v['id']]).Html::label($v['name']);
		echo showChlid($v['id'],$category);
		echo '</li>';
		echo '</ul>';
	}


?>