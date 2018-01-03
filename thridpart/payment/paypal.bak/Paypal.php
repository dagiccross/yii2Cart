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
        $config = array();
        foreach ($options as $k => $v) {
            $config[$v['key']] = $v['value'];
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

            if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && $po['payment_status']) {

                Order::updateAll(['order_status' => $po['payment_status']], ['in','order_id',$order_id]);
                Order::subtractQuanity($v);
            }

            curl_close($curl);
        }
    }

    public function doReturn() {
        unset(\Yii::$app->session['order_id']);
    }

    public function getFrom($flat=0){
        $target = $flat==1 ? 'target="_blank"' : '';
        $submit_css = '';
        if($flat==1||$flat==2){
             $submit_css = 'style="display:none;"';
        }
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
                    <input type="hidden" name="return" value="'.$this->config['return_url'].'">
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
        return $form;
    }

    

}