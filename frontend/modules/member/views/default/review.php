<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Config;
use yii\bootstrap\ActiveForm;

?>
<style type="text/css">
  .red{color: red}
#popupPaypalReview {
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
<?php if(($order->payment_type!='cash')&&$order->order_status=='pending'){
echo '<div class="modal-body" style="border-radius:6px;background-color:white;" id="popupPaypalReview">


  <div class="modal-body" alt="popup">
    <center><span style="font-size:2.0em;" class="glyphicon glyphicon-exclamation-sign"></span></center>
    <p>Please do not pay again if you have paid by Paypal! Website will take a minute to refresh its payment. Thank you.</p>
  </div>

  <button class="btn btn-default pull-right" style="width:100%" id="closePaypal1">OK</button>


</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>
$(document).ready(function () {

  //select the POPUP FRAME and show it
  $("#popupPaypalReview").hide().fadeIn(220);

  //close the POPUP if the button with id="close" is clicked
  $("#closePaypal1").on("click", function (e) {
    e.preventDefault();
    $("#popupPaypalReview").fadeOut(100);
  });

});
</script>';
} else { echo ' '; }
?>

<div class="acount-info">
  <h2>Order Detail <?php if(($order->payment_type!='cash')&&$order->order_status=='pending'):?> - (Pending For Payment)<?php endif;?></h2></div>

  <div class="row order-hr">
    <div class="col-sm-3">Order Summary</div>
    <div class="col-sm-9 t-right"><?php echo '#'.(empty($order['order_no']) ? $order['invoice_no'] :  Config::orderFormat($order['order_no']));?></div>
  </div>
  <?php
    $total = 0;
    $shtml = Html::beginTag('div',['class'=>'row one-order']);
    // $shtml .= Html::tag('div',date('Y.m.d',$order['add_date']),['class'=>'col-sm-2']);
    // $shtml .= Html::tag('div','#'.$order['invoice_no'],['class'=>'col-sm-3']);
    $shtml .= Html::beginTag('div',['class'=>'col-sm-12 dish-part']);

    foreach ($order_goods as $k => $v) {
      $total += $v['subtotal'];

      $sohtml = '';

      if(isset($v['goods_options'])&&!empty($v['goods_options'])){
        foreach ($v['goods_options'] as $_k => $_v) {
          $ohtml = Html::beginTag('div',['class'=>'row or-row']);



          if(isset($_v['group']['options_type'])&&$_v['group']['options_type']=='radio'){
              $v['price'] = $v['price']+$_v['price'];

              $ohtml .= Html::tag('div',$_v['name'],['class'=>'col-sm-12']);

            }else{
              $ohtml .= Html::tag('div','+&nbsp;'.($_v['quanity']*$v['quanity']).'&nbsp;×&nbsp;'.$_v['name'],['class'=>'col-sm-8']);

              $ohtml .= Html::tag('div','',['class'=>'col-sm-1']);
              $ohtml .= Html::tag('div',Config::currencyMoney($_v['price']*$_v['quanity']*$v['quanity']),['class'=>'col-sm-3 t-right']);

            }


          $ohtml .= Html::endTag('div');
          $sohtml .= $ohtml;
        }
      }

      $ghtml = Html::beginTag('div',['class'=>'row or-row']);
      $ghtml .= Html::tag('div',$v['quanity'].'&nbsp;×'.'&nbsp;'.$v['name'],['class'=>'col-sm-8']);
      $ghtml .= Html::tag('div','',['class'=>'col-sm-1']);
      $ghtml .= Html::tag('div',Config::currencyMoney($v['price']*$v['quanity']),['class'=>'col-sm-3 t-right']);
      $ghtml .= Html::endTag('div');

      $ghtml .= $sohtml;
      $shtml .= $ghtml;

    }

    $shtml .= Html::endTag('div');
    $shtml .= Html::endTag('div');
    echo $shtml;

  ?>

  <div class="row">
    <div class="col-sm-12 total-part">

      <div class="row tal-row">
        <div class="col-sm-8">Subtotal   </div>
        <div class="col-sm-4 e-price t-right"><?=Config::currencyMoney($total);?></div>
      </div>

      <?php
      $free_goods = null;
      if(!empty($coupon)):
      foreach ($coupon as $k => $v) {
        $shtml = '';

        if ($v['flat_coup']=='3') {
          $shipment_postcode['price'] = 0;
          continue;
        }else if ($v['flat_coup']=='5') {
          $free_goods = $v;
          continue;
        }


        $last_total = $total;
        $total = ($v['type']=='0') ? $total*$v['coup_value'] : ($total - $v['coup_value']);
        $discount = ($v['type']=='0') ? ((1-$v['coup_value'])*100) . '% Off' : '-'.$v['coup_value'];

        if($v['flat_coup']=='4'){
          // 首单优惠
          $shtml = Html::tag('div',$v['name'].' '.$discount,['class'=>'col-sm-8']);

        }else if ($v['flat_coup']=='0') {
          // 优惠卷
          $shtml = Html::tag('div',$v['name'].' '.$discount,['class'=>'col-sm-8']);
        }else if ($v['flat_coup']=='2') {
          // 满就优惠
          $shtml = Html::tag('div',$v['name'].' '.$discount,['class'=>'col-sm-8']);

        }else{
          $shtml = Html::tag('div',''.$v['name'].' Free '.$discount,['class'=>'col-sm-8']);

        }

        if($v['flat_coup']!='3'&&$v['flat_coup']!='5'){
          $shtml .= Html::tag('div','-'.Config::currencyMoney($last_total-$total),['class'=>'col-sm-4 e-price t-right']);
          $shtml = Html::tag('div',$shtml,['class'=>'row tal-row']);
          echo $shtml;
        }

      }
      endif;
      ?>

    <?php if($order->order_type=='deliver'){
        // 邮费
        $total = $total+$shipment_postcode['price'];
        $shtml = Html::tag('div','Delivery fee',['class'=>'col-sm-8']);
        // $data = ($coupon['2']['type']=='0') ? ($coupon['2']['coup_value']*100).'%' : '-'.$coupon['2']['coup_value'];
        $shtml .= Html::tag('div',Config::currencyMoney($shipment_postcode['price']),['class'=>'col-sm-4 e-price t-right']);
        $shtml = Html::tag('div',$shtml,['class'=>'row tal-row ']);
        echo $shtml;
    }?>
    <div class="row total-row">
      <div class="col-sm-8">Total    </div>
      <div class="col-sm-4 e-price t-right"><?=Config::currencyMoney($order->total);?></div>
    </div>

    </div>

  </div>
  <?php if($order->order_status!='pending'){
    $payment_name = '';
    if($order->payment_type=='paypal'||$order->payment_type=='Paypal'){
      $payment_name = 'Paypal';
    }else if($order->payment_type=='worldpay'||$order->payment_type=='card'){
      $payment_name = 'Card';
    }else{
      $payment_name = 'Cash';
    }
    // var_dump($v['payment_type']);
    $status_html = Html::beginTag('div',['class'=>'row']);
    $status_html .= Html::tag('div','Card fee',['class'=>'col-sm-8']);
    $status_html .= Html::tag('div',Config::currencyMoney($order->card_fee),['class'=>'col-sm-4 e-price t-right']);
    $status_html .= Html::endTag('div');
    $status_html .= Html::beginTag('div',['class'=>'row total-row']);
    $status_html .= Html::tag('div','Total paid by '.$payment_name,['class'=>'col-sm-8']);
    $status_html .= Html::tag('div',Config::currencyMoney($order->total+$order->card_fee),['class'=>'col-sm-4 e-price t-right']);
    $status_html .= Html::endTag('div');

    echo $status_html;
  }?>
  <div class="row">
  <div class="col-sm-12 total-row">Requested <?php echo $order->order_type=='deliver'?'delivery':$order->order_type;?> Time</div>
  <div class="col-sm-12">
    <?php
    $estimatedDate = date('Y',$order->shipment_time) == '1970' ? 'ASAP' : date('d/m/Y'.' - '.'H:i',$order->shipment_time);
    echo $estimatedDate;?>
  </div>
  </div>
  <div class="row">

      <div class="col-sm-12 total-part">
      <?php
      if(!empty($order->additional)&&!empty($free_goods)){
        echo 'FREE '.$order->additional.' for orders over '.Config::getConfig('currency').$free_goods['total'];
      }
      ?>
      <div class="tal-row">
        <?php echo $order->comment; ?>
      </div>

      </div>
  </div>

  <?php if($order->order_status=='pending'&&$list['online_pay']):
  // 支付模块
  ?>
  <div class="row" style="padding:0 15px;">
        <p class="pay-title">How would you like to pay?</p>
            <?php if(!empty($payment_list)):
            $i=0;
            foreach ($payment_list as $k => $v) {
              // $target = !empty($v['alias']) ? '_blank' : '';
              $target = '';
                ActiveForm::begin(['options'=>['method'=>'post',
                          'target'=>$target,
                          'onsubmit'=>!empty($v['alias']) ? 'javascript:ORDER.payment();' : 'javascript:ORDER.payment(1);',
                          ],'action'=>['/order/pay']]);
                $hide = ($i==0) ? '' : 'hide';
                $up = ($i==0) ? 'glyphicon-menu-up' : 'glyphicon-menu-down';
                $phtml = '<p class="paypal-way">'.Html::a($v['name'].Html::tag('span','',['class'=>'glyphicon menu-dag '.$up])).'</p>';
                $phtml .= Html::beginTag('div',['class'=>'paypal-detail '.$hide ]);
                if(!empty($v['card_fee'])){
                    $phtml .= Html::tag('p','card fee:(+'.Config::getConfig('currency').sprintf("%.2f",$v['card_fee']).')');
                }

                $alis_name = '';

                if($v['key']=='paypal'){
                    $phtml .= Html::tag('p',Html::img(showImg(IMG_URL.'/paypal.jpg',['style'=>'width:60px;'])));
                  $alis_name = '(Paypal)';
                }else if($v['key']=='worldpay'){
                    $phtml .= Html::tag('p',Html::img(showImg(IMG_URL.'/worldpay_log.jpg'),['style'=>'height:86px;']));
                    $alis_name = '(Card)';
                }else if($v['key']=='braintree'){
                  $alis_name = '(Braintree)';
                }else{
                    $alis_name = '(Cash)';
                }
                $phtml .= Html::hiddenInput('type',$v['key']);
                $phtml .= Html::hiddenInput('order_id',$order['order_id']);
                $phtml .= Html::tag('div',Html::submitButton('Place my order '.$alis_name,['class'=>'pay-submit ']),['class'=>'paying ']);
                $phtml .= Html::endTag('div');

                $phtml = Html::tag('div',$phtml,['class'=>'paypal-part']);
                echo $phtml;
                $i++;
                ActiveForm::end();
            }
            endif;
            ?>

            <div>
              <?php
              ActiveForm::begin(['options'=>['method'=>'post',
                          ]]);
              echo Html::beginTag('div',['class'=>'paying']);
              echo Html::hiddenInput('Order[order_status]','cancel');
              echo Html::submitButton(\Yii::t('app','Cancel Order'),['class'=>'pay-submit']);
              echo Html::endTag('div');
              ActiveForm::end();
              ?>

            </div>
  </div>
  <?php elseif($order->order_status=='payment'):?>
  <!--  -->
  <div class="row" style="padding:0 5%">
    <div class="feedcap">
      <div class="feed-value">
        <div class="value-caption">Your <strong>feedback</strong> is important in helping us improve our food quality and service Please include contact details if you require a response from the takeaway.</div>
            <?php $form = ActiveForm::begin();?>
            <div class="feed-list">
              <table class="value-table">
                <tbody>
                  <tr>
                    <td>Was the food value for money?</td>
                    <td><?=Html::radio('OrderReview[money]',$order_review->money==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span></td>
                    <td><?=Html::radio('OrderReview[money]',$order_review->money==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span></td>
                    <td><?=Html::radio('OrderReview[money]',$order_review->money==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span></td>
                    <td><?=Html::radio('OrderReview[money]',$order_review->money==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span></td>
                    <td><?=Html::radio('OrderReview[money]',$order_review->money==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span></td>
                  </tr>

                  <tr>
                    <td>How quick was the delivery?</td>
                    <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span></td>
                    <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span></td>
                    <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span></td>
                    <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span></td>
                    <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span></td>
                  </tr>

                  <tr>
                    <td>How was the food?</td>
                    <td><?=Html::radio('OrderReview[food]',$order_review->food==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span></td>
                    <td><?=Html::radio('OrderReview[food]',$order_review->food==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span></td>
                    <td><?=Html::radio('OrderReview[food]',$order_review->food==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span></td>
                    <td><?=Html::radio('OrderReview[food]',$order_review->food==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span></td>
                    <td><?=Html::radio('OrderReview[food]',$order_review->food==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span></td>
                  </tr>

                  <tr>
                    <td>Your Name</td>
                    <td colspan="5"><?=Html::activeTextInput($order_review,'name',['class'=>'name-input'])?>
                      <?php if(isset($order_review->getErrors('name')['0'])):?>
                      <?=Html::tag('p',$order_review->getErrors('name')['0'],['class'=>'red'])?>
                      <?php endif;?>
                    </td>

                  </tr>

                  <tr>
                    <td colspan="6">
                      <p>Coments:</p>
                      <?=Html::activeTextArea($order_review,'comment',['class'=>'coment-textarea'])?>
                      <?php if(isset($order_review->getErrors('comment')['0'])):?>
                      <?=Html::tag('p',$order_review->getErrors('comment')['0'],['class'=>'red']);?>
                      <?php endif;?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6">
                      <?=Html::activeHiddenInput($order_review,'order_id')?>
                      <?php if($order_review->isNewRecord):?>
                      <input type="submit" name="send" class="submit-feed" value="Send">
                      <?php endif;?>
                    </td>
                  </tr>

                </tbody>
              </table>
              <p class="please-text">1.Please note that some of the feedback might be displayed on our website.<br/>
              2.Please include contact details if you require a response from the manager.</p>
            </div>
            <?php ActiveForm::end();?>
            </div>
    </div>
    <div class="clearfix"></div>
  </div>
  <?php endif?>

  <div class="modal fade payment-modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-body" style="min-height: 129px; padding: 50px;">
        <?php echo Html::tag('app','Your order is being processed, it will take a while, please wait…..')?>
      </div>
      <div class="modal-footer" style="text-align:center">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <?php echo Html::a(\Yii::t('app','Close'),['/cart/confirm-success','id'=>$order->order_id],['class'=>'btn btn-default login-btn'])?>
            </div>
            <div class="col-sm-4"></div>
        </div>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
