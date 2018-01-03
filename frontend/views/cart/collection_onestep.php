<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Config;
?>

<section class="login-wrap">
    <div class="col-sm-4"></div>
        <div class="col-sm-4 middle-content">
            <div class="login-form">
            <h2>Confirm your collection details</h2>
            <?php $form = ActiveForm::begin(['id' => 'collection_one_step']); ?>

            <?=Html::tag('p','PHONE NUMBER',['class'=>'lotitle'])?>
            <?=Html::tag('p',Html::textInput('shipment[shipment_phone]',$shipment['shipment_phone'],['class'=>'email-input','placeholder'=>'0 78668 78668/0333 2103288']))?>
            <?=Html::tag('p',Html::hiddenInput('shipment[shipment_name]',$shipment['shipment_name']))?>
            <?=Html::hiddenInput('shipment[shipment_addr1]',$shipment['shipment_addr'])?>
            <?=Html::hiddenInput('shipment[shipment_addr2]',$shipment['shipment_addr2'])?>
            <?=Html::hiddenInput('shipment[shipment_city]',$shipment['shipment_city'])?>
            <?=Html::hiddenInput('shipment[shipment_postcode2]',$shipment['shipment_postcode'])?>
            <?=Html::hiddenInput('shipment[shipment_postcode]',$shipment['shipment_postcode2'])?>
            <?=Html::tag('p',Html::hiddenInput('type','collection_one_step'))?>
            
            <p><?= Html::submitButton('Continue', ['class' => 'login-btn next-step', 'name' => 'login-button']) ?></p>
            
            <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-sm-4"></div>
        <div class="clearfix"></div>
</section>
