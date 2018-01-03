<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use yii\bootstrap\ActiveForm;
$card_fee = 0;

?>

<style>
#popupPaypal {
  display:none;
  position:absolute;
  /*margin:0 auto;*/
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-shadow: 0px 0px 50px 2px #000;
  z-index: 100;
}
</style>
<?php if(!empty($payment_list)):
  $i=0;
  foreach ($payment_list as $k => $v) {
if(false && $v['key']=='paypal'){
echo '<div class="modal-body" style="border-radius:6px;background-color:white;" id="popupPaypal">


  <div class="modal-body" alt="popup">
    <center><span style="font-size:2.0em;" class="glyphicon glyphicon-exclamation-sign"></span></center>
    <p>Please wait for about 10 seconds when checking out using Paypal to avoid interrupt transaction.</p>
  </div>

  <button class="btn btn-default pull-right" style="width:100%" id="closePaypal">I understood!</button>


</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>
$(document).ready(function () {

  //select the POPUP FRAME and show it
  $("#popupPaypal").hide().fadeIn(220);

  //close the POPUP if the button with id="close" is clicked
  $("#closePaypal").on("click", function (e) {
    e.preventDefault();
    $("#popupPaypal").fadeOut(100);
  });

});
</script>';
}else { echo ' ';
}
}
endif;
?>



<section class="pay-section">
  <div class="col-sm-6">
    <p class="pay-title">How would you like to pay?</p>
    <?php if(!empty($payment_list)):
      $i=0;
      foreach ($payment_list as $k => $v) {

        $target = !empty($v['alias']) ? '' : '';

        ActiveForm::begin(['options'=>['method'=>'post',
        'target'=>$target,
        'onsubmit'=>!empty($v['alias']) ? 'javascript:ORDER.payment();' : 'javascript:ORDER.payment(1);',
      ],'action'=>['/cart/confirm-order']]);
      $hide = ($i==0) ? '' : 'hide';
      $up = ($i==0) ? 'glyphicon-menu-up' : 'glyphicon-menu-down';
      $phtml = '<p class="paypal-way" data-key="'.$v['key'].'">'.Html::a($v['name'].Html::tag('span','',['class'=>'glyphicon menu-dag '.$up])).'</p>';
      $phtml .= Html::beginTag('div',['class'=>'paypal-detail '.$hide ]);

      $alis_name = '';
      if($v['key']=='paypal'){
        if(!empty($v['card_fee'])){
          $phtml .= Html::tag('p','fee: ('.Config::currencyMoney($v['card_fee']).')');
        }

        $phtml .= Html::tag('p',Html::img(showImg(IMG_URL.'/paypal.jpg'),['style'=>'']));
        $alis_name = '(Paypal)';
      }else if($v['key']=='worldpay'){
        if(!empty($v['card_fee'])){
          $phtml .= Html::tag('p','card fee: ('.Config::currencyMoney($v['card_fee']).')');
        }

        $phtml .= Html::tag('p',Html::img(showImg(IMG_URL.'/worldpay_log.jpg'),['style'=>'height:86px;']));
        $alis_name = '(Card)';
      }else if($v['key']=='cardmachine'){
        if(!empty($v['card_fee'])){
          $phtml .= Html::tag('p','card fee: ('.Config::currencyMoney($v['card_fee']).')');
        }
        $phtml .= Html::tag('p',Html::img(showImg(IMG_URL.'/visa-back.png'),['style'=>'']));
        $phtml .= '<p>Request card machine upon arrival</p>';
        $alis_name = '(Card Machine)';
      }else if($v['key']=='braintree'){
        if (!empty($v['card_fee'])) {
          $phtml .= Html::tag('p', 'card fee: (' . Config::currencyMoney($v['card_fee']) . ')');
        }
        $phtml .= Html::tag('p', Html::img(showImg(IMG_URL . '/braintree.png'), ['style' => 'width:165px;']));
        $alis_name = '(Braintree)';
      }else{
        $alis_name = '(Cash)';
      }
      $phtml .= Html::hiddenInput('type',$v['key']);
      $phtml .= Html::tag('div',Html::submitButton('Place my order '.$alis_name,['class'=>'pay-submit ']),['class'=>'paying ']);
      $phtml .= Html::endTag('div');

      $phtml = Html::tag('div',$phtml,['class'=>'paypal-part']);
      echo $phtml;
      $card_fee = $i==0 ? $v['card_fee'] : $card_fee;

      $i++;
      ActiveForm::end();
    }
  endif;
  $list['payment']['card_fee'] = $card_fee;
  ?>
</div>

<div class="col-sm-6 confirm-cart-info" >
  <div class="pay-order">
    <div class="payorder-cart">
      <span class="cart"><?=Html::img(showImg(IMG_URL.'/cart.png'));?></span>
      <div class="payorder-title">Your Order<p class="edit-order">
        <?=Html::a('Edit Order',['/site/product'])?></p></div>
        <div class="clearfix"></div>
      </div>

      <?php echo $this->render('_cart_list',['cart'=>$cart,'list'=>$list,'post_info'=>$post_info])?>
    </div>
    <div class="clearfix"></div>
  </section>

  <div class="modal fade payment-modal">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-body" style="min-height: 129px; padding: 50px;">
          <?php echo Html::tag('app','Your order is being processed, it will take a while, please wait...')?>
        </div>
        <div class="modal-footer" style="text-align:center">
          <div class="row">
            <div class="col-sm-4"></div>
            <!-- <div class="col-sm-4">
            <?php //echo Html::a(\Yii::t('app','Payment Error'),['/member/default/order'],['class'=>'btn btn-default login-btn'])?>
          </div> -->
          <div class="col-sm-4">
            <?php echo Html::a(\Yii::t('app','Close'),['/cart/confirm-success'],['class'=>'btn btn-default login-btn'])?>
          </div>
          <div class="col-sm-4"></div>
        </div>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
