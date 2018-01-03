<?php

use yii\helpers\Html;
use yii\bootstrap\DatePicker;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */

?>
<?php
$i =0;
// var_dump($featureattr);
$label['size']= '';
$label['spec']= '';
$spec = '';
$size = '';
// var_dump($goodsfeature);

foreach ($feature as $k => $v) {
	echo '<div class="tag-form" style="padding:10px 0px;">';
	echo Html::label($v['name'].'：');
	echo '<div class="row">';
	if($i==0){
		$label['size']= $v['name'];
		$size = $k;
		$input_name = 'size';
	}else{
		$label['spec']= $v['name'];
		$spec = $k;
		$input_name = 'spec';
	}


	foreach ($featureattr as $_k => $_v) {
		
		$checked = isset($goodsfeature[$k][$_v['id']]) ? true : false;
		$options = isset($goodsfeature[$k][$_v['id']]['options']) ? $goodsfeature[$k][$_v['id']]['options'] : $_v['name'];
		
		
		if($_v['feature']==$k){
			echo '<div class="col-md-2">';
			echo Html::checkbox('feature['.$k.'][]',$checked,['value'=>$_v['id'],'class'=>'check_'.$input_name]);
			echo Html::textInput('goodsfeature['.$k.']['.$_v['id'].']',$options,['id'=>$input_name.'_'.$_v['id']]);
			echo '</div>';
		}

	}
	echo '</div></div>';
	$i++;

}

?>

<div class="row">
	<table class="table table-bordered table-hover" id="J_sku">
		<tr>
			<th><?=Html::label($label['spec'])?></th>
			<th><?=Html::label($label['size'])?></th>
			<th><?=Html::label(Yii::t('app','Price'))?></th>
			<th><?=Html::label(Yii::t('app','Quanity'))?></th>
			<th><?=Html::label(Yii::t('app','Sku No.'))?></th>
		</tr>
		<?php
		$i = 0;$j = 0;
		$sku = array();
		if(isset($goodsfeature[$spec])&&isset($goodsfeature[$size])){
			foreach ($goodsfeature[$spec] as $k => $v) {

				$sku['tr_id'] = $k;
				$sku['price'] = '';
				$sku['quanity'] = '';
				$sku['feature_id'] = '';
				$sku['label'] = '';
				$sku['skuno'] = '';

				foreach ($goodsfeature[$size] as $_k => $_v) {
					$id = $sku['tr_id'].':'.$_k;
					$id2 = $sku['tr_id'].'_'.$_k;
					$sku['price'] .= Html::textInput('sku['.$id.'][price]',(isset($goodssku[$id]['price']) ? $goodssku[$id]['price'] : ''),['id'=>'feature_price_'.$id2,'class'=>'form-control']);
					$sku['quanity'] .= Html::textInput('sku['.$id.'][quanity]',(isset($goodssku[$id]['quanity']) ? $goodssku[$id]['quanity'] : ''),['id'=>'feature_quanity_'.$id2,'class'=>'form-control']);
					$sku['feature_id'] = Html::hiddenInput('sku['.$id.'][feature_arr]',(isset($goodssku[$id]['feature_arr']) ? $goodssku[$id]['feature_arr'] : $id),['id'=>'feature_id_'.$id2]);
					$sku['label'] .= '<label id="label_'.$id2.'" class="form-control label_tr">'.$_v['options'].$sku['feature_id'].'</label>';
					$sku['skuno'] .= Html::textInput('sku['.$id.'][skuno]',(isset($goodssku[$id]['skuno']) ? $goodssku[$id]['skuno'] : ''),['id'=>'feature_skuno_'.$id2,'class'=>'form-control']);
				}

				echo '<tr id="tr_'.$sku['tr_id'].'">';
				echo '<td class="mid_tr">'.$v["options"].'</td>';
				echo '<td>'.$sku['label'].'</td>';
				echo '<td>'.$sku['price'].'</td>';
				echo '<td>'.$sku['quanity'].'</td>';
				echo '<td>'.$sku['skuno'].'</td>';
				echo '</tr>';
			}
		}
		
		?>
	</table>
