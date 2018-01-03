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
        $opp_data = [];

		if(isset($options['0'])&&isset($options['1'])){
            foreach ($options as $k => $v) {
                $opp_data[$v['key']] = $v['value'];
            }
            $options = $opp_data;
        }

		?>
        <div class="form-group">
            <?php echo Html::label('Paypal Account:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][business]',isset($options['business']) ? $options['business'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Sanbox:','',['class'=>'control-label'])?>
            <?php echo Html::dropDownList('options['.$language.'][test]',isset($options['test']) ? $options['test'] : '',['0'=>'live','1'=>'sanbox'],['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Api Name:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][api_name]',isset($options['business']) ? $options['business'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Api Password:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][api_password]',isset($options['business']) ? $options['business'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Api Key:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][api_key]',isset($options['business']) ? $options['business'] : '',['class'=>'form-control'])?>
        </div>
	</div>

</div>
<style type="text/css">
    .row-bar{margin: 10px 0px;}
</style>
