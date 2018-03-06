<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
?>

<!-- <div class="acount-info">
<h2>Account info</h2>

<p class="acount-quest">Why sign up?</p>
<p class="acount-answer">Get local ofers by email every week,re-order saved meals in a few clicks,stores your delivery address and build a list of your favourite local takeaways.</p>

</div>
-->
<?php $form = ActiveForm::begin(['id' => 'member-form']); ?>
<div class="row">
  <div class="col-sm-6">
    <h3 class="form-title">Your details:</h3>
    <div class="row form-group">
      <div class="col-sm-4 control-lable">Email Address:</div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($member,'email',['class'=>'acount-input','placeholder'=>'example@yahoo.com'])?>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable">Name:*</div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($addr,'shipment_name',['class'=>'acount-input','placeholder'=>'Full Name'])?>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable">Phone number:*</div>
      <div class="col-sm-8 control">

        <?=Html::activeTextInput($addr,'shipment_phone',['class'=>'acount-input'])?>
      </div>
    </div>

  </div>

  <div class="col-sm-6">
    <h3 class="form-title">Delivery address:</h3>
    <div class="row form-group">
      <div class="col-sm-4 control-lable">Address:</div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($addr,'shipment_addr',['class'=>'acount-input','style'=>'text-transform:capitalize;','maxlength'=> 24])?>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable"></div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($addr,'shipment_addr2',['class'=>'acount-input','style'=>'text-transform:capitalize;','maxlength'=> 24])?>
      </div>
    </div>

    <div class="row form-group">
      <?php
      $map_calculation = \common\models\Config::getConfig('map_calculation');
      if ($map_calculation == 0){

        echo '<div class="col-sm-4 control-lable"></div>';
      } elseif ($map_calculation == 1){
        echo '<div class="col-sm-4 control-lable">City / Town:</div>';
      }
      ?>
      <div class="col-sm-8 control">
        <?php
        $map_calculation = \common\models\Config::getConfig('map_calculation');
        if ($map_calculation == 0){
          ?>
          <!-- will remove since using postcode price calculations -->
          <!-- <?=Html::activeDropdownList($addr,'shipment_city',getShipmentPostcode(),['class'=>'acount-input'])?> -->
        <?php } elseif ($map_calculation == 1){ ?>
          <?=Html::activeTextInput($addr,'shipment_city',['class'=>'acount-input'])?>
        <?php } ?>
      </div>
    </div>
    <div class="row form-group">
      <div class="col-sm-4 control-lable">Postcode:*</div>
      <div class="col-sm-8 control">
        <?php

        if ($map_calculation == 0){
          ?>
          <?=Html::activeTextInput($addr,'shipment_postcode2',['class'=>'acount-input','style'=>'text-transform:uppercase;','maxlength'=> 8])?>
        <?php } elseif ($map_calculation == 1){ ?>
          <?=Html::activeDropdownList($addr,'shipment_postcode',getShipmentPostcode(),['class'=>'postcode-input'])?>
          <?=Html::activeTextInput($addr,'shipment_postcode2',['class'=>'postcode2-input','placeholder'=>'','style'=>'text-transform:uppercase;','maxlength'=> 3])?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<!-- <div class="row form-group">
<div class="col-sm-12 form-check">
<?php //Html::checkbox('User[offers]',$member->offers,['class'=>'control-check'])?>
<label>Send me tasty takeway offers(we won’t sell your details to anyone)</label>
</div>
</div> -->

<div class="row form-group">
  <div class="col-sm-12">
    <a class="change-btn col-xs-12" style="cursor:pointer;"data-toggle="modal" data-target="#w0"><center>Change password</center></a>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">

    <div class="row form-group">

      <div class="control">
        <!-- <button class="delete-btn">Delete</button>  -->
        <button class="save-btn col-xs-12" type="submit">Save changes</button></div>
      </div>

    </div>
  </div>
  <?php ActiveForm::end(); ?>


  <div style="display: none;" id="w0" class="fade modal in" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h2>Change password</h2>
        </div>
        <div class="modal-body">
          <div class="upfile-create">
            <div class="form-group">
              <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>
              <div class="row form-group">
                <div class="col-sm-3">
                  <?=Html::label('Old password')?>
                </div>
                <div class="col-sm-9">
                  <?=Html::passwordInput('Password[old]','',['class'=>'form-control'])?>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-sm-3">
                  <?=Html::label('New password')?>
                </div>
                <div class="col-sm-9">
                  <?=Html::passwordInput('Password[new]','',['class'=>'form-control'])?>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-sm-3">
                  <?=Html::label('Confirm password')?>
                </div>
                <div class="col-sm-9">
                  <?=Html::passwordInput('Password[confirm]','',['class'=>'form-control'])?>
                </div>
                <div class="col-sm-12">
                  <button type="submit" id="change_password" class="btn save-btn col-sm-12">Confirm</button>
                </div>
                <?php ActiveForm::end(); ?>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
