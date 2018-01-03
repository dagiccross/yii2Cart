<?php

use \yii\helpers\Url;

?>

<div class="shopcart-wrap" id="payment-method">

	<p><?=\Yii::t('cart','Please select the preferred payment method to use on this order.')?></p>

	<?php if ($payment) : ?>
	<?php foreach ($payment as $key => $val) : ?>
	<div class="address-row row">
		<div class="col-sm-12">
			<input type="radio" name="payment-method" value="<?= $val['id'] ?>" <?php if ($key == 0) echo 'checked="checked"'; ?>>
			<span class="method-lable"><?= ucfirst($val['name']) ?></span>
		</div>                 
	</div>
	<?php endforeach; ?>
	<?php endif; ?>

	<div class="row form-group">
		<div class="col-sm-12"><a href="javascript:void(0);" onclick="CHECKOUT.goToConfirm()"><button class="next-btn"><?= \Yii::t('cart', 'Next') ?></button></a></div>
	</div>

</div>