<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\models\Config;
?>
<?php if(!isset($list['payment'])):?>

<div id="cart-info" class="home-cat">
<form name="cart_order" action="<?=Url::to(['/cart/checkout'])?>" class="cart-checkout-from" method="post">
	<?php echo Html::hiddenInput('cart[additional]','');?>
	<div class="right-t1" style="margin-bottom:-20px;padding:-15px;">
		<div id="menuSwitcher">
	    <div class="collect">
	    	<?=Html::tag('p',Html::radio('cart[send]',$data['send']=='collection',['value'=>'collection','onchange'=>'javascript:CART.changeSend(this)']).'Collection');?>
	      <p><?=$send_time['collection']?></p>
	    </div>
	    <div class="deliver">
	    	<?=Html::tag('p',Html::radio('cart[send]',$data['send']=='deliver',['value'=>'deliver','onchange'=>'javascript:CART.changeSend(this)']).'Delivery');?>
	      <p><?=$send_time['deliver']?></p>
	    </div>
		</div>
	    <div class="clearfix"></div>
	    <div style="display: none;" id="menuSwitcherAlert">
		    <p id="menuSwitcherAlertMessage" class="warning">
		        This will take you to a different basket. Your current basket will be saved.<br>
		        <input id="confirmMenuSwitch" value="Continue" class="aLink" type="button"> or <input id="cancelMenuSwitch" value="Cancel" class="aLink" type="button">
		    </p>
		</div>
	</div>

	<div class="order">
		<?php
		if(!empty($cart)&&isset($list['list_free_up'])&&!empty($list['list_free_up'])):?>
		<?php
			if($list['list_free_up']['type']==0){
				$list['list_free_up']['coup_value'] = (1-$list['list_free_up']['coup_value'])*100;
				$list['list_free_up']['coup_value'] .='%';
			}else{
				$list['list_free_up']['coup_value'] = $list['list_free_up']['coup_value'];
			}
			$message_free_up = $list['list_free_up']['coup_value'].' off today on orders over '.Config::currencyMoney($list['list_free_up']['total']);
			echo Html::tag('div',Html::tag('p',$message_free_up),['class'=>'off-show']);
		?>
		<?php endif;?>

		<?php
		if(!empty($cart)&&isset($list['free_goods'])&&!empty($list['free_goods'])):?>
		<?php

			$message_free_up = 'FREE '.$list['free_goods']['memo'].' for orders over '.Config::currencyMoney($list['free_goods']['total']);
			echo Html::tag('div',Html::tag('p',$message_free_up),['class'=>'off-show']);
			echo Html::hiddenInput('cart[free_goods]',$list['free_goods']['coup_id']);
		?>
		<?php endif;?>
			<div>
	    <!-- <div class="order-cart"> -->
				<span style="font-size:15px;">Your Order</span>
	  		<!-- <span class="cart"><img src="<?php echo IMG_URL?>/cart.png"></span><span class="order-title">Your Order</span> -->
	      <div class="clearfix"></div>
	    </div>
	    <?php
	    $total = 0;
	    $all_items = count($cart);
	    if(!empty($cart)&&is_array($cart)):


	    	$s_html = '<div style="overflow-y:scroll;overflow-x:hidden;max-height:33vh;" class="order-menu"><ul class="order-list row" style="margin-left:5px;font-size:12px;padding-left:-5px;">';
	    	foreach ($cart as $k => $v) {
	    		$goods = $v;

	    		$goods_quanity = $goods['quanity'].'&nbsp;x&nbsp;';

	    		// 初始化子产品的內容
	    		$child_html = '';
	    		if(isset($v['child'])){
	    			foreach ($v['child'] as $_k => $_v) {

	    				if($_v['price_prefix']=='+'){
	    					$goods['price'] += $_v['price'];
	    				}else{
	    					$goods['price'] -= $_v['price'];
	    				}

	    				$sshtml = Html::tag('div',$_v['name'],['class'=>'order-name1']);
	    				$sshtml .= Html::tag('div','',['class'=>'order-quanity']);
	    				$child_html .= Html::tag('div',$sshtml,['class'=>'orderoption']);
	    				$child_html .= '<div class="clearfix"></div>';
	    			}
	    		}

	    		// 初始化多选项目时
	    		$options_html = '';

	    		if(isset($v['options'])){
	    			foreach ($v['options'] as $_k => $_v) {
	    				if($_v['group']['options_type']=='radio'){

	    					if($_v['price_prefix']=='+'){
		    					$goods['price'] += $_v['price'];
		    				}else{
		    					$goods['price'] -= $_v['price'];
		    				}
		    				// $goods['quanity'].'&nbsp;x&nbsp;'.
	    					$sshtml = Html::tag('div',$_v['name'],['class'=>'order-name1']);
		    				$sshtml .= Html::tag('div','',['class'=>'order-quanity']);
		    				$sshtml .= Html::tag('div','',['class'=>'pre-price1']);

	    				}else{
	    					$sshtml = Html::tag('div',$_v['price_prefix'].'&nbsp;'.($_v['quanity']*$goods['quanity']).'&nbsp;x&nbsp;'.$_v['name'],['class'=>'order-name1']);
		    				$sshtml .= Html::tag('div','',['class'=>'order-quanity']);
		    				$sshtml .= Html::tag('div',Config::moneyFormat($_v['price']*$_v['quanity']*$goods['quanity']),['class'=>'pre-price1']);

	    				}

	    				$options_html .= Html::tag('div',$sshtml,['class'=>'orderoption']);
	    				$options_html .= '<div class="clearfix"></div>';
	    			}
	    		}


	    		$goods_subtotal = $goods['price'];
	    		// 显示名称
	    		$shtml = Html::tag('div',Html::tag('button','-',['class'=>'cut-button','type'=>'button','onclick'=>'javascript:CART.edit("'.$k.'","cut")']),['class'=>'cut-num']);
	    		$shtml .= Html::tag('div',Html::tag('button','+',['class'=>'add-btn','type'=>'button','onclick'=>'javascript:CART.edit("'.$k.'","add")']),['class'=>'add-num']);
	    		$shtml .= Html::tag('div',$goods_quanity.$goods['title'].(empty($goods['coupon_id']) ? '' : ' <i>(discounted)</i>'),['class'=>'order-name1']);
	    		$shtml .= Html::tag('div','',['class'=>'order-quanity']);
	    		$shtml .= Html::tag('div',empty($goods_subtotal) ? '' : Config::moneyFormat($goods_subtotal*$goods['quanity']),['class'=>'pre-price1']);
	    		$shtml .= '<div class="clearfix"></div>';

	    		// 显示有子产品
	    		$shtml .= $child_html;

	    		// 显示多选项目
	    		$shtml .= $options_html;

	    		$s_html .= Html::tag('li',$shtml);
	    		$total +=$goods['subtotal'];
	    	}

	    echo ($total>=$this->context->getConfig('Minimum')||$data['send']=='collection') ? '' : Html::tag('div','You need to spend '.$this->context->getConfig('currency').$this->context->getConfig('Minimum').' or<br/>more to order for  delivery',['class'=>'need']);

	    $s_html .= '</ul></div>';
	    echo $s_html;

	    $old_total = $total;
	    ?>

	    <div style="padding-left:5px;"class="total">

	    	<p>Subtotal <span class="to-price"><?=Config::currencyMoney($total)?></span></p>

	      	<?php if(isset($list['free_goods'])):
	    		// 4滿就送餐

                echo '<div style="font-size:11px;"class="cart-right-coupon"><span>FREE '.$list['free_goods']['memo'].'</span><span class="to-price"> '.Config::moneyFormat(0).'</span></div>';
            endif;?>

	    	<?php if(isset($list['free_first_discount'])):
	    		// 1首单优惠
	    		$last_total = $total;
                $total = ($list['free_first_discount']['type']=='0') ? $list['free_first_discount']['coup_value']*$total : ($total-$list['free_first_discount']['coup_value']);
                // $discount = ($list['free_first_discount']['type']=='0') ? ((1-$list['free_first_discount']['coup_value'])*100) . '% Off' : $list['free_first_discount']['coup_value'];
                $discount = '';
                echo '<div class="cart-right-coupon"><span>'.$list['free_first_discount']['name'].' '.$discount.'</span> <span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></div>';
            endif;?>

            <?php if(isset($list['free_up'])&&!empty($list['free_up']['coup_value'])):
            	// 2满就优惠
            	$last_total = $total;
                $total = ($list['free_up']['type']=='0') ? $total*$list['free_up']['coup_value'] : ($total - $list['free_up']['coup_value']);
                // $discount = ($list['free_up']['type']=='0') ? ((1-$list['free_up']['coup_value'])*100) . '% Off' : $list['free_up']['coup_value'];
                $discount = '';
                echo '<div class="cart-right-coupon"><span>'.$list['free_up']['name'].' '.$discount.'</span> <span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></div>';

            endif;?>

            <?php if(isset($list['free_member'])&&!empty($list['free_member']['coup_value'])):
            	// 5會員打折
            	$last_total = $total;
                $total = ($list['free_member']['type']=='0') ? $total*$list['free_member']['coup_value'] : ($total - $list['free_member']['coup_value']);
                // $discount = ($list['free_member']['type']=='0') ? ((1-$list['free_member']['coup_value'])*100) . '% Off' : $list['free_member']['coup_value'];
                $discount = '';
                echo '<div class="cart-right-coupon"><span>'.$list['free_member']['name'].' '.$discount.'</span> <span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></div>';

            endif;?>

			<?php if($data['send']=='deliver'&&false):?>
            <p>Delivery fee <?=isset($list['free_ship'])&&!empty($list['free_ship']) ? '(free)' : '';?><span class="to-price"><?=sprintf("%.2f",$list['shipment_price']);?></span></p>
            <?php endif;?>

	      
	      <p>Total<span class="to-price" id="cart-total" data-total="<?=$old_total?>"> <?=Config::currencyMoney($total)?></span></p>
	    </div>
	    <?php
	    endif;
	    ?>
	    <div class="ormark" style="margin-top:-10px;">
	      <p class="note">Coupon</p>
	      <input style="width:100%;" name="cart[coupon]" placeholder="HappyHour" class="book-input"/>
	    </div>
	    <div class="ormark" style="margin-top:-20px;margin-bottom:-40px;">
	      <p class="note">Leave us a note</p>
	      <textarea name="cart[note]" placeholder="e.g. If you have a food allergy or instructions for the driver"></textarea>
	      <center><p style="background-color:white; cursor:pointer; font-size:12px; margin-top:-18px;" class="allergy" onclick="javascript:allergy();">Do you have an allergy?</p></center>
	    </div>


	  </div>
		<?
		// 假日不下單
	  $holiday = getExtdata('holiday','ext');

	  $holiday_flag = false; //放假標識
	  if(isset($holiday['options'])&&is_array($holiday['options'])&&$holiday['status']):
	    $holiday_message = '';
	    $M = strtolower(date("l"));

	    // 當開始時間和結束時間不為空時放假開始
	    if(!empty($holiday['options']['start'])&&!empty($holiday['options']['end'])){
	      $start_hour = $holiday['options']['start'];
	      $endhour_ext = explode(' ', $holiday['options']['end']);
	      $endhour = isset($endhour_ext['1']) ? $holiday['options']['end'] : $holiday['options']['end'].' 23:59:59';

	      $today = time();

	      $start_hour = strtotime($start_hour);
	      $endhour = strtotime($endhour);

	      $holiday_flag = ($today>=$start_hour&&$today<=$endhour) ? true : $holiday_flag;
	    }
	    // 當禮拜幾放假時
	    if(isset($holiday['options'][$M])&&$holiday['options'][$M]==1){
	      $holiday_flag = true;
	    }
	    // 當提示不為空時顯示提示
	    if(!empty($holiday['options']['message'])){
	      $holiday_message = $holiday['options']['message'];
	    }
			$webtemp = \common\models\Config::getConfig('webtemp');
			if(!isset($webtemp)){ echo ' '; }
			if($webtemp == 1){
				echo '<div class="checkout"><input type="button" value="Online Order Coming Soon" name="closed" class="btn checkout-button disabled" disabled="disabled"></div>';

			} elseif($holiday_flag){ echo '<div class="checkout"><input type="button" value="Online Order Unavailable" name="closed" class="btn checkout-button disabled" disabled="disabled"></div>'; } else { echo '<div class="checkout"><input type="button" value="Go to checkout" name="checkout" class="checkout-button" onclick="javascript:CART.formSubmit()"></div>'; } endif;


			?>


		</form>
		<div id="mobileFixedBasket" class="emptyBasket">
			<?=Html::tag('p','You need to spend '.$this->context->getConfig('currency').$this->context->getConfig('Minimum').' or more to order for delivery',['class'=>'panelMessage'])?>
		    <?=Html::a(empty($total) ? 'View basket' : 'Total: &nbsp;'.Config::currencyMoney($total).'&nbsp;('.$all_items.')',Url::to(['/cart/info','type'=>'show']).'#cart-info',['class'=>'viewBasketLink'])?>
		</div>

</div>

<?php else:?>
	<!-- start checkout -->
<div id="cart-confirm-info">

	<div class="pay-order">
        <div class="payorder-cart">
            <span class="cart"><?=Html::img(showImg(IMG_URL.'/cart.png'));?></span>
            <div class="payorder-title">Your Order<p class="edit-order">
             	<?=Html::a('Edit Order',['/site/product'])?></p></div>
            <div class="clearfix"></div>
        </div>

        <?php echo $this->render('_cart_list',['cart'=>$cart,'list'=>$list,'post_info'=>$post_info])?>
</div>

<?php endif;?>
