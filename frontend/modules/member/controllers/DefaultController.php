<?php

namespace frontend\modules\member\controllers;

use frontend\components\CController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Useraddr;
use common\models\UserAttr;
use common\models\OrderGoodsOptions;
use common\models\OrderReview;
use common\models\Order;
use common\models\Coupon;
use common\models\ShipmentPostcode;
use yii\db\Query;
use yii\data\Pagination;

class DefaultController extends CController
{

    public $layout = 'main';
    public $page_info = [];

    public function init(){
        parent::init();
        $this->getView()->title = \Yii::t('app','Member Center');

        $this->page_info['opentime'] = getPageByKey('opentime');
        $this->page_info['delivery'] = getPageByKey('delivery');

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['ratings','set-default',
                                    'index','ajax','address',
                                    'addressdelete','order','review'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $member = User::findone(['id'=>\Yii::$app->user->id]);
        // $userattr = UserAttr::findone(['member_id'=>\Yii::$app->user->id]);
        $addr = Useraddr::findone(['member_id'=>\Yii::$app->user->id,'flat'=>1]);
        if(empty($addr)) $addr = new Useraddr;
        // $addr = Useraddr::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
        // var_dump($addr);exit();
        $type = 'base';
        // 修改用戶基本信息
        if(isset($_POST['User'])){
            // $member->sex = \Yii::$app->request->post('User')['sex'];
            // $member->phone = \Yii::$app->request->post('User')['phone'];
            // $member->offers = \Yii::$app->request->post('User')['offers'];
            $member->attributes = \Yii::$app->request->post('User');

            $year = \Yii::$app->request->post('year');
            $month = \Yii::$app->request->post('month');
            $day = \Yii::$app->request->post('day');
            if ($year && $month && $day) $member->birthdate = $year.'-'.$month.'-'.$day;

            if($member->validate()){
                $member->save();

                $addr->attributes = \Yii::$app->request->post('Useraddr');
                if(empty($addr->member_id))
                    $addr->member_id = \Yii::$app->user->id;

                $addr->flat = 1;
                $addr->save();
                // var_dump($addr->getErrors());
                \Yii::$app->session->setFlash('message', \Yii::t('app','Your account details have been updated successfully!'));
                return $this->redirect(['index']);
                // \Yii::$app->session->setFlash('info', Yii::t('app','Success'));
            }else{
                $message = '';
                foreach ($member->getErrors() as $k => $v) {
                    $message.= isset($v['0'])? $v['0'] : '';

                }
                \Yii::$app->session->setFlash('message', $message);
            }
            // \Yii::$app->session->setFlash('success', 'This is the message');
        }

        // 修改密碼信息
        if(isset($_POST['Password'])){
            $type = 'password';
            $old = \Yii::$app->request->post('Password')['old'];
            $new = \Yii::$app->request->post('Password')['new'];
            $confirm = \Yii::$app->request->post('Password')['confirm'];

            $flat = $member->validatePassword($old);
            if(!empty($old)&&!empty($new)&&!empty($confirm)&&!empty($confirm)&&$flat&&($new==$confirm)){
                $member->setPassword($new);
                $member->save();
                \Yii::$app->session->setFlash('message', \Yii::t('app','Success'));
            }else{
                \Yii::$app->session->setFlash('message', \Yii::t('app','password error'));
            }

        }
        return $this->render('_base_form',['member'=>$member,'addr'=>$addr]);
        // return $this->render('index',['member'=>$member,'addr'=>$addr,'type'=>$type]);
    }

    // 地址管理
    public function actionAddress(){

        // var_dump(\Yii::$app->user);
        $id = \Yii::$app->request->get('id');
        $isNewRecord = false;

        if(!empty($id)){
            $useraddr = Useraddr::findone(['id'=>$id]);
        }else{
            $useraddr = new Useraddr();
            $isNewRecord = true;
            $useraddr->member_id = \Yii::$app->user->id;
        }
        $useraddr->scenario = 'address';

        if ($useraddr->load(\Yii::$app->request->post())) {
            // $useraddr->flat = isset(\Yii::$app->request->post('Useraddr')['flat']) ? \Yii::$app->request->post('Useraddr')['flat'] : 0;

            // var_dump($model->modifydate);
            if($useraddr->validate()){
                $useraddr->save();

                if($useraddr){
                    $message = $isNewRecord ? \Yii::t('app','New address added successfully!') : \Yii::t('app','Address updated successfully!');
                    \Yii::$app->getSession()->setFlash('message', $message);
                }

                return $this->redirect(['address']);
            }

        }

        $addr = Useraddr::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
        return $this->render('_addr_form',['addr'=>$addr,'useraddr'=>$useraddr]);
    }
    //设置默认地址
    public function actionSetDefault(){
        $id = \Yii::$app->request->get('id');

        $transaction=\Yii::$app->db->beginTransaction();
        try {
            $flat = Useraddr::updateAll(['flat'=>1],['id'=>$id,'member_id'=>\Yii::$app->user->id]);

            if($flat){
                Useraddr::updateAll(['flat'=>0],'id!=:key0 and member_id=:key1',[':key0'=>$id,':key1'=>\Yii::$app->user->id]);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
        }
        \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));
        return $this->redirect(['address']);
    }
    // 删除收货地址
    public function actionAddressdelete($id){
        $model = Useraddr::findOne($id);
        if(isset($model->flat)&&$model->flat){
            return $this->redirect(['address']);
        }
        $model->delete();
        \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));
        return $this->redirect(['address']);
    }

    // 订单
    public function actionOrder(){
        $last_seven = strtotime('-7 days');

        $data = (new Query())->select('o.*,og.name,og.quanity,og.price,e.alias')
                            ->from("{{%order}} o")
                            ->leftJoin("{{%order_goods}} og",'og.order_id=o.order_id')
                            ->leftJoin("{{%extension}} e",'e.`key`=o.payment_type')
                            ->where("o.member_id=:key0",[':key0'=>\Yii::$app->user->id])
                            ->andWhere('o.add_date>=:key1',[':key1'=>$last_seven])
                            ->orderBy('add_date desc')
                            ->groupBy('o.order_id');

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);

        $data = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('order',['order_list'=>$data,'pages'=>$pages]);
    }
    // 訂單詳情
    public function actionReview($id){
        $order = Order::find()->where(['member_id'=>\Yii::$app->user->id,'order_id'=>$id])->one();


        if(!empty($order)){

            // 當支付未完成的時候
            $flat = \Yii::$app->request->get('flat');
            if($flat==1){
                \Yii::$app->session->setFlash('message', \Yii::t('app','Transaction incomplete. Please make payment to confirm order.'));
            }
            if($flat==3){
                \Yii::$app->session->setFlash('message', \Yii::t('app','Thanks for ordering! Please wait a few minutes for the payment status to update.'));
            }

            $order_goods = $this->findOrderGoods($id);
            $order_review = OrderReview::find()->where(['order_id'=>$id,'member_id'=>\Yii::$app->user->id])->one();
            $shipment = Useraddr::find()->where(['member_id'=>\Yii::$app->user->id,'flat'=>1])->asArray()->one();

            if(empty($order_review)){
                $order_review = new OrderReview;

                $order_review->loadDefaultValues();
                $order_review->order_id = $order->order_id;
                $order_review->member_id = \Yii::$app->user->id;
                $order_review->name = $shipment['shipment_name'];
            }

            if(\Yii::$app->request->post('Order')){
                // 提交POST關閉訂單
                $post_info = \Yii::$app->request->post('Order');
                $order->order_status = $post_info['order_status'];
                if($order->save()){
                    \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));
                }

            }else if ($order_review->load(\Yii::$app->request->post())&&$order_review->isNewRecord) {
                // 提交POST评价
                $order_review->name = empty($order_review->name) ? $shipment['shipment_name'] :$order_review->name;
                $order_review->add_date = date('Y-m-d H:i:s');
                // var_dump($model->modifydate);
                if($order_review->validate()){
                    $order_review->save();

                    \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));
                    return $this->redirect(['default/order']);
                }

            }

            // 首單免費

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

            // 支付列表
            $payment_list = \common\models\Extension::getPayment();
            $payment = \common\models\Extension::getPayment($order->payment_type);

            $list['online_pay'] = !empty($payment['alias'])&&class_exists($payment['alias']);

            return $this->render('review',['order'=>$order,
                                        'order_goods'=>$order_goods,
                                        'order_review'=>$order_review,
                                        'shipment_postcode'=>$shipment_postcode,
                                        'coupon'=>$coupon,
                                        'list'=>$list,
                                        'payment_list'=>$payment_list]);
        }else{
            return $this->redirect(['/member/default/order']);
        }

    }

    //订单评价列表
    public function actionRatings(){

        $list = OrderReview::find()->where(['member_id'=>\Yii::$app->user->id])
                                ->orderBy('add_date desc');

        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '50']);

        $data = $list->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()->all();
        return $this->render('ratings',['list'=>$data,'pages'=>$pages]);


    }

    public function actionAjax(){
        $shipment = \Yii::$app->request->post('Shipment');
        if(isset($shipment['id'])&&!empty($shipment['id'])){
            $model = Useraddr::findone(['id'=>$shipment['id']]);

        }else{
            $model = new Useraddr();
            $model->member_id = \Yii::$app->user->id;
        }

        $model->attributes = $shipment;

        if($model->validate()){
            $model->save();
            echo '1';
            exit();
        }
        // var_dump($model->attributes);
        // var_dump($model->getErrors());
        echo '0';
    }

    protected function findOrderGoods($order_id){
        $rs = (new Query())->select("og.*,g.pic")
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

            $rs[$i]['goods_options'] =$this->findOrderGoodsOptions($order_id,$rs[$i]['goods_id'],$rs[$i]['id']);

        }

        return $rs;

    }

    protected function findOrderGoodsOptions($order_id,$goods_id,$order_goods_id){
        $rs = OrderGoodsOptions::find()->with(['group','options'])->where([
                                    'order_id'=>$order_id,
                                    'goods_id'=>$goods_id,
                                    'order_goods_id'=>$order_goods_id])->asArray()->all();
        return $rs;
    }
}
