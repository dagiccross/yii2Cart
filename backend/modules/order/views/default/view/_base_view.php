<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;
use common\models\Config;
/* @var $this yii\web\View */
/* @var $model common\models\Order */

?>
<div class="order-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['label'=>\Yii::t('info','Order No.'),
            'value'=>Config::orderFormat($model->order_no)],
            // ['label'=>\Yii::t('app','Invoice No.'),
            // 'value'=>$model->invoice_prefix.$model->invoice_no],
            ['label'=>\Yii::t('label','Comment'),
            'value'=>$model->comment],
            ['label'=>\Yii::t('app','Total Price'),
            'value'=>$model->total],
            // ['label'=>\Yii::t('label','Order Status'),
            // 'value'=>Order::orderStatus($model->order_status)],
            //['label'=>\Yii::t('info','Currency Code'),
            //'value'=>$model->currency_code],
            //['label'=>\Yii::t('info','Currency Value'),
            //'value'=>$model->currency_value],
            //['label'=>\Yii::t('info','Member IP'),
            //'value'=>$model->member_ip],
            ['label'=>\Yii::t('info','Add Date'),
            'value'=>date('d-m-Y - H:i',$model->add_date)],
            //['label'=>\Yii::t('info','Modify Date'),
            //'value'=>date('Y-m-d H:i:s',$model->modify_date)],
            ['label'=>\Yii::t('label','Order Type'),
            'value'=>($model->order_type=='deliver' ? 'Delivery' : 'Collection')],
            ['label'=>\Yii::t('label','Shipment Time'),
            // edited -> ASAP WORKAROUND
            'value'=> (date('Y',$model->shipment_time) == '1970' ? 'ASAP' : date('d/m/Y'.' - '.'H:i',$model->shipment_time))],
            ['label'=>\Yii::t('label','Payment Method'),
            'value'=>$model->payment_type],
        ],
    ]) ?>

</div>
