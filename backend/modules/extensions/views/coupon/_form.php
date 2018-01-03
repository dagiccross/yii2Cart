<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use common\models\Config;
/* @var $this yii\web\View */
/* @var $model common\models\Coupon */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('label','Basic Infomation'),
                'content' => $this->render('form/_date_form', [
                                'model' => $model,
                                'form' => $form,
                            ]),
                'active' => true
            ],
            [
                'label' => \Yii::t('label','Goods Infomation'),
                'content' => $this->render('form/_goods_form', [
                                'model' => $model,
                                'form' => $form,
                                'coupongoods'=>$coupongoods,
                                'goods'=>$goods,
                            ]),
            ],
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
