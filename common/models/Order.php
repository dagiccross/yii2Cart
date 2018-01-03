<?php
// BETA v1.00
// CUSTOM CARD PAYMENT & DELIVERY ASAP
namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

use thridpart\phpmailer\EXTMailer;

/**
* This is the model class for table "{{%order}}".
*
*/
class Order extends \yii\db\ActiveRecord
{
  /**
  * @inheritdoc
  */
  public static function tableName()
  {
    return '{{%order}}';
  }

  public static function orderStatus($key=''){
    $arr = ['pending'=>\Yii::t('info','Pending'),
    'payment'=>\Yii::t('info','Paid'),
    'deliver'=>\Yii::t('info','Deliver'),
    'confirm'=>\Yii::t('info','Confirm'),
    'refund'=>\Yii::t('info','Refund'),
    'cancel'=>\Yii::t('info','Cancel'),
    'error'=>\Yii::t('info','Error')];
    return (!empty($key)) ? $arr[$key] :$arr;
  }

  public static function paymentStatus($key=''){
    $arr = ['1'=>\Yii::t('info','Cash on Delivery'), //order_status = pending order_type = deliver
    '2'=>\Yii::t('info','Cash on Collection'), //order_status = pending order_type = collecion
    '3'=>\Yii::t('info','Paid by Paypal'), //order_status = payment  payment_type = paypal
    '4'=>\Yii::t('info','Paid by Card'), //order_status = payment  payment_type = card
    '5'=>\Yii::t('info','Pending for Payment'), //order_status = pending payment_type = paypal||card
    '6'=>\Yii::t('info','Cancel Order'), //order_status = cancel
    '7'=>\Yii::t('info','Paying by Card')]; //order_status = paying by card
    return (!empty($key)) ? $arr[$key] :$arr;
  }

  // 支付狀態
  public static function getPaymentStatus($order_status='',$payment_type='',$order_type=''){
    $status = '';

    switch ($order_status) {
      case 'pending':
      if($payment_type=='cash'&&$order_type=='deliver'){
        $status = self::paymentStatus(1);
      }else if($payment_type=='cash'&&$order_type=='collection'){
        $status = self::paymentStatus(2);
      }else if($payment_type=='cardpayment'){
        $status = self::paymentStatus(7);
      }
      else{
        $status = self::paymentStatus(5);
      }
      break;

      case 'payment':
      if($payment_type=='paypal'){
        $status = self::paymentStatus(3);
      }else if($payment_type!='cash'){
        $status = self::paymentStatus(4);
      }
      break;
      case 'cancel':
      $status = self::paymentStatus(6);
      break;
    }
    return $status;
  }
  // 支付狀態KEY
  public static function getPaymentStatusKey($order_status='',$payment_type='',$order_type=''){
    $status = '';

    switch ($order_status) {
      case 'pending':
      if($payment_type=='cash'&&$order_type=='deliver'){
        $status = 1;
      }else if($payment_type=='cash'&&$order_type=='collection'){
        $status = 2;
      }else if($payment_type=='cardpayment'){
        $status = 7;
      }
      else{
        $status = 5;
      }
      break;

      case 'payment':
      if($payment_type=='paypal'){
        $status = 3;
      }else if($payment_type!='cash'){
        $status = 4;
      }
      break;
      case 'cancel':
      $status = 6;
      break;
    }
    return $status;
  }
  // 查詢狀態
  public static function searchByPaymentStatus($model,$key=''){
    $rs = '';
    if(!empty($model)){
      switch ($key) {
        case '1':
        $rs = $model->andWhere('order_status="pending" and order_type="deliver" and payment_type="cash"');
        break;

        case '2':
        $rs = $model->andWhere('order_status="pending" and order_type="collection" and payment_type="cash"');
        break;
        case '3':
        $rs = $model->andWhere('order_status="payment" and payment_type="paypal"');
        break;
        case '4':
        $rs = $model->andWhere('order_status="payment" and (payment_type="card" or payment_type="worldpay")');
        break;
        case '5':
        $rs = $model->andWhere('order_status="pending" and payment_type!="cash"');
        break;
        case '6':
        $rs = $model->andWhere('order_status="cancel"');
        break;
        case '7':
        $rs = $model->andWhere('order_status="pending" and payment_type="cardpayment"');
        break;
      }
      return $rs;
    }else{
      return null;
    }
  }
  // 修改狀態
  public function modifyPaymentStatus($key=''){
    switch ($key) {
      case '1':
      $this->order_status = 'pending';
      $this->order_type = 'deliver';
      $this->payment_type = 'cash';
      break;

      case '2':
      $this->order_status = 'pending';
      $this->order_type = 'collection';
      $this->payment_type = 'cash';
      break;
      case '3':
      $this->order_status = 'payment';
      $this->payment_type = 'paypal';
      break;
      case '4':
      $this->order_status = 'payment';
      $this->payment_type = 'card';
      break;
      case '5':
      $this->order_status = 'pending';
      $this->payment_type = ($this->payment_type!='cash') ? $this->payment_type : 'paypal';
      break;
      case '6':
      $this->order_status = 'cancel';
      break;
      case '7':
      $this->order_status = 'payment';
      $this->payment_type = 'cardpayment';
      break;
    }

  }
  // ==============================================================================================================
  // ========================================== OWNER EMAIL =======================================================
  // ==============================================================================================================

