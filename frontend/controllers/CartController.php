<?php

namespace frontend\controllers;
use frontend\components\CController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\web\NotFoundHttpException;

use common\models\Goods;
use common\models\Goodsmeta;
use common\models\Goodssku;
use common\models\GoodsOptionsGroup;
use common\models\GoodsOptions;
use common\models\Order;
use common\models\OrderGoods;
use common\models\OrderGoodsOptions;
use common\models\User;
use common\models\Useraddr;
use common\models\Time;
use common\models\ShipmentPostcode;
use common\models\Extension;
use common\models\Coupon;
use common\models\CouponGoods;
use common\models\Config;
use yii\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;

class CartController extends CController
{

  public $page_info = [];

  public function init(){
    parent::init();
    $this->enableCsrfValidation = false;

    $this->page_info['opentime'] = getPageByKey('opentime');
    $this->page_info['delivery'] = getPageByKey('delivery');

  }

  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['confirm','confirm-order','confirm-success'],
        'rules' => [
          [
            'actions' => ['confirm','confirm-order','confirm-success'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  // 加入到购物车动作
  public function actionAdd(){

    $data = ['status'=>0,'message'=>''];

    // 當POST添加有OPTIONS的產品時
    if(\Yii::$app->request->get('type')=='form'){

      $goods_id = \Yii::$app->request->post('goods_id');
      $selecter = \Yii::$app->request->post('options');
      $optional = \Yii::$app->request->post('goods_options');
      // var_dump($requiredoption);
      // var_dump($optional);
      $data = [];

      if(!empty($selecter)){
        foreach ($selecter as $k => $v) {
          $data[$v] = $optional[$v];
        }
      }

      $this->cart($goods_id,1,'add','',['options'=>$data],'');
      $data['message'] = \Yii::t('app','Added to your cart successfully!');
      echo json_encode($data);
      exit();
    }else if(\Yii::$app->request->get('type')=='options'){
      $goods_id = \Yii::$app->request->post('goods_id');

      $optiona_id = \Yii::$app->request->post('options_id');
      $optional[$optiona_id] =  1;
      $this->cart($goods_id,1,'add',$goods_id.'_'.$optiona_id,['child'=>$optional],'');
      $data['message'] = \Yii::t('app','Added to your cart successfully!');
      echo json_encode($data);
      exit();
    }else if(\Yii::$app->request->isPost){
      $goods_id = \Yii::$app->request->post('goods_id');

      $data = Goods::optionsData($goods_id);
      // 如果有OPTIONS選擇
      if(!empty($data)){
        $goods = getGoodsById($goods_id);

        $coupon = Coupon::getGoodsCoup($goods_id);

        if(isset($coupon['type'])){
          $goods['price'] = $coupon['type']=='0' ? $coupon['coupon']*$goods['price'] : ($goods['price']-$coupon['coupon']);
          $goods['coupon_id'] =  $coupon['coup_id'];
          $goods['coupon_name'] =  $coupon['name'];
          $goods['coupon_type'] =  $coupon['type'];
          $goods['coupon_price'] =  $coupon['coupon'];
        }



        echo $this->renderpartial('goodsinfo',['goods'=>$goods,
        'goods_options_group'=>$data,
      ]);
      exit();
    }else{
      // 當沒有OPTIONS時 直接加入購物車
      $spec = \Yii::$app->request->post('spec','');
      $size = \Yii::$app->request->post('size','');

      $this->cart($goods_id,1,'add','',[],$spec.':'.$size);

      echo json_encode(array('status'=>1,'message'=>\Yii::t('app','Added to your cart successfully!')));
      exit();
    }
  }


  echo json_encode($data);
  Yii::$app->end();
}

// 購車車支付流程
public function actionCheckout(){
  $this->getView()->title = 'Detail';

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

  endif;

  // end 假日不下單

  // no delivery
  $delivery_flat = Config::getConfig('delivery_flat');
  $post_info = \Yii::$app->request->post('cart');
  $delivery_flat_1 = isset($post_info['send'])&&$post_info['send']=='deliver' ? true : false;
  if(empty($post_info['send'])){
    $post_info = \Yii::$app->session['post_info'];
    $delivery_flat_1 = isset($post_info['send'])&&$post_info['send']=='deliver' ? true : false;
  }
  if($delivery_flat&&$delivery_flat_1){
    $delivery_message = \Yii::t('info','Oops! Delivery not available at this moment. Please try again later or collect order at our shop. ');
    \Yii::$app->getSession()->setFlash('message',$delivery_message);
    return $this->redirect(['/site/product']);
  }
  if(\Yii::$app->user->isGuest){
    if(\Yii::$app->request->isPost){
      \Yii::$app->session['return_url'] = Url::to(['/cart/checkout']);
      \Yii::$app->session['post_info'] = \Yii::$app->request->post();
    }
    return $this->redirect(['/site/login']);
  }

  if(isset(\Yii::$app->session['return_url'])) unset(\Yii::$app->session['return_url']);

  $type = \Yii::$app->request->get('type');

  // 當購物車沒有產品
  if(!isset(\Yii::$app->session['cart'])||empty(\Yii::$app->session['cart'])){
    \Yii::$app->getSession()->setFlash('message',\Yii::t('info','Cart is empty!'));
    return $this->redirect(['/site/product']);

  }
  //自提下單第二步
  if($type=='collection_two_step'){
    // 自提下單第二步
    $timelist = Time::getTimeList('collection');
    $post_info = \Yii::$app->session['post_info'];
    $allergy = getPageByKey('allergy');

    return $this->render('collection_two_step',['cart'=>$post_info['cart'],
    // 'shipment'=>$shipment,
    'timelist'=>$timelist,
    'allergy'=>$allergy]);

  }else if($type=='deliver_two_step'){

    $timelist = Time::getTimeList('delivery');
    $post_info = \Yii::$app->session['post_info'];
    $allergy = getPageByKey('allergy');

    return $this->render('deliver_two_step',['cart'=>$post_info['cart'],
    // 'shipment'=>$shipment,
    'timelist'=>$timelist,
    'allergy'=>$allergy]);
  }


  // 第一步
  $post_info = \Yii::$app->request->post();
  if(empty($post_info)&&isset(\Yii::$app->session['post_info'])){
    $post_info = \Yii::$app->session['post_info'];
  }

  \Yii::$app->session['post_info'] = $post_info;

  $sendMethod = $post_info['cart']['send'];
  $shipment = Useraddr::find()->where(['member_id'=>\Yii::$app->user->id,'flat'=>1])->asArray()->one();

  // 當今天是在假日開始和結束的日期之間
  if($holiday_flag){
    $holiday_message = !empty($holiday_message) ? $holiday_message : \Yii::t('info','Shop is now on holiday, please order again later!');
    \Yii::$app->getSession()->setFlash('message',$holiday_message);
    // return $this->redirect(['/site/product']);
  }
  if($sendMethod=='deliver'){
    return $this->render('deliver_onestep',['shipment'=>$shipment]);
  }else{
    return $this->render('collection_onestep',['shipment'=>$shipment]);
  }

}

// 確認下單
public function actionConfirm(){

  $userback =  \common\models\UserBack::find()->where(['member_id'=>\Yii::$app->user->id,'flat'=>1])->exists();

  // 當加入了黑名單時
  if(!empty($userback)){
    unset(\Yii::$app->session['cart']);
    unset(\Yii::$app->session['post_info']);
    unset(\Yii::$app->session['shipment']);
    Yii::$app->session->setFlash('message', \Yii::t('app','Your account has been locked. Please call us for more information.'));
    return $this->redirect(['/site/index']);
  }
  $this->getView()->title = 'Confirm';

  $cart  = \Yii::$app->session['cart'];

  if(empty($cart)){
    return $this->redirect(['/site/product']);
  }
  $post_info = \Yii::$app->session['post_info'];

  // 送貨信息
  $shipment = \Yii::$app->session['shipment'];

  $shipment_postcode = null;
  // var_dump($post_info['cart']['send']);

  if($post_info['cart']['send']=='deliver'){

    $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>$shipment['shipment_postcode']])->asArray()->one();

  }

  // 購物車信息

  $cart_list = $this->getCartGoods($cart);

  list($data,$total) = $cart_list;

  // 打折处理
  $coupon_list = $this->couponCart($total,$post_info);

  // 首單免費
  $list['free_first_discount'] = $coupon_list['0'];

  // 优惠卷
  $list['coupon'] = $coupon_list['1'];

  // 滿就減
  $list['free_up'] = $coupon_list['2'];

  // 滿就包郵件
  $list['free_ship'] = $coupon_list['3'];

  // 滿就送餐
  $list['free_goods'] = $coupon_list['4'];

  // 會員打折
  $list['free_member'] = $coupon_list['5'];

  $shipment_postcode['price'] = empty($list['free_ship'])&&$post_info['cart']['send']=='deliver' ? $shipment_postcode['price'] : 0;

  $list['shipment'] = $shipment;

  $list['shipment_price'] = $shipment_postcode['price'];

  // 支付列表
  $payment_list = Extension::getPayment();

  return $this->render('checkout',['cart'=>$data,
  'shipment_postcode'=>$shipment_postcode,
  'shipment'=>$shipment,
  'post_info'=>$post_info,
  'payment_list'=>$payment_list,
  'list'=>$list]);

}
// 下单支付保存订单
public function actionConfirmOrder(){

  $this->getView()->title = 'Confirm';

  $cart  = \Yii::$app->session['cart'];
  $id = \Yii::$app->request->get('id');

  if(empty($cart)&&empty($id)){
    return $this->redirect(['/site/product']);
  }

  $post_info = \Yii::$app->session['post_info'];

  // 送貨信息
  $shipment = \Yii::$app->session['shipment'];

  $shipment_postcode = null;
  if($post_info['cart']['send']=='deliver'){
    $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>$shipment['shipment_postcode']])->asArray()->one();
  }

