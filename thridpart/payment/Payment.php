<?php

namespace thridpart\payment;

use yii;
use yii\helpers\ArrayHelper;

abstract class Payment
{
    protected $order;
    //$order['order_id'],$order['goods'],$order['total']
    protected $config;
    // 定義支付动作
    public function pay(){

    }

    public function setConfig($config){
        $this->config = ArrayHelper::merge($this->config,$config);
    }

    public function setOrder($order){
        $this->order = ArrayHelper::merge($this->order,$order);
    }

    public function __destruct()
    {
        
    }


}