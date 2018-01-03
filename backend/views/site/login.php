<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app','Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?php echo Yii::t('app','Please fill out the following fields to login:');?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'verifyCode', [
                            'options' => ['class' => 'form-group form-group-lg'],
                    ])->widget(Captcha::className(),[
                           'template' => "{image}{input}",
                           'imageOptions' => ['alt' => Yii::t('info','verify code')],
                           'captchaAction' => 'site/captcha',
                    ]); ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app','Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
