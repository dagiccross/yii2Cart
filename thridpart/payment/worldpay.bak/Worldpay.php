<?php

namespace thridpart\payment\worldpay;

use yii;
use yii\helpers\ArrayHelper;
use thridpart\payment\Payment;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Order;
use common\models\Config;
class Worldpay extends Payment
{

    // __construct
    public function __construct($order=array()){

        $this->order = $order;
        $this->config['post_url'] = 'https://secure.worldpay.com/wcc/purchase';
        $this->config['sandbox_url'] = 'https://secure-test.worldpay.com/wcc/purchase';
        $this->config['return_url'] = Url::to(['order/return','type'=>'worldpay'],true);
        $this->config['notify_url'] = Url::to(['order/notify','type'=>'worldpay'],true);
        $this->config['cancel_return'] = Url::to(['order/cancel','type'=>'worldpay'],true);

        $currency_code =  Config::getConfig('currency_code');
        $this->config['currency_code'] = empty($currency_code) ? 'GBP' : $currency_code;

        $rs = (new Query())->select('m.options')
                        ->from("{{%extensionmeta}} m")
                        ->leftJoin("{{%extension}} e","m.ext_id=e.id")
                        ->where("e.`key`=:key and m.language=:language",[':key'=>'worldpay',':language'=>Yii::$app->language])
                        ->one();
        $options = @unserialize($rs['options']);
        $config = array();
        foreach ($options as $k => $v) {
            $config[$v['key']] = $v['value'];
        }
        $this->setConfig($config);
        if(isset($this->config['testMode'])&&$this->config['testMode']=='100'){
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

        $file = @fopen($filename, "w+");
        // echo $filename;
        $txt = "Post". date('Y-m-d H:i:s') ." \n";
        $txt .= json_encode($po) . " \n";
        @fwrite($file, $txt);
        @fclose($file);

        

        if ($po['MC_custom']) {
            $order_id = $po['MC_custom'];
        } else {
            $order_id = 0;
        }

        $order_id = explode(',', $order_id);
        $order = Order::find()->where(['in','order_id',$order_id])->exists();

        if ($order > 0) {
            if($po['transStatus']=='Y'||$po['transStatus']=='y'){
                Order::updateAll(['order_status' => 'payment'], ['in','order_id',$order_id]);
                Order::subtractQuanity($v);
                return true;
            }
            
        }
        return false;

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

        $SignatureFields = $this->config['currency_code'].":".$this->order['total'].":".$this->config['testMode'].":".$this->config['instId'];
        $SignatureFields = $this->config['cartId'].$this->order['order_id'].':'.$SignatureFields;

        $this->order['total'] = isset($this->order['total'])&& !empty($this->order['total']) ? $this->order['total'] : 0.01;
        $form=' <form action="'.$this->config['action'].'" method="post" name="worldpay_form" '.$target.'>';
        $form .= Html::hiddenInput('instId',$this->config['instId']);
        $form .= Html::hiddenInput('cartId',$this->config['cartId'].$this->order['order_id']);
        $form .= Html::hiddenInput('testMode',$this->config['testMode']);
        $form .= Html::hiddenInput('accId1',$this->config['accId1']);
        $form .= Html::hiddenInput('email',\Yii::$app->user->identity->email);
        $form .= Html::hiddenInput('name',$this->order['shipment_name']);
        $form .= Html::hiddenInput('authMode','A');
        $form .= Html::hiddenInput('withDelivery','false');
        $form .= Html::hiddenInput('lang','en');
        $form .= Html::hiddenInput('subst','yes');
        $form .= Html::hiddenInput('signature',md5($SignatureFields));

        // addr
        $form .= Html::hiddenInput('address1',$this->order['shipment_addr1']);
        $form .= Html::hiddenInput('address2',$this->order['shipment_addr2']);
        $form .= Html::hiddenInput('town',$this->order['shipment_city']);
        $form .= Html::hiddenInput('postcode',$this->order['shipment_postcode']);
        $form .= Html::hiddenInput('tel',$this->order['shipment_phone']);
        // end addr
        $form .= Html::hiddenInput('desc',Yii::t("app","Order Payment:").$this->order['order_id']);
        $form .= Html::hiddenInput('amount',$this->order['total']);
        $form .= Html::hiddenInput('currency',$this->config['currency_code']);

        $form .= Html::hiddenInput('MC_custom',$this->order['order_id']);
        $form .= Html::hiddenInput('MC_callback',$this->config['return_url']);

                    // <input type="hidden" name="return" value="'.$this->config['return_url'].'">
                    // <input type="hidden" name="notify_url" value="'.$this->config['notify_url'].'">
                    // <input type="hidden" name="cancel_return" value="'.$this->config['cancel_return'].'">
        $form .='<div class="buttons">
                    <div class="right">     
                    <input type="submit" class="pay-submit comfirm-btn" value="'.Yii::t('app','Payment').'" '.$submit_css.'>
                  </div>
                </div>       
            </form>
            
            ';
        if($flat){
            $form .= '<script>document.worldpay_form.submit();</script>';
        }
            // $form .= '<script>document.paypal_form.submit();</script>';
        return $form;
    }

    

}