  // 購物車信息
  $cart_list = $this->getCartGoods($cart);

  list($data,$total) = $cart_list;

  // 打折处理
  $coupon_list = $this->couponCart($total,$post_info);

  // 首單免費
  $list['free_first_discount'] = $coupon_list['0'];

  // 优惠卷
  $list['coupon'] = $coupon_list['1'];

  // 滿就減
  $list['free_up'] = $coupon_list['2'];
  // 滿就包郵件
  $list['free_ship'] = $coupon_list['3'];

  // 滿就送餐
  $list['free_goods'] = $coupon_list['4'];

  // 會員打折
  $list['free_member'] = $coupon_list['5'];

  $coupon_id_arr ='';

  if(isset($list['free_first_discount'])):
    // 1首单优惠
    $total = ($list['free_first_discount']['type']=='0') ? $list['free_first_discount']['coup_value']*$total : $list['free_first_discount']['coup_value'];
    $coupon_id_arr .= $list['free_first_discount']['coup_id'].',';
  endif;

  if(isset($list['coupon'])):
    // 2优惠卷
    $total = ($list['coupon']['type']=='0') ? $total*$list['coupon']['coup_value'] : ($total - $list['coupon']['coup_value']);
    $coupon_id_arr .= $list['coupon']['coup_id'].',';