  // service temp
  public function mailTemplate($email,$setting=[]){

    $order_goods = \common\models\OrderGoods::findOrderGoods($this->order_id);

    $map_calculation = \common\models\Config::getConfig('map_calculation');
    if ($map_calculation == 0){
      $shipment_city_new = $this->shipment_postcode;
      $shipment_postcode2_new = strtoupper($this->shipment_postcode2);
    } elseif ($map_calculation == 1){
      $shipment_city_new = $this->shipment_city;
      $shipment_postcode2_new = strtoupper($this->shipment_postcode.$this->shipment_postcode2);
    }


    // 優惠ID
    $coupon_id_arr = explode(',', $this->coupon);
    $coupon =null;
    foreach ($coupon_id_arr as $k => $v) {
      $model = Coupon::find()->where(['coup_id'=>$v])->asArray()->one();
      if(!empty($model)){
        $coupon[$model['flat_coup']] = $model;
      }

    }

    $shipment_postcode = null;
    if($this->order_type=='deliver'){
      // $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>substr($this->shipment_postcode, (strpos($this->shipment_postcode, ")") ?: -1) +1)])->asArray()->one();
      // Postcode only, now using town/city
      $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>$this->shipment_postcode])->asArray()->one();
    }

    $delivery = $this->order_type=='deliver' ? 'delivery' : $this->order_type;
    $payMethod = $this->payment_type=='cardpayment' ? 'CARD' : 'CASH';


    $mhtml = '<html>
    <head>
    <title>Order '.$this->invoice_prefix.$this->invoice_no.'</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body style="padding:3px; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    </style>
    <div style="width:100%;">

    <div style="magin-top:10px; text-align:center; border-style:dashed none; border-width:thin; padding:5px; font-size:13pt;"><strong><h3 style="text-align:center;">Order ID: #'.(empty($this->order_no) ? $this->invoice_no :  Config::orderFormat($this->order_no)).'</h3>

    <h3 style="text-transform:Uppercase; text-align:center;">'.$delivery.' ORDER</h3>

    </div></strong>

    <p><strong>Name:&nbsp;'.$this->shipment_name.'</strong></p>
    <p><strong>Phone:&nbsp;'.$this->shipment_phone.'<strong></p>';
    if($this->order_type=='deliver'){
      $mhtml .= '
      <p><strong>Address:&nbsp;'.$this->shipment_addr1.'</p>
      <p>Address:&nbsp;'.$this->shipment_addr2.'</p>
      <p>City:&nbsp;'.$shipment_city_new.'</p></strong>
      <p><strong>Postcode:&nbsp;'.$shipment_postcode2_new.'</strong></p>';
    }

    $mhtml .= '<p><strong>Order Time:&nbsp;'.date('d/m/Y'.' - '.'H:i',$this->add_date).'</strong></p>
    <center>
    <table style="width:93%;">
    <thead>
    <tr>
    <td><strong>Code</strong></td>
    <td><strong>Qty</strong></td>
    <td><strong>Name</strong></td>
    <td style="text-align:right; padding:3px;"><strong>Price</strong></td>
    </tr>
    </thead>
    <tbody>';
    $total = 0;
    $items_i = count($order_goods);
    foreach ($order_goods as $k => $v) {
      $total += $v['subtotal'];

      // 初始化子产品的內容
      $child_html = '';

      if(isset($v['goods_options'])){
        foreach ($v['goods_options'] as $_k => $_v) {
          $flat = true;

          if($_v['options']['group']['options_type']=='select'||$_v['options']['group']['options_type']=='radio'){
            if($_v['options']['price_prefix']=='+'){
              $v['price'] += $_v['price'];
            }else{
              $v['price'] -=$_v['price'];
            }
            $flat = false;
          }

          $show_quanity = $flat ? '+&nbsp;'.($_v['quanity']*$v['quanity']).'&nbsp;' : '';
          $sshtml = Html::tag('td','',['style'=>'border-top:hidden;']);
          $sshtml .= Html::tag('td','',['style'=>'border-top:hidden;']);
          $sshtml .= Html::tag('td',$show_quanity.$_v['name'],['style'=>'border-top:hidden; text-indent:20px;']);
          // $sshtml .= Html::tag('td',$flat ? Config::currencyMoney($_v['price']*$_v['quanity']) : '',['style'=>'text-align: right; border-top:hidden;']);
          $sshtml .= Html::tag('td','',['style'=>'border-top:hidden;']);

          $child_html .= Html::tag('tr',$sshtml);

        }
      }

      $sku = empty($v['sku_no']) ? $v['goods_id'] : $v['sku_no'];
      $mhtml .= '<tr class="bd-td">
      <td style="text-weight:800; text-align:center; padding:3px;"><h1>'.$sku.'</h1></td>
      <td style="text-align:center; padding:3px;"><h1>'.$v['quanity'].'&nbsp;× </h1></td>
      <td style="text-weight:800; text-align:left; padding:3px;"><h1>'.$v['name'].'</h1></td>

      <td style="text-align:right; padding:3px;"><h1>'.Config::currencyMoney($v['subtotal']).'</h1></td>
      </tr>';
      $mhtml .= $child_html;

    }

    $mhtml .= '<tr class="bd-all">
    <td style="text-align: right; padding: 3px;" colspan="2"><b>Items('.$items_i.')</b></td>
    <td style="text-align: right; padding: 3px;"></td>
    <td style="text-align: right; padding: 3px;">'.Config::currencyMoney($total).'</td>
    </tr>';

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
          // 首单打折
          $shtml = '<!-- DC -->';
          $shtml .= Html::tag('td',$v['name'].' ' .$discount ,['style'=>"text-align: right; padding: 3px;",'colspan'=>'3']);

        }else if ($v['flat_coup']=='0') {
          // 优惠卷
          $shtml = '<!-- DC -->';
          $shtml .= Html::tag('td',$v['name'].' ' .$discount ,['style'=>"text-align: right; padding: 3px;",'colspan'=>'3']);

        }else if ($v['flat_coup']=='2') {
          // 满就优惠
          $shtml = '<!-- DC -->';
          $shtml .= Html::tag('td',$v['name'].' '.$discount ,['style'=>"text-align: right; padding: 3px;",'colspan'=>'3']);

        }else{
          $shtml = '<!-- DC -->';
          $shtml .= Html::tag('td',''.$v['name'].' Free '.$discount ,['style'=>"text-align: right; padding: 3px;",'colspan'=>'3']);
        }

        if($v['flat_coup']!='3'&&$v['flat_coup']!='5'){

          $shtml .= Html::tag('td','-'.Config::currencyMoney($last_total-$total),['style'=>'text-align: right; padding: 3px;']);
          $shtml = Html::tag('tr',$shtml,['class'=>'bd-all']);
          $mhtml .= $shtml;
        }

      }
    endif;
    if($this->order_type=='deliver'){
      $mhtml .= '<tr class="bd-all"><!-- DC -->
      <td style="text-align:right; padding:3px;" colspan="3"><b>Delivery price:</b></td>
      <td style="text-align:right; padding:3px;">'.self::currencyMoney($shipment_postcode['price']).'</td>
      </tr>';
    }
    if(!empty($this->card_fee)){
      $mhtml .= '<tr class="bd-all">
      <td style="text-align:right; padding:3px;" colspan="3"><b>Card Fee:</b></td>
      <td style="text-align:right; padding:3px;">'.self::currencyMoney($this->card_fee).'</td>
      </tr>';

    }
    $mhtml .= '</tbody>
    <tfoot>
    <tr class="bd-all">
    <td style="text-align: right; padding: 3px;" colspan="3"><b>Total:</b></td>
    <td style="text-align: right; padding: 3px;">'.self::currencyMoney($this->total+$this->card_fee).'</td>
    </tr>';
    if(!empty($this->additional)){
      //if(isset($free_goods['memo'])){
      $mhtml .= '<tr class="bd-all"><!-- DC -->
      <td style="text-align: right; padding: 3px;" colspan="4"><b>FREE '.$this->additional.'</b></td>
      </tr>';
    }
    // EDITED -> ASAP WORKAROUND
    $estimatedDate = date('Y',$this->shipment_time) == '1970' ? 'ASAP' : date('d/m/Y'.' - '.'H:i',$this->shipment_time);
    $mhtml .= '</tfoot>
    </table>
    </center>
    <p style="width:94%;"><strong>Remarks:&nbsp;</strong>'.$this->comment.'</p>

    <p><strong>Payment Status:&nbsp;</strong>'.($this->order_status=='payment' ? ('PAID by '.(isset($setting['payment']) ? $setting['payment'] : 'Paypal').' ') : ''.$payMethod.' on '.$delivery).'</p>

    <p><strong>'.ucfirst($this->order_type).' Time:</strong> '.$estimatedDate.'</p>

    <center><br>
    <p>Powered by Milpo Technologies<br /><a href="http://www.milpo.co.uk/">http://www.milpo.co.uk</a></p>
    </center>
    </div>

    </body></html>';
    return $mhtml;
  }

  // ==============================================================================================================
  // ========================================== CUSTOMERS EMAIL ===================================================
  // ==============================================================================================================

  public function cusTemplate($email,$setting=[]){

    $order_goods = \common\models\OrderGoods::findOrderGoods($this->order_id);

    // new postcode and city feature!
    $map_calculation = \common\models\Config::getConfig('map_calculation');
    if ($map_calculation == 0){
      $shipment_city_new = $this->shipment_postcode;
      $shipment_postcode2_new = strtoupper($this->shipment_postcode2);
    } elseif ($map_calculation == 1){
      $shipment_city_new = $this->shipment_city;
      $shipment_postcode2_new = strtoupper($this->shipment_postcode.$this->shipment_postcode2);
    }

    // 優惠ID
    $coupon_id_arr = explode(',', $this->coupon);
    $coupon =null;
    foreach ($coupon_id_arr as $k => $v) {
      $model = Coupon::find()->where(['coup_id'=>$v])->asArray()->one();
      if(!empty($model)){
        $coupon[$model['flat_coup']] = $model;
      }

    }

    $shipment_postcode = null;
    if($this->order_type=='deliver'){
      $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>$this->shipment_postcode])->asArray()->one();
    }

    $delivery = $this->order_type=='deliver' ? 'delivery' : $this->order_type;
    $payMethod = $this->payment_type=='cardpayment' ? 'CARD' : 'CASH';

    $mhtml = '<html>
    <head>
    <title>Order '.(empty($this->order_no) ? $this->invoice_no :  Config::orderFormat($this->order_no)).'</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
    <style>
    body { font-family: Arial, Helvetica, sans-serif; }
    a { color: #000; }
    .hr { border-top: 1px solid #333 !important;width:100%;height:1px; }
    </style>
    <div>
    <div style="width: 100%;">

    <center>
    <div><img src="http://onlineorder.milpohosting.co.uk/uploads/Thankyou.png" alt="Thank you!"></div>

    <h4>Order ID: #'.(empty($this->order_no) ? $this->invoice_no :  Config::orderFormat($this->order_no)).'</h4>

    <h4>'.(($this->order_type=='deliver') ? 'Delivery' : 'Collection').' order from '.Config::getConfig('company_name').'</h4>

    <p>Shop Address : '.Config::getConfig('address').', '.Config::getConfig('postcode').', '.Config::getConfig('city').'</p><hr>
    <h4>Your order Details</h4>';


    $mhtml .= '<table width="100%" style="border-collapse: collapse; border-left: hidden; margin-bottom: 25px; background-color:#ffcccc;">

    <thead><tr>
    <td style="font-size: 13px; border-right: 2px solid #FFF; border-bottom: 2px solid #FFF; font-weight: bold; text-align: left; padding: 5px; color: #000;">Code</td>
    <td style="font-size: 13px; border-right: 2px solid #FFF; border-bottom: 2px solid #FFF; font-weight: bold; text-align: left; padding: 5px; color: #000;">Qty</td>
    <td style="font-size: 13px; border-right: 2px solid #FFF; border-bottom: 2px solid #FFF; font-weight: bold; text-align: left; padding: 5px; color: #000;">Name</td>
    <td style="font-size: 13px; border-right: 2px solid #FFF; border-bottom: 2px solid #FFF; font-weight: bold; text-align: right; padding: 5px; color: #000;">Unit Price</td>
    <td style="font-size: 13px; border-right: 2px solid #FFF; border-bottom: 2px solid #FFF; font-weight: bold; text-align: right; padding: 5px; color: #000;">Price</td>
    </tr></thead>
    <tbody>';
    $total = 0;
    $items_i = count($order_goods);
    foreach ($order_goods as $k => $v) {
      $total += $v['subtotal'];

      // 初始化子产品的內容
      $child_html = '';

      if(isset($v['goods_options'])){
        foreach ($v['goods_options'] as $_k => $_v) {
          $flat = true;

          if($_v['options']['group']['options_type']=='select'||$_v['options']['group']['options_type']=='radio'){
            if($_v['options']['price_prefix']=='+'){
              $v['price'] += $_v['price'];
            }else{
              $v['price'] -=$_v['price'];
            }
            $flat = false;
          }

          $show_quanity = $flat ? '+&nbsp;'.($_v['quanity']*$v['quanity']).'&nbsp;' : '';
          $sshtml = Html::tag('td','',['style'=>'border-right: hidden;']);
          $sshtml .= Html::tag('td','',['style'=>'border-right: hidden;']);
          $sshtml .= Html::tag('td',$show_quanity.$_v['name'],['style'=>'font-size: 14px; color: #111;text-align: left; padding: 7px; border-right: hidden; text-indent:20px;']);
          $sshtml .= Html::tag('td',$flat ? Config::currencyMoney($_v['price']*$_v['quanity']) : '',['style'=>'font-size: 12px; text-align: right; padding: 7px; border-right: hidden;']);
          $sshtml .= Html::tag('td','',['style'=>'border-right: hidden;']);

          $child_html .= Html::tag('tr',$sshtml);

        }
      }
      $sku = empty($v['sku_no']) ? $v['goods_id'] : $v['sku_no'];

      $mhtml .= '<tr class="bd-td">
      <td style="font-size: 14px; font-weight:800;text-align: left; padding: 3px;">'.$sku.'</td>
      <td style="font-size: 14px; text-align: left; padding: 3px;">'.$v['quanity'].'&nbsp;× </td>
      <td style="font-size: 14px; font-weight:800; text-align: left; padding: 3px;">'.$v['name'].'</td>
      <td style="font-size: 12px; text-align: right; padding: 3px;">'.Config::currencyMoney($v['price']).'</td>
      <td style="font-size: 12px; text-align: right; padding: 3px;">'.Config::currencyMoney($v['subtotal']).'</td>
      </tr>';
      $mhtml .= $child_html;

    }

    $mhtml .= '<tr class="bd-all">
    <td style="font-size: 12px;text-align: right; padding: 7px;" colspan="4"><b>Items('.$items_i.')</b></td>
    <td style="font-size: 12px; text-align: right; padding: 7px;">'.Config::currencyMoney($total).'</td>
    </tr>';

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
          // 首单打折
          $shtml = Html::tag('td',$v['name'].' ' .$discount ,['style'=>"font-size: 12px; text-align: right; padding: 7px;",'colspan'=>'4']);


        }else if ($v['flat_coup']=='0') {
          // 优惠卷
          $shtml = Html::tag('td',$v['name'].' ' .$discount ,['style'=>"font-size: 12px; text-align: right; padding: 7px;",'colspan'=>'4']);

        }else if ($v['flat_coup']=='2') {
          // 满就优惠
          $shtml = Html::tag('td',$v['name'].' '.$discount ,['style'=>"font-size: 12px; text-align: right; padding: 7px;",'colspan'=>'4']);

        }else{
          $shtml = Html::tag('td',''.$v['name'].' Free '.$discount ,['style'=>"font-size: 12px; text-align: right; padding: 7px;",'colspan'=>'4']);
        }

        if($v['flat_coup']!='3'&&$v['flat_coup']!='5'){
          $shtml .= Html::tag('td','-'.Config::currencyMoney($last_total-$total),['style'=>'font-size: 12px; text-align: right; padding: 7px;']);
          $shtml = Html::tag('tr',$shtml,['class'=>'bd-all']);
          $mhtml .= $shtml;
        }

      }
    endif;
    if($this->order_type=='deliver'){
      $mhtml .= '<tr class="bd-all">
      <td style="font-size: 12px;text-align: right; padding: 7px;" colspan="4"><b>Deliver:</b></td>
      <td style="font-size: 12px; text-align: right; padding: 7px;">'.self::currencyMoney($shipment_postcode['price']).'</td>
      </tr>';
    }
    if(!empty($this->card_fee)){
      $mhtml .= '<tr class="bd-all">
      <td style="font-size: 12px; text-align: right; padding: 7px;" colspan="4"><b>Card Fee:</b></td>
      <td style="font-size: 12px; text-align: right; padding: 7px;">'.self::currencyMoney($this->card_fee).'</td>
      </tr>';

    }
    $mhtml .= '</tbody>
    <tfoot>
    <tr class="bd-all">
    <td style="font-size: 12px; text-align: right; padding: 7px;" colspan="4"><b>Total:</b></td>
    <td style="font-size: 12px; text-align: right; padding: 7px;">'.self::currencyMoney($this->total+$this->card_fee).'</td>
    </tr>';
    //if(isset($free_goods['memo'])){
    if(!empty($this->additional)){
      $mhtml .= '<tr class="bd-all">
      <td style="font-size: 12px; text-align: right; padding: 7px;" colspan="5"><b>FREE '.$this->additional.'</b></td>
      </tr>';
    }

    $mhtml .= '</tfoot>
    </table>
    <p>Remarks: '.$this->comment.'</p>';
    // Add & edit ASAP WORKAROUND
    $estimatedDate = date('Y',$this->shipment_time) == '1970' ? 'ASAP' : date('d/m/Y'.' - '.'H:i',$this->shipment_time);
    if($this->order_type=='deliver'){
      $mhtml .= 'The order is to be delivered: '.$estimatedDate;
      $mhtml .= '<p>Name: '.$this->shipment_name.'</p><p>Delivery Address: '.$this->shipment_addr1.', '.$this->shipment_addr2.', '.$shipment_city_new.', '.$shipment_postcode2_new.'</p><p>Phone: '.$this->shipment_phone.'</p>';
    }else{
      $mhtml .= 'The order is to be collected: '.$estimatedDate;

    }

    $mhtml .= '<p>Payment Status:&nbsp;'.($this->order_status=='payment' ? ('PAID by <span style="text-transform:capitalize;">'.(isset($setting['payment']) ? $setting['payment'] : 'Paypal').'</span>') : ''.$payMethod.' on '.$delivery).'</p>
    <br>
    <div style="background-color:#e6e6e6; padding:10px;"><strong>If you have any enquiries about this order, please give us a call at <strong><span>'.Config::getConfig('company_tel').'</span></strong></strong></div>

    </div>

    </div></center></body></html>';
    return $mhtml;
  }

  public function sendEmail($email,$from_email='',$flat=false,$setting=[])
  {
    $from_email = empty($from_email) ? \common\models\Config::getConfig('smtp_user') : $from_email;

    $mail = new EXTMailer();

    $mail->AddAddress($email);

    $mail->from = $from_email;      // 发件人邮箱

    // 邮件主题
    $mail->subject = Config::getConfig('company_name').' - Order #'.(empty($this->order_no) ? $this->invoice_no :  Config::orderFormat($this->order_no));
    // 邮件内容
    if($flat){
      //$bccmail = "testnew@milpohosting.co.uk"; // BCC email for APP
      //$mail->AddBCC($bccmail); // BCC function
      $mail->body = $this->mailTemplate($email,$setting);
    }else{
      $mail->body = $this->cusTemplate($email,$setting);
    }

    $mail->AltBody ="text/html";
    // echo $this->cusTemplate($email);
    // exit();
    return $mail->Send();
  }

  public static function subtractQuanity($order_id){
    $model = static::find()->where(['in','order_id',$order_id])->all();

    $flat = 0;
    foreach ($model as $k => $order) {
      $order_goods = OrderGoods::find()->where(['order_id'=>$order->order_id])->asArray()->all();

      foreach ($order_goods as $k => $v) {
        if(empty($v['feature'])||$v['feature']=='0:0'){
          $goods = Goods::findOne($v['goods_id']);
          if(!empty($goods)){
            $goods->quanity -= $v['quanity'];
            $goods->save();
            $flat++;
          }

        }else{
          $sku = Goodssku::findOne(['goods_id'=>$v['goods_id'],'feature_arr'=>$v['feature']]);
          // Goodssku::updateAll('quanity=quanity-'.$v['quanity'],['goods_id'=>$v['goods_id'],
          // 'feature_arr'=>$v['feature']]);
          if(!empty($sku)){
            $sku->quanity -= $v['quanity'];
            \Yii::$app->db->createCommand()->update("{{%goodssku}}",['quanity'=>$sku->quanity],['goods_id'=>$v['goods_id'],'feature_arr'=>$v['feature']])->execute();
            $flat++;
          }

        }

      }

      $coupon_id_arr = explode(',', $order->coupon);

      foreach ($coupon_id_arr as $k => $v) {
        $coupon = Coupon::findOne($v);
        if($coupon!==null){
          $coupon->coup_quanity = $coupon->coup_quanity-1;
          $coupon->save();
        }
        // @Coupon::updateAll('coup_quanity = coup_quanity -1',['coup_id'=>$v]);
      }

    }

    return $flat;

  }

  public static function findOrderGoods($order_id){
    $rs = (new \yii\db\Query())->select("og.*,g.pic")
    ->from("{{%order_goods}} og")
    ->leftJoin("{{%goods}} g",'g.id=og.goods_id')
    ->where(['og.order_id'=>$order_id])
    ->all();

    for ($i=0; $i <count($rs) ; $i++) {
      $rs[$i]['sku'] = [];
      if(!empty($rs[$i]['feature'])){
        $feature = explode(':', $rs[$i]['feature']);
        if(isset($feature['0'])){
          $rs_freature = (new Query())->select("gf.*")
          ->from("{{%goodsfeature}} gf")
          ->where(['gf.goods_id'=>$rs[$i]['goods_id'],'gf.fatt_id'=>$feature['0']])
          ->one();
          $rs[$i]['sku'][] = $rs_freature['options'];
        }

        if(isset($feature['1'])){
          $rs_freature = (new Query())->select("gf.*")
          ->from("{{%goodsfeature}} gf")
          ->where(['gf.goods_id'=>$rs[$i]['goods_id'],'gf.fatt_id'=>$feature['1']])
          ->one();
          $rs[$i]['sku'][] = $rs_freature['options'];
        }
      }

      $rs[$i]['goods_options'] =self::findOrderGoodsOptions($order_id,$rs[$i]['goods_id'],$rs[$i]['id']);

    }

    return $rs;

  }

  public static function findOrderGoodsOptions($order_id,$goods_id,$order_goods_id){
    $rs = OrderGoodsOptions::find()->with(['group','options'])->where([
      'order_id'=>$order_id,
      'goods_id'=>$goods_id,
      'order_goods_id'=>$order_goods_id])->asArray()->all();
      return $rs;
    }


    // 金額格式
    public static function currencyMoney($money){
      $currency = Config::getConfig('currency');
      return $currency.sprintf("%.2f",$money);
    }
  }
