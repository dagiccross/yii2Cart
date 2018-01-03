<?php

use yii\helpers\Html;
use thridpart\kindeditor\KindEditor;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form" style="padding:10px 0px;">
	<?=Html::label(Yii::t('info','Add Attributes'))?><i class="glyphicon glyphicon-plus jia_attr" style="cursor:pointer" data-id="<?=$language?>"></i>
	
</div>
<div class="row">
	<table class="table table-bordered table-hover" id="J_attr_<?=$language?>">
		<tr>
			<th><?=Html::label('#')?></th>
			<th><?=Html::label(Yii::t('info','Attributes Name'))?></th>
			<th><?=Html::label(Yii::t('info','Attributes Value'))?></th>
			<th><?=Html::label(Yii::t('info','Sales'))?></th>
			<th><?=Html::label(Yii::t('app','Action'))?></th>
		</tr>
		<?php 
		$i = 0;
		$attr_data = @unserialize($attr['attr_value']) ? unserialize($attr['attr_value']) : array();
		if(is_array($attr_data)):
		foreach ($attr_data as $k => $v) {

			// var_dump($v);exit();
			$v["options"] = isset($v['value']) ? $v['value'] : (isset($v["options"]) ? $v["options"] : '');
			echo '<tr id="attr_tr_'.$language.$i.'">
				<td>'.$i.'</td>
				<td>'.Html::textInput("goods_attr[".$language."][".$i."][name]",$v["name"],["class"=>"form-control"]).'</td>
				<td>'.Html::textInput("goods_attr[".$language."][".$i."][options]",$v["options"],["class"=>"form-control"]).'</td>
				<td>'.Html::checkBox("goods_attr[".$language."][".$i."][sales]",(isset($v["sales"])&&$v["sales"]==1),["class"=>"form-control",'value'=>'1']).'</td>
				<td><i class="glyphicon glyphicon-trash" onclick="javascript:del_attr('.$language.$i.')" style="cursor:pointer"></i></td>
			</tr>';
			$i++;
		}
		endif;
		?>
	</table>
</div>
<?php
$script ='
	jQuery(function($){
		var i ='.$i.';
		$(".jia_attr").click(function(){
			var language = $(this).attr("data-id");
			var html = "<tr id=\'attr_tr_"+language+""+i+"\'><td>"+i+"</td>";
			html += "<td><input name=\'goods_attr["+language+"]["+i+"][name]\' class=\'form-control\' /></td>";
			html += "<td><input name=\'goods_attr["+language+"]["+i+"][options]\' class=\'form-control\' /></td>";
			html += "<td><input type=\'checkbox\' name=\'goods_attr["+language+"]["+i+"][sales]\' class=\'form-control\' value=\'1\'/></td>";
			html += "<td><i class=\'glyphicon glyphicon-trash\' onclick=\'javascript:del_attr(\""+language+""+i+"\")\' style=\'cursor:pointer\'></i></td></tr>";
			$("#J_attr_"+language).append(html);
			i++;
		});
		
		
	});
	function del_attr(id){
		document.getElementById("attr_tr_"+id).remove();
	}
';
$this->registerJs($script, View::POS_END);
?>