<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(isset($model['name']) ? $model['name'] : '');?>
	<br/>
	<div class="row" id="show_image_attr_<?=$language?>">
		<?php

        $model['options'] = isset($model['options']) ? $model['options'] : '';
		$options = @unserialize($model['options']) ? unserialize($model['options']) : array('0'=>$model['options']);
		$i = 0;
		
		?>
        <div class="form-group">
            <?php echo Html::label('Start Date:','',['class'=>'control-label'])?>
            <div class="input-group">
                <?php echo Html::textInput('options['.$language.'][start]',isset($options['start']) ? $options['start'] : '',['class'=>'form-control','id'=>'start_date'])?>
            <div class="input-group-addon">
                <i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="start_date" data-link-format="yyyy-mm-dd"></i>
            </div>
            </div>  
        </div>

        <div class="form-group">
            <?php echo Html::label('End Date:','',['class'=>'control-label'])?>
            <div class="input-group">
                <?php echo Html::textInput('options['.$language.'][end]',isset($options['end']) ? $options['end'] : '',['class'=>'form-control','id'=>'end_date'])?>
            <div class="input-group-addon">
                <i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="end_date" data-link-format="yyyy-mm-dd"></i>
            </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo Html::label('Message:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][message]',isset($options['message']) ? $options['message'] : '',['class'=>'form-control'])?>
        </div>

        <?= Html::checkbox('options['.$language.'][monday]',isset($options['monday'])&&$options['monday']==1)?>
        Monday&nbsp;
        <?= Html::checkbox('options['.$language.'][tuesday]',isset($options['tuesday'])&&$options['tuesday']==1)?>
        Tuesday&nbsp;
        <?= Html::checkbox('options['.$language.'][wednesday]',isset($options['wednesday'])&&$options['wednesday']==1)?>
        Wednesday&nbsp; 
        <?= Html::checkbox('options['.$language.'][thursday]',isset($options['thursday'])&&$options['thursday']==1) ?>
        Thursday&nbsp; 
        <?= Html::checkbox('options['.$language.'][friday]',isset($options['friday'])&&$options['friday']==1)?>
        Friday&nbsp; 
        <?= Html::checkbox('options['.$language.'][saturday]',isset($options['saturday'])&&$options['saturday']==1)?>
        Saturday&nbsp; 
        <?= Html::checkbox('options['.$language.'][sunday]',isset($options['sunday'])&&$options['sunday']==1) ?>
	    Sunday&nbsp; 
    </div>

</div>
<style type="text/css">
    .row-bar{margin: 10px 0px;}
</style>
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
