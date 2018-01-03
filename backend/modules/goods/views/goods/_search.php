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
            <label><?php echo \Yii::t('info','Name');?></label>
        </div>
        <div class="col-sm-2">
            <?php echo Html::hiddenInput('r',\Yii::$app->request->get('r'))?>
            <div class="input-group">
                <?php echo Html::textInput('name','',['class'=>'form-control','id'=>'BookingForm-date'])?>
                <!-- <div class="input-group-addon"><i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="BookingForm-date" data-link-format="yyyy-mm-dd"></i></div> -->
            </div>

            
        </div>
        <div class="col-sm-1">
            <label><?php echo \Yii::t('info','Sku');?></label>
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <?php echo Html::textInput('sku','',['class'=>'form-control','id'=>'BookingForm-date-end'])?>
                <!-- <div class="input-group-addon"><i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="BookingForm-date-end" data-link-format="yyyy-mm-dd"></i></div> -->
            </div>
            
        </div>
        <div class="col-sm-1">
            <label><?php echo Yii::t('info','Status');?></label>
        </div>
        <div class="col-sm-2">
            <?php echo Html::dropDownList('status','',array_merge([''=>\Yii::t('app','Please Select')],['0'=>'no show','1'=>'show']),['class'=>'form-control','placeholder'=>date('Y-m-d H:i:s')])?>
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
    showMeridian: 1
});
});

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['search'], \yii\web\View::POS_END); ?>
