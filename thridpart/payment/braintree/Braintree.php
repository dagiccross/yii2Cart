<?php

namespace thridpart\payment\braintree;

use Yii;
use yii\helpers\ArrayHelper;
use thridpart\payment\Payment;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Order;
use common\models\Config;

class Braintree extends Payment
{
    public $key;

    public function __construct($order = [])
    {
        $this->key = 'braintree';
        $this->order = $order;

        $this->config['merchant'] = '';
        $this->config['public_key'] = '';
        $this->config['private_key'] = '';
        $this->config['environment'] = '';
        $this->config['send_url'] = Url::to(['/order/return', 'type' => 'braintree'], true);

        $currency_code =  Config::getConfig('currency_code');
        $this->config['currency_code'] = empty($currency_code) ? 'GBP' : $currency_code;

        $rs = (new Query())->select('m.options')
            ->from('{{%extensionmeta}} m')
            ->leftJoin('{{%extension}} e', 'm.ext_id=e.id')
            ->where('e.`key`=:key and m.language=:language', [':key' => $this->key, ':language' => Yii::$app->language])
            ->one();

        $options = @unserialize($rs['options']);
        if (is_array($options) && !empty($options)) $this->setConfig($options);
    }

    protected function log($msg = '')
    {
        $fp = fopen(__DIR__ . '/log.txt', 'a');
        flock($fp, LOCK_EX) ;
        // fwrite($fp, 'Log date：' . strftime('%Y%m%d%H%M%S', time()) . "\n" . $msg . "\n\n");
        fwrite($fp, 'Log date：' . date('Y-m-d H:i:s') . "\n" . $msg . "\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    // 定義支付动作
    public function pay($type = 0)
    {
        $pay = $this->getForm($type);
        return $pay;
    }

    public function doNotify($po)
    {
        echo 'ok';
    }

    public function doReturn()
    {
        $json = [];
        $json['status'] = 0;
        $json['msg'] = '';

        $pendingStatus = 'pending';

        $order_id = Yii::$app->request->post('o', 0);
        $order = Order::findOne(['order_id' => $order_id, 'member_id' => Yii::$app->user->identity->id]);

        if ($order && $order->order_status != $pendingStatus) $json['msg'] = 'Order status error. Please contact the webmaster.';

        $nonce = Yii::$app->request->post('payment_method_nonce', '');

        if (Yii::$app->request->isPost && !empty($nonce) && $order && $order->order_status == $pendingStatus) {
            require_once(__DIR__ . '/lib/Braintree.php');
            \Braintree_Configuration::environment($this->config['environment']);
            \Braintree_Configuration::merchantId($this->config['merchant']);
            \Braintree_Configuration::publicKey($this->config['public_key']);
            \Braintree_Configuration::privateKey($this->config['private_key']);

            $logMsg = '';

            if ($this->config['environment'] == 'sandbox') {
                $logMsg .= "BRAINTREE Sandbox: \n";
            } else {
                $logMsg .= "BRAINTREE: \n";
            }

            if ($this->config['transaction_method'] == 'charge') {
                $submitForSettlement = true;
            } else {
                $submitForSettlement = false;
            }
            $submitForSettlement = true;

            $result = null;

            try {
                $result = \Braintree_Transaction::sale(array(
                    'amount' => $order->total + (float)$order->card_fee,
                    'paymentMethodNonce' => $nonce,
                    // Optional
                    'orderId' => $order->order_id,
                    'options' => [
                        'submitForSettlement' => $submitForSettlement
                    ],
                    'customer' => [
                        'email' => Yii::$app->user->identity->email,
                        'phone' => $order->shipment_phone,
                        'firstName' => $order->shipment_name,
                    ],
                ));

                if ($result->success) {
                    $json['status'] = 1;
                    $json['msg'] = 'Success';
                    $order->order_status = 'payment';
                    $order->payment_type = $this->key;
                    $order->save();
                    // $json['url'] = Url::base() . '/thankyou.php';
                    $json['url'] = Url::to(['/cart/confirm-thank'], true);

                    Yii::$app->session->set('success_order', $order->order_id);
                    unset(Yii::$app->session['order_id']);

                    // 發郵件？
                    $member = \common\models\User::findOne($order->member_id);
                    $order->sendEmail(Config::getConfig('server_mail'), '', true, ['payment' => ucfirst($this->key)]);
                    // sleep(2);
                    $order->sendEmail($member->email, '', false, ['payment' => ucfirst($this->key)]);
                } elseif ($result->transaction) {
                    $logMsg .= 'Error! ' . $result->transaction->processorResponseCode . '. ' . $result->transaction->processorResponseText . "\n";
                    if (in_array($result->transaction->processorResponseCode, ['2000', '2001', '2002', '2003', '2038', '2046'])) {
                        $json['msg'] = 'Your transaction could not be approved. Please contact your bank or use another card.';
                    } elseif ($result->transaction->processorResponseCode == '3000') {
                        $json['msg'] = 'Cannot connect to your bank. Please try again later.';
                    } else {
                        $json['msg'] = 'Your transaction could not be approved. Please check your card details and try again.';
                    }
                } else {
                    $logMsg .= sizeof($result->errors) . " total error(s)\n";
                    foreach($result->errors->deepAll() as $error) {
                        $logMsg .= 'Error! ' . $error->code . '. ' . $error->message . "\n";
                    }
                    $json['msg'] = 'Your transaction could not be approved. Please check your card details and try again.';
                }

                $logMsg .= $result . "\n";
            } catch (\Exception $e) {
                $logMsg .= "Transaction init falid.\n";
                $excmessage = 'Code: ' . $e->getCode() . ' ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine();
                $debuginfo = $e->getTraceAsString();
                $logMsg .= 'Error! ' . $excmessage . "\n";
                $logMsg .= 'Info! ' . $debuginfo . "\n";

                $json['msg'] = $e->getMessage();
            }

            if ((int)$this->config['debug_mode'] == 1) $this->log($logMsg);
        }

        echo json_encode($json);
        Yii::$app->end();
    }

    public function getForm($type = 0)
    {
        if (empty($this->order)) return '';

        $type = (int)$type;
        $target = $type == 1 ? 'target="_blank"' : '';
        $submitStyle = '';
        // if ($type == 1 || $type == 2) $submitStyle = 'style="display: none;"';

        $payment = \common\models\Extension::getPayment($this->key);

        require_once(__DIR__ . '/lib/Braintree.php');
        \Braintree_Configuration::environment($this->config['environment']);
        \Braintree_Configuration::merchantId($this->config['merchant']);
        \Braintree_Configuration::publicKey($this->config['public_key']);
        \Braintree_Configuration::privateKey($this->config['private_key']);

        if ((int)$this->config['force_tls12'] == 1) {
            \Braintree_Configuration::sslVersion(6);
        }

        try {
            $clientToken = \Braintree_ClientToken::generate();

            // Form elemen
            $form = '<style>
            @font-face { font-family: "myfont"; src: url("/frontend/web/fonts/OpenSans-Semibold.ttf"); }
            @font-face { font-family: "myfont-bold"; src: url("/frontend/web/fonts/OpenSans-Bold.ttf"); }
            @font-face { font-family: "myfont-regular"; src: url("/frontend/web/fonts/OpenSans-Regular.ttf"); }
            html, body { margin: 0; padding: 0; font-family: "myfont", "Microsoft YaHei"; color: #414040; font-size: 15px; line-height: 22px; }
            a { color: #337ab7; text-decoration: none; }
            p { margin:  0 0 10px 0; }
            form { margin: 20px 0 30px 0;}
            #braintree-wrap { width: 90%; max-width: 700px; margin: 4rem auto; padding: 1rem; }
            .logos { overflow: hidden; }
            .logos img { width: 45%; float: left; }
            .logos img:last-child { float: right;}
            .paypal-part { border: 0; }
            .paying { margin: 20px 0; }
            .pay-submit { background: #fec752; border-radius: 5px; padding: 10px; width: 100%; color: #000; font-family: "myfont", "Microsoft YaHei"; font-size: 18px; font-weight: 500; border: none; -webkit-appearance: button; cursor: pointer; outline: none; }
            </style>';
            $form .= '<div id="braintree-wrap">
                <p class="logos">
                    ' . Html::img(showImg(IMG_URL . '/braintree.png')) . '
                    ' . Html::img(showImg(IMG_URL . '/paypal-logo.png')) . '
                </p>
                <p style="font-size: 17px;">
                    Payment details securely processed by Braintree, a Paypal company. We do not store any sensitive data on our server
                </p>
                <form id="' . $this->key . '-form" method="post" action="" onsubmit="return false;">
                    <div class="paypal-part">
                        <div class="paypal-detail">
                            <div id="' . $this->key . '-container"></div>
                            <span id="' . $this->key . '-msg"></span>
                            <div class="paying">
                                <input type="submit" class="pay-submit" id="' . $this->key . '-submit" value="Place my order (' . ucfirst($this->key) . ')">
                            </div>
                        </div>
                    </div>
                </form>
                <p>
                    ' . Html::a('Cancel and return', Url::to(['/member/default/review', 'id' => $this->order['order_id']])) . '
                </p>
            </div>';

            // Load js library
            $form .= '<script src="/frontend/web/js/jquery-1.11.3.min.js"></script>';
            $form .= '<script src="https://js.braintreegateway.com/v2/braintree.js"></script>';
            $form .= '<script>;(function($) {
            wait_for_braintree_to_load();
            function wait_for_braintree_to_load() {
                if (window.braintree && window.braintree.setup)
                    braintree_setup();
                else
                    setTimeout(function() { wait_for_braintree_to_load() }, 50);
            }
            function braintree_setup() {
                braintree.setup("' . $clientToken . '", "dropin", {
                    container: "' . $this->key . '-container",
                    paymentMethodNonceReceived: function (event, nonce) {
                        //complete payment transaction
                        complete_braintree_payment(nonce);
                        $("#' . $this->key . '-msg").html("Please wait...");
                    }
                });
            }
            function complete_braintree_payment(nonce) {
                $("#' . $this->key . '-submit").prop("disabled", true);
                $.ajax({
                    url: "' . $this->config['send_url'] . '",
                    type: "POST",
                    data: "payment_method_nonce=" + nonce + "&o=" + "' . $this->order['order_id'] . '",
                    dataType: "json",
                    complete: function() {},
                    success: function(json) {
                        if (json.status) {
                            window.location = json["url"];
                        } else {
                            $("#' . $this->key . '-msg").html(json["msg"]);
                            $("#' . $this->key . '-submit").prop("disabled", false);
                        }
                    }
                });
            }
            })(jQuery);</script>';

        } catch (Exception $e) {
            if ((int)$this->config['debug_mode'] == 1) {
                $logMsg = "BRAINTREE: Cannot generate client token.\n";
                $excmessage = 'Code: ' . $e->getCode() . ' ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine();
                $debuginfo = $e->getTraceAsString();
                $logMsg .= 'Error! ' . $excmessage . "\n";
                $logMsg .= 'Info! ' . $debuginfo . "\n";
                $this->log($logMsg);
            }
            $form = Yii::t('Something went wrong.');
        }

        return $form;
    }
}
