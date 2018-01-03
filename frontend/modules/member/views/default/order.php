<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\extensions\Pages;
use common\models\Order;
use common\models\Config;
?>
<div class="acount-info">
  <h2>Order Review</h2></div>
  <div class="row order-hr">              
    <div class="col-sm-2">Date</div>
    <div class="col-sm-3" style=" font-size: 14px;">Order number</div>
    <div class="col-sm-7">
      <div class="row">
      <div class="col-sm-8">Payment Status</div>
      <div class="col-sm-4 e-price">Total Price</div>
      </div>
    </div>                
    <!-- <div class="col-sm-1">Review</div> -->
  </div>                  
  <?php 
  foreach ($order_list as $k => $v) {
    $payment_data = !empty($v['alias']) ? 'Paid by <span>' . $v['payment_type'].'</span>' : 'Cash on '.($v['order_type']=='collection' ? 'Collection' : 'Delivery');
    $order_status = Order::getPaymentStatus($v['order_status'],$v['payment_type'],$v['order_type']);//!empty($v['alias'])&&$v['order_status']!=='payment' ? Order::orderStatus($v['order_status']) : $payment_data;
    $shtml = Html::beginTag('div',['class'=>'row one-order dish-part']);
    $shtml .= Html::tag('div',date('d.m.Y',$v['add_date']),['class'=>'col-sm-2']);
    $shtml .= Html::tag('div',Html::a('#'.(empty($v['order_no']) ? $v['invoice_no'] :  Config::orderFormat($v['order_no'])),['default/review','id'=>$v['order_id']],['class'=>'c-red']),['class'=>'col-sm-3']);
    $shtml .= Html::beginTag('div',['class'=>'col-sm-7 ']);
    $shtml .= Html::beginTag('div',['class'=>'row or-row']);
    $shtml .= Html::tag('div',$order_status,['class'=>'col-sm-8']); //$v['name']
    $shtml .= Html::tag('div',Config::currencyMoney($v['total']+$v['card_fee']),['class'=>'col-sm-4 e-price']);
    $shtml .= Html::endTag('div');
    $shtml .= Html::endTag('div');
    // $shtml .= Html::tag('div',Html::a('Review',['default/review','id'=>$v['order_id']],['class'=>'review-btn']),['class'=>'col-sm-1 review-col']);
    $shtml .= Html::endTag('div');
    echo $shtml;
  }
  ?>
                
<div class="clearfix"></div>
<div class="row">
  <div class="col-sm-12">
  <?= Pages::widget(['pagination' => $pages]) ?>
  </div>
</div>