  endif;

  if(isset($list['free_up'])&&!empty($list['free_up']['coup_value'])):
    // 3满就优惠
    $total = ($list['free_up']['type']=='0') ? $total*$list['free_up']['coup_value'] : ($total - $list['free_up']['coup_value']);
    $coupon_id_arr .= $list['free_up']['coup_id'].',';

  endif;

  if(isset($list['free_member'])&&!empty($list['free_member']['coup_value'])):
    // 5會員打折
    $total = ($list['free_member']['type']=='0') ? $total*$list['free_member']['coup_value'] : ($total - $list['free_member']['coup_value']);
    $coupon_id_arr .= $list['free_member']['coup_id'].',';
  endif;

  // 優惠ID


  $coupon_id_arr .= isset($list['free_ship']['coup_id']) ? $list['free_ship']['coup_id'].',' : '';
  $coupon_id_arr .= isset($list['free_goods']['coup_id']) ? $list['free_goods']['coup_id'] : '';

  $shipment_postcode['price'] = empty($list['free_ship'])&&$post_info['cart']['send']=='deliver' ? $shipment_postcode['price'] : 0;

  $total +=$shipment_postcode['price'];

  // 支付POST動作
  if(\Yii::$app->request->isPost){

    $type = \Yii::$app->request->post('type');
    $payment = Extension::getPayment($type);
    $list['payment'] = $payment;

    $card_fee = isset($payment['card_fee']) ? $payment['card_fee'] : 0;

    // $total += $card_fee;

    $order = null;

    // if(isset(\Yii::$app->session['order_id'])){
    //     $order_id = \Yii::$app->session['order_id'];
    //     $order = Order::findOne($order_id);
    // }

    if(empty($order)){
      $last_order = Order::find()->where('`delete`!=1 or `delete` is null')->orderBy('add_date desc')->one();
      $order_no = empty($last_order->order_no) ? 0 : (int)$last_order->order_no;
      $order_no++;

      $order = new Order;

      $transaction=Yii::$app->db->beginTransaction();
      try {

        $order->invoice_no = time();
        $order->order_no = $order_no;
        $order->invoice_prefix = $post_info['cart']['send'];
        $order->member_id = \Yii::$app->user->id;
        $order->shipment_name = isset($shipment['shipment_name']) ? $shipment['shipment_name'] : '';
        $order->shipment_phone = isset($shipment['shipment_phone']) ? $shipment['shipment_phone'] : '';
        $order->shipment_city = isset($shipment['shipment_city']) ? $shipment['shipment_city'] : '';
        $order->shipment_addr1 = isset($shipment['shipment_addr1']) ? $shipment['shipment_addr1'] : '';
        $order->shipment_addr2 = isset($shipment['shipment_addr2']) ? $shipment['shipment_addr2'] : '';
        $order->shipment_postcode = isset($shipment['shipment_postcode']) ? $shipment['shipment_postcode'] : '';
        $order->shipment_postcode2 = isset($shipment['shipment_postcode2']) ? $shipment['shipment_postcode2'] : '';
        $order->shipment_time = strtotime($post_info['cart']['time']);
        $order->comment = $post_info['cart']['note'];
        $order->order_type = $post_info['cart']['send'];
        $order->additional = isset($post_info['cart']['additional']) ? $post_info['cart']['additional'] : '';

        $order->total = sprintf('%.2f',$total);
        $order->order_status = 'pending';
        $order->currency_code = $this->getConfig('currency_code');
        $order->currency_value = $this->getConfig('currency_value');
        $order->member_ip = \Yii::$app->request->userIP;
        $order->add_date = time();
        $order->modify_date = $order->add_date;
        $order->payment_type = $type; //$payment['name'];
        $order->first_fee = !empty($free_first_discount)&&!$order_exists ? 1 : 0;
        $order->coupon = $coupon_id_arr;
        $order->card_fee = $card_fee;

        $order->save();

        $i = 0;
        // 訂單產品
        foreach ($data as $k => $v) {

          if(empty($v['id'])){
            break;
          }
          $order_goods = new OrderGoods;
          $order_goods->order_id = $order->primaryKey;
          $order_goods->goods_id = $v['id'];
          $order_goods->name = $v['title'];
          $order_goods->quanity = $v['quanity'];
          $order_goods->price = $v['price'];
          $order_goods->subtotal = $v['subtotal'];
          $order_goods->spec_id = isset($v['coupon_id']) ? $v['coupon_id'] : 0;

          $order_goods->save();

          if(isset($v['child'])){
            foreach ($v['child'] as $_k => $_v) {
              $order_goods_options = new OrderGoodsOptions;
              $order_goods_options->g_options_id = $_v['g_options_id'];
              $order_goods_options->order_id = $order->primaryKey;
              $order_goods_options->name = $_v['name'];
              $order_goods_options->price = $_v['price'];
              $order_goods_options->quanity = $_v['quanity'];
              $order_goods_options->required = 1;
              $order_goods_options->goods_id = $v['id'];
              $order_goods_options->g_options_group_id = isset($_v['group']['g_options_group_id']) ? $_v['group']['g_options_group_id'] : 0;
              $order_goods_options->order_goods_id = $order_goods->primaryKey;

              $order_goods_options->save();
            }
          }
          if(isset($v['options'])){
            foreach ($v['options'] as $_k => $_v) {

              $order_goods_options = new OrderGoodsOptions;
              $order_goods_options->g_options_id = $_v['g_options_id'];
              $order_goods_options->order_id = $order->primaryKey;
              $order_goods_options->name = $_v['name'];
              $order_goods_options->price = $_v['price'];
              $order_goods_options->quanity = $_v['quanity'];
              $order_goods_options->required = 0;
              $order_goods_options->goods_id = $v['id'];
              $order_goods_options->g_options_group_id = isset($_v['group']['g_options_group_id']) ? $_v['group']['g_options_group_id'] : 0;
              $order_goods_options->order_goods_id = $order_goods->primaryKey;

              $order_goods_options->save();

            }
          }

          $i++;
        }

        if($i>0){
          User::updateAll(['fen'=>((int)\Yii::$app->user->identity->fen+1)],['id'=>\Yii::$app->user->id]);
          $transaction->commit();
          \Yii::$app->session['order_id'] = $order->order_id;
          \Yii::$app->session['success_order'] = $order->order_id;

          unset(\Yii::$app->session['cart']);
          unset(\Yii::$app->session['post_info']);
          unset(\Yii::$app->session['shipment']);
        }else{
          $transaction->rollback();

        }
      } catch (Exception $e) {
        $transaction->rollback();
      }
    }

    // endif;
    orderpay:

    if(isset($payment['alias'])&&!empty($payment['alias'])&&class_exists($payment['alias'])){
      // 在线支付动作
      $list['payment']['flat'] = true;

      $pay = new $payment['alias'];
      $order_arr = ArrayHelper::toArray($order);
      $order_arr['total'] += $order_arr['card_fee'];
      $pay->setOrder($order_arr);
      $dopay = $pay->pay(2);

      // echo $dopay;exit();
      // renderpartial
      return $this->renderpartial('dopay',['dopay'=>$dopay,'list'=>$list]);
      return $this->render('confirm',['order'=>$order,
      'dopay'=>$dopay,
      'cart'=>$data,
      'shipment_postcode'=>$shipment_postcode,
      'shipment'=>$shipment,
      'post_info'=>$post_info,
      'list'=>$list]);
    }else{
      $list['payment']['flat'] = false;
      // var_dump(\Yii::$app->session['order_id']);
      // 不用在线支付的直接发邮件
      $order->sendEmail(Config::getConfig('server_mail'),'',true);
      // sleep(2);
      $order->sendEmail(\Yii::$app->user->identity->email);
      // 數量減1
      Order::subtractQuanity($order->order_id);

      return $this->render('confirm',['order'=>$order,
      'cart'=>$data,
      'shipment_postcode'=>$shipment_postcode,
      'shipment'=>$shipment,
      'post_info'=>$post_info,
      'list'=>$list]);
    }
  }
}

