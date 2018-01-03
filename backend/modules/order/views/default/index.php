<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use common\models\Order;
use common\models\Config;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
if($list['flat']==1){
    $this->title = Yii::t('app', 'Order Archive');
}else{
    $this->title = Yii::t('app', 'Order Manager');
}

$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    .upfile-create{padding: 15px;}
</style>
<div class="goods-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
            // echo Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']);
            echo Html::a(Yii::t('app', 'Export'), ['output','flat'=>$list['flat']], ['class' => 'btn btn-success','style'=>'margin-right:10px;']);
            if($list['flat']==1){

                echo Html::a(Yii::t('app', 'Clear All'), ['delete','type'=>'all','id'=>0], ['class' => 'btn btn-danger','data-method'=>"post",'data-confirm'=>"Are you sure you want to delete all item?"]);
            }

        ?>
    </p>
    <div class="row">
        <?php echo $this->render('_search');?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['label'=>Yii::t('app','Order#'),'value'=>function($m){return Config::orderFormat($m['order_no']);}],
            ['label'=>Yii::t('info','Order Date'),'value'=>function($m){return (!empty($m['add_date'])) ? date('d-m-Y - H:i',$m['add_date']) : '';}],
            ['label'=>Yii::t('info','Customer Name'),'value'=>function($m){return $m['shipment_name'];}],
            ['label'=>Yii::t('info','Order Type'),'value'=>function($m){return $m['order_type']=='deliver' ? 'Delivery' : 'Collection';}],
            ['label'=>Yii::t('app','Payment Status'),'value'=>function($m){return Order::getPaymentStatus($m['order_status'],$m['payment_type'],$m['order_type']);}],
            // ['label'=>Yii::t('app','Quanity'),'value'=>function($m){return $m['quanity'];}],
            ['label'=>Yii::t('app','Total Price'),'value'=>function($m){return Config::moneyFormat($m['total']+$m['card_fee']);}],
            ['class' => 'yii\grid\ActionColumn',
             'template'=>'{view} {delete}'],
        ],
    ]); ?>

</div>
