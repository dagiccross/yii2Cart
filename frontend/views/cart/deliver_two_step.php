<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Config;
use common\models\Time;
?>
<section class="login-wrap">
    <div class="col-sm-4"></div>
        <div class="col-sm-4 middle-content">
            <div class="login-form">
            <h2>Confirm your delivery time</h2>
            <?php $form = ActiveForm::begin(['id' => 'deliver_two_step']); ?>

            <?=Html::tag('p','Delivery  time',['class'=>'lotitle'])?>
            <?=Html::tag('p',Html::dropDownList('time','',$timelist,['class'=>'email-input']))?>
            <?=Html::tag('p','Leave us a note',['class'=>'lotitle'])?>
            <?=Html::tag('p',Html::textArea('note',$cart['note'],['class'=>'email-input','placeholder'=>'leave a message']))?>
            <p class="allergy" onclick="javascript:allergy();">Do you have an allergy?</p>
            <?=Html::hiddenInput('type','deliver_two_step')?>
            <p><?= Html::button('Go to payment', ['class' => 'login-btn next-step', 'name' => 'login-button']) ?></p>

            <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-sm-4"></div>
        <div class="clearfix"></div>
</section>


<!-- NO USE|EXTRA -->
<!-- <div class="modal fade allergy-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm candialog ">
    <div class="modal-content cancal-content" style="padding: 15px; width: 550px;">

      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

        </div>
        <div>
        <?php echo isset($allergy['content']) ? $allergy['content'] : '';?>
        </div>
        <div><button class="checkout-button" type="button" data-dismiss="modal" aria-label="Close"><?=\Yii::t('app','Close')?></button> </div>

    </div>
  </div>
</div> -->