public function actionConfirmThank()
{
  $order_id = Yii::$app->session->get('success_order', 0);
  $order = Order::findOne(['order_id' => $order_id, 'member_id' => Yii::$app->user->identity->id]);
  if (!$order) return $this->redirect(['/site/product']);

  $order_goods = Order::findOrderGoods($order_id);

  // 優惠ID
  $coupon_id_arr = explode(',', $order->coupon);
  $coupon = null;
  foreach ($coupon_id_arr as $k => $v) {
    $model = Coupon::find()->where(['coup_id' => $v])->asArray()->one();
    if (!empty($model)) {
      $coupon[$model['flat_coup']] = $model;
    }
  }

  $shipment_postcode = null;
  if ($order->order_type == 'deliver') {
    $shipment_postcode = ShipmentPostcode::find()
    ->where(['postcode' => $order->shipment_postcode])
    ->asArray()
    ->one();
  }
  if (isset($coupon['3'])) {
    $shipment_postcode['price'] = 0;
  }

  $post_info = [];
  $post_info['cart']['time'] = date('l H:i', $order->shipment_time);
  $post_info['cart']['note'] = $order->comment;
  $post_info['cart']['send'] = $order->order_type;

  $list = [];
  $list['coupon'] = $coupon;
  $list['template'] = 'success';
  $list['payment']['flat'] = 0;
  if ($order->payment_type == 'paypal') {
    $list['payment']['flat'] = 1;
    $list['payment']['key'] = 'Paypal';
  } elseif ($order->payment_type == 'worldpay') {
    $list['payment']['flat'] = 1;
    $list['payment']['key'] = 'Cash';
  }

  $shipment = null;

  return $this->render('confirm', [
    'order' => $order,
    'cart' => $order_goods,
    'shipment_postcode' => $shipment_postcode,
    'shipment' => $shipment,
    'post_info' => $post_info,
    'list' => $list
  ]);
}

