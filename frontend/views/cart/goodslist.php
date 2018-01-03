<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Goods;
// print_r($cart);

?>

<div class="shopcart-wrap" id="goods-list">

<?php if (count($cart) > 0) : ?>

<?php
$total = 0;
// foreach ($cart as $k => $v) {
//     $total += $v['total'];
// }
?>

<div class="shoppingcart-list">
  <div class="cartlist-col1"><input type="checkbox" name="" class="select-all"><?=\Yii::t('app','Check All')?></div>
  <div class="cartlist-col2"><?=\Yii::t('app','Product Infomations')?></div>
  <div class="cartlist-col3"><?=\Yii::t('app','Price')?></div>
  <div class="cartlist-col4"><?=\Yii::t('app','Quanity')?></div>
  <div class="cartlist-col5"><?=\Yii::t('app','Total Price')?></div>
  <div class="cartlist-col6"><?=\Yii::t('app','Action')?></div>
  <div class="clearfix"></div>
</div>

 <?php foreach ($cart as $key => $val) : ?>
 <?php
  $goods = Goods::getGoodsById($val['goods_id']);
  $goods_sku_arr = $goods['sku'];

  $sku_key = $val['sku'];

  $price = isset($goods_sku_arr[$sku_key]) ? $goods_sku_arr[$sku_key]['price'] : $goods['price'];
  $sub_total = $val['quanity'] * $price;
  $total +=$sub_total;
  
  $sku_arr = explode(':', $sku_key);
 ?>
 <div class="shoppingcart-row">
   <div class="cartlist-col1">
    <input name="cart_goods[]" type="checkbox" class="select-goods" value="<?= $key ?>" checked onclick="javascript:CHECKOUT.checkGoods();">
  </div>
   <div class="cartlist-col2">
    <div class="col-sm-2 thumb">
      <img src="<?= $goods['pic'] ?>">
    </div>
    <div class="col-sm-10 product-description">
      <ol>
        <li><a href="<?= Url::to(['site/detail', 'id' => $val['goods_id']]); ?>"><?= $goods['title'] ?></a></li>
        <?php if(isset($goods['group'])):?>
        <?php
          foreach ($goods['group'] as $_k => $_v) {

            $size_label = ['options'=>''];
            if(isset($sku_arr['0'])&&isset($sku_arr['1'])):

            $size_label = isset($goods[$_v['feature']][$sku_arr['0']]) ? $goods[$_v['feature']][$sku_arr['0']] : (isset($goods[$_v['feature']][$sku_arr['1']]) ? $goods[$_v['feature']][$sku_arr['1']] : '');
            
            endif;
            $size_label['options'] = isset($size_label['options']) ? $size_label['options'] : '';
            $shtml = '<li>'.$_v['name'].''.$size_label['options'].'</li>';
            echo $shtml;
          }
        ?>
        <?php endif;?>
      </ol>
    </div>
  </div>
   <div class="cartlist-col3 cartproduct-price"><?= $price ?>
    <input type="hidden" name="goods['<?= $key ?>'][price]" id="goods-price-<?= $key ?>" value="<?= $price ?>">
   </div>
   <div class="cartlist-col4">
    <div class="stock">
    <a href="javascript:void(0);" class="cut-productnum" onclick="CHECKOUT.quanity('goods-quanity-<?= $key ?>', '-', '<?= $key ?>')">-</a>
    <input type="text" name="goods['<?= $key ?>'][quanity]" class="number-input" id="goods-quanity-<?= $key ?>" value="<?= $val['quanity'] ?>">
    <a href="javascript:void(0);" class="add-productnum" onclick="CHECKOUT.quanity('goods-quanity-<?= $key ?>', '+', '<?= $key ?>')">+</a>
    <div class="clearfix"></div>
    </div>
   </div>
   <div class="cartlist-col5 cartproduct-price">$<?= sprintf('%.2f',$sub_total) ?></div>
   <div class="cartlist-col6"><a href="javascript:void(0);" onclick="CHECKOUT.removeGoods('<?= $key ?>')" class="delete-product"><span class="glyphicon glyphicon-remove"></span><?=\Yii::t('app','Delete')?></a></div>
   <div class="clearfix"></div>
 </div>

 <?php endforeach; ?>

<div class="bottom-menu">
 <div class="bottom-col1"><input type="checkbox" name="" class="select-all"><?=\Yii::t('app','Check All')?></div>
 <div class="bottom-col2"><a href="javascript:void(0);" id="remove-all-goods"><?=\Yii::t('cart','Delete the selected')?></a></div>
 <div class="bottom-col3"><a href="<?= Url::to(['site/index']) ?>"><?=\Yii::t('cart','Continue shopping')?></a></div>
 <div class="bottom-col4"><?=\Yii::t('cart','Amount:')?><span class="check-price">$<?= sprintf('%.2f',$total) ?></span></div>
 <div class="bottom-col5"><a href="javascript:void(0);" onclick="CHECKOUT.goToBillingDetails()"><button class="next-btn"><?= \Yii::t('cart', 'Next') ?></button></a></div>                      
 <div class="clearfix"></div>
</div>

<?php else : ?>

  <p><?=Yii::t('app','Cart is empty!')?></p>

<?php endif; ?>

</div>