<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use thridpart\phpmailer\EXTMailer;

/**
 * ContactForm is the model behind the contact form.
 */
class BookingForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $phone;
    public $date;
    public $hour;
    public $minute;
    public $people;
    public $hearus;
    public $verifyCode;
    public $verifyValue;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone', 'date', 'hour', 'minute', 'people','verifyCode'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['phone', 'integer'],
            ['phone','validatePhone'],
            ['date', 'validateDate'],
            ['subject','string','max'=>255],
            ['verifyCode','validateVerifyCode'],
            // verifyCode needs to be entered correctly
            // ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */

    // 驗證電話
    public function validatePhone($attribute, $params){

        if (!$this->hasErrors()) {
            $length = strlen($this->phone);
            // var_dump($length);exit();
            if ($length<11||$length>13) {
                $this->addError($attribute, 'Phone should contain at least 11 characters digits.');
            }
        }
    }
    // 驗證日期
    public function validateDate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // $date = $this->datetotime($this->date,'DD-MM-YYYY');
            $date = strtotime($this->date);
            $now = time();

            // echo date('Y-m-d',$date);
            // echo date('Y-m-d',$now);
            // echo ($date-$now);
            // exit();

            $shijiancha=ceil(($date-$now)/86400); //60s*60min*24h

            if ($shijiancha<0) {
                $this->addError($attribute, 'can not booking taday before.');
            }else if($shijiancha>30){
                $this->addError($attribute, 'can not booking out of 30 days.');
            }
        }
    }
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
    public function datetotime ($date, $format = 'YYYY-MM-DD') {

        if ($format == 'YYYY-MM-DD') list($year, $month, $day) = explode('-', $date);
        if ($format == 'YYYY/MM/DD') list($year, $month, $day) = explode('/', $date);
        if ($format == 'YYYY.MM.DD') list($year, $month, $day) = explode('.', $date);

        if ($format == 'DD-MM-YYYY') list($day, $month, $year) = explode('-', $date);
        if ($format == 'DD/MM/YYYY') list($day, $month, $year) = explode('/', $date);
        if ($format == 'DD.MM.YYYY') list($day, $month, $year) = explode('.', $date);

        if ($format == 'MM-DD-YYYY') list($month, $day, $year) = explode('-', $date);
        if ($format == 'MM/DD/YYYY') list($month, $day, $year) = explode('/', $date);
        if ($format == 'MM.DD.YYYY') list($month, $day, $year) = explode('.', $date);

        return mktime(0, 0, 0, $month, $day, $year);

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
    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        $html = Html::tag('p','Booking Form: ');
        $html .= Html::tag('p','Name: '.$this->name);
        $html .= Html::tag('p','Email: '.$this->email);
        $html .= Html::tag('p','Phone: '.$this->phone);
        $html .= Html::tag('p','Date: '.$this->date.' Time: '.$this->hour.':'.$this->minute);
        $html .= Html::tag('p','Number of people: '.$this->people);
        $html .= Html::tag('p','Message: '.$this->subject);
        // $html .= Html::tag('p','Where do you hear us: '.$this->hearus);

                $mail = new EXTMailer();

                $mail->AddAddress($email);  // 收件人邮箱和姓名

                // 邮件主题
                $mail->subject = 'Booking Request';
                // 邮件内容

                $mail->body = '
                <html><head>
                <meta http-equiv="Content-Language" content="zh-cn">
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                </head>
                <body>

                <div class="password-reset">
                '.$html.'
                </div>
                </body>
                </html>
                ';
                $mail->AltBody ="text/html";
                $flat = $mail->Send();
                // var_dump($flat);
                // exit();
                return $flat;
    }
}