// 订单成功页面
public function actionConfirmSuccess(){
  //To make sure paypal is paid! IMPORTANT FOR DUPLICATED!
  sleep(5);
  $order_id = isset(\Yii::$app->session['success_order']) ? \Yii::$app->session['success_order'] : '';
  $order_id2 =\Yii::$app->request->get('id');

  $order_flag =\Yii::$app->request->get('flag');

  if(!empty($order_id2)){
    $order_id = \Yii::$app->request->get('id');
  }
  $order = Order::find()->where(['order_id'=>$order_id,'member_id'=>\Yii::$app->user->id])->one();

  if(empty($order)){
    return $this->redirect(['/member/default/order']);
  }else if($order->order_status=='pending'&&$order_flag!='order'){
    \Yii::$app->session->setFlash('message', \Yii::t('app','Transaction incomplete. Please make payment to confirm order. **Please ignore this message if you have paid by Paypal**'));
    return $this->redirect(['/member/default/review','id'=>$order_id]);
  }
  $order_goods = Order::findOrderGoods($order_id);

  // 優惠ID
  $coupon_id_arr = explode(',', $order->coupon);
  $coupon =null;
  foreach ($coupon_id_arr as $k => $v) {
    $model = Coupon::find()->where(['coup_id'=>$v])->asArray()->one();
    if(!empty($model)){
      $coupon[$model['flat_coup']] = $model;
    }

  }

  $shipment_postcode = null;
  if($order->order_type=='deliver'){
    $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>$order->shipment_postcode])->asArray()->one();
  }
  if(isset($coupon['3'])){
    $shipment_postcode['price'] = 0;
  }

  $list['coupon'] = $coupon;
  $list['template'] = 'success';

  $list['payment']['flat'] = 0;
  if($order->payment_type=='paypal'){
    $list['payment']['flat'] = 1;
    $list['payment']['key'] = 'Paypal';
  }else if($order->payment_type=='worldpay'){
    $list['payment']['flat'] = 1;
    $list['payment']['key'] = 'Cash';
  }

  $post_info['cart']['time'] = date('l H:i',$order->shipment_time);
  $post_info['cart']['note'] = $order->comment;
  $post_info['cart']['send'] = $order->order_type;

  return $this->render('confirm',['order'=>$order,'cart'=>$order_goods,
  'post_info'=>$post_info,
  'shipment_postcode'=>$shipment_postcode,
  'list'=>$list]);
}
// 购物车列表
public function actionInfo(){
  // unset(\Yii::$app->session['cart']);
  $cart  = \Yii::$app->session['cart'];

  $payment  = \Yii::$app->request->get('payment');

  if(!empty($payment)){
    $list['payment'] = Extension::getPayment($payment);
    $list['shipment'] = \Yii::$app->session['shipment'];
  }
  $time1 =$this->getConfig('Collection_Time');
  $time2 =$this->getConfig('Delivery_Time');




  // 购物车信息
  $cart_list = $this->getCartGoods($cart);

  list($data,$total) = $cart_list;

  $post_info = \Yii::$app->session['post_info'];

  $cookies = \Yii::$app->request->cookies;

  if(isset($_COOKIE['send'])){

    $data_info['send'] = $_COOKIE['send'];

  }else if(isset($post_info['cart']['send'])){

    $data_info['send'] = $post_info['cart']['send'];
  }else{
    $data_info['send'] = '';
  }
  // 满送列表
  $list_free_up = Coupon::freeUpData('total>:key0',[':key0'=>$total]);
  $list['list_free_up'] = $list_free_up;

  // 打折处理
  $coupon_list = $this->couponCart($total,$post_info);


  // 首單免費
  $list['free_first_discount'] = $coupon_list['0'];

  // 滿就減
  $list['coupon'] = $coupon_list['1'];

  // 滿就減
  $list['free_up'] = $coupon_list['2'];

  // 滿就包郵件
  $list['free_ship'] = $coupon_list['3'];

  $shipment_postcode = null;
  // var_dump($post_info['cart']['send']);
  // var_dump($list['shipment']['shipment_postcode']);
  if($post_info['cart']['send']=='deliver'&&isset($list['shipment']['shipment_postcode'])){
    $shipment_postcode = ShipmentPostcode::find()->where(['postcode'=>$list['shipment']['shipment_postcode']])->asArray()->one();
  }

  $shipment_postcode['price'] = empty($list['free_ship'])&&$post_info['cart']['send']=='deliver'&&isset($shipment_postcode['price']) ? $shipment_postcode['price'] : 0;
  $list['shipment_price'] = $shipment_postcode['price'];

  // 滿就送菜
  $list['free_goods'] = $coupon_list['4'];

  // 會員打折
  $list['free_member'] = $coupon_list['5'];

  $type = \Yii::$app->request->get('type');
  if(!empty($type)&&$type=='show'){
    return $this->render('info',['cart'=>$data,
    'data'=>$data_info,
    'list'=>$list,
    'post_info'=>$post_info,
    'send_time'=>['collection'=>$time1,'deliver'=>$time2]]);
  }else{
    return $this->renderpartial('info',['cart'=>$data,
    'data'=>$data_info,
    'post_info'=>$post_info,
    'list'=>$list,
    'send_time'=>['collection'=>$time1,'deliver'=>$time2]]);
  }
  // renderpartial
}

