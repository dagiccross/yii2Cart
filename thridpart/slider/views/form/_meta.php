<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(isset($model['name']) ? $model['name'] : '');?>
	<br/>
	<?=Html::label(\Yii::t('app','Images').'：')?><i class="glyphicon glyphicon-plus image_attr" style="cursor:pointer" data-id="<?=$language?>"></i>
	<div class="row" id="show_image_attr_<?=$language?>">
		<?php

        $model['options'] = isset($model['options']) ? $model['options'] : '';
		$images = @unserialize($model['options']) ? unserialize($model['options']) : array('0'=>$model['options']);
		$i = 0;
		foreach ($images as $k => $v) {
			$shtml = '<div class="row row-bar" id="image_attr_div_'.$language.$i.'">';
        	$shtml .='<div class="col-xs-1">'.Yii::t('app','Images').$i.':</div>';
        	$shtml .='<div class="col-xs-6" ><input name="options['.$language.']['.$i.'][images]" id="image_attr_'.$language.$i.'" class="form-control" value="'.(isset($v['images']) ? $v['images'] : '') .'"/></div>';
            $shtml .='<div class="col-xs-5"><i style="cursor:pointer" onclick="javascript:select_image_attr(\'#image_attr_'.$language.$i.'\')" class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<i class="glyphicon glyphicon-trash" onclick="javascript:del_image_attr(\''.$language.$i.'\')" style="cursor:pointer"></i></div>';
        	$shtml .='<div class="col-xs-7" style="padding-left: 10%;">'.Yii::t('app','Url').'<input name="options['.$language.']['.$i.'][url]" value="'.(isset($v['url']) ? $v['url'] : '') .'" class="form-control"/></div>';
            $shtml .='<div class="col-xs-6" style="padding-left: 10%;">'.Yii::t('app','Options').'<textarea name="options['.$language.']['.$i.'][options]" class="form-control">'.(isset($v['options']) ? $v['options'] : '').'</textarea></div>';
            $shtml .='</div>';
        	$i++;
        	echo $shtml;
		}
		?>
	</div>

</div>
<style type="text/css">
    .row-bar{margin: 10px 0px;}
</style>
<?php $this->beginBlock('image_attr') ?>  
    jQuery(function($) {
    	var i = <?=$i?>;
    	$('.image_attr').click(function() {
            var language = $(this).attr("data-id"); 
        	var shtml = '<div class="row row-bar" id="image_attr_div_'+language+''+i+'">';
        	shtml +='<div class="col-xs-1"><?=Yii::t('app','Images')?>'+i+':</div>';
        	shtml +='<div class="col-xs-6" ><input name="options['+language+']['+i+'][images]" id="image_attr_'+language+''+i+'" class="form-control"/></div>';
        	shtml +='<div class="col-xs-5"><i style="cursor:pointer" onclick="javascript:select_image_attr(\'#image_attr_'+language+''+i+'\')" class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<i class="glyphicon glyphicon-trash" onclick="javascript:del_image_attr(\''+language+''+i+'\')" style="cursor:pointer"></i></div>';
        	shtml +='<div class="col-xs-7" style="padding-left: 10%;"><?=Yii::t('app','Url')?><input name="options['+language+']['+i+'][url]" class="form-control"/></div>';
            shtml +='<div class="col-xs-6" style="padding-left: 10%;"><?=Yii::t('app','Options')?><textarea name="options['+language+']['+i+'][options]" class="form-control"></textarea></div>';
            shtml +='</div>';
        	i++;
        	$("#show_image_attr_"+language).append(shtml);
      	});
    }); 


<?php $this->endBlock() ?>

<?php $this->registerJs($this->blocks['image_attr'], \yii\web\View::POS_END); ?>