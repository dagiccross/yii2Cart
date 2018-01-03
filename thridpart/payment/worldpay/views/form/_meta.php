<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label($model['name']);?>
	<br/>
	<div class="row" id="show_image_attr_<?=$language?>">
		<?php

        $model['options'] = isset($model['options']) ? $model['options'] : '';
		$options = @unserialize($model['options']) ? unserialize($model['options']) : array('0'=>$model['options']);
		$i = 0;
		$opp_data = [];

        if(!isset($options['instId'])){
            foreach ($options as $k => $v) {
                $opp_data[$v['key']] = $v['value'];
            }
            $options = $opp_data;
        }
		?>
        <div class="form-group">
            <?php echo Html::label('instId:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][instId]',isset($options['instId']) ? $options['instId'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('testMode:','',['class'=>'control-label'])?>
            <?php echo Html::dropDownList('options['.$language.'][testMode]',isset($options['testMode']) ? $options['testMode'] : '',['0'=>'live','100'=>'sanbox'],['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('cartId:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][cartId]',isset($options['cartId']) ? $options['cartId'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('accId1:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][accId1]',isset($options['accId1']) ? $options['accId1'] : '',['class'=>'form-control'])?>
        </div>
	</div>

</div>
<style type="text/css">
    .row-bar{margin: 10px 0px;}
</style>
