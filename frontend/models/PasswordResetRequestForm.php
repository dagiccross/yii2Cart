<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'common\models\User',
                // 'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            // 'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save()) {
                require(dirname(\Yii::$app->basePath).'/thridpart/phpmailer/class.phpmailer.php');
                $mail = new \PHPMailer();
                $mail->IsSMTP();                  // send via SMTP   
                $mail->Host = \common\models\Config::getConfig('smtp_server');   // SMTP servers   

                $ssl = \common\models\Config::getConfig('smtp_ssl');
                $ssl = empty($ssl)||$ssl==false ? false : true;
                $port = \common\models\Config::getConfig('smtp_port');
                $port = empty($port) ? '25' : $port;

                $mail->SMTPAuth = $ssl;           // turn on SMTP authentication 
                $mail->Port = \common\models\Config::getConfig('smtp_port'); // SMTP Port $mail->Port = Config::getConfig('smtp_port'); // SMTP Port 


                $mail->Username = \common\models\Config::getConfig('smtp_user');     // SMTP username  注意：普通邮件认证不需要加 @域名   
                $mail->Password = \common\models\Config::getConfig('smtp_password'); // SMTP password   
                $mail->From = \common\models\Config::getConfig('smtp_user');      // 发件人邮箱   
                $mail->FromName =  \common\models\Config::getConfig('smtp_user');  // 发件人   

                $mail->CharSet = "UTF-8";   // 这里指定字符集！   
                $mail->Encoding = "base64";   
                $mail->AddAddress($this->email);  // 收件人邮箱和姓名   

                $mail->IsHTML(true);  // send as HTML   
                // 邮件主题   
                $mail->Subject = 'Password reset for ' . \common\models\Config::getConfig('company_name');   
                // 邮件内容  

                $resetLink = \Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

                $mail->Body = '  
                <html><head>  
                <meta http-equiv="Content-Language" content="zh-cn">  
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
                </head>  
                <body>  
                
                <div class="password-reset">
                    <p>Hello '.\yii\helpers\Html::encode($user->username).' ,</p>

                    <p>Follow the link below to reset your password:</p>

                    <p>'.\yii\helpers\Html::a(\yii\helpers\Html::encode($resetLink), $resetLink).'</p>
                </div>  
                </body>  
                </html>  
                ';                                                                         
                $mail->AltBody ="text/html";

                $flat = $mail->Send();
                // var_dump($mail->ErrorInfo);
                return $flat;
                // return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                //     ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                //     ->setTo($this->email)
                //     ->setSubject('Password reset for ' . \Yii::$app->name)
                //     ->send();
            }
        }

        return false;
    }
}
