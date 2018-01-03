<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Logininfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logininfo-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <div class="form-group field-logininfo-passwd required has-success">
        <?php
        echo Html::label('Old Password','',['class'=>'control-label']);
        echo Html::passwordInput('old_password','',['class'=>'form-control']);
        ?>
        <div class="help-block"></div>
    </div>

    <div class="form-group field-logininfo-passwd required has-success">
        <?php
        echo Html::label('New Password','',['class'=>'control-label']);
        echo Html::passwordInput('new_password','',['class'=>'form-control']);
        ?>
        <div class="help-block"></div>
    </div>

    <div class="form-group field-logininfo-passwd required has-success">
        <?php
        echo Html::label('Confirm Password','',['class'=>'control-label']);
        echo Html::passwordInput('confirm_password','',['class'=>'form-control']);
        ?>
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
