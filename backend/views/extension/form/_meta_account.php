<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(\Yii::t('app','Name').'：')?>
    <?=Html::textInput('meta['.$language.'][name]',isset($model['name']) ? $model['name'] : '',['class'=>'form-control']);?>
</div>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Options'))?><i class="glyphicon glyphicon-plus image_attr" style="cursor:pointer" data-id="<?=$language?>"></i>
	<div class="row" id="show_image_attr_<?=$language?>">
		<?php
        $model['options'] = isset($model['options']) ? $model['options'] : '';
		$options = @unserialize($model['options']) ? unserialize($model['options']) : array('0'=>$model['options']);
		$i = 0;
		foreach ($options as $k => $v) {
			$shtml = '<div class="row" id="image_attr_div_'.$language.$i.'">';
        	$shtml .='<div class="col-xs-1">'.$i.':</div>';
        	$shtml .='<div class="col-xs-4" >'.Yii::t('app','Key').':<input name="options['.$language.']['.$i.'][key]" class="form-control" value="'.(isset($v['key']) ? $v['key'] : '').'"/></div>';
        	$shtml .='<div class="col-xs-4" >'.Yii::t('app','Value').':<input name="options['.$language.']['.$i.'][value]" class="form-control" value="'.(isset($v['value']) ? $v['value'] : '').'"/></div>';
            $shtml .='<div class="col-xs-1"><i class="glyphicon glyphicon-trash" onclick="javascript:del_image_attr(\''.$language.$i.'\')" style="cursor:pointer"></i></div>';
        	$shtml .='</div>';
        	$i++;
        	echo $shtml;
		}
		?>
	</div>

</div>
<?php $this->beginBlock('image_attr') ?>  
    jQuery(function($) {
    	var i = <?=$i?>;
    	$('.image_attr').click(function() {
            var language = $(this).attr("data-id"); 
        	var shtml = '<div class="row" id="image_attr_div_'+language+''+i+'">';
        	shtml +='<div class="col-xs-1">'+i+':</div>';
        	shtml +='<div class="col-xs-4" ><?=Yii::t('app','Key')?>:<input name="options['+language+']['+i+'][key]" class="form-control" /></div>';
            shtml +='<div class="col-xs-4" ><?=Yii::t('app','Value')?>:<input name="options['+language+']['+i+'][value]" class="form-control" /></div>';
            shtml +='<div class="col-xs-1"><i class="glyphicon glyphicon-trash" onclick="javascript:del_image_attr(\''+language+''+i+'\')" style="cursor:pointer"></i></div>';
        	shtml +='</div>';
        	i++;
        	$("#show_image_attr_"+language).append(shtml);
      	});
      	reloadimg(1,0);
    	<!-- $("#inserimg_dialog").show(); -->

    	
    }); 
    function reloadimg(page,id){
		loadimg('<?php echo Yii::$app->urlManager->createUrl('upfile/json',true);?>',page,id);
	}


<?php $this->endBlock() ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/jq.insertimg.js", ['position'=>\yii\web\View::POS_END,'depends'=> [yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJs($this->blocks['image_attr'], \yii\web\View::POS_END); ?>  