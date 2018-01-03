<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<section class="login-wrap">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 middle-content">
                    <div class="login-form">
                    <h2>Login</h2>
                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                        <?=Html::tag('p','Email',['class'=>'lotitle'])?>
                        <?=Html::tag('p',Html::activeTextInput($model,'email',['class'=>'email-input','placeholder'=>'example@email.com']))?>
                        <?php if(isset($model->getErrors('email')['0'])):?>
                        <?=Html::tag('p',$model->getErrors('email')['0'],['style'=>'color:red'])?>
                        <?php endif;?>
                    <p><?= Html::submitButton('Send', ['class' => 'login-btn', 'name' => 'login-button']) ?></p>


                    <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-sm-4"></div>
                <div class="clearfix"></div>
</section>
