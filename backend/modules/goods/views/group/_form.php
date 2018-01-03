<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model common\models\Group */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?php
	$items = array();
	$i = 0;

	foreach (getLanguage() as $k => $v) {
		$items[] = ['label'=>Yii::t('info','Tag').$v['name'],
					'content'=>$this->render('form/_meta_form', [
						        'model' => isset($meta[$k]) ? $meta[$k] : array(),
						        'language'=>$k
						    ]),
					'active' => $i==0 ? true : false,
					];
		$i++;
	}
	echo Tabs::widget([
	    'items' => $items,
	]);
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
