<?php

use \yii\helpers\Url;
use \common\models\Goods;
$total = 0;
foreach ($cart as $k => $v) {
    $total += $v['total'];
}

?>

<div class="shopcart-wrap" id="order-confirm">

  <div class="row comfirm-order">
    <div class="col-sm-5"><?=\Yii::t('cart','Goods Informations')?></div>
    <div class="col-sm-3"><?=\Yii::t('app','Quanity')?></div>
    <div class="col-sm-2"><?=\Yii::t('app','Price')?></div>
    <div class="col-sm-2"><?=\Yii::t('cart','Total')?></div>
  </div>

  <?php foreach ($cart as $key => $val) : ?>
  <?php
  $goods = Goods::getGoodsById($val['goods_id']);
  $goods_sku_arr = $goods['sku'];
  
  $sku_key = $val['sku'];
  $price = isset($goods_sku_arr[$sku_key]) ? $goods_sku_arr[$sku_key]['price'] : $goods['price'];
  $sub_total = $val['quanity'] * $price;
  ?>
  <div class="row order-infomation">
    <div class="col-sm-5 order-name"><?= $goods['title'] ?></div>
    <div class="col-sm-3 order-number"><?= $val['quanity'] ?></div>
    <div class="col-sm-2 order-price"><?= $price ?></div>
    <div class="col-sm-2 order-price"><?= $sub_total ?></div>
  </div>
  <?php endforeach; ?>

  <div class="row order-total">
    <div class="col-sm-12">
    <div class="total-lable"> <?=\Yii::t('cart','Total amount of goods:')?><span class="total-price">$<?= $total ?></span></div>
    <div class="total-lable"><?=\Yii::t('cart','Shipping:')?><span class="total-price">$<?= $shipment['price'] ?></span></div>
    <div class="total-lable"><?=\Yii::t('cart','Order Total:')?><span class="total-price">$<?= $total + $shipment['price'] ?></span></div>
  </div>
  </div>
    <div class="row">
    <div class="col-sm-12" style=" text-align: right; margin: 20px 0;">
      <?= $payment ?>
    </div>
  </div>

</div>