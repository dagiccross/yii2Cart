<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\DatePicker;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .upfile-create{padding: 15px;}
</style>
<?php
		Modal::begin([
                'header' => Html::tag('h2',yii::t('app','Goods Category')),
                'toggleButton' => ['label' => Yii::t('app', 'Modify Goods Category'),
                                    'tag' => 'a',
                                    'class'=>'btn btn-success'],
            ]);

                echo '<div class="upfile-create">';
                foreach ($goods_category as $k => $v) {
                    $flat = ($model->goods_cat_id==$v['g_cat_id']);
                    echo '<div class="row form-group">'.Html::radio('g_cat_id',$flat,['value'=>$v['g_cat_id']]).Html::label($v['name']).'</div>';
                }
            ?>
            <div class="form-group">
                <?= Html::button(Yii::t('app', 'Modify'), ['class' => 'btn btn-success','id'=>'goods_cat_modify']) ?>
            </div>
            <?php
                echo '</div>';
            Modal::end();
            ?>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Encoding products').'：')?>
	<?=Html::activeTextInput($model,'sku',['class'=>'form-control','maxlength' => true]);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Picture').'：')?>
	<div class="row">
		<div class="col-xs-6">
		<?=Html::activeTextInput($model,'pic',['class'=>'form-control']);?>
		</div>
		<div class="col-xs-1"><i style="cursor:pointer" onclick="javascript:select_image_attr('#goods-pic')" class="glyphicon glyphicon-picture"></i></div>
	</div>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('app','images').'：')?><i class="glyphicon glyphicon-plus" style="cursor:pointer" id="image_attr"></i>
	<div class="row" id="show_image_attr">
		<?php
		$images = @unserialize($model->images) ? unserialize($model->images) : array('0'=>$model->images);
		$i = 0;
		foreach ($images as $k => $v) {
			$shtml = '<div class="row" id="image_attr_div'.$i.'">';
        	$shtml .='<div class="col-xs-1">'.Yii::t('app','images').$i.':</div>';
        	$shtml .='<div class="col-xs-6" ><input name="image_attr[]" id="image_attr_'.$i.'" class="form-control" value="'.$v.'"/></div>';
        	$shtml .='<div class="col-xs-1"><i style="cursor:pointer" onclick="javascript:select_image_attr(\'#image_attr_'.$i.'\')" class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<i class="glyphicon glyphicon-trash" onclick="javascript:del_div_id(\'#image_attr_div'.$i.'\')" style="cursor:pointer"></i></div>';
        	$shtml .='</div>';
        	$i++;
        	echo $shtml;
		}
		?>
	</div>

</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Price').'：')?>
	<?=Html::activeTextInput($model,'price',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Quanity').'：')?>
	<?=Html::activeTextInput($model,'quanity',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Tag List').'：')?>
	<div class="row">
		
		<?php
		$tag = $goodstotag;
		// var_dump(tagCat('product'));
		foreach (tagCat('product') as $k => $v) {
			$check = !empty($tag)&&in_array($v['id'], $tag) ? true : false;
			echo '<div class="col-xs-2">';
			echo Html::label($v['name'].':');
			echo Html::checkbox('tag[]',$check,['value'=>$v['id']]);
			
			echo '</div>';
		}
		?>
	</div>
	
</div>
<div class="tag-form" style="padding:10px 0px;">
	
	<?=Html::activeCheckbox($model,'status');?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Show Date').'：')?>
	<?=Html::activeTextInput($model,'modifytime',['class'=>'form-control']);?>
</div>
<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Sort Order').'：')?>
	<?=Html::activeTextInput($model,'order_id',['class'=>'form-control']);?>
</div>

<?php $this->beginBlock('image_attr') ?>  
    jQuery(function($) {
    	var i = <?=$i?>;
    	$('#image_attr').click(function() {  
        	var shtml = '<div class="row" id="image_attr_div'+i+'">';
        	shtml +='<div class="col-xs-1"><?=Yii::t('app','images')?>'+i+':</div>';
        	shtml +='<div class="col-xs-6" ><input name="image_attr[]" id="image_attr_'+i+'" class="form-control"/></div>';
        	shtml +='<div class="col-xs-1"><i style="cursor:pointer" onclick="javascript:select_image_attr(\'#image_attr_'+i+'\')" class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<i class="glyphicon glyphicon-trash" onclick="javascript:del_div_id(\'#image_attr_div'+i+'\')" style="cursor:pointer"></i></div>';
        	shtml +='</div>';
        	i++;
        	$("#show_image_attr").append(shtml);
      	});
      	reloadimg(1,0);
    	<!-- $("#inserimg_dialog").show(); -->
		
		$("#goods_cat_modify").click(function(){

            
            var id = $("input[name='g_cat_id']:checked").val();
            console.log(id);
			$.ajax({
				type:"post",
				data:{goods_id:"<?=$model->id?>",g_cat_id:id},
				url:"<?=Url::to(['goods/ajax'])?>",
				success:function(data){
					window.location.reload();
				}
			});
        });
    	
    }); 
    function reloadimg(page,id){
		loadimg('<?php echo Yii::$app->urlManager->createUrl('upfile/json',true);?>',page,id);
	}


<?php $this->endBlock() ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/jq.insertimg.js", ['position'=>\yii\web\View::POS_END,'depends'=> [yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJs($this->blocks['image_attr'], \yii\web\View::POS_END); ?>  