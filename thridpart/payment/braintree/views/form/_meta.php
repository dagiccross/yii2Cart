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
            <?php echo Html::label('Merchant ID:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][merchant]',isset($options['merchant']) ? $options['merchant'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Public Key:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][public_key]',isset($options['public_key']) ? $options['public_key'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Private Key:','',['class'=>'control-label'])?>
            <?php echo Html::textInput('options['.$language.'][private_key]',isset($options['private_key']) ? $options['private_key'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group hide">
            <?php echo Html::label('Default Currency:', '', ['class' => 'control-label']) ?>
            <?php echo Html::textInput('options['.$language.'][currency_code]',isset($options['currency_code']) ? $options['currency_code'] : '',['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Sanbox:','',['class'=>'control-label'])?>
            <?php echo Html::dropDownList('options['.$language.'][environment]',isset($options['environment']) ? $options['environment'] : '',['sandbox'=>'Sandbox','production'=>'Production'],['class'=>'form-control'])?>
        </div>
        <div class="form-group hide">
            <?php echo Html::label('Transaction Method:','',['class'=>'control-label'])?>
            <?php echo Html::dropDownList('options['.$language.'][transaction_method]',isset($options['transaction_method']) ? $options['transaction_method'] : '',['authorization'=>'Authorization','charge'=>'Charge'],['class'=>'form-control'])?>
        </div>
        <div class="form-group hide">
            <?php echo Html::label('Force TLS 1.2:','',['class'=>'control-label'])?>
            <?php echo Html::dropDownList('options['.$language.'][force_tls12]',isset($options['force_tls12']) ? $options['force_tls12'] : '',['1'=>'Yes','0'=>'No'],['class'=>'form-control'])?>
        </div>
        <div class="form-group">
            <?php echo Html::label('Debug Mode:','',['class'=>'control-label'])?>
            <?php echo Html::dropDownList('options['.$language.'][debug_mode]',isset($options['debug_mode']) ? $options['debug_mode'] : '',['1'=>'Yes','0'=>'No'],['class'=>'form-control'])?>
        </div>
	</div>

</div>
<style type="text/css">
    .row-bar{margin: 10px 0px;}
</style>
