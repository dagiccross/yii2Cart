<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

<?php
	if(isset($model['sitename'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Site Name'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['sitename']['id'].']',$model['sitename']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
//finding map_calculation if exist, display, if not createCommand;
$command = Yii::$app->db->createCommand("SELECT `options`, `values` FROM yii2_config WHERE options='map_calculation' ")->queryAll();


if ($command[0]['options'] === 'map_calculation'){
	// echo $command[0]['options'],' '.$command[0]['values'];
	if(isset($model['map_calculation'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Map Calculation Method'),'',['class'=>'control-label']);
		echo Html::dropDownList('Config['.$model['map_calculation']['id'].']',$model['map_calculation']['values'],['0'=>'City or Town (Select town to count money, use Full postcode)','1'=>'Postcode (Select postcode to count money, use Half postcode)'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
} else {
	$sql = "insert into yii2_config (`options`, `values`) values ('map_calculation', '0')";

	$res = Yii::$app->db->createCommand($sql)->execute();
	echo "New feature (Map Calculation) not found, creating new data... Please refresh this page now.";
 };

 ?>
 <?php
 //finding webtemp if exist, display, if not createCommand;
 $command = Yii::$app->db->createCommand("SELECT `options`, `values` FROM yii2_config WHERE options='webtemp' ")->queryAll();


 if ($command[0]['options'] === 'webtemp'){
 	// echo $command[0]['options'],' '.$command[0]['values'];
 	if(isset($model['webtemp'])){
 		echo Html::beginTag('div',['class'=>'form-group']);
 		echo Html::label(\Yii::t('label','Web Template'),'',['class'=>'control-label']);
 		echo Html::dropDownList('Config['.$model['webtemp']['id'].']',$model['webtemp']['values'],['0'=>'Default','1'=>'No Online Ordering', '2'=>'Added Pictures on Menu'],['class'=>'form-control']);
 		echo Html::endTag('div');
 	}
 } else {
 	$sql = "insert into yii2_config (`options`, `values`) values ('webtemp', '0')";

 	$res = Yii::$app->db->createCommand($sql)->execute();
 	echo "New feature (Web Template) not found, creating new data... Please refresh this page now.";
  };

  ?>

<?php
	if(isset($model['cache'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Cache'),'',['class'=>'control-label']);
		echo ' ';
		echo Html::checkBox('Config['.$model['cache']['id'].']',$model['cache']['values']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['logo'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Logo'),'',['class'=>'control-label']);
		echo ' <i style="cursor:pointer" onclick="javascript:select_image_attr(\'#logo-picture\')" class="glyphicon glyphicon-picture"></i>';
		echo Html::textInput('Config['.$model['logo']['id'].']',$model['logo']['values'],['class'=>'form-control','id'=>'logo-picture']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['seo_title'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Seo Title'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['seo_title']['id'].']',$model['seo_title']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['seo_keywords'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Seo Keywords'),'',['class'=>'control-label']);
		echo Html::textArea('Config['.$model['seo_keywords']['id'].']',$model['seo_keywords']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['seo_content'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Seo Content'),'',['class'=>'control-label']);
		echo Html::textArea('Config['.$model['seo_content']['id'].']',$model['seo_content']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<!-- <?php
	if(isset($model['copyright'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Copyright'),'',['class'=>'control-label']);
		echo Html::textArea('Config['.$model['copyright']['id'].']',$model['copyright']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?> -->

<?php
	if(isset($model['Collection_Time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Collection Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Collection_Time']['id'].']',$model['Collection_Time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['Delivery_Time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Delivery Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Delivery_Time']['id'].']',$model['Delivery_Time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['delivery_flat'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Delivery Options (Delivery = 0, NO Delivery = 1)'),'',['class'=>'control-label']);
		echo Html::dropDownList('Config['.$model['delivery_flat']['id'].']',$model['delivery_flat']['values'],['0'=>'Delivery','1'=>'Not doing Delivery'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['Open_time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Open Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Open_time']['id'].']',$model['Open_time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>
<?php
	if(isset($model['Close_time'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Close Time'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Close_time']['id'].']',$model['Close_time']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>
<?php
	if(isset($model['Minimum'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Minimum Consumption'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Minimum']['id'].']',$model['Minimum']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['Minpay'])&&0){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Minimum Payment'),'',['class'=>'control-label']);
		echo Html::textInput('Config['.$model['Minpay']['id'].']',$model['Minpay']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['start_cookies'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Start Cookies Information'),'',['class'=>'control-label']);
		echo Html::textArea('Config['.$model['start_cookies']['id'].']',$model['start_cookies']['values'],['class'=>'form-control']);
		echo Html::endTag('div');
	}
?>

<?php
	if(isset($model['downloadpdf'])){
		echo Html::beginTag('div',['class'=>'form-group']);
		echo Html::label(\Yii::t('label','Menu Download (PDF)'),'',['class'=>'control-label']);
		echo ' <i style="cursor:pointer" onclick="javascript:select_image_attr(\'#downloadpdf-picture\')" class="glyphicon glyphicon-picture"></i>';
		echo Html::textInput('Config['.$model['downloadpdf']['id'].']',$model['downloadpdf']['values'],['class'=>'form-control','id'=>'downloadpdf-picture']);
		echo Html::endTag('div');
	}
?>

</div>
