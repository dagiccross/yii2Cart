<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use yii\bootstrap\ActiveForm;
?>
<div class="container-fluid">
  <div class="col-sm-4"></div>
  <div class="col-sm-4 middle-content">
    <div class="login-form">
      <h2>Confirm your delivery details</h2>
      <?php $form = ActiveForm::begin(['id' => 'deliver_one_step']); ?>

      <?=Html::tag('p','PHONE NUMBER',['class'=>'lotitle'])?>
      <?=Html::tag('p',Html::textInput('shipment[shipment_phone]',$shipment['shipment_phone'],['class'=>'email-input','placeholder'=>'']))?>

      <?=Html::tag('p','ADDRESS',['class'=>'lotitle'])?>
      <?=Html::tag('p',Html::textInput('shipment[shipment_addr1]',$shipment['shipment_addr'],['class'=>'email-input','placeholder'=>'Address line 1']))?>
      <?=Html::tag('p',Html::textInput('shipment[shipment_addr2]',$shipment['shipment_addr2'],['class'=>'email-input','placeholder'=>'Address line 2']))?>

      <?php
      $map_calculation = \common\models\Config::getConfig('map_calculation');
      if ($map_calculation == 0){
        ?>
        <script type="text/javascript">
        // set varible for map calculation
        var map_cal = "0";
        </script>


        <?=Html::tag('p','POSTCODE',['class'=>'lotitle'])?>
        <p>
          <?=Html::textInput('shipment[shipment_postcode2]',$shipment['shipment_postcode2'],['id'=>'txtPostCode','class'=>'post-input','placeholder'=>'Full postcode','style'=>'width:100%;margin-left:0px;text-transform:uppercase;','maxlength'=> 8])?>
        </p>
        <?php } elseif ($map_calculation == 1){ ?>
          <script type="text/javascript">
          // set varible for map calculation
          var map_cal = "1";
          </script>
          <?=Html::tag('p','CITY/TOWN',['class'=>'lotitle'])?>
          <?=Html::tag('p',Html::textInput('shipment[shipment_city]',$shipment['shipment_city'],['class'=>'email-input','placeholder'=>'']))?>

          <?=Html::tag('p','POSTCODE <small style="color:red;">(please select the correct postcode)</small>',['class'=>'lotitle'])?>
          <p>
            <?=Html::dropdownlist('shipment[shipment_postcode]',$shipment['shipment_postcode'],getShipmentPostcode(),['class'=>'poselect','style'=>'width:60%;'])?>
            <?=Html::textInput('shipment[shipment_postcode2]',$shipment['shipment_postcode2'],['class'=>'post-input','placeholder'=>'last 3 characters','style'=>'width:35%;text-transform:uppercase;','maxlength'=> 3])?>
          </p>
          <?php } ?>

          <?=Html::tag('p',Html::hiddenInput('shipment[shipment_name]',$shipment['shipment_name']))?>
          <?=Html::tag('p',Html::hiddenInput('type','deliver_one_step'))?>
          <p><?= Html::button('Continue', ['class' => 'login-btn next-step', 'name' => 'login-button']) ?></p>

          <?php ActiveForm::end(); ?>
        </div>
      </div>
      <div class="col-sm-4"></div>
      <div class="clearfix"></div>
    </div>
