<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if(Yii::$app->user->identity->power=='admin'):?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passwd')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'power')->dropDownList(userFlat(0,1), ['prompt' => '权限']) ?>

    <?php // $form->field($model, 'fen')->textInput() ?>

    <?php //  $form->field($model, 'money')->textInput() ?>

    <?php //  $form->field($model, 'freezing')->textInput() ?>

    <?php // $form->field($model, 'addtime')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'modifytime')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'loginip')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'status')->dropDownList(userStatus(0,1), ['prompt' => \Yii::t('app','Status')]) ?>
    <?= Html::activeCheckbox($userback,'flat')?>
    <?php endif;?>
    <?= $form->field($model, 'member_discount')->dropDownList(\common\models\Coupon::memberList(), ['prompt' => \Yii::t('app','Please Select')]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
