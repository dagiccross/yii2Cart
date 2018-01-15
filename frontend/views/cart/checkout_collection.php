<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use yii\bootstrap\ActiveForm;
?>

    		<section class="pay-section">
    			<div class="col-sm-6">
    				<p class="pay-title">How would you like to pay?</p>
            <?php if(!empty($payment_list)):
            $i=0;
            foreach ($payment_list as $k => $v) {
                ActiveForm::begin(['options'=>['method'=>'post','target'=>'_blank']]);
                $hide = ($i==0) ? '' : 'hide';

                $phtml = '<p class="paypal-way">'.Html::a($v['name'].Html::tag('span','',['class'=>'glyphicon glyphicon-menu-down menu-dag'])).'</p>';
                $phtml .= Html::beginTag('div',['class'=>'paypal-detail '.$hide ]);
                if($v['key']=='paypal'){
                    $phtml .= Html::tag('p',Html::img(showImg(IMG_URL.'/paypal.jpg',['style'=>'width:60px;'])));
                }
                $phtml .= Html::hiddenInput('type',$v['key']);
                $phtml .= Html::tag('div',Html::submitButton('Place my order',['class'=>'pay-submit ']),['class'=>'paying ']);
                $phtml .= Html::endTag('div');

                $phtml = Html::tag('div',$phtml,['class'=>'paypal-part']);
                echo $phtml;
                $i++;
                ActiveForm::end();
            }
            endif;
            ?>

    	</div>

        <div class="col-sm-6">
    				<div class="pay-order">
            <div class="payorder-cart">
             <span class="cart"><?=Html::img(showImg(IMG_URL.'/cart.png'));?></span>
             <div class="payorder-title">Your Order<p class="edit-order">
             	<?=Html::a('Edit Order',['/site/product'])?></p></div>
             <div class="clearfix"></div>
           </div>

           <div class="payorder-menu">
             <ul class="payorder-list">
             	<?php
             	$total = 0;
             	if(isset($cart)&&!empty($cart)):?>
             	<?php foreach ($cart as $k => $v) {

             		$shtml = Html::beginTag('li');

             		$shtml .= Html::tag('div',$v['title'],['class'=>'payorder-name']);
             		$shtml .= Html::tag('div',$v['subtotal']*$v['quanity'],['class'=>'payorder-preprice']);
             		$shtml .= Html::tag('div','',['class'=>'clearfix']);

             		if(isset($v['required'])){
		    			foreach ($v['required'] as $_k => $_v) {

		    				$sshtml = Html::tag('div',$_v['name'],['class'=>'order-name1']);
		    				$sshtml .= Html::tag('div','×1',['class'=>'order-quanity']);
		    				$shtml .= Html::tag('div',$sshtml);
		    				$shtml .= '<div class="clearfix"></div>';
		    			}
		    		}
		    		if(isset($v['options'])){
		    			foreach ($v['options'] as $_k => $_v) {

		    				$sshtml = Html::tag('div',$_v['name'],['class'=>'order-name1']);
		    				$sshtml .= Html::tag('div','×'.$_v['quanity'],['class'=>'order-quanity']);
		    				$sshtml .= Html::tag('div',$_v['price'],['class'=>'pre-price1']);
		    				$shtml .= Html::tag('div',$sshtml);
		    				$shtml .= '<div class="clearfix"></div>';
		    			}
		    		}
             		$shtml .= Html::endTag('li');
             		$total +=$v['quanity']*$v['subtotal'];
             		echo $shtml;

             	}?>

             	<?php endif;?>

             </ul>
           </div>
           <div class="paytotal">
            <p class="pay-subtotal">Subtotal <span class="to-price"><?=$total?></span></p>


            <p class="pay-subtotal">Total<span class="to-price"><?=Config::getConfig('currency').$total?></span></p>
           </div>
           <p class="spicy">more spicy on curry no taste enhancer</p>
           <p class="detail-address">Wok Exp LTD,25 East Stret,<br/>
CO1 2TR</p>
           <p><?=Html::img(showImg(IMG_URL.'/deliver-time.png'));?></p>


           </div>
    			</div>
    			<div class="clearfix"></div>
    		</section>
