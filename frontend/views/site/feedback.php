<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Config;
?>
<!DOCTYPE html>
<html class=" js no-touch geolocation postmessage boxshadow boxsizing no-framed position-fixed"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-16">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="initial-scale=1.0, maximum-scale=1.0, width=device-width, user-scalable=no" name="viewport">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="language" content="en">
<title>
</title>
<link charset="utf-8" href="<?=CSS_URL?>/universal.css" media="screen" rel="stylesheet" type="text/css">

<script charset="utf-8" src="<?=JS_URL?>/modernizr.min.js" type="text/javascript"></script>
<script src="<?=JS_URL?>/b5m.main.js" charset="utf-8" id="b5mmain" type="text/javascript"></script></head>
<body class="two_column">
<noscript>
<p><strong>Javascript Disabled</strong><br>
  Hey! Looks like you have javascript disabled or are using a browser that doesn't support it. You can still leave feedback it just won't be as pretty and easy to do it.
</p>
</noscript>
<div class="nocss">
<p>You are using a browser that doesn't support css or has incomplete support. That means things aren't going to look good! You can still leave feedback it just won't be as easy.</p>
</div>
<?php $form = ActiveForm::begin(['options'=>['autocomplete'=>'off']])?>
<div id="banner" role="banner">
  <div class="shell">
    <?=Html::a(Html::img(Config::getConfig('logo')))?>
    <button class="button disabled" id="header_submit_button" type="submit">
      <span>Send</span>
    </button>
    <a class="sr_close none screen_reader" href="#">Close this comment card</a>
  </div>
</div>
<div id="submitResultAlert" role="alert" aria-live="assertive"></div>
<div id="thankyou">
  <div class="shell">
  <h1 id="tyHeader">Thanks for your feedback!</h1>
  <div class="rater_button"></div>
  <p id="tyMessage"></p>
  <div id="is_links"></div>
  </div>
</div>
<div id="fail">
  <div class="shell">
    <h1>Oops! Either you are having connectivity issues or we are.</h1>
    <p>Sorry for the inconvenience.
      <br>
      You can wait a bit and try submitting your feedback again.
    </p>
    <button class="button" type="submit"><span>Try Again</span>
    </button>
  </div>
</div>
<div id="main" class="shell" role="main">
<div class="section none" id="form_errors" aria-live="assertive" role="alert">
<div>
  There were problems with your comment card.
  <br>
  Please fill out the highlighted fields.
</div>
</div>
<style type="text/css">
  #header_submit_button {display: none;}
  button.button {width:100%;}
</style>
<div id="nps_container">
<div class="section">
  <fieldset class="nps">
  <legend>How likely is it that you would recommend <?=Config::getConfig('sitename')?> to a friend or family member?</legend>
    <div><input id="nps_1_0" name="FeedbackForm[answer]" value="Zero" type="radio"><label for="nps_1_0">0</label></div>
    <div><input id="nps_1_1" name="FeedbackForm[answer]" value="1" type="radio"><label for="nps_1_1">1</label></div>
    <div><input id="nps_1_2" name="FeedbackForm[answer]" value="2" type="radio"><label for="nps_1_2">2</label></div>
    <div><input id="nps_1_3" name="FeedbackForm[answer]" value="3" type="radio"><label for="nps_1_3">3</label></div>
    <div><input id="nps_1_4" name="FeedbackForm[answer]" value="4" type="radio"><label for="nps_1_4">4</label></div>
    <div><input id="nps_1_5" name="FeedbackForm[answer]" value="5" type="radio"><label for="nps_1_5">5</label></div>
    <div><input id="nps_1_6" name="FeedbackForm[answer]" value="6" type="radio"><label for="nps_1_6">6</label></div>
    <div><input id="nps_1_7" name="FeedbackForm[answer]" value="7" type="radio"><label for="nps_1_7">7</label></div>
    <div><input id="nps_1_8" name="FeedbackForm[answer]" value="8" type="radio"><label for="nps_1_8">8</label></div>
    <div><input id="nps_1_9" name="FeedbackForm[answer]" value="9" type="radio"><label for="nps_1_9">9</label></div>
    <div><input id="nps_1_10" name="FeedbackForm[answer]" value="10" type="radio"><label for="nps_1_10">10</label></div>
  </fieldset>
<div class="clear"></div>
</div>
</div>

<fieldset id="comments">
<legend class="c_head">Comments</legend>
<div class="section">
<div class="section">
  <label id="ts_label" for="topic_selector">What's your feedback about?</label>
  <div id="topic_selector" class="" aria-required="">
    <select aria-required="true" name="FeedbackForm[feedback_about]" id="topic_select">
      <option value="0"></option>
      <option value="I want to write a review">I want to write a review</option>
      <option value="An order I've placed">An order I've placed</option>
      <option value="An issue with the website">An issue with the website</option>
      <option value="I have a suggestion">I have a suggestion</option>
      <option value="I have a compliment">I have a compliment</option>
      <option value="Other">Other</option>
    </select>
  </div>
</div>
<label class="screen_reader" for="comment_box">Comments</label>
<div class="placeholder visible">Enter page feedback.</div>
<textarea data-max-length="1000" name="FeedbackForm[feedback]" id="comment_box" class="" placeholder=""></textarea>
<div id="comment_limit">
<div class="counter_label">1000 characters left</div>
</div>
</div>
<div class="section" id="customer_service_links">If you have a problem with an order, please contact our <?=Html::a('customer services',['/site/contact','id'=>22],['target'=>"_blank"])?> directly.</div>
</fieldset>
<div id="custom_questions">
<div class="q_container">
</div>
<div class="section" id="email">
  <label for="email_address" class="">Enter your email (optional)<span class="screen_reader"></span></label>
  <input id="email_address" autocapitilize="off" autocorrect="off" maxlength="128" name="FeedbackForm[email]" class="" type="text"><div class="clear"></div>
</div>
</div>
<div class="section" id="foot">
  <button class="button disabled" id="submit_button" type="submit">
    <span>Send Feedback</span>
  </button>
  <a class="sr_close none screen_reader" href="#">Close this comment card</a><div id="foot_text">
  <p><?=Config::getConfig('copyright')?></p>
</div>
<div class="clear"></div>
</div>
</div>
<?php ActiveForm::end();?>
<script charset="utf-8" src="<?=JS_URL?>/cc-engine.min.js" type="text/javascript"></script>

</body></html>
