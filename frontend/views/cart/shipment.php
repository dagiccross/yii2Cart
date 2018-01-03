<?php

use \yii\helpers\Url;

?>

<div class="shopcart-wrap" id="shipment-method">

	<p><?=\Yii::t('cart','Please select the preferred shipment method to use on this order.')?></p>

	<?php if (isset($shipment[0]['options']) && $shipment[0]['options']) : ?>
	<?php foreach ($shipment[0]['options'] as $key => $val) : ?>
	<div class="address-row row">
		<div class="col-sm-12">
			<input type="radio" name="shipment-method" value="<?= $key ?>" <?php if ($key == 0) echo 'checked="checked"'; ?>>
			<span class="method-lable"><?= ucfirst($val['images']) ?> $<?= $val['options'] ?></span>
		</div>                 
	</div>
	<?php endforeach; ?>
	<?php endif; ?>

	<div class="row form-group">
		<div class="col-sm-12"><a href="javascript:void(0);" onclick="CHECKOUT.goToPaymentMethod()"><button class="next-btn"><?= Yii::t('cart', 'Next') ?></button></a></div>
	</div>

</div>