// ajax 信息
public function actionAjax(){

  $key = \Yii::$app->request->post('key','');
  $data = ['status'=>0,'message'=>\Yii::t('app','Error')];
  $type = \Yii::$app->request->post('type','');
  // 修改购物车方式
  if(!empty($key)){
    $action = \Yii::$app->request->post('action','');
    $goods_id = \Yii::$app->request->post('goods_id','');
    $spec = \Yii::$app->request->post('spec','');
    $size = \Yii::$app->request->post('size','');

    $this->cart($goods_id,1,$action,$key,[],$spec.':'.$size);

    echo json_encode(array('status'=>1,'message'=>\Yii::t('app','Success')));
    exit();
  }

  // 修改送货方式
  if(!empty($type)&&$type=='send'){
    $cart  = \Yii::$app->session['post_info'];
    $cart['cart']['send'] = \Yii::$app->request->post('send');
    if(!empty($cart['cart']['send'])){
      \Yii::$app->session['post_info'] = $cart;

      $cookies = \Yii::$app->response->cookies;

      $flat = $cookies->has('send') ? true :false;

      $cookies = \Yii::$app->response->cookies;

      $cookies->remove('send');

      setcookie("send", "", time()-3600);
      unset($_COOKIE['send']);

      $cookies->add(new \yii\web\Cookie([
        'name' => 'send',
        'value' => $cart['cart']['send'],
        'expire'=>time()+7*24*3600
      ]));

      setcookie('send',$cart['cart']['send'],time()+7*24*3600);
    }

    echo json_encode(array('status'=>1,'message'=>\Yii::t('app','Success')));
    exit();
  }

  // 操作免费送产品
  if(!empty($type)&&$type=='additional'){
    $coup_id = \Yii::$app->request->post('coup_id','');
    $list['coupon'] = Coupon::findOne($coup_id);
    $list['goods'] = CouponGoods::getCouponGoods($coup_id);
    echo $this->renderpartial('additional',['list'=>$list]);
    exit();
  }
  echo json_encode($data);

}

