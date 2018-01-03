<?php 
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use common\models\GoodsOptions;

$zs = isset($goods_options_group['options']) ? count($goods_options_group['options']) : 0;
$currency = $this->context->getConfig("currency");

?>


<div id="customisableProduct">
	<div id="productDialogDetails" class="customisableDetails">
		<h1><?=Html::encode($goods['title'])?></h1>
		<p><?=$goods['content']?></p>
	</div>
	<form id="customisableProductForm" action="" onsubmit="javascript:CART.addform('#customisableProductForm');return false;">
		<div id="customisableProductSummary">
			<div id="customisableProductPrice" data-price="<?=$goods['price']?>" data-currency="<?=Config::getConfig('currency')?>" class="priceLabel">Total: <?=Config::currencyMoney($goods['price'])?></div>
			<div class="actions"><input value="Add to Basket" class="submit" id="customisableProductSubmit" type="button" data-i="0" data-zs="<?=$zs?>"></div>
		</div>
		<div class="standardControl addedItems">
			<?=Html::hiddenInput('goods_id',$goods['id']);?>
		</div>
		
		<?php

		$i=0;

		$shtml = '';
		$key = [];

		if(isset($goods_options_group['options'])):
			
			foreach ($goods_options_group['options'] as $k => $v) {
				$hide = $i>0 ? 'hide' : '';
				if(empty($v['goods_options'])){
					break;
				}

				$key[] = $v['g_options_group_id'];
				$shtml = '';

				if($v['options_type']=='radio'){
					$shtml .= Html::beginTag('div',['class'=>'requiredAccessories '.$hide,'data-i'=>$i,'data-zs'=>$zs,'id'=>'goods_options_'.$i,'data-type'=>'radio','data-required'=>$v['required']]);
					$shtml .= Html::tag('div',Html::tag('h2','Select options:'),['class'=>'title','id'=>'showOptions']);

					foreach ($v['goods_options'] as $_k => $_v) {
						$price_r = empty($_v['price'])||$_v['price']==0 ? '' : Html::tag('div','+'.$_v['price'],['class'=>'price','data-price'=>$_v['price']]);
						$shtml .= '<div class="accessoryGroup">
						<div class="checkboxControl">
						<label class="control-label" for="'.$v['g_options_group_id'].'">
						<div class="control">'.Html::checkbox('options[]',false,['value'=>$_v['g_options_id'],'id'=>'g_options_'.$_v['g_options_id']]).'
						'.Html::textInput('goods_options['.$_v['g_options_id'].']','1',[
							'data-currency'=>$currency,
							'data-price'=>$_v['price'],
							'data-name'=>$_v['name'],
							'class'=>'g-options-info',
							]).'
						</div>
						<div class="indicator">
						<div class="name">'.$_v['name'].'</div>
						'.$price_r.'
						</div>
						<div class="description hide"></div>
						</label>
						</div>
						</div>';

					}
					$shtml .= Html::endTag('div');

				}else if($v['options_type']=='checkbox'){
					$shtml = Html::beginTag('div',['class'=>'optionsChecked '.$hide,'data-i'=>$i,'data-zs'=>$zs,'id'=>'goods_options_'.$i,'data-type'=>'checkbox','data-required'=>$v['required']]);
					$shtml .= Html::tag('div',Html::tag('h2','Select options:'),['class'=>'title','id'=>'showOptions']);
					$shtml .= Html::beginTag('div',['class'=>'optionalcheckAccessories ']);
					foreach ($v['goods_options'] as $_k => $_v) {
						$shtml .= '<div class="optionalAccessories">
								<div class="standardControl">
									<div class="indicator">
										<div class="name">
											<label for="'.$_v['name'].'">'.$_v['name'].'</label>
										</div>
										<div class="price">'.Config::currencyMoney($_v['price']).'</div>
									</div>
									<div class="control">
										<a class="addButton" onclick="javascript:CART.optionsAction(\''.$_v['g_options_id'].'\',\'add\');">+</a>
									</div>
								</div>
								<div class="standardControl addedAccessories" style="display:none;">
									<div class="remove">
										<a class="removeButton" onclick="javascript:CART.optionsAction(\''.$_v['g_options_id'].'\',\'cut\');"></a>
									</div>
									<div class="amount">
									'.Html::checkbox('options[]',false,[
										'value'=>$_v['g_options_id'],
										'id'=>'g_options_'.$_v['g_options_id'],
										'class'=>'hide',
										]).'
									'.Html::textInput('goods_options['.$_v['g_options_id'].']','0',[
										'data-currency'=>$currency,
										'data-price'=>$_v['price'],
										'data-name'=>$_v['name'],
										'class'=>'g-options-info',
										]).'
									 x 
									</div>
									<div class="price">'.Config::currencyMoney($_v['price']).'</div>
								</div>
							</div>';
					}
					$shtml .= Html::endTag('div');
					$shtml .= Html::endTag('div');
				}
				$i++;
				
				echo $shtml;
			}
		endif;
			
		?>
		<script type="text/javascript">
			var g_options_group_key = <?=json_encode($key)?>; 
		</script>
	</form>
</div>

