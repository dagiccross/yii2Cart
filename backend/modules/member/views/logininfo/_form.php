<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Logininfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logininfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passwd')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'power')->dropDownList($model::userFlat(), ['prompt' => 'Power']) ?>

    <?= $form->field($model, 'logintime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'loginip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_koken')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'status')->dropDownList(userStatus(0,1), ['prompt' => \Yii::t('app','Status')]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