//checkout ajax
public function actionCheckoutAjax(){
  $type = \Yii::$app->request->post('type');
  $data = ['status'=>0,'message'=>''];

  if($type=='collection_one_step'){

    $shipment = \Yii::$app->request->post('shipment');

    \Yii::$app->session['shipment'] = $shipment;

    $data['url'] = Url::to(['/cart/checkout','type'=>'collection_two_step']);
    echo json_encode($data);
    exit();
  }else if($type=='deliver_one_step'){
    // $shipment = \Yii::$app->session['shipment'];

    $shipment_post = \Yii::$app->request->post('shipment');

    $shipment_post2 = strtoupper(preg_replace('/( *)/', '',$shipment_post['shipment_postcode2']));
    $exist_postcode = ShipmentPostcode::find()->where(['REPLACE(postcode, " ", "")' => $shipment_post2])->exists();
    $existedPostcode = ShipmentPostcode::find()->where(['REPLACE(postcode, " ", "")' => $shipment_post2])->asArray()->one();

    if($exist_postcode){
      $shipment_post['shipment_postcode'] = $existedPostcode['postcode'];
      \Yii::$app->session['shipment'] = $shipment_post;
      $data['status'] = 1;
      $data['url'] = Url::to(['/cart/checkout','type'=>'deliver_two_step']);

    }else{
      $data['message'] = strtoupper($shipment_post['shipment_postcode2']).' cannot be delivered';
    }
    echo json_encode($data);
    exit();
  }else if($type=='collection_two_step'){
    // 自提第二步
    $post_info = \Yii::$app->session['post_info'];

    $post_info['cart']['note'] = \Yii::$app->request->post('note');
    $post_info['cart']['time'] = \Yii::$app->request->post('time');

    \Yii::$app->session['post_info'] = $post_info;

    $data['url'] = Url::to(['/cart/confirm','type'=>'collection_confirm']);
    echo json_encode($data);
    exit();
  }else if($type=='deliver_two_step'){
    // 自提第二步
    $post_info = \Yii::$app->session['post_info'];

    $post_info['cart']['note'] = \Yii::$app->request->post('note');
    $post_info['cart']['time'] = \Yii::$app->request->post('time');

    \Yii::$app->session['post_info'] = $post_info;

    $data['url'] = Url::to(['/cart/confirm','type'=>'deliver_confirm']);
    echo json_encode($data);
    exit();
  }
}

// 單品加入購物車邏輯
public function actionGoodsinfo(){
  // 獲取GOODS_ID
  $goods_id = (\Yii::$app->request->isPost) ? \Yii::$app->request->post('goods_id') : \Yii::$app->request->get('goods_id');

  $goods = getGoodsById($goods_id);

  $data = Goods::optionsData($goods_id);

  // $order = Order::findOne(52);
  // $order->sendEmail("test@test.com");
  // $v = 52;
  // Order::subtractQuanity($v);
  // $order_model = Order::findOne($v);

  // $order_model->sendEmail(Config::getConfig('server_mail'),'',true,['payment'=>'Paypal']);

  // $order_model->sendEmail('445366484@qq.com','',false,['payment'=>'Paypal']);
  exit();
  if(empty($goods)){
    $this->exception();
  }
  return $this->render('goodsinfo',['goods'=>$goods,
  'goods_options_group'=>$data,
]);


}

// 下單發郵件
public function actionOrderMail(){
  $orderSession = \Yii::$app->session['order_id'];

  $order = Order::findOne($orderSession);

  $order->sendEmail($this->getConfig('smtp_user'),\Yii::$app->user->identity->email);

  $order->sendEmail(\Yii::$app->user->identity->email);
}

