<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>
<style>
.book-input {width:100%;}
.noofpeople {width:19%;}
textarea.message {width:100%;}
</style>
<section class="booking-wrap">

  <div class="row">
    <div class="col-sm-3 col-xs-4">
      <div class="row">
            <div class="col-sm-12 book-title"><h2>Booking</h2></div>
      </div>
    </div>
    <div class="col-sm-9 col-xs-8 book-info-row">
        <p class="book-info">Please fill in your booking information. We will have someone to contact you to confirm your booking.</p>
    </div>
  </div>

  <?php $form = ActiveForm::begin(['id' => 'book-form']); ?>
    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable" style="padding-top:6px;">Your Name*:</p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <p><?=Html::activeTextInput($model,'name',['class'=>'book-input','placeholder'=>'full name'])?>
                    <?php if(isset($model->getErrors('name')['0'])):?>
                    <?=Html::tag('span',$model->getErrors('name')['0'],['style'=>'color:red'])?>
                    <?php endif;?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable">Your Email*:</p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <p><?=Html::activeTextInput($model,'email',['class'=>'book-input','placeholder'=>'example@yahoo.co.uk'])?>
                    <?php if(isset($model->getErrors('email')['0'])):?>
                    <?=Html::tag('span',$model->getErrors('email')['0'],['style'=>'color:red'])?>
                    <?php endif;?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable" style="padding-top:6px;">Telephone*:</p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <p><?=Html::activeTextInput($model,'phone',['class'=>'book-input','placeholder'=>'phone number'])?>
                    <?php if(isset($model->getErrors('phone')['0'])):?>
                    <?=Html::tag('span',$model->getErrors('phone')['0'],['style'=>'color:red'])?>
                    <?php endif;?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable" style="padding-top:6px;">Date*:</p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <p><?=Html::activeTextInput($model,'date',['class'=>'book-date','placeholder'=>date('d-m-Y'),'id'=>'BookingForm-date'])?>
        <i class="glyphicon glyphicon-calendar date-selecter" data-date="" data-date-format="dd MM yyyy" data-link-field="BookingForm-date" data-link-format="dd-mm-yyyy"></i>
                    <?php if(isset($model->getErrors('date')['0'])):?>
                    <?=Html::tag('span',$model->getErrors('date')['0'],['style'=>'color:red'])?>
                    <?php endif;?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable">Time*:</p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <div class="booking-se" style="margin-bottom:26px;">
          <SELECT name='BookingForm[hour]'>
            <option value=''>hour</option>
            <?php
               // var_dump($hear);
              $start_hour=0;
              $endhour=24;

              if(isset($hour['options'])):
              foreach ($hour['options'] as $k => $v) {
                if($v['name']=='start'){
                  $start_hour = $v['options'];
                }else if ($v['name']=='end') {
                  $endhour = $v['options'];
                }

              }
              endif;

            for ($i=$start_hour; $i <=$endhour ; $i++) {
              echo Html::tag('option',$i,['value'=>$i,'selected'=>($model->hour==$i ? true : false)]);
            }
            ?>
          </SELECT>
          <SELECT name='BookingForm[minute]' >
            <option value=''>minute</option>
            <?php
            echo Html::tag('option',0,['value'=>0,'selected'=>($model->minute=='0' ? true : false)]);
            echo Html::tag('option',15,['value'=>15,'selected'=>($model->minute=='15' ? true : false)]);
            echo Html::tag('option',30,['value'=>30,'selected'=>($model->minute=='30' ? true : false)]);
            echo Html::tag('option',45,['value'=>45,'selected'=>($model->minute=='45' ? true : false)]);
            ?>
          </SELECT>
          <?php if(isset($model->getErrors('hour')['0'])):?>
              <?=Html::tag('span',$model->getErrors('hour')['0'],['style'=>'color:red'])?>
          <?php endif;?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable">Number of people*:</p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <p><?=Html::activeTextInput($model,'people',['class'=>'book-input noofpeople','placeholder'=>'2'])?>
                  <?php if(isset($model->getErrors('people')['0'])):?>
                    <?=Html::tag('span',$model->getErrors('people')['0'],['style'=>'color:red;width:19%;'])?>
                    <?php endif;?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable">Your Message:</p>
      </div>
      <div class="col-sm-9 col-xs-8 ">
        <?=Html::tag('p',Html::activeTextarea($model,'subject',['class'=>'message','placeholder'=>'leave a message']))?>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
        <p class="book-lable"><?php echo $model->setverifyLabel()?></p>
      </div>
      <div class="col-sm-9 col-xs-8">
        <p><?=Html::activeTextInput($model,'verifyCode',['class'=>'message noofpeople'])?>

                    <?php if(isset($model->getErrors('verifyCode')['0'])):?>
                    <?=Html::tag('span',$model->getErrors('verifyCode')['0'],['style'=>'color:red'])?>
                    <?php endif;?>
        </p>

      </div>
    </div>

    <div class="row">
      <div class="col-sm-3 col-xs-4">
      </div>
      <div class="col-sm-9 col-xs-8">
        <p> <input type="submit" name="send" class="book-send" value="Send"></p>
      </div>
    </div>



  <?php ActiveForm::end(); ?>
  <div class="clearfix"></div>
</section>

<?php $this->registerCssFile(Yii::getAlias('@web')."/frontend/web/js/datepicker/bootstrap-datetimepicker.css"); ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/frontend/web/js/datepicker/bootstrap-datetimepicker.js", ['position'=>\yii\web\View::POS_END,'depends'=> [\backend\assets\AppAsset::className()]]); ?>
<?php $this->beginBlock('booking') ?>
jQuery(document).ready(function () {
$('.date-selecter').datetimepicker({
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    showMeridian: 1,
    bootcssVer:3
});
});

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['booking'], \yii\web\View::POS_END); ?>
