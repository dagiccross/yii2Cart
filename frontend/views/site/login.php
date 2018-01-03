<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<div class="container">
<section class="login-wrap">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 middle-content">
                    <div class="login-form">
                    <h2>Login</h2>
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <?=Html::tag('p','Email',['class'=>'lotitle'])?>
                        <?=Html::tag('p',Html::activeTextInput($model,'username',['class'=>'email-input','placeholder'=>'example@email.co.uk']))?>
                        <?php if(isset($model->getErrors('username')['0'])):?>
                        <?=Html::tag('p',$model->getErrors('username')['0'],['style'=>'color:red'])?>
                        <?php endif;?>

                        <?=Html::tag('p','Password',['class'=>'lotitle'])?>
                        <?=Html::tag('p',Html::activePasswordInput($model,'password',['class'=>'password-input','placeholder'=>'password']))?>
                        <?php if(isset($model->getErrors('password')['0'])):?>
                        <?=Html::tag('p',$model->getErrors('password')['0'],['style'=>'color:red'])?>
                        <?php endif;?>

                        <p class="logged">
                            <?=Html::activeCheckBox($model,'rememberMe')?>
                            <span>(Stay logged in)</span></p>
                        <?=Html::tag('p',Html::a('Forgotten your password?',['/site/request-password-reset']),['class'=>'forget-word']);?>
                        <p><?= Html::submitButton('Login', ['class' => 'login-btn', 'name' => 'login-button']) ?></p>
                        <p class="eat">No account? Only takes a minute to register.</p>
                        <p><?=Html::button('Sign up',['class'=>'sign-btn btn-info','onclick'=>'javascript:window.location.href="'.Url::to(['/site/signup']).'"'])?></p>
                        <!-- <p class="pageback feedback">[-]Page Feedback</p> -->
                    <?php ActiveForm::end(); ?>
                    </div>

                </div>
                <div class="col-sm-4"></div>
                <div class="clearfix"></div>
            </section>
			</div>
