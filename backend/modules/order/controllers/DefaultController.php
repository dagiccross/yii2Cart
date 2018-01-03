<?php

namespace backend\modules\order\controllers;

use common\models\Goods;
use common\models\Goodsmeta;
use common\models\Order;
use common\models\Config;
use common\models\Coupon;
use common\models\OrderGoods;
use common\models\OrderGoodsOptions;
use common\models\OrderAction;
use common\models\User;

use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\db\Query;
class DefaultController extends Controller
{
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update',
                         'view','delete','ajax','goods-list','output'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->power=='user'||'admin';
                        }
                    ],
                    [
                        'actions'=>['index','update','output','view'],
                        'allow'=>true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    public function actionIndex()
    {
        $flat = \Yii::$app->request->get('flat');
        $flat = empty($flat) ? 0 : $flat;
        $list['flat'] = $flat;
        // 查询条件
        $start_date = \Yii::$app->request->get('start_date');
        $end_date = \Yii::$app->request->get('end_date');
        $order_status = \Yii::$app->request->get('order_status');

        $query = new Query;
        $rs = $query->select('o.*,og.name,og.quanity,og.price')
                            ->from("{{%order}} o")
                            ->leftJoin("{{%order_goods}} og",'og.order_id=o.order_id');
                            // ->where("m.language=:language",[':language'=>Yii::$app->language])
        // 赛选条件
        if(!empty($flat)){
            $rs = $rs->where('o.delete=:k0',[':k0'=>$flat]);
        }else{
            $rs = $rs->where('o.delete!=1 or o.delete is null');
        }

        if(!empty($end_date)){
            $end_date = strtotime($end_date . ' 23:59:59');
            $rs = $rs->andWhere('add_date<=:key0',[':key0'=>$end_date]);
        }

        if(!empty($start_date)){
            $start_date = strtotime($start_date . ' 0:0:0');
            $rs = $rs->andWhere('add_date>=:key1',[':key1'=>$start_date]);
        }

        if(!empty($order_status)){
            // $rs = $rs->andWhere('order_status=:key2',[':key2'=>$order_status]);
            $rs = Order::searchByPaymentStatus($rs,$order_status);
        }

        $rs = $rs->groupBy('o.order_id')
                ->orderBy('o.add_date desc')
                ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $rs,
            'sort' => [
                'attributes' => [ 'order_id'],
            ],
            'key'=>'order_id',
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        // $order = Order::findOne(2);
        // $data = ArrayHelper::toArray($order);
        // var_dump($data);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'list'=>$list,
        ]);
    }

    public function actionGoodsList(){

    }
    public function actionCreate(){
        $model = new Order();
        $model->loadDefaultValues();

        $model->invoice_no = time();

        return $this->render('create',['model'=>$model]);
    }

    public function actionView($id){
        $model = $this->findModel($id);
        $member = User::findOne($model->member_id);
        if(\Yii::$app->request->post('Order')){
            if(empty(\Yii::$app->request->post('Order')['comment'])){
                \Yii::$app->getSession()->setFlash('message', \Yii::t('info','Comment not null'));
            }else{
                $payment_status = \Yii::$app->request->post('Order')['payment_status'];

                if(!empty($payment_status)){
                    $model->modifyPaymentStatus($payment_status);

                    // var_dump($model->attributes);
                    // exit();
                    $orderaction = new OrderAction();

                    $orderaction->order_id = $model->order_id;
                    $orderaction->user_id = \Yii::$app->user->id;
                    $orderaction->user_flat = 'admin';
                    $orderaction->adddate = time();
                    $orderaction->order_status = $model->order_status;
                    $orderaction->comment = \Yii::$app->request->post('Order')['comment'];

                    $orderaction->save();

                    // $model->order_status = $order_status;
                    $model->save();

                    \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));

                }
            }
        }
        // 優惠ID
        $coupon_id_arr = explode(',', $model->coupon);
        $coupon =null;
        foreach ($coupon_id_arr as $k => $v) {
            $model_coupon = Coupon::find()->where(['coup_id'=>$v])->asArray()->one();
            if(!empty($model_coupon)){
                $coupon[$model_coupon['flat_coup']] = $model_coupon;
            }

        }
        $list['coupon'] = $coupon;

        $list['shipment_price'] = 0;
        if($model->order_type=='deliver'){
           $shipment_postcode = \common\models\ShipmentPostcode::find()->where(['postcode'=>$model->shipment_postcode])->asArray()->one();
             $list['shipment_price'] = $shipment_postcode['price'];
        }
        if(isset($coupon['3'])){
            $list['shipment_price'] = 0;
        }

        return $this->render('view',[
            'model' => $model,
            'member' => $member,
            'list'=>$list,
            'goods'=>$this->findOrderGoods($id),
            ]);
    }

    // 导出CSV
    public function actionOutput(){
        $start_date = \Yii::$app->request->post('start_date');
        $end_date = \Yii::$app->request->post('end_date');
        $order_status = \Yii::$app->request->post('order_status');

        //動態
        $flat = \Yii::$app->request->get('flat');
        $flat = empty($flat) ? 1 : 0;
        $end_date = empty($end_date) ? time() : strtotime($end_date);
        $order_list = Order::find()
                        ->where('add_date<=:key0',[':key0'=>$end_date]);

        if($flat){
            $order_list = $order_list->andWhere('`delete` !=:k1 or `delete` is null',[':k1'=>$flat]);
        }else{
            $order_list = $order_list->andWhere('`delete` =:k1 ',[':k1'=>1]);
        }
        if(!empty($start_date)&&false){
            $start_date = strtotime($start_date);
            $order_list = $order_list->andWhere('add_date>=:key1',[':key1'=>$start_date]);
        }

        if(!empty($order_status)&&false){
            $order_list = $order_list->andWhere('order_status=:key2',[':key2'=>$order_status]);
        }
        $order_list = $order_list->asArray()->all();

        $data = "Date,Order ID,Amount,Payment Status\n";
        foreach($order_list as $k => $v){

            $data .= date('d/m/Y',$v['add_date']).",".Config::orderFormat($v['order_no']).",".Config::moneyFormat($v['total']).",".Order::getPaymentStatus($v['order_status'],$v['payment_type'],$v['order_type'])."\n";

        }

        if($flat){
          Order::updateAll(['`delete`'=>1]);
        }

        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".date('d-m-Y').".csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
        exit;


    }

    public function actionDelete($id){

        $transaction=\Yii::$app->db->beginTransaction();
        $type = \Yii::$app->request->get('type');

        try {
            if($type=='all'){
                $order_list = Order::find()->select('order_id')->where('`delete`=1')->asArray()->all();
                // var_dump($order_list);
                // exit();
                Order::deleteAll(['in','order_id',$order_list]);
                OrderGoods::deleteAll(['in','order_id',$order_list]);
                OrderAction::deleteAll(['in','order_id',$order_list]);
                OrderGoodsOptions::deleteAll(['in','order_id',$order_list]);
                $transaction->commit();
                \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));

            }else{
                $this->findModel($id)->delete();
                OrderGoods::deleteAll(['order_id'=>$id]);
                OrderAction::deleteAll(['order_id'=>$id]);
                OrderGoodsOptions::deleteAll(['order_id'=>$id]);
                $transaction->commit();
                \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));
            }
        } catch (Exception $e) {
            $transaction->rollback();
        }
        return $this->redirect(['index']);
    }
    protected function findModel($id){

        return Order::findOne($id);

    }

    protected function findOrderGoods($order_id){
        $rs = (new Query())->select("og.*,g.pic,g.sku as sku_no")
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
        $rs = OrderGoodsOptions::find()->with(['options'])->where(['order_id'=>$order_id,
                                            'goods_id'=>$goods_id,
                                            'order_goods_id'=>$order_goods_id])->asArray()->all();
        return $rs;
    }
}
