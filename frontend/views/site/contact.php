<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;


?>
<style>
.map iframe {width:100%;}
.con-input {width:100%;}
textarea.message {width:100%;}
</style>
        <section class="contact-wrap">
            <?php echo isset($page['content']) ? $page['content'] : '';?>

            <div class="contact-info">
              <h2>Enquiry Form</h2>
              <p class="contact-note">Please note: Input fields marked with a * are required fields.</p>
              <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-3 col-xs-4">
                      <div class="row"><p class="contact-lable">Name*:</p></div>
                      <div class="row"><p class="contact-lable">Phone number:</p></div>
                      <div class="row"><p class="contact-lable">Email Address*:</p></div>
                      <div class="row"><p class="contact-lable">Message:</p></div>

                    </div>
                    <div class="col-sm-9 col-xs-8">
                      <div class="row">
                       <p> <?=Html::activeTextInput($model,'name',['class'=>'con-input','placeholder'=>'your name']);?>
                        <?php
                        if(isset($model->getErrors('name')['0'])) echo Html::tag('span',$model->getErrors('name')['0'],['style'=>'color:red']);
                        ?>
                       </p>
                      </div>
                      <div class="row">
                       <p> <?=Html::activeTextInput($model,'phone',['class'=>'con-input','placeholder'=>'phone number'])?>
                        <?php
                        if(isset($model->getErrors('phone')['0'])) echo Html::tag('span',$model->getErrors('phone')['0'],['style'=>'color:red']);
                        ?>
                       </p>
                      </div>
                      <div class="row">
                       <p> <?=Html::activeTextInput($model,'email',['class'=>'con-input','placeholder'=>'example@email.co.uk'])?>
                        <?php
                        if(isset($model->getErrors('email')['0'])) echo Html::tag('span',$model->getErrors('email')['0'],['style'=>'color:red']);
                        ?>
                        </p>
                      </div>
                      <div class="row">
                       <p> <?=Html::activeTextarea($model,'message',['class'=>'message','placeholder'=>'please leave a message'])?>
                        <?php
                        if(isset($model->getErrors('message')['0'])) echo Html::tag('span',$model->getErrors('message')['0'],['style'=>'color:red']);
                        ?>
                       </p>
                      </div>
                      <div class="row">
                        <p> <input type="submit" name="send" class="book-send" value="Send"></p>
                      </div>

                    </div>
                  </div>
                </div>
              <?php ActiveForm::end(); ?>
            </div>


            <div class="clearfix"></div>
        </section>
