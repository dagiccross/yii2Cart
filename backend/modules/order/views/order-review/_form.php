<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderReview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-review-form ">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'money')->textInput() ?>
    <div class="form-group field-orderreview-money has-success">
    <label>Was the food value for money?</label>
    <div class="row container-fluid">
    <?=Html::radio('OrderReview[money]',$model->money==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span>
    <?=Html::radio('OrderReview[money]',$model->money==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span>
    <?=Html::radio('OrderReview[money]',$model->money==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span>
    <?=Html::radio('OrderReview[money]',$model->money==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span>
    <?=Html::radio('OrderReview[money]',$model->money==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span>
    </div>
    <div class="help-block"></div>
    </div>
    <label>How quick was the delivery?</label>
    <div class="row container-fluid">
    <?=Html::radio('OrderReview[delivery]',$model->delivery==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span>
    <?=Html::radio('OrderReview[delivery]',$model->delivery==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span>
    <?=Html::radio('OrderReview[delivery]',$model->delivery==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span>
    <?=Html::radio('OrderReview[delivery]',$model->delivery==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span>
    <?=Html::radio('OrderReview[delivery]',$model->delivery==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span>
    </div>
    <div class="help-block"></div>
    </div>

    <label>How was the food?</label>
    <div class="row container-fluid">
    <?=Html::radio('OrderReview[food]',$model->food==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span>
    <?=Html::radio('OrderReview[food]',$model->food==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span>
    <?=Html::radio('OrderReview[food]',$model->food==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span>
    <?=Html::radio('OrderReview[food]',$model->food==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span>
    <?=Html::radio('OrderReview[food]',$model->food==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span>
    </div>
    <div class="help-block"></div>
    </div>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'member_id')->textInput() ?>
    <?= $form->field($model, 'flat')->checkbox() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
