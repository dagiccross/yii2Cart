<?php

use yii\helpers\Url;

?>

<div class="shopcart-wrap" id="billing-details">

  <?php if ($addr) : ?>
  <?php foreach ($addr as $key => $val) : ?>
  <div class="address-row row address-<?= $val['id'] ?>">
    <div class="col-sm-1">
      <input type="radio" name="billing-address" value="<?= $val['id'] ?>" <?php if ($key == 0) echo 'checked="checked"'; ?>>
    </div>
    <div class="col-sm-2 addr-name"><?= $val['shipment_name'] ?></div>
    <div class="col-sm-6 addr-addr"><?= $val['shipment_addr'] ?></div>
    <div class="col-sm-2 addr-phone"><?= $val['shipment_phone'] ?></div>
    <div class="col-sm-1"><a href="javascript:void(0);" onclick="CHECKOUT.editAddress('address-<?= $val['id'] ?>')"><?=\Yii::t('cart','Modify')?></a></div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>

  <div class="address-row row add-address-row">
    <?=\Yii::t('cart','Add New Address')?>
  </div>
  <form id="address-form" onsubmit="return false">
    <input type="hidden" name='addr-action' value="add">
    <input type="hidden" name='addr-id' value="0">
    <div class="row form-group">
      <div class="col-sm-2 control-label"><?=\Yii::t('cart','Receiver')?> :</div>
      <div class="col-sm-10 control"><input type="text" name="addr-name" class="ad_input"></div>
    </div>
    <div class="row form-group">
      <div class="col-sm-2 control-label"><?=\Yii::t('cart','Shipping address')?> :</div>
      <div class="col-sm-10 control"><textarea class="ad_textarea" name="addr-addr" cols="40" rows="5"></textarea></div>
    </div>
    <div class="row form-group">
      <div class="col-sm-2 control-label"><?=\Yii::t('cart','Receiver Phone')?> :</div>
      <div class="col-sm-10 control"><input type="text" name="addr-phone" class="ad_input"></div>
    </div> 
    <div class="row form-group">
      <div class="col-sm-2 control-label"></div>
      <div class="col-sm-10 control">
        <a href="javascript:void(0);" onclick="CHECKOUT.saveAddress()"><button class="save_btn"><?=\Yii::t('app','Save')?></button></a>
        <a href="javascript:void(0);" onclick="CHECKOUT.goToShipmentMethod()"><button class="next-btn ad_next"><?= Yii::t('cart', 'Next') ?></button></a>
      </div>
    </div>
  </form>

</div> 