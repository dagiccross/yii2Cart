<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<style type="text/css">
.red{color: red}
</style>

<section class="review-wrap">
  <div class="col-sm-12">
    <div class="customer-feedback">
      <span class="fespan"><?=Html::a(\Yii::t('app','Leave Us Feedback'),['/site/review'])?></span>

    </div>
    <div class="clearfix"></div>
    <div class="row" >
      <div class="feedcap">
        <div class="feed-value">
          <div class="value-caption">Your <strong>feedback</strong> is important in helping us improve our food quality and service Please include contact details if you require a response from the takeaway.</div>
          <?php $form = ActiveForm::begin();?>
          <div class="feed-list">
            <table class="value-table">
              <tbody>
                <tr>
                  <td>Was the food value for money?</td>
                  <td><?=Html::radio('OrderReview[money]',$order_review->money==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span></td>
                  <td><?=Html::radio('OrderReview[money]',$order_review->money==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span></td>
                  <td><?=Html::radio('OrderReview[money]',$order_review->money==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span></td>
                  <td><?=Html::radio('OrderReview[money]',$order_review->money==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span></td>
                  <td><?=Html::radio('OrderReview[money]',$order_review->money==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span></td>
                </tr>

                <tr>
                  <td>How quick was the delivery?</td>
                  <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span></td>
                  <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span></td>
                  <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span></td>
                  <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span></td>
                  <td><?=Html::radio('OrderReview[delivery]',$order_review->delivery==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span></td>
                </tr>

                <tr>
                  <td>How was the food?</td>
                  <td><?=Html::radio('OrderReview[food]',$order_review->food==4,['value'=>4,'class'=>'value-radio'])?><span class="value-txt">Very Good</span></td>
                  <td><?=Html::radio('OrderReview[food]',$order_review->food==3,['value'=>3,'class'=>'value-radio'])?><span class="value-txt">Good</span></td>
                  <td><?=Html::radio('OrderReview[food]',$order_review->food==2,['value'=>2,'class'=>'value-radio'])?><span class="value-txt">Average</span></td>
                  <td><?=Html::radio('OrderReview[food]',$order_review->food==1,['value'=>1,'class'=>'value-radio'])?><span class="value-txt">Bad</span></td>
                  <td><?=Html::radio('OrderReview[food]',$order_review->food==0,['value'=>0,'class'=>'value-radio'])?><span class="value-txt">Very Bad</span></td>
                </tr>

                <tr>
                  <td>Your Name</td>
                  <td colspan="5"><?=Html::activeTextInput($order_review,'name',['class'=>'name-input', 'style'=>'width:100%;'])?>
                    <?php if(isset($order_review->getErrors('name')['0'])):?>
                      <?=Html::tag('p',$order_review->getErrors('name')['0'],['class'=>'red'])?>
                    <?php endif;?>
                  </td>

                </tr>

                <tr>
                  <td colspan="6">
                    <p>Coments:</p>
                    <?=Html::activeTextArea($order_review,'comment',['class'=>'form-control', 'rows'=>'5'])?>
                    <?php if(isset($order_review->getErrors('comment')['0'])):?>
                      <?=Html::tag('p',$order_review->getErrors('comment')['0'],['class'=>'red']);?>
                    <?php endif;?>
                  </td>
                </tr>



                      <?=Html::activeHiddenInput($order_review,'order_id')?>
                      <?php if($order_review->isNewRecord):?>
                        <!-- STARTS captcha -->

                        <tr>
                          <td colspan="6">
                        <p>Enter Code >
                          <span id="txtCaptchaDiv" style="border-radius:3px;background-color:black;color:#FFF;padding:3px 5px"></span></p>

                          <div>

                            <input type="hidden" id="txtCaptcha" />
                            <input type="text" class="name-input" placeholder="Captcha" style="width:100%;" required="on" name="txtInput" id="txtInput" />
                            <p id="captchaError"></p>
                          </div>
                          
                          <p class="continue-btn" onClick="return checkform(this);" style="cursor:pointer;text-align:center;">Submit</p>
                          <!-- STOPS captcha -->
                        <input type="submit" name="send" class="submit-feed hidden" id="capSubmit" value="Send">
                      </td>
                        </tr>
                      <?php endif;?>




                </tbody>
              </table>
              <p class="please-text">1.Please note that some of the feedback might be displayed on our website.<br/>
                2.Please include contact details if you require a response from the manager.</p>
              </div>
              <?php ActiveForm::end();?>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>

      </div>
      <div class="clearfix"></div>
      <div class="row">

      </div>

      <div class="clearfix"></div>
    </section>

    <script type="text/javascript">
    /* STARTS Captcha */

    function checkform(theform){
      var why = "";
      var txtInput = $("input[name='txtInput']").val();
      if(txtInput == ""){
        why += "Security code should not be empty.\n";
      }
      if(txtInput != ""){
        if(ValidCaptcha(txtInput) == false){
          why += "Security code did not match.\n";
        }
      }
      if(why != ""){

        modal_alert(why);
        return false;
      }
    }

    var a = Math.ceil(Math.random() * 9)+ '';
    var b = Math.ceil(Math.random() * 9)+ '';
    var c = Math.ceil(Math.random() * 9)+ '';
    var d = Math.ceil(Math.random() * 9)+ '';
    var e = Math.ceil(Math.random() * 9)+ '';

    var code = a + b + c + d + e;
    document.getElementById("txtCaptcha").value = code;
    document.getElementById("txtCaptchaDiv").innerHTML = code;

    function ValidCaptcha(){
      var str1 = removeSpaces(document.getElementById('txtCaptcha').value);
      var str2 = removeSpaces(document.getElementById('txtInput').value);
      if (str1 == str2){
        document.getElementById('capSubmit').click();
        return true;

      }else{
        return false;
      }
    }

    function removeSpaces(string){
      return string.split(' ').join('');
    }
    /* ENDS Captcha */
    </script>
