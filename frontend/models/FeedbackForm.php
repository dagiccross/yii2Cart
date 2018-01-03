<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
/**
 * ContactForm is the model behind the contact form.
 */
class FeedbackForm extends Model
{
    public $answer;
    public $feedback_about;
    public $feedback;
    public $email;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            // [['email', 'phone', 'date', 'hour', 'minute', 'people','verifyCode'], 'required'],
            // email has to be a valid email address
            // ['email', 'email'],
            // ['verifyCode','compare', 'compareAttribute'=>'verifyValue'],
            // verifyCode needs to be entered correctly
            // ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'verifyCode' => 'verifyCode',
        ];
    }

    
    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        $html = Html::tag('p','How likely is it that you would recommend ME to a friend or family member?:'.$this->answer);
        $html .= Html::tag('p','What\'s your feedback about?:'.$this->feedback_about);
        $html .= Html::tag('p','review:'.$this->feedback);
        $html .= Html::tag('p','email:'.$this->email);

        require(dirname(\Yii::$app->basePath).'/thridpart/phpmailer/class.phpmailer.php');
                $mail = new \PHPMailer();
                $mail->IsSMTP();                  // send via SMTP   
                $mail->Host = \common\models\Config::getConfig('smtp_server');   // SMTP servers   
                // $mail->SMTPAuth = true;           // turn on SMTP authentication   
                $mail->Username = \common\models\Config::getConfig('smtp_user');     // SMTP username  注意：普通邮件认证不需要加 @域名   
                $mail->Password = \common\models\Config::getConfig('smtp_password'); // SMTP password   
                $mail->From = \common\models\Config::getConfig('smtp_user');      // 发件人邮箱   
                $mail->FromName =  \Yii::$app->name.'robot';  // 发件人   

                $mail->CharSet = "UTF-8";   // 这里指定字符集！   
                $mail->Encoding = "base64";   
                $mail->AddAddress($email);  // 收件人邮箱和姓名   

                $mail->IsHTML(true);  // send as HTML   
                // 邮件主题   
                $mail->Subject = 'feedback ' . \Yii::$app->name;   
                // 邮件内容  

                $mail->Body = '  
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
                return $mail->Send();
    }
}
