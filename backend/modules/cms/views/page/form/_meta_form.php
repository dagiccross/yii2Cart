<?php

use yii\helpers\Html;
use thridpart\kindeditor\KindEditor;
/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Name').'：')?>
	<?=Html::textInput('meta['.$language.'][title]',isset($model['title']) ? $model['title'] : '',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('app','images').'：')?><i class="glyphicon glyphicon-plus image_attr" style="cursor:pointer" data-id="<?=$language?>"></i>
	<div class="row" id="show_image_attr_<?=$language?>">
		<?php
        $model['image'] = isset($model['image']) ? $model['image'] : '';
		$images = @unserialize($model['image']) ? unserialize($model['image']) : array('0'=>$model['image']);
		$i = 0;
		foreach ($images as $k => $v) {
			$shtml = '<div class="row" id="image_attr_div_'.$language.$i.'">';
        	$shtml .='<div class="col-xs-1">'.Yii::t('app','images').$i.':</div>';
        	$shtml .='<div class="col-xs-6" ><input name="image_attr['.$language.'][]" id="image_attr_'.$language.$i.'" class="form-control" value="'.$v.'"/></div>';
        	$shtml .='<div class="col-xs-1"><i style="cursor:pointer" onclick="javascript:select_image_attr(\'#image_attr_'.$language.$i.'\')" class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<i class="glyphicon glyphicon-trash" onclick="javascript:del_image_attr(\''.$language.$i.'\')" style="cursor:pointer"></i></div>';
        	$shtml .='</div>';
        	$i++;
        	echo $shtml;
		}
		?>
	</div>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Description').'：')?>
	<?=Html::textArea('meta['.$language.'][description]',isset($model['description']) ? $model['description'] : '',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Content').'：')?>
	<?php
	echo KindEditor::widget(['name'=>'meta['.$language.'][content]',
							'id'=>$language.'content',
							'value'=>isset($model['content']) ? $model['content'] : '',
							'class'=>'form-control']);
	?>
</div>


<?php $this->beginBlock('image_attr') ?>
    jQuery(function($) {
    	var i = <?=$i?>;
    	$('.image_attr').click(function() {
            var language = $(this).attr("data-id");
        	var shtml = '<div class="row" id="image_attr_div_'+language+''+i+'">';
        	shtml +='<div class="col-xs-1"><?=Yii::t('app','images')?>'+i+':</div>';
        	shtml +='<div class="col-xs-6" ><input name="image_attr['+language+'][]" id="image_attr_'+language+''+i+'" class="form-control"/></div>';
        	shtml +='<div class="col-xs-1"><i style="cursor:pointer" onclick="javascript:select_image_attr(\'#image_attr_'+language+''+i+'\')" class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<i class="glyphicon glyphicon-trash" onclick="javascript:del_image_attr(\''+language+''+i+'\')" style="cursor:pointer"></i></div>';
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
