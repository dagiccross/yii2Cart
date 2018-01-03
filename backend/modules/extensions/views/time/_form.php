<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Time;
/* @var $this yii\web\View */
/* @var $model common\models\Time */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="time-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'time')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(Time::type()) ?>

    <?= $form->field($model, 'Monday')->checkbox() ?>

    <?= $form->field($model, 'Tuesday')->checkbox() ?>

    <?= $form->field($model, 'Wednesday')->checkbox() ?>

    <?= $form->field($model, 'Thursday')->checkbox() ?>

    <?= $form->field($model, 'Friday')->checkbox() ?>

    <?= $form->field($model, 'Saturday')->checkbox() ?>

    <?= $form->field($model, 'Sunday')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
