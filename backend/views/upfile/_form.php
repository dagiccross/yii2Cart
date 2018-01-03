<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Upfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="upfile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thumb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
