<?php

namespace frontend\controllers;
use yii\helpers\ArrayHelper;
use common\models\Extension;
use common\models\Order;
use common\models\Config;

class OrderController extends \frontend\components\CController
{
    public $page_info = [];
    
	public function init() {
        $this->getView()->title = 'Order';
		$this->enableCsrfValidation = false;
	}

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPay(){
        if(\Yii::$app->request->isPost){
            $order_id = \Yii::$app->request->post('order_id');

            \Yii::$app->session['order_id'] = $order_id;
            

            $order = \common\models\Order::findOne($order_id);

            $type = \Yii::$app->request->post('type');
            $payment = \common\models\Extension::getPayment($type);

            if(!empty($type)){
                $order->payment_type = $type;
                $order->card_fee = 0;
                $order->update();
            }

            if(isset($payment['alias'])&&!empty($payment['alias'])){
                $pay = new $payment['alias'];
                $order_arr = ArrayHelper::toArray($order);
                $order_arr['total'] += (float)$payment['card_fee'];
                
                $order->card_fee = $payment['card_fee'];

                $order->save();

                $pay->setOrder($order_arr);
                $dopay = $pay->pay(2);
                \Yii::$app->session['success_order'] = $order_id;
                
                return $this->renderpartial('confirm',['dopay'=>$dopay]);
            }else{
                // 不用在线支付的直接发邮件
                $order->sendEmail(Config::getConfig('server_mail'),'',true);
                sleep(2);
                $order->sendEmail(\Yii::$app->user->identity->email);
                // 數量減1
                Order::subtractQuanity($order->order_id);
                
                return $this->redirect(['/cart/confirm-success','id'=>$order->order_id,'flag'=>'order']);
                // return $this->redirect(\Yii::$app->request->getReferrer());
            }
        }
    }

    public function actionNotify() {
    	if (\Yii::$app->request->get('type')) {
            $type = \Yii::$app->request->get('type');

    		if (strtolower(\Yii::$app->request->get('type')) === 'paypal') {
    			$ext = Extension::getPayment('Paypal');
    			$payment = new $ext['alias'];
    			$payment->doNotify(\Yii::$app->request->post());
    			// Order::updateAll(['order_status' => rand()], 'order_id = :id', [':id' => 25]);
    		}else{
                $ext = Extension::getPayment($type);
                $payment = new $ext['alias'];
                $payment->doNotify(\Yii::$app->request->post());
            }
		}
	}
    	
    public function actionReturn() {
        $order_id = \Yii::$app->session['order_id'];

    	if (\Yii::$app->request->get('type')) {
            $type = \Yii::$app->request->get('type');

            
    		if (strtolower(\Yii::$app->request->get('type')) === 'paypal') {
    			$ext = \common\models\Extension::getPayment('Paypal');
    			$payment = new $ext['alias'];
    			$payment->doReturn();
    			// return $this->redirect(\Yii::$app->urlManager->createUrl(['/site/index']));
    		}else{
                $type = \Yii::$app->request->get('type');
                $ext = \common\models\Extension::getPayment($type);
                $payment = new $ext['alias'];
                $payment->doReturn();
                // \Yii::$app->session->setFlash('message', \Yii::t('app','Payment success,please check your order for confirm already pay'));
                // return $this->redirect(\Yii::$app->urlManager->createUrl(['/member/default/order']));
                
            }
    	}
        return $this->redirect(['/cart/confirm-success','id'=>$order_id]);
        // return $this->redirect(\Yii::$app->urlManager->createUrl(['/site/index']));
    }

    public function actionCancel() {
        return $this->redirect(\Yii::$app->urlManager->createUrl(['/site/index']));
    }

    // 獲取訂單狀態是不是已經支付成功
    public function actionAjax(){
        if(\Yii::$app->request->isPost){
            $order_id = \Yii::$app->session['order_id'];
            $exists = Order::find()->where(['order_id'=>$order_id,'order_status'=>'payment'])->exists();
            // return true;
            return $exists;
        }
        return false;
    }
}
