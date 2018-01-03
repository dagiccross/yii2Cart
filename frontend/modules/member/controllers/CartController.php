<?php

namespace frontend\modules\member\controllers;

use frontend\components\CController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Useraddr;
use yii\db\Query;
use common\models\Order;
use common\models\OrderGoods;
use common\models\OrderAction;
use yii\data\Pagination;

class CartController extends CController
{

    public $layout = 'main';
    public $active = 'index';

    public function init(){
		parent::init();
		$this->getView()->title = \Yii::t('app','Member Center');
	}

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['checkout','payment'],
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

    // 訂單信息
    public function actionCheckout(){

        $order = \Yii::$app->session['OrderCart'];
        $payment = getPayment();

        if(\Yii::$app->request->isPost){

            $order  = \Yii::$app->request->post('order');

        }else if(\Yii::$app->request->get('id')){
            $id[]  = \Yii::$app->request->get('id');
            $order = $id;
        }

        if(empty($order)){
            \Yii::$app->session->setFlash('info', \Yii::t('app','Please select order!'));
            return $this->redirect(['/member/default/order']);
        }else{
            \Yii::$app->session['OrderCart'] = $order;
        }
        // var_dump($order);
        // var_dump(\Yii::$app->user->id);
        // var_dump(\Yii::$app->user->identity);
        $data = Order::find()->where(['member_id'=>\Yii::$app->user->id])->andWhere(['in','order_id',$order])->asArray()->all();
        // var_dump($data);exit();

        for ($i=0; $i <count($data) ; $i++) { 

            $data[$i]['order_goods'] = $this->getOrderGoods($data[$i]['order_id']);

        }
        
                 
        return $this->render('checkout', ['cart' => $data,'payment'=>$payment]);
    }

    // 訂單支付動作
    public function actionPayment(){

        $order = \Yii::$app->session['OrderCart'];

        if(empty($order)){
            \Yii::$app->session->setFlash('info', \Yii::t('app','Please select order!'));
            return $this->redirect(['/member/default/order']);
        }

        $payment_id = \Yii::$app->request->post('payment-method');

        $order_id = \Yii::$app->request->post('order_id');
        $invoice_no = \Yii::$app->request->post('invoice_no');
        $total = \Yii::$app->request->post('total');

        // 設置支付總價格
        $data['total'] = 0;
        for ($i=0; $i <count($total) ; $i++) { 
            $data['total'] += $total[$i];

         } 

        // 設計支付名稱
        $data['order_id']  = '';
        for ($i=0; $i <count($order_id) ; $i++) { 
            if($i==0){
                $data['order_id'] .= $order_id[$i];
            }else{
                $data['order_id'] .= ','.$order_id[$i];
            }
            

         }

        $payment = getExtensionById((int)$payment_id);

        $payment = new $payment['alias']($data);
        echo $payment->pay(1);

    }
    // 獲取訂單產品
    protected function getOrderGoods($order_id){
        $data = (new Query())->select("og.*,m.title,g.pic")
                    ->from("{{%order_goods}} og")
                    ->leftJoin("{{%goods}} g",'g.id=og.goods_id')
                    ->leftJoin("{{%goodsmeta}} m",'m.goods_id=og.goods_id')
                    ->where('m.language=:key1',[':key1'=>\Yii::$app->language])
                    ->andWhere(['og.order_id'=>$order_id])
                    ->all();
        return $data;
    }
}