</div>

<style type="text/css">
	.label_tr{margin-bottom: 0;}
	.mid_tr{vertical-align: middle !important;}
</style>
<?php
$script ='
	jQuery(function($){
		var size_leng = 0;
		var spec_leng = 0;
		//当点击尺寸时
		$(".check_size:checkbox").click(function(){
			var size_id = $(this).val();
			var action = "del";
			if ($(this).attr("checked")) {
				$(this).removeAttr("checked");
				size_leng--;
			}else{
				$(this).attr("checked",true);
				action = "add";
				size_leng++;
			}
			actionSize(size_id,action);
		});
		// 当点击规格时
		$(".check_spec:checkbox").click(function(){
			var spec_id = $(this).val();
			var action = "del";
			if ($(this).attr("checked")) {
				$(this).removeAttr("checked");
				spec_leng--;
			}else{
				$(this).attr("checked",true);
				action = "add";
				spec_leng++;
			}

			actionSpec(spec_id,action);
		});

		function actionSize(size,action){
			spec_id = "null";
			$(".check_spec:checkbox").each(function(){
				if($(this).attr("checked")){
					spec_id = $(this).val();
					actionTd(spec_id,size,action);
				}
				
			});
			
			
		}

		function actionSpec(spec,action){
			var size_id = "null";
			var table_id = "#J_sku";

			actionTr(table_id,spec,action);

			$(".check_size:checkbox").each(function(){
				if($(this).attr("checked")){
					size_id = $(this).val();
					actionTd(spec,size_id,action);
				}

			});
			
		}

		function actionTr(table_id,tr_id,action){
			if(action=="add"){
				console.log(table_id);
				var td_1_name = $("#spec_"+tr_id).val();
				var tr_data = "<tr id=\'tr_"+tr_id+"\'><td class=\'mid_tr\'>"+td_1_name+"</td><td></td><td></td><td></td><td></td></tr>";
				$(table_id).find("tbody").append(tr_data);
			}else{
				$(table_id).find("#tr_"+tr_id).remove();
				$("#tr_"+tr_id).remove();
			}
		}
		function actionTd(tr_id,td_id,action){
			
			var id = tr_id+":"+td_id;
			var id2 = tr_id+"_"+td_id;

			if(action=="add"){
				var shtml_price = "<input type=\'text\' id=\'feature_price_"+id2+"\' name=\'sku["+id+"][price]\' class=\'form-control\'/>";
				var shtml_quanity = "<input type=\'text\' id=\'feature_quanity_"+id2+"\' name=\'sku["+id+"][quanity]\' class=\'form-control\'/>";
				var shtml_id = "<input type=\'hidden\' id=\'feature_id_"+id2+"\' name=\'sku["+id+"][feature_arr]\' value=\'"+id+"\'/>";
				var shtml_label = "<label id=\'label_"+id2+"\' class=\'form-control label_tr\'>"+$("#size_"+td_id).val()+shtml_id+"</label>";
				var shtml_skuno = "<input type=\'text\' id=\'feature_skuno_"+id2+"\' name=\'sku["+id+"][skuno]\' class=\'form-control\'/>";
				
				$("#tr_"+tr_id).find("td").eq(1).append(shtml_label);
				$("#tr_"+tr_id).find("td").eq(2).append(shtml_price);
				$("#tr_"+tr_id).find("td").eq(3).append(shtml_quanity);
				$("#tr_"+tr_id).find("td").eq(4).append(shtml_skuno);
			}else{
				$("#tr_"+tr_id).find("#label_"+id2).remove();
				$("#tr_"+tr_id).find("#feature_price_"+id2).remove();
				$("#tr_"+tr_id).find("#feature_quanity_"+id2).remove();
				$("#tr_"+tr_id).find("#feature_skuno_"+id2).remove();
			}
		}
	});
';
$this->registerJs($script, View::POS_END);
?>
