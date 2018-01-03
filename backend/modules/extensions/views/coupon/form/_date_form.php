<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Config;
/* @var $this yii\web\View */
/* @var $model common\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

    <?= $form->field($model, 'coup_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($model::getCoupType()) ?>

    <?= $form->field($model, 'coup_value')->textInput(['maxlength' => true , 'placeholder'=>0.00]) ?>

    <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coup_quanity')->textInput() ?>

    <?php // $form->field($model, 'start_date')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?=Html::activeLabel($model,'start_date')?>
        <div class="input-group">
            <?= Html::activeTextInput($model,'start_date',['class'=>'form-control'])?>
            <div class="input-group-addon">
                <i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="coupon-start_date" data-link-format="dd MM yyyy"></i>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?=Html::activeLabel($model,'end_date')?>
        <div class="input-group">
            <?= Html::activeTextInput($model,'end_date',['class'=>'form-control'])?>
            <div class="input-group-addon">
                <i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="coupon-end_date" data-link-format="dd MM yyyy"></i>
            </div>
        </div>
    </div>

    <?php // $form->field($model, 'end_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'flat_date')->checkbox() ?>

    <?= $form->field($model, 'flat_coup')->dropDownList($model::getCoupFlat()) ?>

    <?= $form->field($model, 'memo')->textArea() ?>

    <?= Html::activeCheckbox($model, 'monday')?>

    <?= Html::activeCheckbox($model, 'tuesday')?>

    <?= Html::activeCheckbox($model, 'wednesday')?>

    <?= Html::activeCheckbox($model, 'thursday') ?>

    <?= Html::activeCheckbox($model, 'friday')?>

    <?= Html::activeCheckbox($model, 'saturday')?>

    <?= Html::activeCheckbox($model, 'sunday') ?>


<?php $this->registerCssFile(Yii::getAlias('@web')."/web/js/datepicker/bootstrap-datetimepicker.css"); ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/datepicker/bootstrap-datetimepicker.js", ['position'=>\yii\web\View::POS_END,'depends'=> [\backend\assets\AppAsset::className()]]); ?>
<?php $this->beginBlock('coupon_date') ?>
jQuery(document).ready(function () {
$('.date-selecter').datetimepicker({
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    showMeridian: 1,
    bootcssVer:3
});
});

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['coupon_date'], \yii\web\View::POS_END); ?>
