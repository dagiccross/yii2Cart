<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use thridpart\phpmailer\EXTMailer;

/**
* ContactForm is the model behind the contact form.
*/
class ContactForm extends Model
{
  public $name;
  public $email;
  public $phone;
  public $message;

  public $verifyCode;
  public $verifyValue;

  /**
  * @inheritdoc
  */
  public function rules()
  {
    return [
      // name, email, subject and body are required
      [['name', 'email', 'verifyCode', 'phone', 'message'], 'required'],
      // email has to be a valid email address
      ['email', 'email'],
      ['verifyCode','validateVerifyCode'],
      // verifyCode needs to be entered correctly
      // ['verifyCode', 'captcha'],
    ];
  }

  /**
  * @inheritdoc
  */


  /**
  * Sends an email to the specified email address using the information collected by this model.
  *
  * @param  string  $email the target email address
  * @return boolean whether the email was sent
  */
  // 驗證碼驗證
  public function validateVerifyCode($attribute, $params){
    // if (!$this->hasErrors()) {
    $verifyValue = \Yii::$app->session['verifyValue'];
    $flat = $this->verifyCode == $verifyValue;
    // var_dump($length);exit();
    if (!$flat) {
      $this->addError($attribute, 'Answer Incorrect.');
    }
    // }
  }

  public function attributeLabels()
  {

    return [
      'verifyCode' => 'verifyCode',
    ];
  }

  // 設置驗證
  public function setverifyLabel(){



    if(Yii::$app->request->isPost){
      $r1 = \Yii::$app->session['r1'];
      $r2 = \Yii::$app->session['r2'];
      $this->verifyValue = \Yii::$app->session['verifyValue'];
    }else{
      $r1 = rand(0,10);
      $r2 = rand(0,10);
    }
    $this->verifyValue = $r1+$r2;
    $label = $r1.'+'.$r2.'=?';
    \Yii::$app->session['verifyValue'] = $this->verifyValue;
    \Yii::$app->session['r1'] = $r1;
    \Yii::$app->session['r2'] = $r2;
    // echo $this->verifyValue;
    return $label;
  }

  public function sendEmail($email)
  {
    $startdate = isset(\Yii::$app->session['contact_eamil_time']) ? \Yii::$app->session['contact_eamil_time'] : null;
    if (!empty($startdate)){
      $enddate = time();
      $minute = floor((strtotime($enddate)-strtotime($startdate))%86400/60);

      if($minute<30){
        \Yii::$app->session['error_email'] = 'Message was sent out a minute ago. Please try again in 30 minutes.';
        return false;
      }
    }
    unset(\Yii::$app->session['error_email']);

    $mail = new EXTMailer();

    $mail->AddAddress($email);  // 收件人邮箱和姓名

    // 邮件主题
    $mail->subject = 'Contact Form';
    // 邮件内容

    $mail->body = '
    <html><head>
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>

    <div class="password-reset">
    <p>Contact Form: </p>
    <p>Name: '.\yii\helpers\Html::encode($this->name).' </p>
    <p>Phone: '.\yii\helpers\Html::encode($this->phone).' </p>
    <p>Email: '.\yii\helpers\Html::encode($this->email).' </p>
    <p>Message: '.\yii\helpers\Html::encode($this->message).'</p>
    </div>
    </body>
    </html>
    ';

    $flat = $mail->Send();
    // var_dump($flat);
    // exit();
    return $flat;
  }
}