// 購物車操作
public function cart($goods_id,$quanity=1,$action='add',$key='',$options=[],$sku=''){
  $cart = \Yii::$app->session['cart'];
  if($key==''){
    if(empty($options)){
      $key = $goods_id;
    }else{
      $key = $goods_id.'_'.time();
    }

  }

  if($action=='add'){
    if(is_array($cart)&&array_key_exists($key, $cart)){
      $cart[$key]['quanity'] ++;
    }else{

      $cart[$key]['goods_id'] = $goods_id;
      $cart[$key]['quanity'] = 1;
      $cart[$key]['sku'] = $sku;
      $cart[$key]['options'] = $options;

    }
  }else{
    if(is_array($cart)&&array_key_exists($key, $cart)){
      $cart[$key]['quanity'] --;
      if($cart[$key]['quanity'] <=0){
        unset($cart[$key]);
      }
    }
  }

  \Yii::$app->session['cart'] = $cart;
}
// 购物车产品信息处理
protected function getCartGoods($cart){
  $data = [];
  $total = 0;

  if(!empty($cart)):
    foreach ($cart as $k => $v) {
      // 獲取產品信息
      $goods = getGoodsById($v['goods_id']);
      $coupon = Coupon::getGoodsCoup($v['goods_id']);

      if(isset($coupon['type'])){
        $goods['price'] = $coupon['type']=='0' ? $coupon['coupon']*$goods['price'] : ($goods['price']-$coupon['coupon']);
        $goods['coupon_id'] =  $coupon['coup_id'];
        $goods['coupon_name'] =  $coupon['name'];
        $goods['coupon_type'] =  $coupon['type'];
        $goods['coupon_price'] =  $coupon['coupon'];
      }

      $goods['price'] = $goods['price']>=0 ? $goods['price'] : 0;

      $subtotal = $goods['price'];
      $goods['quanity'] = $v['quanity'];

      // 处理子产品
      if(isset($v['options']['child'])&&!empty($v['options']['child'])){
        $optional_key = array_keys($v['options']['child']);
        $optional = GoodsOptions::find()->with('group')->where(['in','g_options_id',$optional_key])->asArray()->all();

        foreach ($optional as $_k => $_v) {
          $optional[$_k]['quanity'] = $v['options']['child'][$_v['g_options_id']];
          $_v['price'] = !empty($coupon) ? (isset($coupon['type'])&&$coupon['type']=='0' ? $coupon['coupon']*$_v['price'] : ($_v['price']-$coupon['coupon'])) : $_v['price'];
          $optional[$_k]['price'] = $_v['price'];

          if($_v['price_prefix']=='+'){
            $subtotal +=$_v['price']*$v['options']['child'][$_v['g_options_id']];
          }else{
            $subtotal -=$_v['price']*$v['options']['child'][$_v['g_options_id']];
          }

        }
        $goods['child'] = $optional;
      }

      // 處理當有產品的附加選項時
      if(isset($v['options']['options'])&&!empty($v['options']['options'])){
        $optional_key = array_keys($v['options']['options']);
        $optional = GoodsOptions::find()->with('group')->where(['in','g_options_id',$optional_key])->asArray()->all();

        foreach ($optional as $_k => $_v) {
          $optional[$_k]['quanity'] = $v['options']['options'][$_v['g_options_id']];
          // $_v['price'] = !empty($coupon)&&$goods['price']==0 ? (isset($coupon['type'])&&$coupon['type']=='0' ? $coupon['coupon']*$_v['price'] : ($_v['price']-$coupon['coupon'])) : $_v['price'];
          $optional[$_k]['price'] = $_v['price'];

          if($_v['price_prefix']=='+'){
            $subtotal +=$_v['price']*$v['options']['options'][$_v['g_options_id']];
          }else{
            $subtotal -=$_v['price']*$v['options']['options'][$_v['g_options_id']];
          }

        }
        $goods['options'] = $optional;
      }

      $goods['subtotal'] = $subtotal*$goods['quanity'];
      $total +=$goods['subtotal'];

      $data[$k] = $goods;

    }
  endif;
  $list = [$data,$total];
  return $list;
}

// 折扣处理
protected function couponCart($total=0,$post_info=[]){
  $coupon = isset($post_info['cart']['coupon']) ? $post_info['cart']['coupon'] : null;

  $list = Coupon::couponCart($total,$coupon);

  return $list;

}

protected function exception(){
  throw new NotFoundHttpException('The requested page does not exist.');
}
}
