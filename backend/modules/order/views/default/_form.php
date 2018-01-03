<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('app','Basic Information'),
                'content' => $this->render('form/_base_form', [
                                'model' => $model,
                                'form'=>$form,
                            ]),
                'active' => true
            ],
            [
                'label' => \Yii::t('app','Address Information'),
                'content' => $this->render('form/_addr_form', [
                                'model' => $model,
                                'form'=>$form,
                            ]),
            ],
            [
                'label' => \Yii::t('app','Goods Information'),
                'content' => $this->render('form/_goods_form', [
                                'model' => $model,
                                'form'=>$form,
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
