<?php

namespace thridpart\payment\paypal;

use yii;
use yii\helpers\ArrayHelper;
use thridpart\payment\Payment;
use yii\db\Query;
use yii\helpers\Url;
use common\models\Order;
use common\models\Config;
class Paypal extends Payment
{
    public $key = '';
    // __construct
    public function __construct($order=array()){

        $this->order = $order;
        $this->config['post_url'] = 'https://www.paypal.com/cgi-bin/webscr';
        $this->config['sandbox_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        $this->config['return_url'] = Url::to(['order/return','type'=>'paypal'],true);
        $this->config['notify_url'] = Url::to(['order/notify','type'=>'paypal'],true);
        $this->config['cancel_return'] = Url::to(['order/cancel','type'=>'paypal'],true);

        $currency_code =  Config::getConfig('currency_code');
        $this->config['currency_code'] = empty($currency_code) ? 'GBP' : $currency_code;
        // $this->config['currency_code'] = 'HKD';

        $rs = (new Query())->select('m.options')
                        ->from("{{%extensionmeta}} m")
                        ->leftJoin("{{%extension}} e","m.ext_id=e.id")
                        ->where("e.`key`=:key and m.language=:language",[':key'=>'paypal',':language'=>Yii::$app->language])
                        ->one();
        $options = @unserialize($rs['options']);
        $this->key = isset($rs['key']) ? $rs['key'] : 'paypal';

        $config = array();
        $opp_data = [];
        // 獲取OPTIONS保存的配置信息
        if(isset($options['0'])&&isset($options['1'])){
            foreach ($options as $k => $v) {
                $config[$v['key']] = $v['value'];
            }
        }else{
            $config = $options;
        }
        $this->setConfig($config);

        if(isset($this->config['test'])&&$this->config['test']){
            $this->config['action'] = $this->config['sandbox_url'];
        }else{
            $this->config['action'] = $this->config['post_url'];
        }
    }
    // 定義支付动作
    public function pay($flat=0){
        $pay = $this->getFrom($flat);
        return $pay;
    }

    public function doNotify($po) {

        $filename = \Yii::getAlias("@runtime/post.txt");

        $file = @fopen($filename, "a");
        // echo $filename;
        $txt = "\nPayPal notify start at: ". date('Y-m-d H:i:s') . ", Order id: " . (isset($po['custom']) ? $po['custom'] : 'null') . "\n";
        $txt .= json_encode($po) . "\n";
        @fwrite($file, $txt);
        @fclose($file);

        if ($po['custom']) {
            $order_id = $po['custom'];
        } else {
            $order_id = 0;
        }

        $order_id = explode(',', $order_id);
        $order = Order::find()->where(['in','order_id',$order_id])->exists();

        if ($order > 0) {
            $request = 'cmd=_notify-validate';

            foreach ($po as $key => $val) {
                $request .= '&' . $key . '=' . urlencode(html_entity_decode($val, ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->config['test']) && $this->config['test']) {
                $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
            } else {
                $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
            }

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($curl);

            // if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && $po['payment_status']) {
            if(isset($po['payment_status'])&&strtolower($po['payment_status'])=='completed'){

                Order::updateAll(['order_status' => 'payment','payment_type'=>$this->key], ['in','order_id',$order_id]);
                foreach ($order_id as $k => $v) {

                    try {
                        Order::subtractQuanity($v);
                        // 發關EMAIL 當notify連接修改訂單成功時

                        $order_model = Order::findOne($v);
                        if(!empty($order_model)){
                            $member = \common\models\User::findOne($order_model->member_id);
                            $flat = $order_model->sendEmail(Config::getConfig('server_mail'),'',true,['payment'=>'Paypal']);
                            //sleep(2);
                            $flat = $order_model->sendEmail($member->email,'',false,['payment'=>'Paypal']);
                        }
                    } catch (Exception $e) {

                    }


                }
            }

            curl_close($curl);
        }
    }

    public function doReturn() {

        $orderSession = \Yii::$app->session['order_id'];
        // $orderSession = \Yii::$app->request->get('cm');

        $order = Order::findOne($orderSession);

        $payment_status = \Yii::$app->request->get('st');

        if(!\Yii::$app->user->isGuest&&$order!=null){
            if($order->order_status!='payment'&&strtolower($payment_status)=='completed'){
                $order->order_status ='payment';
                $order->payment_type =$this->key;
                $order->save();

                \Yii::$app->session->set('success_order', $order->order_id);

                $flat = $order->sendEmail(Config::getConfig('server_mail'),'',true,['payment'=>'Paypal']);
                //sleep(2);
                $flat = $order->sendEmail(\Yii::$app->user->identity->email,'',false,['payment'=>'Paypal']);

            }
        }
        unset(\Yii::$app->session['order_id']);
    }

    public function getFrom($flat=0){
        $target = $flat==1 ? 'target="_blank"' : '';
        $target = '';
        $submit_css = '';
        $headers = $_SERVER['SERVER_NAME'];
        if($flat==1||$flat==2){
             $submit_css = 'style="display:none;"';
        }

        $returnUrl = 'http://' . $headers . '/thankyou.php';
        $returnUrl = Url::to(['/cart/confirm-thank'], true);
        $returnUrl = 'http://' . $headers . '/thankyou-pp.php';

        $this->config['cancel_return'] = Url::to(['/member/default/review','id'=>$this->order['order_id'],'flat'=>1],true);


        $this->order['total'] = isset($this->order['total'])&& !empty($this->order['total']) ? $this->order['total'] : 0.01;
        $form=' <form action="'.$this->config['action'].'" method="post" name="paypal_form" '.$target.'>
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="upload" value="1">
                    <input type="hidden" name="business" value="'.$this->config['business'].'">
                    <input type="hidden" name="item_name" value="'.Yii::t("app","Order Payment:").$this->order['order_id'].'">
                    <input type="hidden" name="amount" value="'.$this->order['total'].'">
                    <input type="hidden" name="currency_code" value="'.$this->config['currency_code'].'">
                    <input type="hidden" name="no_shipping" value="1"/>
                    <input type="hidden" name="charset" value="utf-8">
                    <input type="hidden" name="return" value="' . $returnUrl . '">
                    <input type="hidden" name="notify_url" value="'.$this->config['notify_url'].'">
                    <input type="hidden" name="cancel_return" value="'.$this->config['cancel_return'].'">
                    <input type="hidden" name="lc" value="en">
                    <input type="hidden" name="rm" value="2">
                    <input type="hidden" name="no_note" value="1">
                    <input type="hidden" name="paymentaction" value="sale">
                    <input type="hidden" name="custom" value="'.$this->order['order_id'].'">
                    <div class="buttons">
                    <div class="right">
                    <input type="submit" class="pay-submit comfirm-btn" value="'.Yii::t('app','Payment').'" '.$submit_css.'>
                  </div>
                </div>
            </form>

            ';
        if($flat){
            $form .= '<script>document.paypal_form.submit();</script>';
        }
            // $form .= '<script>document.paypal_form.submit();</script>';

        // Call PayPal payment record
        $filename = \Yii::getAlias("@runtime/post.txt");
        $file = @fopen($filename, "a");
        // echo $filename;
        $txt = "\nCall PayPal payment start at: ". date('Y-m-d H:i:s') . ", Order id: " . $this->order['order_id'] . ", Order time: " . (isset($this->order['add_date']) ? date('Y-m-d H:i:s', $this->order['add_date']) : 'null') . "\n";
        @fwrite($file, $txt);
        @fclose($file);

        return $form;
    }



}
