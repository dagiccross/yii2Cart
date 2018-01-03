<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use yii\bootstrap\ActiveForm;
use common\models\Order;
?>
<div class="container-fluid">
  <div class="container">

  <section class="thank-wrap">

    <div class="col-sm-7">
      <div class="tank-title">
        <h2>Thank you for ordering</h2>
      </div>

      <div class="t-place">
        <?php
        echo Html::tag('h2','Remarks:');
        echo Html::tag('p',$order->comment);
        $shipment_type = 'collection';

        if($order->order_type=='deliver'){
          $map_calculation = \common\models\Config::getConfig('map_calculation');
          if ($map_calculation == 0){
            $shipment_city_new = $order->shipment_postcode;
            $shipment_postcode2_new = strtoupper($order->shipment_postcode2);
          } elseif ($map_calculation == 1){
            $shipment_city_new = $order->shipment_city;
            $shipment_postcode2_new = strtoupper($order->shipment_postcode.$order->shipment_postcode2);
          }


          $shipment_type = 'delivery';

          echo Html::beginTag('div',['class'=>'row']);
          echo Html::tag('div','For Delivery  : ',['class'=>'col-sm-5']);
          $dsHtml = Html::tag('p',$order->shipment_name);
          $dsHtml .= Html::tag('p',$order->shipment_addr1);
          if(!empty($order->shipment_addr2)){
            $dsHtml .= Html::tag('p',$order->shipment_addr2);
          }
          $dsHtml .= Html::tag('p',$shipment_city_new);
          $dsHtml .= Html::tag('p',$shipment_postcode2_new);
          $dsHtml .= Html::tag('p',$order->shipment_phone);
          echo Html::tag('div',$dsHtml,['class'=>'col-sm-7']);
          echo Html::endTag('div');
        }else{
          echo Html::beginTag('div',['class'=>'row']);
          echo Html::tag('div','Collection Pick Up @ : ',['class'=>'col-sm-5']);
          $dsHtml = Html::tag('p',$this->context->getConfig('company_name'));
          $dsHtml .= Html::tag('p',$this->context->getConfig('address'));
          $dsHtml .= Html::tag('p',$this->context->getConfig('city').'&nbsp;'.$this->context->getConfig('postcode'));
          $dsHtml .= Html::tag('p',$this->context->getConfig('company_tel'));
          echo Html::tag('div',$dsHtml,['class'=>'col-sm-7']);
          echo Html::endTag('div');
        }

        // if(isset($list['payment']['key'])&&isset($list['payment']['flat'])&&$list['payment']['flat']){
        //   echo Html::tag('p','Payment Status : Paid by <span style="text-transform:capitalize;">'.$list['payment']['key'].'</span>');
        // }else{
        //    echo Html::tag('p','Payment Status : <span style="text-transform:capitalize;">'.$order->payment_type.'</span> on <span style="text-transform:capitalize;">'.$shipment_type.'</span>');
        // }
        echo Html::tag('p','Payment Status : '.Order::getPaymentStatus($order->order_status,$order->payment_type,$order->order_type));
        /*
        <h2>Order placed</h2>
          <?=Html::tag('p','For '.$post_info['cart']['send']);?>
          <?=Html::tag('p',$shipment['shipment_name']);?>
          <?php if($post_info['cart']['send']=='delivery'):?>
          <?=Html::tag('p',$shipment['shipment_addr1'].','.$shipment['shipment_city'].','.$shipment['shipment_postcode'].$shipment['shipment_postcode2']);?>
          <?php else:?>
          <p><?=$this->context->getConfig('address').','.$this->context->getConfig('city').','.$this->context->getConfig('postcode')?></p>
          <?php endif;?>
          */
          ?>
      </div>

      <div class="t-problem">
          <?php
          $company_tel = \common\models\Config::getConfig('company_tel');
          $needhelp = getPageByKey('needhelp');
           echo showContent($needhelp['content']);
           echo $company_tel;
          ?>
      </div>
    </div>

    <div class="col-sm-5">

      <div class="t-order">
        <div class="t-cart">
         <span class="cart"><?=Html::img(showImg(IMG_URL.'/cart.png'));?></span>
         <div class="tcart-title">Your Order<span><a href="#">#<?=(empty($order->order_no) ? $order->invoice_no :  Config::orderFormat($order->order_no));?></a></span></div>
         <div class="clearfix"></div>
        </div>

        <?php if(isset($list['template'])&&$list['template']=='success'){

          echo $this->render('ext/success',['order'=>$order,'cart'=>$cart,'list'=>$list,
                            'shipment_postcode'=>$shipment_postcode]);
        }else{
          echo $this->render('ext/confirm',['order'=>$order,'cart'=>$cart,'list'=>$list,
                            'shipment_postcode'=>$shipment_postcode]);
        }
        ?>
        <p class="request">Requested <?=($post_info['cart']['send']=='deliver' ? 'delivery' : $post_info['cart']['send'])?> time</p>
        <p class="request-time"><?=$post_info['cart']['time']?></p>

      </div>
    </div>

    <div class="clearfix"></div>

  </section>
  </div>

</div>
<?php if(isset($dopay)):?>
  <?=$dopay;?>
<?php endif?>
