<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use common\models\GoodsOptions;

$coupon = isset($list['coupon']) ? $list['coupon'] : '';
$goods = isset($list['goods']) ? $list['goods'] : '';
?>


<div id="customisableProduct">
	<div id="productDialogDetails" class="customisableDetails">
		<h1><?=Html::encode(isset($coupon->memo) ? 'FREE '.(!empty($coupon->memo) ? $coupon->memo : $coupon->name) : '')?></h1>
		<p class="alert"></p>
	</div>

		<div >
			<div class="actions"><input value="Confirm" class="submit" id="additional_confirm" type="button"></div>
		</div>
		<div class="standardControl addedItems">

		</div>

		<?php

		$i=0;

		$shtml = '';
		$key = [];

		if(!empty($goods)):
			$shtml = '';

			$shtml .= Html::beginTag('div',['class'=>'additional_select']);
			$shtml .= Html::tag('div',Html::tag('h2','Select options:'),['class'=>'selecter-title','id'=>'showOptions']);

			foreach ($goods as $k => $v) {


					$shtml .= '<div class="accessoryGroup">
					<div class="checkboxControl">
					<label class="control-label" for="'.$v['id'].'">
					<div class="control">'.Html::checkbox('options[]',false,['value'=>$v['id'],'id'=>'g_options_'.$v['id']]).'
					'.Html::textInput('goods_options['.$v['id'].']','1',[
						'data-currency'=>'',
						'data-price'=>0,
						'data-name'=>$v['title'],
						'class'=>'g-options-info',
						]).'
					</div>
					<div class="indicator">
					<div class="name">'.$v['title'].'</div>
					</div>
					<div class="description hide"></div>
					</label>
					</div>
					</div>';
				$i++;
			}
			$shtml .= Html::endTag('div');
			echo $shtml;
		endif;

		?>
</div>
