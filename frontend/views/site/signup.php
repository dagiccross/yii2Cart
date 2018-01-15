<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>sign up here to order</title>
  <link rel="stylesheet" href="<?php echo CSS_URL?>/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo CSS_URL?>/style.css">
  <link rel="stylesheet" href="<?php echo CSS_URL?>/responsive.css">
  <script src="<?php echo JS_URL?>/jquery-1.11.3.min.js"></script>
  <script src="<?php echo JS_URL?>/bootstrap.min.js"></script>
  <script src="<?php echo JS_URL?>/waimai.js"></script>
  <script src="<?php echo JS_URL?>/signup.js"></script>
</head>
<body>
  <!-- Trigger the modal with a button -->
  <p style="display:none;" data-toggle="modal" id="myModal2" data-target="#myModal"></p>

  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><center><span style="font-size:2.0em;" class="glyphicon glyphicon-exclamation-sign"></span></center></h4>
        </div>
        <div class="modal-body">
          <center><p id="popupsign"></p></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <!-- end -->

  <!-- form starts -->
  <div class="container">
    <section class="sign-wrap">
      <div class="row">

        <div class="col-lg-6 col-lg-offset-3 col-md-12 col-sm-12">
          <div class="sign-form">
            <h2>Sign up here to order</h2>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?=Html::tag('p','Email*',['class'=>'detitle'])?>
            <?=Html::tag('p',Html::activeInput('email',$model,'username',['class'=>'form-control','placeholder'=>'example@email.co.uk']))?>
            <?php if(isset($model->getErrors('username')['0'])):?>
              <?=Html::tag('p',$model->getErrors('username')['0'],['style'=>'color:red'])?>
            <?php endif;?>


            <?=Html::tag('p','Password*',['class'=>'detitle'])?>
            <?=Html::tag('p',Html::activePasswordInput($model,'password',['class'=>'form-control','placeholder'=>'password']))?>
            <?php if(isset($model->getErrors('password')['0'])):?>
              <?=Html::tag('p',$model->getErrors('password')['0'],['style'=>'color:red'])?>
            <?php endif;?>

            <?=Html::tag('p','Confirm password*',['class'=>'detitle'])?>
            <?=Html::tag('p',Html::activePasswordInput($model,'confirm_password',['class'=>'form-control','placeholder'=>'Re-enter your password']))?>
            <?php if(isset($model->getErrors('confirm_password')['0'])):?>
              <?=Html::tag('p',$model->getErrors('confirm_password')['0'],['style'=>'color:red'])?>
            <?php endif;?>

            <p>
              <?=Html::checkBox('SignupForm[offers]')?>
              <span class="send-note">Subscribe to newsletter</span>
            </p>

            <?=Html::tag('p','Full name*',['class'=>'detitle'])?>
            <?=Html::tag('p',Html::activeTextInput($model,'shipment_name',['class'=>'form-control','placeholder'=>'Your name']))?>
            <?php if(isset($model->getErrors('shipment_name')['0'])):?>
              <p style="color:red;">Please enter your full name</p>
            <?php endif;?>

            <?=Html::tag('p','Mobile phone*',['class'=>'detitle'])?>
            <!-- <input type="text" id="signupform-shipment_phone" class="form-control" name="SignupForm[shipment_phone]"  pattern=".{7,11}" required title="Phone number should have at least 7 to 11 numbers" placeholder="07805000123" > -->
            <?=Html::tag('p',Html::activeTextInput($model,'shipment_phone',['class'=>'form-control','placeholder'=>'07805000123']))?>
            <?php if(isset($model->getErrors('shipment_phone')['0'])):?>
              <p style="color:red;">Please enter your correct phone number</p>
            <?php endif;?>

            <?=Html::tag('p','Address*',['class'=>'detitle'])?>
            <?=Html::tag('p',Html::activeTextInput($model,'shipment_addr',['class'=>'form-control','placeholder'=>'Address line 1','style'=>'text-transform:capitalize;','maxlength'=> 24]))?>
            <?php if(isset($model->getErrors('shipment_addr')['0'])):?>
              <p style="color:red;">Address line 1 cannot be blank</p>
            <?php endif;?>
            <?=Html::tag('p',Html::activeTextInput($model,'shipment_addr2',['class'=>'form-control','placeholder'=>'Address line 2 (optional)','style'=>'text-transform:capitalize;','maxlength'=> 24]))?>

            <!-- find out map_calculation -->
            <?php
            $map_calculation = \common\models\Config::getConfig('map_calculation');
            if ($map_calculation == 0){

              ?>
              <script type="text/javascript">
              // set varible for map calculation
              var map_cal = "0";
              </script>
              <?=Html::tag('p','Town or city* <small style=\'color:blue;\'>(please select the correct city/town)</small>',['class'=>'detitle'])?>
              <!-- <?=Html::dropdownlist('shipment[shipment_postcode]',$shipment['shipment_postcode'],getShipmentPostcode(),['class'=>'form-control','style'=>'width:100%;'])?> -->
              <?=Html::activeDropdownlist($model,'shipment_city',getShipmentPostcode(),['class'=>'form-control','style'=>'width:100%;'])?>
              <p class="detitle">Postcode* <span style="color:blue;"><small>(please fill in the correct postcode)</small></span></p>
              <p>

                <input type="text" id="signupform-shipment_postcode2" class="form-control" name="SignupForm[shipment_postcode2]" value="" maxlength="8" placeholder="Full postcode (no space)" pattern="[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]?( |)[0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}" style="width:100%;margin-left:0px;text-transform:uppercase" >
              </p>

              <?php if(isset($model->getErrors('shipment_postcode2')['0'])){
                echo '<p style="color:red;">Postcode cannot be blank</p>';
              }

            } elseif ($map_calculation == 1){ ?>
              <script type="text/javascript">
              // set varible for map calculation
              var map_cal = "1";
              </script>

              <?=Html::tag('p','Town or city',['class'=>'detitle'])?>
              <?=Html::tag('p',Html::activeTextInput($model,'shipment_city',['class'=>'sign-order','placeholder'=>'e.g. London']))?>

              <p class="detitle">Postcode <span style="color:red;">*Please select the correct postcode below then fill in the last remaining</span></p>
              <p>
                <?=Html::activeDropdownlist($model,'shipment_postcode',getShipmentPostcode(),['class'=>'poselect'])?>
                <?=Html::activeTextInput($model,'shipment_postcode2',['class'=>'post-input','placeholder'=>'last 3 characters','style'=>'text-transform:uppercase','maxlength'=> 3])?>
              </p>

              <?php    }; ?>
              <p>
                <?=Html::checkBox('SignupForm[policy]')?>
                <span class="send-note2">I accept the <?=Html::a('Terms and conditions',['/site/page' ,'page_id'=>'terms'])?>, <?=Html::a('Privacy Policy',['/site/page' ,'page_id'=>'Privacy'])?> and <?=Html::a('Cookies Policy*',['/site/page','page_id'=>'cookies'])?></span>
              </p>
              <?php if(isset($model->getErrors('policy')['0'])):?>
                <p style="color:red;">Please accept our policy before continue</p>
              <?php endif;?>
              <p class="continue-btn" onClick="myFunction()" style="cursor:pointer;text-align:center;">Register</p>
              <button id="submitSignUp" style="display:none;" type="submit" value="Continue" name="delogin" class="continue-btn">Register</button>
              <p>
                <?=Html::activeCheckBox($model,'rememberMe')?>
                <span class="send-note2">(Stay logged in)</span></p>
                <!-- <p class="add-feedback pageback">[-]Page Feedback</p> -->
                <?php ActiveForm::end(); ?>
              </div><!-- sign-form -->
            </div><!-- middle-content -->
          </div><!-- row -->

        </section>

      </div><!-- container -->

    </body>
    </html>
