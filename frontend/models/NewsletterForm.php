<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class NewsletterForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail()
    {
        require(dirname(\Yii::$app->basePath).'/thridpart/phpmailer/class.phpmailer.php');
                $mail = new \PHPMailer();
                $mail->IsSMTP();                  // send via SMTP   
                $mail->Host = \common\models\Config::getConfig('smtp_server');   // SMTP servers   
                // $mail->SMTPAuth = true;           // turn on SMTP authentication   
                $mail->Username = \common\models\Config::getConfig('smtp_user');     // SMTP username  注意：普通邮件认证不需要加 @域名   
                $mail->Password = \common\models\Config::getConfig('smtp_password'); // SMTP password   
                $mail->From = $this->email;      // 发件人邮箱   
                $mail->FromName =  \common\models\Config::getConfig('smtp_user');  // 发件人   

                $mail->CharSet = "UTF-8";   // 这里指定字符集！   
                $mail->Encoding = "base64";   
                $mail->AddAddress(\common\models\Config::getConfig('smtp_user'));  // 收件人邮箱和姓名   

                $mail->IsHTML(true);  // send as HTML   
                // 邮件主题   
                $mail->Subject = 'News letter ';   
                // 邮件内容  

                $mail->Body = '<p>newsletter email:'.$this->email.'</p>';                                                                         
                $mail->AltBody ="text/html";   
                return $mail->Send();
    }
}
