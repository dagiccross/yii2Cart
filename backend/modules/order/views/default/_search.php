<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use common\models\Order;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <form method="get">

    <div class="row" style="padding: 15px;">
        <div class="col-sm-1">
            <label>Start date</label>
        </div>
        <div class="col-sm-2">
            <?php echo Html::hiddenInput('r','/order/default/index')?>
            <div class="input-group">
                <?php echo Html::textInput('start_date','',['class'=>'form-control','placeholder'=>date('d-m-Y'),'id'=>'BookingForm-date'])?>
                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="BookingForm-date" data-link-format="dd MM yyyy"></i></div>
            </div>


        </div>
        <div class="col-sm-1">
            <label>End&nbsp;&nbsp; date</label>
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <?php echo Html::textInput('end_date','',['class'=>'form-control','placeholder'=>date('d-m-Y'),'id'=>'BookingForm-date-end'])?>
                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="BookingForm-date-end" data-link-format="dd MM yyyy"></i></div>
            </div>

        </div>
        <div class="col-sm-1">
            <label><?php echo Yii::t('info','Payment Status');?></label>
        </div>
        <div class="col-sm-2">
            <?php echo Html::dropDownList('order_status','',array_merge(['0'=>\Yii::t('app','Show All')],Order::paymentStatus()),['class'=>'form-control','placeholder'=>date('d-m-Y H:i:s')])?>
        </div>
        <?php
        $flat = \Yii::$app->request->get('flat');
        if(!empty($flat)){
            echo Html::hiddenInput('flat',$flat);
        }

        ?>
        <div class="col-sm-2">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Filter'), ['class' =>'btn btn-primary']) ?>
            </div>
        </div>

    </div>



    </form>

</div>
<?php $this->registerCssFile(Yii::getAlias('@web')."/web/js/datepicker/bootstrap-datetimepicker.css"); ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/datepicker/bootstrap-datetimepicker.js", ['position'=>\yii\web\View::POS_END,'depends'=> [\backend\assets\AppAsset::className()]]); ?>
<?php $this->beginBlock('search') ?>
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
<?php $this->registerJs($this->blocks['search'], \yii\web\View::POS_END); ?>
